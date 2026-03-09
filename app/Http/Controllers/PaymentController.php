<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Services\PaymentService;
use App\Services\TwilioVideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private TwilioVideoService $twilioService
    ) {}

    /**
     * Redirect to PayMongo checkout page
     */
    public function checkout(Consultation $consultation)
    {
        // Verify user can pay for this consultation
        if (auth()->id() !== $consultation->client_id) {
            abort(403, 'Unauthorized');
        }

        // Check if consultation is in correct status
        if (!in_array($consultation->status, ['accepted', 'payment_pending'])) {
            return redirect()
                ->route('client.consultations')
                ->with('error', 'This consultation cannot be paid at this time.');
        }

        // Check if already paid
        if ($consultation->payment_status === 'paid') {
            return redirect()
                ->route('client.consultation.details', $consultation)
                ->with('info', 'This consultation has already been paid.');
        }

        try {
            // Create PayMongo checkout session
            $checkoutUrl = $this->paymentService->createCheckoutSession($consultation);

            if (!$checkoutUrl) {
                return redirect()
                    ->route('client.consultations')
                    ->with('error', 'Failed to create payment session. Please try again.');
            }

            // Redirect to PayMongo checkout page
            return redirect($checkoutUrl);

        } catch (\Exception $e) {
            Log::error('Payment checkout failed', [
                'consultation_id' => $consultation->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('client.consultations')
                ->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Handle successful payment callback
     */
    public function success(Request $request, Consultation $consultation)
    {
        Log::info('Payment success callback received', [
            'consultation_id' => $consultation->id,
            'payment_intent_id' => $consultation->payment_intent_id,
            'request_params' => $request->all(),
        ]);

        try {
            // Verify payment with PayMongo
            $verified = $this->paymentService->verifyPayment($consultation);

            Log::info('Payment verification result', [
                'consultation_id' => $consultation->id,
                'verified' => $verified,
            ]);

            if ($verified) {
                // Create Twilio video room if consultation type is video
                if ($consultation->consultation_type === 'video') {
                    $roomSid = $this->twilioService->createRoom($consultation);
                    
                    if ($roomSid) {
                        $consultation->update(['video_room_sid' => $roomSid]);
                        
                        Log::info('Video room created for consultation', [
                            'consultation_id' => $consultation->id,
                            'room_sid' => $roomSid,
                        ]);
                    } else {
                        Log::error('Failed to create video room', [
                            'consultation_id' => $consultation->id,
                        ]);
                    }
                }
                
                return redirect()
                    ->route('client.consultation.details', $consultation)
                    ->with('success', 'Payment successful! Your consultation is now scheduled.');
            }

            Log::warning('Payment verification returned false', [
                'consultation_id' => $consultation->id,
                'payment_intent_id' => $consultation->payment_intent_id,
            ]);

            return redirect()
                ->route('client.consultations')
                ->with('error', 'Payment verification failed. Please contact support.');

        } catch (\Exception $e) {
            Log::error('Payment success callback error', [
                'consultation_id' => $consultation->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('client.consultations')
                ->with('error', 'An error occurred. Please contact support.');
        }
    }

    /**
     * Handle failed/cancelled payment
     */
    public function failed(Consultation $consultation)
    {
        return redirect()
            ->route('client.consultations')
            ->with('error', 'Payment was cancelled or failed. Please try again.');
    }

    /**
     * Handle PayMongo webhook events
     */
    public function webhook(Request $request)
    {
        // Log webhook data for debugging
        Log::info('PayMongo Webhook Received', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        try {
            // Get event type and data
            $eventType = $request->input('data.attributes.type');
            $eventData = $request->input('data.attributes.data');

            if (!$eventType || !$eventData) {
                Log::warning('Invalid webhook payload', ['request' => $request->all()]);
                return response()->json(['error' => 'Invalid payload'], 400);
            }

            // Handle different event types
            switch ($eventType) {
                case 'checkout_session.payment.paid':
                    $this->handleCheckoutSessionPaid($eventData);
                    break;
                    
                case 'payment.paid':
                    $this->handlePaymentPaid($eventData);
                    break;
                    
                case 'payment.failed':
                    $this->handlePaymentFailed($eventData);
                    break;
                    
                case 'refund.succeeded':
                    $this->handleRefundSucceeded($eventData);
                    break;
                    
                case 'refund.failed':
                    $this->handleRefundFailed($eventData);
                    break;
                    
                case 'source.chargeable':
                    $this->handleSourceChargeable($eventData);
                    break;
                    
                default:
                    Log::info('Unhandled webhook event type', ['type' => $eventType]);
            }

            return response()->json(['success' => true], 200);

        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle checkout_session.payment.paid webhook event
     */
    private function handleCheckoutSessionPaid($data)
    {
        $checkoutSessionId = $data['id'] ?? null;
        
        if (!$checkoutSessionId) {
            Log::warning('Checkout session paid event missing session ID');
            return;
        }

        // Try to find consultation first
        $consultation = Consultation::where('payment_intent_id', $checkoutSessionId)->first();
        
        if ($consultation) {
            // Handle consultation payment
            $this->handleConsultationPayment($consultation, $data);
            return;
        }

        // Try to find document request
        $documentRequest = \App\Models\DocumentDraftingRequest::where('payment_intent_id', $checkoutSessionId)->first();
        
        if ($documentRequest) {
            // Handle document payment
            $this->handleDocumentPayment($documentRequest, $data);
            return;
        }

        Log::warning('No consultation or document request found for checkout session', [
            'session_id' => $checkoutSessionId
        ]);
    }

    /**
     * Handle consultation payment from webhook
     */
    private function handleConsultationPayment($consultation, $data)
    {
        $checkoutSessionId = $data['id'];
        
        // Check if already processed
        if ($consultation->status === 'scheduled' && $consultation->payment_status === 'paid') {
            Log::info('Payment already processed', [
                'consultation_id' => $consultation->id
            ]);
            return;
        }

        // Retrieve full checkout session details from PayMongo API
        try {
            $secretKey = config('services.paymongo.secret_key');
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($secretKey, '')
                ->get("https://api.paymongo.com/v1/checkout_sessions/{$checkoutSessionId}");
            
            if ($response->successful()) {
                $sessionData = $response->json()['data'];
                
                Log::info('Retrieved checkout session from PayMongo API', [
                    'consultation_id' => $consultation->id,
                    'session_data' => $sessionData,
                ]);
                
                // Extract payment details from the retrieved session
                $payments = $sessionData['attributes']['payments'] ?? [];
                $payment = $payments[0] ?? null;
                
                $paymentId = null;
                $paymentMethod = 'unknown';
                $paymentMethodId = null;
                
                if ($payment) {
                    $paymentId = $payment['id'] ?? null;
                    $paymentMethod = $payment['attributes']['source']['type'] ?? 'unknown';
                    $paymentMethodId = $payment['attributes']['source']['id'] ?? null;
                    
                    Log::info('Extracted payment details from API', [
                        'payment_id' => $paymentId,
                        'payment_method' => $paymentMethod,
                        'payment_method_id' => $paymentMethodId,
                    ]);
                } else {
                    Log::warning('No payment found in checkout session', [
                        'consultation_id' => $consultation->id,
                        'checkout_session_id' => $checkoutSessionId,
                    ]);
                }
                
                // Update transaction with all PayMongo IDs
                $transaction = \App\Models\Transaction::where('consultation_id', $consultation->id)
                    ->where('paymongo_payment_intent_id', $checkoutSessionId)
                    ->first();
                
                if ($transaction) {
                    $transaction->update([
                        'paymongo_payment_id' => $paymentId,
                        'paymongo_payment_method_id' => $paymentMethodId,
                        'payment_method' => $paymentMethod,
                        'status' => 'completed',
                        'processed_at' => now(),
                    ]);
                    
                    Log::info('Transaction updated with PayMongo IDs', [
                        'transaction_id' => $transaction->id,
                        'payment_id' => $paymentId,
                        'payment_method_id' => $paymentMethodId,
                        'payment_method' => $paymentMethod,
                    ]);
                } else {
                    Log::warning('Transaction not found for consultation', [
                        'consultation_id' => $consultation->id,
                        'checkout_session_id' => $checkoutSessionId,
                    ]);
                }
                
                // Process successful payment
                $this->paymentService->processSuccessfulPayment(
                    $consultation,
                    $checkoutSessionId,
                    $paymentMethod,
                    $paymentId,
                    $paymentMethodId
                );
                
                Log::info('Consultation payment processed via webhook', [
                    'consultation_id' => $consultation->id,
                    'checkout_session_id' => $checkoutSessionId,
                    'payment_id' => $paymentId,
                    'payment_method_id' => $paymentMethodId,
                    'payment_method' => $paymentMethod,
                ]);
            } else {
                Log::error('Failed to retrieve checkout session from PayMongo', [
                    'consultation_id' => $consultation->id,
                    'checkout_session_id' => $checkoutSessionId,
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error retrieving checkout session', [
                'consultation_id' => $consultation->id,
                'checkout_session_id' => $checkoutSessionId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle document payment from webhook
     */
    private function handleDocumentPayment($documentRequest, $data)
    {
        $checkoutSessionId = $data['id'];
        
        // Check if already processed
        if ($documentRequest->status === 'in_progress' && $documentRequest->payment_status === 'paid') {
            Log::info('Document payment already processed', [
                'request_id' => $documentRequest->id
            ]);
            return;
        }

        // Retrieve full checkout session details from PayMongo API
        try {
            $secretKey = config('services.paymongo.secret_key');
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($secretKey, '')
                ->get("https://api.paymongo.com/v1/checkout_sessions/{$checkoutSessionId}");
            
            if ($response->successful()) {
                $sessionData = $response->json()['data'];
                
                Log::info('Retrieved document checkout session from PayMongo API', [
                    'request_id' => $documentRequest->id,
                    'session_data' => $sessionData,
                ]);
                
                // Extract payment details
                $payments = $sessionData['attributes']['payments'] ?? [];
                $payment = $payments[0] ?? null;
                
                if ($payment) {
                    $paymentId = $payment['id'] ?? null;
                    $paymentMethod = $payment['attributes']['source']['type'] ?? 'unknown';
                    $paymentMethodId = $payment['attributes']['source']['id'] ?? null;
                    $amount = $payment['attributes']['amount'] / 100; // Convert from centavos
                    
                    // Create transaction record
                    $transaction = \App\Models\Transaction::create([
                        'user_id' => $documentRequest->client_id,
                        'lawyer_id' => $documentRequest->lawyer_id,
                        'document_request_id' => $documentRequest->id,
                        'type' => 'document_drafting',
                        'amount' => $amount,
                        'platform_fee' => 0,
                        'lawyer_payout' => $amount,
                        'payment_method' => $paymentMethod,
                        'paymongo_payment_intent_id' => $checkoutSessionId,
                        'paymongo_payment_id' => $paymentId,
                        'paymongo_payment_method_id' => $paymentMethodId,
                        'status' => 'completed',
                        'processed_at' => now(),
                    ]);

                    // Update document request
                    $documentRequest->update([
                        'status' => 'in_progress',
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                    ]);

                    // Send notifications
                    $documentRequest->client->notify(new \App\Notifications\DocumentCompleted($documentRequest));
                    $documentRequest->lawyer->notify(new \App\Notifications\DocumentRequestReceived($documentRequest));

                    Log::info('Document payment processed via webhook', [
                        'request_id' => $documentRequest->id,
                        'transaction_id' => $transaction->id,
                        'checkout_session_id' => $checkoutSessionId,
                        'payment_id' => $paymentId,
                        'payment_method_id' => $paymentMethodId,
                        'payment_method' => $paymentMethod,
                        'amount' => $amount,
                    ]);
                } else {
                    Log::warning('No payment found in document checkout session', [
                        'request_id' => $documentRequest->id,
                        'checkout_session_id' => $checkoutSessionId,
                    ]);
                }
            } else {
                Log::error('Failed to retrieve document checkout session from PayMongo', [
                    'request_id' => $documentRequest->id,
                    'checkout_session_id' => $checkoutSessionId,
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error retrieving document checkout session', [
                'request_id' => $documentRequest->id,
                'checkout_session_id' => $checkoutSessionId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle payment.paid webhook event
     */
    private function handlePaymentPaid($data)
    {
        $paymentIntentId = $data['attributes']['payment_intent_id'] ?? null;
        
        if (!$paymentIntentId) {
            Log::warning('Payment paid event missing payment_intent_id', ['data' => $data]);
            return;
        }

        // Find transaction by payment_intent_id
        $transaction = \App\Models\Transaction::where('payment_intent_id', $paymentIntentId)->first();
        
        if (!$transaction) {
            Log::warning('Transaction not found for payment intent', [
                'payment_intent_id' => $paymentIntentId
            ]);
            return;
        }

        // Check if already processed
        if ($transaction->status === 'completed') {
            Log::info('Payment already processed', ['transaction_id' => $transaction->id]);
            return;
        }

        // Update transaction status
        $transaction->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Update consultation status
        $consultation = $transaction->consultation;
        // Document reviews go to in_progress, others go to scheduled
        $status = $consultation->consultation_type === 'document_review' ? 'in_progress' : 'scheduled';
        $consultation->update([
            'status' => $status,
            'started_at' => $consultation->consultation_type === 'document_review' ? now() : null,
        ]);
        
        // Calculate review completion deadline for document reviews
        if ($consultation->consultation_type === 'document_review' && $consultation->estimated_turnaround_days) {
            $deadlineService = app(\App\Services\DeadlineCalculationService::class);
            $consultation->review_completion_deadline = $deadlineService->calculateReviewCompletionDeadline($consultation);
            $consultation->save();
        }

        // Send notifications
        $consultation->client->notify(new \App\Notifications\PaymentSuccessful($consultation));
        $consultation->lawyer->notify(new \App\Notifications\PaymentReceived($consultation));

        Log::info('Payment completed via webhook', [
            'transaction_id' => $transaction->id,
            'consultation_id' => $consultation->id,
            'amount' => $transaction->amount,
        ]);
    }

    /**
     * Handle payment.failed webhook event
     */
    private function handlePaymentFailed($data)
    {
        $paymentIntentId = $data['attributes']['payment_intent_id'] ?? null;
        
        if (!$paymentIntentId) {
            Log::warning('Payment failed event missing payment_intent_id', ['data' => $data]);
            return;
        }

        $transaction = \App\Models\Transaction::where('payment_intent_id', $paymentIntentId)->first();
        
        if (!$transaction) {
            Log::warning('Transaction not found for failed payment', [
                'payment_intent_id' => $paymentIntentId
            ]);
            return;
        }

        // Update transaction status
        $transaction->update([
            'status' => 'failed',
        ]);

        // Update consultation - keep status as 'payment_pending' for auto-accepted bookings
        // Only update payment_status to 'failed'
        $consultation = $transaction->consultation;
        $consultation->update([
            'payment_status' => 'failed',
            // Status remains 'payment_pending' - don't change it
        ]);

        // Send notification to client
        $errorMessage = $data['attributes']['last_payment_error']['message'] ?? 'Payment could not be processed';
        $consultation->client->notify(new \App\Notifications\PaymentFailed($consultation, $errorMessage));

        Log::info('Payment failed via webhook', [
            'transaction_id' => $transaction->id,
            'consultation_id' => $consultation->id,
            'error' => $errorMessage,
        ]);
    }

    /**
     * Handle source.chargeable webhook event
     */
    private function handleSourceChargeable($data)
    {
        // This event is triggered when a payment source becomes chargeable
        // Useful for payment methods like GCash, GrabPay that require user action
        Log::info('Source chargeable event received', [
            'source_id' => $data['id'] ?? null,
            'type' => $data['attributes']['type'] ?? null,
        ]);
    }

    /**
     * Document Payment Methods
     */

    /**
     * Redirect to PayMongo checkout page for document request
     */
    public function documentCheckout($requestId)
    {
        $documentRequest = \App\Models\DocumentDraftingRequest::findOrFail($requestId);

        // Verify user can pay for this document request
        if (auth()->id() !== $documentRequest->client_id) {
            abort(403, 'Unauthorized');
        }

        // Check if request is in correct status
        if ($documentRequest->status !== 'pending_payment') {
            return redirect()
                ->route('client.documents')
                ->with('error', 'This document request cannot be paid at this time.');
        }

        // Check if already paid
        if ($documentRequest->payment_status === 'paid') {
            return redirect()
                ->route('client.document.details', $documentRequest)
                ->with('info', 'This document request has already been paid.');
        }

        try {
            // Create PayMongo checkout session for document
            $checkoutUrl = $this->paymentService->createDocumentCheckoutSession($documentRequest);

            if (!$checkoutUrl) {
                return redirect()
                    ->route('client.documents')
                    ->with('error', 'Failed to create payment session. Please try again.');
            }

            // Redirect to PayMongo checkout page
            return redirect($checkoutUrl);

        } catch (\Exception $e) {
            Log::error('Document payment checkout failed', [
                'request_id' => $documentRequest->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('client.documents')
                ->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Handle successful document payment callback
     */
    public function documentSuccess(Request $request, $requestId)
    {
        $documentRequest = \App\Models\DocumentDraftingRequest::findOrFail($requestId);

        Log::info('Document payment success callback received', [
            'request_id' => $documentRequest->id,
            'payment_intent_id' => $documentRequest->payment_intent_id,
        ]);

        try {
            // Verify payment with PayMongo
            $verified = $this->paymentService->verifyDocumentPayment($documentRequest);

            if ($verified) {
                return redirect()
                    ->route('client.document.details', $documentRequest)
                    ->with('success', 'Payment successful! The lawyer will start working on your document.');
            } else {
                return redirect()
                    ->route('client.document.details', $documentRequest)
                    ->with('warning', 'Payment is being processed. You will be notified once confirmed.');
            }

        } catch (\Exception $e) {
            Log::error('Document payment verification failed', [
                'request_id' => $documentRequest->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('client.document.details', $documentRequest)
                ->with('error', 'Payment verification failed. Please contact support if amount was deducted.');
        }
    }

    /**
     * Handle failed document payment callback
     */
    public function documentFailed(Request $request, $requestId)
    {
        $documentRequest = \App\Models\DocumentDraftingRequest::findOrFail($requestId);

        Log::warning('Document payment failed callback', [
            'request_id' => $documentRequest->id,
        ]);

        return redirect()
            ->route('client.document.details', $documentRequest)
            ->with('error', 'Payment failed. Please try again.');
    }

    /**
     * Handle refund.succeeded webhook event
     */
    private function handleRefundSucceeded($data)
    {
        $refundId = $data['id'] ?? null;
        $paymentIntentId = $data['attributes']['payment_intent_id'] ?? null;
        $amount = ($data['attributes']['amount'] ?? 0) / 100; // Convert from centavos
        
        if (!$refundId) {
            Log::warning('Refund succeeded event missing refund ID', ['data' => $data]);
            return;
        }

        // Find refund by PayMongo refund ID
        $refund = \App\Models\Refund::where('paymongo_refund_id', $refundId)->first();
        
        if (!$refund) {
            Log::warning('Refund not found for PayMongo refund ID', [
                'paymongo_refund_id' => $refundId,
                'payment_intent_id' => $paymentIntentId,
            ]);
            return;
        }

        // Check if already processed
        if ($refund->status === 'completed') {
            Log::info('Refund already completed', ['refund_id' => $refund->id]);
            return;
        }

        // Update refund status to completed
        $refund->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);

        // Update transaction status to refunded
        $refund->transaction->update([
            'status' => 'refunded',
        ]);

        // Send notifications to client
        $refund->user->notify(new \App\Notifications\RefundCompleted($refund));

        // Send notification to lawyer
        if ($refund->lawyer) {
            $refund->lawyer->notify(new \App\Notifications\RefundCompletedForLawyer($refund));
        }

        Log::info('Refund completed via webhook', [
            'refund_id' => $refund->id,
            'paymongo_refund_id' => $refundId,
            'transaction_id' => $refund->transaction_id,
            'amount' => $amount,
        ]);
    }

    /**
     * Handle refund.failed webhook event
     */
    private function handleRefundFailed($data)
    {
        $refundId = $data['id'] ?? null;
        $failureReason = $data['attributes']['failure_reason'] ?? 'Unknown error';
        
        if (!$refundId) {
            Log::warning('Refund failed event missing refund ID', ['data' => $data]);
            return;
        }

        // Find refund by PayMongo refund ID
        $refund = \App\Models\Refund::where('paymongo_refund_id', $refundId)->first();
        
        if (!$refund) {
            Log::warning('Refund not found for failed refund', [
                'paymongo_refund_id' => $refundId,
            ]);
            return;
        }

        // Update refund status to failed
        $refund->update([
            'status' => 'failed',
            'admin_notes' => 'PayMongo refund failed: ' . $failureReason,
        ]);

        Log::error('Refund failed via webhook', [
            'refund_id' => $refund->id,
            'paymongo_refund_id' => $refundId,
            'failure_reason' => $failureReason,
        ]);

        // Notify admin about failed refund (you can create a notification for this)
        // For now, just log it
    }
}

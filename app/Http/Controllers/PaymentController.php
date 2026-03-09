<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Services\PaymentService;
use App\Services\TwilioVideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * PaymentController
 * 
 * Handles PayMongo payment processing and webhook events.
 * 
 * CRITICAL: Payment Data Synchronization
 * ======================================
 * Payment data exists in BOTH consultations and transactions tables for performance.
 * All webhook handlers MUST update both tables atomically using DB::transaction()
 * to ensure data consistency.
 * 
 * Pattern:
 * DB::transaction(function() {
 *     $transaction->update([...]);  // Update transaction table
 *     $consultation->update([...]);  // Update consultation table
 * });
 * 
 * This prevents race conditions and ensures both tables always stay in sync.
 */
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
        if ($consultation->isPaid()) {
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
     * NOTE: This is just a redirect endpoint. Actual payment processing happens via webhook.
     */
    public function success(Request $request, Consultation $consultation)
    {
        Log::info('Payment success callback received', [
            'consultation_id' => $consultation->id,
            'payment_intent_id' => $consultation->payment_intent_id,
            'request_params' => $request->all(),
        ]);

        // Refresh consultation to get latest status
        $consultation->load('transaction')->refresh();

        // Check if payment was already processed by webhook
        if ($consultation->isPaid() && $consultation->status === 'scheduled') {
            Log::info('Payment already processed by webhook', [
                'consultation_id' => $consultation->id,
            ]);
            
            return redirect()
                ->route('client.consultation.details', $consultation)
                ->with('success', 'Payment successful! Your consultation is now scheduled.');
        }

        // Set transaction status to processing (webhook will update to completed)
        if ($consultation->transaction && $consultation->transaction->status === 'pending') {
            $consultation->transaction->update(['status' => 'processing']);
            
            Log::info('Transaction status updated to processing', [
                'consultation_id' => $consultation->id,
                'transaction_id' => $consultation->transaction->id,
            ]);
        }

        // Set consultation status to payment_processing
        if ($consultation->status === 'payment_pending') {
            $consultation->update(['status' => 'payment_processing']);
            
            Log::info('Consultation status updated to payment_processing', [
                'consultation_id' => $consultation->id,
            ]);
        }

        // Payment not yet processed - webhook will handle it
        Log::info('Payment pending webhook processing', [
            'consultation_id' => $consultation->id,
            'current_status' => $consultation->status,
            'payment_status' => $consultation->isPaid() ? 'paid' : 'pending',
        ]);

        return redirect()
            ->route('client.consultation.details', $consultation)
            ->with('info', 'Payment is being processed. You will be notified once confirmed.');
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
        if ($consultation->isPaid() && $consultation->status === 'scheduled') {
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
                
                // Find transaction
                $transaction = \App\Models\Transaction::where('consultation_id', $consultation->id)
                    ->where('paymongo_payment_intent_id', $checkoutSessionId)
                    ->first();
                
                if (!$transaction) {
                    Log::warning('Transaction not found for consultation', [
                        'consultation_id' => $consultation->id,
                        'checkout_session_id' => $checkoutSessionId,
                    ]);
                    return;
                }
                
                // CRITICAL: Update ONLY transaction table in a database transaction
                // Consultation status is updated separately (not payment_status)
                \Illuminate\Support\Facades\DB::transaction(function () use (
                    $transaction, 
                    $consultation, 
                    $paymentId, 
                    $paymentMethodId, 
                    $paymentMethod
                ) {
                    // Update transaction with all PayMongo IDs
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
                    
                    // Update consultation status (NOT payment_status - that comes from transaction)
                    // Document reviews go to in_progress immediately, others go to scheduled
                    $status = $consultation->consultation_type === 'document_review' ? 'in_progress' : 'scheduled';
                    
                    $consultationUpdateData = [
                        'status' => $status,
                    ];

                    // For document reviews, set started_at and calculate review completion deadline
                    if ($consultation->consultation_type === 'document_review') {
                        $consultationUpdateData['started_at'] = now();
                        
                        $deadlineService = app(\App\Services\DeadlineCalculationService::class);
                        $reviewDeadline = $deadlineService->calculateReviewCompletionDeadline($consultation);
                        $consultationUpdateData['review_completion_deadline'] = $reviewDeadline;

                        Log::info('Document review started with deadline', [
                            'consultation_id' => $consultation->id,
                            'turnaround_days' => $consultation->estimated_turnaround_days,
                            'started_at' => now()->toDateTimeString(),
                            'deadline' => $reviewDeadline->toDateTimeString(),
                        ]);
                    }

                    $consultation->update($consultationUpdateData);
                    
                    Log::info('Consultation updated with status', [
                        'consultation_id' => $consultation->id,
                        'status' => $status,
                    ]);
                });
                
                // Send notifications to both client and lawyer (outside transaction)
                $consultation->client->notify(new \App\Notifications\PaymentSuccessful($consultation));
                $consultation->lawyer->notify(new \App\Notifications\PaymentReceived($consultation));
                
                // Create Twilio video room if consultation type is video
                if ($consultation->consultation_type === 'video') {
                    $roomSid = $this->twilioService->createRoom($consultation);
                    
                    if ($roomSid) {
                        $consultation->update(['video_room_sid' => $roomSid]);
                        
                        Log::info('Video room created for consultation via webhook', [
                            'consultation_id' => $consultation->id,
                            'room_sid' => $roomSid,
                        ]);
                    } else {
                        Log::error('Failed to create video room via webhook', [
                            'consultation_id' => $consultation->id,
                        ]);
                    }
                }
                
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
                    
                    // CRITICAL: Update both transaction AND document request in a database transaction
                    // This ensures both tables stay in sync atomically
                    \Illuminate\Support\Facades\DB::transaction(function () use (
                        $documentRequest,
                        $checkoutSessionId,
                        $paymentId,
                        $paymentMethod,
                        $paymentMethodId,
                        $amount
                    ) {
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
                        
                        Log::info('Document payment processed via webhook', [
                            'request_id' => $documentRequest->id,
                            'transaction_id' => $transaction->id,
                            'checkout_session_id' => $checkoutSessionId,
                            'payment_id' => $paymentId,
                            'payment_method_id' => $paymentMethodId,
                            'payment_method' => $paymentMethod,
                            'amount' => $amount,
                        ]);
                    });

                    // Send notifications (outside transaction)
                    $documentRequest->client->notify(new \App\Notifications\DocumentCompleted($documentRequest));
                    $documentRequest->lawyer->notify(new \App\Notifications\DocumentRequestReceived($documentRequest));
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
        $transaction = \App\Models\Transaction::where('paymongo_payment_intent_id', $paymentIntentId)->first();
        
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

        // Get consultation
        $consultation = $transaction->consultation;
        
        if (!$consultation) {
            Log::warning('Consultation not found for transaction', [
                'transaction_id' => $transaction->id
            ]);
            return;
        }

        // CRITICAL: Update ONLY transaction table in a database transaction
        // Consultation status is updated separately (not payment_status)
        \Illuminate\Support\Facades\DB::transaction(function () use ($transaction, $consultation) {
            // Update transaction status
            $transaction->update([
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            // Update consultation status (NOT payment_status - that comes from transaction)
            // Document reviews go to in_progress, others go to scheduled
            $status = $consultation->consultation_type === 'document_review' ? 'in_progress' : 'scheduled';
            
            $consultationUpdateData = [
                'status' => $status,
            ];
            
            // For document reviews, set started_at
            if ($consultation->consultation_type === 'document_review') {
                $consultationUpdateData['started_at'] = now();
            }
            
            $consultation->update($consultationUpdateData);
            
            Log::info('Payment completed via webhook', [
                'transaction_id' => $transaction->id,
                'consultation_id' => $consultation->id,
                'amount' => $transaction->amount,
                'consultation_status' => $status,
            ]);
        });
        
        // Calculate review completion deadline for document reviews (outside transaction)
        if ($consultation->consultation_type === 'document_review' && $consultation->estimated_turnaround_days) {
            $deadlineService = app(\App\Services\DeadlineCalculationService::class);
            $consultation->review_completion_deadline = $deadlineService->calculateReviewCompletionDeadline($consultation);
            $consultation->save();
        }

        // Send notifications (outside transaction)
        $consultation->client->notify(new \App\Notifications\PaymentSuccessful($consultation));
        $consultation->lawyer->notify(new \App\Notifications\PaymentReceived($consultation));
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

        $transaction = \App\Models\Transaction::where('paymongo_payment_intent_id', $paymentIntentId)->first();
        
        if (!$transaction) {
            Log::warning('Transaction not found for failed payment', [
                'payment_intent_id' => $paymentIntentId
            ]);
            return;
        }

        // Get consultation
        $consultation = $transaction->consultation;
        
        if (!$consultation) {
            Log::warning('Consultation not found for transaction', [
                'transaction_id' => $transaction->id
            ]);
            return;
        }

        // CRITICAL: Update transaction AND consultation status
        \Illuminate\Support\Facades\DB::transaction(function () use ($transaction, $consultation) {
            // Update transaction status
            $transaction->update([
                'status' => 'failed',
            ]);
            
            // Update consultation status to payment_failed
            $consultation->update([
                'status' => 'payment_failed',
            ]);
            
            Log::info('Payment failed via webhook', [
                'transaction_id' => $transaction->id,
                'consultation_id' => $consultation->id,
                'consultation_status' => 'payment_failed',
            ]);
        });

        // Send notification to client (outside transaction)
        $errorMessage = $data['attributes']['last_payment_error']['message'] ?? 'Payment could not be processed';
        $consultation->client->notify(new \App\Notifications\PaymentFailed($consultation, $errorMessage));
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
     * NOTE: This is just a redirect endpoint. Actual payment processing happens via webhook.
     */
    public function documentSuccess(Request $request, $requestId)
    {
        $documentRequest = \App\Models\DocumentDraftingRequest::findOrFail($requestId);

        Log::info('Document payment success callback received', [
            'request_id' => $documentRequest->id,
            'payment_intent_id' => $documentRequest->payment_intent_id,
        ]);

        // Refresh to get latest status
        $documentRequest->refresh();

        // Check if payment was already processed by webhook
        if ($documentRequest->payment_status === 'paid' && $documentRequest->status === 'in_progress') {
            Log::info('Document payment already processed by webhook', [
                'request_id' => $documentRequest->id,
            ]);
            
            return redirect()
                ->route('client.document.details', $documentRequest)
                ->with('success', 'Payment successful! The lawyer will start working on your document.');
        }

        // Set payment status to processing (webhook will update to paid)
        if ($documentRequest->payment_status === 'pending') {
            $documentRequest->update(['payment_status' => 'processing']);
            
            Log::info('Document payment status updated to processing', [
                'request_id' => $documentRequest->id,
            ]);
        }

        // Payment not yet processed - webhook will handle it
        Log::info('Document payment pending webhook processing', [
            'request_id' => $documentRequest->id,
            'current_status' => $documentRequest->status,
            'payment_status' => $documentRequest->payment_status,
        ]);

        return redirect()
            ->route('client.document.details', $documentRequest)
            ->with('info', 'Payment is being processed. You will be notified once confirmed.');
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

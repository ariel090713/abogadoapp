<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private string $secretKey;
    private string $baseUrl = 'https://api.paymongo.com/v1';

    public function __construct()
    {
        $this->secretKey = config('services.paymongo.secret_key');
    }

    /**
     * Create PayMongo checkout session
     */
    public function createCheckoutSession(Consultation $consultation): ?string
    {
        try {
            $amount = (int) ($consultation->total_amount * 100); // Convert to centavos
            
            Log::info('Creating PayMongo checkout session', [
                'consultation_id' => $consultation->id,
                'amount' => $amount,
                'total_amount' => $consultation->total_amount,
            ]);
            
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/checkout_sessions", [
                    'data' => [
                        'attributes' => [
                            'cancel_url' => route('payment.failed', $consultation),
                            'success_url' => route('payment.success', $consultation),
                            'line_items' => [
                                [
                                    'name' => $consultation->title,
                                    'description' => "Consultation with {$consultation->lawyer->name}",
                                    'amount' => $amount,
                                    'currency' => 'PHP',
                                    'quantity' => 1,
                                ],
                            ],
                            'payment_method_types' => ['card', 'gcash', 'grab_pay', 'paymaya'],
                            'description' => "Consultation: {$consultation->title}",
                            'metadata' => [
                                'consultation_id' => (string) $consultation->id,
                                'client_id' => (string) $consultation->client_id,
                                'lawyer_id' => (string) $consultation->lawyer_id,
                            ],
                        ],
                    ],
                ]);

            Log::info('PayMongo API response received', [
                'consultation_id' => $consultation->id,
                'status_code' => $response->status(),
                'successful' => $response->successful(),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $checkoutSession = $data['data'];
                $checkoutSessionId = $checkoutSession['id'];
                $checkoutUrl = $checkoutSession['attributes']['checkout_url'];

                Log::info('Checkout session created successfully', [
                    'consultation_id' => $consultation->id,
                    'checkout_session_id' => $checkoutSessionId,
                    'checkout_url' => $checkoutUrl,
                ]);

                // Store checkout session ID
                $consultation->update([
                    'payment_intent_id' => $checkoutSessionId,
                    'payment_status' => 'pending',
                ]);

                // Create pending transaction record
                $platformFee = 0; // Platform fee set to 0
                $lawyerPayout = $consultation->total_amount; // Lawyer gets full amount
                
                Transaction::create([
                    'user_id' => $consultation->client_id,
                    'lawyer_id' => $consultation->lawyer_id,
                    'consultation_id' => $consultation->id,
                    'document_request_id' => null,
                    'type' => 'consultation_payment',
                    'amount' => $consultation->total_amount,
                    'platform_fee' => $platformFee,
                    'lawyer_payout' => $lawyerPayout,
                    'status' => 'pending',
                    'payment_method' => null,
                    'paymongo_payment_intent_id' => $checkoutSessionId,
                ]);

                Log::info('Consultation updated with payment_intent_id', [
                    'consultation_id' => $consultation->id,
                    'payment_intent_id' => $checkoutSessionId,
                ]);

                return $checkoutUrl;
            }

            Log::error('PayMongo checkout session creation failed', [
                'consultation_id' => $consultation->id,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('PayMongo checkout session creation exception', [
                'consultation_id' => $consultation->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Verify payment completion
     */
    public function verifyPayment(Consultation $consultation): bool
    {
        try {
            if (!$consultation->payment_intent_id) {
                Log::warning('Payment verification failed: No payment_intent_id', [
                    'consultation_id' => $consultation->id,
                ]);
                return false;
            }

            Log::info('Verifying payment with PayMongo', [
                'consultation_id' => $consultation->id,
                'payment_intent_id' => $consultation->payment_intent_id,
            ]);

            $response = Http::withBasicAuth($this->secretKey, '')
                ->get("{$this->baseUrl}/checkout_sessions/{$consultation->payment_intent_id}");

            Log::info('PayMongo API response', [
                'consultation_id' => $consultation->id,
                'status_code' => $response->status(),
                'response_body' => $response->json(),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $checkoutSession = $data['data'];
                $sessionStatus = $checkoutSession['attributes']['status'] ?? 'unknown';
                $paidAt = $checkoutSession['attributes']['paid_at'] ?? null;

                Log::info('Checkout session status', [
                    'consultation_id' => $consultation->id,
                    'status' => $sessionStatus,
                    'paid_at' => $paidAt,
                ]);

                // Check if paid - either status is 'paid' OR paid_at is set OR payment_intent status is succeeded
                $paymentIntent = $checkoutSession['attributes']['payment_intent'] ?? null;
                $paymentIntentStatus = $paymentIntent['attributes']['status'] ?? null;
                
                $isPaid = $sessionStatus === 'paid' 
                    || $paidAt !== null 
                    || $paymentIntentStatus === 'succeeded';

                Log::info('Payment status check', [
                    'consultation_id' => $consultation->id,
                    'session_status' => $sessionStatus,
                    'paid_at' => $paidAt,
                    'payment_intent_status' => $paymentIntentStatus,
                    'is_paid' => $isPaid,
                ]);

                if ($isPaid) {
                    // Get payment details
                    $payments = $checkoutSession['attributes']['payments'] ?? [];
                    
                    Log::info('Payment details', [
                        'consultation_id' => $consultation->id,
                        'payments_count' => count($payments),
                    ]);
                    
                    $payment = $payments[0] ?? null;
                    
                    if ($payment) {
                        // Process successful payment
                        $paymentMethod = $payment['attributes']['source']['type'] ?? 'unknown';
                        
                        Log::info('Processing successful payment', [
                            'consultation_id' => $consultation->id,
                            'payment_method' => $paymentMethod,
                        ]);
                        
                        $this->processSuccessfulPayment(
                            $consultation,
                            $checkoutSession['id'],
                            $paymentMethod
                        );

                        return true;
                    } else {
                        Log::warning('No payment found in checkout session', [
                            'consultation_id' => $consultation->id,
                        ]);
                    }
                } else {
                    Log::warning('Checkout session not paid', [
                        'consultation_id' => $consultation->id,
                        'status' => $sessionStatus,
                        'paid_at' => $paidAt,
                        'payment_intent_status' => $paymentIntentStatus,
                    ]);
                }
            } else {
                Log::error('PayMongo API request failed', [
                    'consultation_id' => $consultation->id,
                    'status_code' => $response->status(),
                    'response' => $response->body(),
                ]);
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Payment verification exception', [
                'consultation_id' => $consultation->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Process successful payment
     */
    public function processSuccessfulPayment(Consultation $consultation, string $paymentIntentId, string $paymentMethod): Transaction
    {
        // Calculate platform fee and lawyer payout
        $platformFee = 0; // Platform fee set to 0
        $lawyerPayout = $consultation->total_amount; // Lawyer gets full amount

        // Update existing transaction or create new one
        $transaction = Transaction::where('consultation_id', $consultation->id)
            ->where('paymongo_payment_intent_id', $paymentIntentId)
            ->first();
        
        if ($transaction) {
            // Update existing pending transaction
            $transaction->update([
                'status' => 'completed',
                'payment_method' => $paymentMethod,
                'processed_at' => now(),
            ]);
        } else {
            // Fallback: Create transaction if not exists (shouldn't happen)
            $transaction = Transaction::create([
                'consultation_id' => $consultation->id,
                'user_id' => $consultation->client_id,
                'lawyer_id' => $consultation->lawyer_id,
                'type' => 'consultation_payment',
                'amount' => $consultation->total_amount,
                'platform_fee' => $platformFee,
                'lawyer_payout' => $lawyerPayout,
                'status' => 'completed',
                'payment_method' => $paymentMethod,
                'paymongo_payment_intent_id' => $paymentIntentId,
                'processed_at' => now(),
            ]);
        }

        // Update consultation status
        // Document reviews go to in_progress immediately, others go to scheduled
        $status = $consultation->consultation_type === 'document_review' ? 'in_progress' : 'scheduled';
        
        $updateData = [
            'status' => $status,
            'payment_status' => 'paid',
        ];

        // For document reviews, set started_at and calculate review completion deadline
        if ($consultation->consultation_type === 'document_review') {
            $updateData['started_at'] = now();
            
            $deadlineService = app(DeadlineCalculationService::class);
            $reviewDeadline = $deadlineService->calculateReviewCompletionDeadline($consultation);
            $updateData['review_completion_deadline'] = $reviewDeadline;

            Log::info('Document review started with deadline', [
                'consultation_id' => $consultation->id,
                'turnaround_days' => $consultation->estimated_turnaround_days,
                'started_at' => now()->toDateTimeString(),
                'deadline' => $reviewDeadline->toDateTimeString(),
            ]);
        }

        $consultation->update($updateData);

        // Send notifications to both client and lawyer
        $consultation->client->notify(new \App\Notifications\PaymentSuccessful($consultation));
        $consultation->lawyer->notify(new \App\Notifications\PaymentReceived($consultation));

        Log::info('Payment processed successfully', [
            'consultation_id' => $consultation->id,
            'transaction_id' => $transaction->id,
            'amount' => $consultation->total_amount,
            'consultation_type' => $consultation->consultation_type,
            'status' => $status,
        ]);

        return $transaction;
    }

    /**
     * Document Payment Methods
     */

    /**
     * Create PayMongo checkout session for document request
     */
    public function createDocumentCheckoutSession($documentRequest): ?string
    {
        try {
            $amount = (int) ($documentRequest->price * 100); // Convert to centavos
            
            Log::info('Creating PayMongo checkout session for document', [
                'request_id' => $documentRequest->id,
                'amount' => $amount,
                'price' => $documentRequest->price,
            ]);
            
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/checkout_sessions", [
                    'data' => [
                        'attributes' => [
                            'cancel_url' => route('document.payment.failed', $documentRequest),
                            'success_url' => route('document.payment.success', $documentRequest),
                            'line_items' => [
                                [
                                    'name' => $documentRequest->document_name,
                                    'description' => "Document drafting by {$documentRequest->lawyer->name}",
                                    'amount' => $amount,
                                    'currency' => 'PHP',
                                    'quantity' => 1,
                                ],
                            ],
                            'payment_method_types' => ['card', 'gcash', 'grab_pay', 'paymaya'],
                            'description' => "Document: {$documentRequest->document_name}",
                            'reference_number' => "DOC-{$documentRequest->id}-" . time(),
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $checkoutSession = $response->json();
                $checkoutUrl = $checkoutSession['data']['attributes']['checkout_url'] ?? null;
                $paymentIntentId = $checkoutSession['data']['attributes']['payment_intent']['id'] ?? null;

                if ($checkoutUrl && $paymentIntentId) {
                    // Store payment intent ID
                    $documentRequest->update([
                        'payment_intent_id' => $paymentIntentId,
                    ]);

                    // Create pending transaction record
                    $platformFee = 0; // Platform fee set to 0
                    $lawyerPayout = $documentRequest->price; // Lawyer gets full amount
                    
                    Transaction::create([
                        'user_id' => $documentRequest->client_id,
                        'lawyer_id' => $documentRequest->lawyer_id,
                        'consultation_id' => null,
                        'document_request_id' => $documentRequest->id,
                        'type' => 'document_drafting',
                        'amount' => $documentRequest->price,
                        'platform_fee' => $platformFee,
                        'lawyer_payout' => $lawyerPayout,
                        'status' => 'pending',
                        'payment_method' => null,
                        'paymongo_payment_intent_id' => $paymentIntentId,
                    ]);

                    Log::info('Document checkout session created', [
                        'request_id' => $documentRequest->id,
                        'payment_intent_id' => $paymentIntentId,
                        'checkout_url' => $checkoutUrl,
                    ]);

                    return $checkoutUrl;
                }
            }

            Log::error('Failed to create document checkout session', [
                'request_id' => $documentRequest->id,
                'response' => $response->json(),
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Document checkout session creation failed', [
                'request_id' => $documentRequest->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Verify document payment with PayMongo
     */
    public function verifyDocumentPayment($documentRequest): bool
    {
        try {
            if (!$documentRequest->payment_intent_id) {
                Log::warning('No payment intent ID for document request', [
                    'request_id' => $documentRequest->id,
                ]);
                return false;
            }

            $response = Http::withBasicAuth($this->secretKey, '')
                ->get("{$this->baseUrl}/payment_intents/{$documentRequest->payment_intent_id}");

            if ($response->successful()) {
                $paymentIntent = $response->json();
                $status = $paymentIntent['data']['attributes']['status'] ?? null;

                Log::info('Document payment intent status', [
                    'request_id' => $documentRequest->id,
                    'payment_intent_id' => $documentRequest->payment_intent_id,
                    'status' => $status,
                ]);

                if ($status === 'succeeded') {
                    // Update document request status
                    $documentRequest->update([
                        'payment_status' => 'paid',
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);

                    // Update transaction record to completed
                    $transaction = Transaction::where('document_request_id', $documentRequest->id)
                        ->where('paymongo_payment_intent_id', $documentRequest->payment_intent_id)
                        ->first();
                    
                    if ($transaction) {
                        $transaction->update([
                            'status' => 'completed',
                            'payment_method' => $paymentIntent['data']['attributes']['payment_method_used'] ?? 'card',
                            'processed_at' => now(),
                        ]);
                    } else {
                        // Fallback: Create transaction if not exists (shouldn't happen)
                        $platformFee = 0; // Platform fee set to 0
                        $lawyerPayout = $documentRequest->price; // Lawyer gets full amount
                        
                        Transaction::create([
                            'user_id' => $documentRequest->client_id,
                            'lawyer_id' => $documentRequest->lawyer_id,
                            'consultation_id' => null,
                            'document_request_id' => $documentRequest->id,
                            'type' => 'document_drafting',
                            'amount' => $documentRequest->price,
                            'platform_fee' => $platformFee,
                            'lawyer_payout' => $lawyerPayout,
                            'status' => 'completed',
                            'payment_method' => $paymentIntent['data']['attributes']['payment_method_used'] ?? 'card',
                            'paymongo_payment_intent_id' => $documentRequest->payment_intent_id,
                            'processed_at' => now(),
                        ]);
                    }

                    Log::info('Document payment verified and recorded', [
                        'request_id' => $documentRequest->id,
                    ]);

                    // TODO: Send notification to lawyer

                    return true;
                }
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Document payment verification failed', [
                'request_id' => $documentRequest->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}

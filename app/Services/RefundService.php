<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\DocumentRequest;
use App\Models\Refund;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundService
{
    /**
     * Create automatic refund (system-triggered)
     */
    public function createAutoRefund(
        Transaction $transaction,
        string $reason,
        ?string $detailedReason = null,
        ?float $refundAmount = null,
        ?string $refundType = null
    ): Refund {
        return DB::transaction(function () use ($transaction, $reason, $detailedReason, $refundAmount, $refundType) {
            // Default to full refund if not specified
            $amount = $refundAmount ?? $transaction->amount;
            $type = $refundType ?? 'full';
            
            $refund = Refund::create([
                'transaction_id' => $transaction->id,
                'consultation_id' => $transaction->consultation_id,
                'document_request_id' => $transaction->document_request_id,
                'user_id' => $transaction->user_id,
                'refund_type' => $type,
                'refund_amount' => $amount,
                'original_amount' => $transaction->amount,
                'reason' => $reason,
                'detailed_reason' => $detailedReason,
                'status' => 'approved', // Auto-approve system refunds
                'approved_at' => now(),
            ]);

            // Link refund to transaction
            $transaction->update(['refund_id' => $refund->id]);

            Log::info('Auto-refund created', [
                'refund_id' => $refund->id,
                'transaction_id' => $transaction->id,
                'reason' => $reason,
                'type' => $type,
                'amount' => $refund->refund_amount,
            ]);

            return $refund;
        });
    }

    /**
     * Create manual refund request (client-initiated)
     */
    public function createManualRefund(
        Transaction $transaction,
        string $reason,
        string $detailedReason,
        string $refundType = 'full',
        ?float $refundAmount = null
    ): Refund {
        return DB::transaction(function () use ($transaction, $reason, $detailedReason, $refundType, $refundAmount) {
            $amount = $refundType === 'full' 
                ? $transaction->amount 
                : ($refundAmount ?? $transaction->amount);

            // Get lawyer ID from transaction
            $lawyerId = null;
            if ($transaction->consultation_id) {
                $lawyerId = $transaction->consultation->lawyer_id ?? null;
            } elseif ($transaction->document_request_id) {
                $lawyerId = $transaction->documentRequest->lawyer_id ?? null;
            }

            $refund = Refund::create([
                'transaction_id' => $transaction->id,
                'consultation_id' => $transaction->consultation_id,
                'document_request_id' => $transaction->document_request_id,
                'user_id' => $transaction->user_id,
                'lawyer_id' => $lawyerId,
                'refund_type' => $refundType,
                'refund_amount' => $amount,
                'original_amount' => $transaction->amount,
                'reason' => $reason,
                'detailed_reason' => $detailedReason,
                'status' => 'pending', // Manual refunds need admin approval
                'lawyer_approval_status' => 'pending', // Lawyer needs to respond
                'lawyer_response_deadline' => now()->addDays(7), // 7-day deadline for lawyer response
            ]);

            // Link refund to transaction
            $transaction->update(['refund_id' => $refund->id]);

            // Send notification to client
            $transaction->user->notify(new \App\Notifications\RefundRequestReceived($refund));

            // Send notification to lawyer if exists
            if ($lawyerId) {
                $lawyer = \App\Models\User::find($lawyerId);
                if ($lawyer) {
                    $lawyer->notify(new \App\Notifications\RefundRequestForLawyer($refund));
                }
            }

            Log::info('Manual refund request created', [
                'refund_id' => $refund->id,
                'transaction_id' => $transaction->id,
                'reason' => $reason,
                'amount' => $refund->refund_amount,
                'lawyer_id' => $lawyerId,
            ]);

            return $refund;
        });
    }

    /**
     * Approve refund (admin action)
     */
    public function approveRefund(Refund $refund, int $adminId, ?string $notes = null): bool
    {
        if ($refund->status !== 'pending') {
            return false;
        }

        $refund->update([
            'status' => 'approved',
            'approved_by' => $adminId,
            'approved_at' => now(),
            'admin_notes' => $notes,
        ]);

        // Send notification to client
        $refund->user->notify(new \App\Notifications\RefundApproved($refund));

        // Send notification to lawyer
        if ($refund->lawyer) {
            $refund->lawyer->notify(new \App\Notifications\RefundApprovedForLawyer($refund));
        }

        Log::info('Refund approved', [
            'refund_id' => $refund->id,
            'approved_by' => $adminId,
        ]);

        return true;
    }

    /**
     * Reject refund (admin action)
     */
    public function rejectRefund(Refund $refund, int $adminId, string $reason): bool
    {
        if ($refund->status !== 'pending') {
            return false;
        }

        $refund->update([
            'status' => 'rejected',
            'approved_by' => $adminId,
            'rejected_at' => now(),
            'rejection_reason' => $reason,
        ]);

        // Send notification to client
        $refund->user->notify(new \App\Notifications\RefundRejected($refund));

        // Send notification to lawyer
        if ($refund->lawyer) {
            $refund->lawyer->notify(new \App\Notifications\RefundRejectedForLawyer($refund));
        }

        Log::info('Refund rejected', [
            'refund_id' => $refund->id,
            'rejected_by' => $adminId,
            'reason' => $reason,
        ]);

        return true;
    }

    /**
     * Process refund via PayMongo
     */
    public function processRefund(Refund $refund): bool
    {
        if ($refund->status !== 'approved') {
            Log::warning('Attempted to process non-approved refund', [
                'refund_id' => $refund->id,
                'status' => $refund->status,
            ]);
            return false;
        }

        try {
            $refund->update(['status' => 'processing']);

            $transaction = $refund->transaction;
            
            // Call PayMongo Refund API
            $paymongoRefund = $this->createPayMongoRefund($transaction, $refund);
            
            // Save PayMongo refund ID but keep status as "processing"
            // Webhook will update to "completed" and send notifications
            $refund->update([
                'paymongo_refund_id' => $paymongoRefund['id'] ?? null,
            ]);

            Log::info('Refund initiated with PayMongo', [
                'refund_id' => $refund->id,
                'amount' => $refund->refund_amount,
                'transaction_id' => $refund->transaction_id,
                'paymongo_refund_id' => $paymongoRefund['id'] ?? null,
            ]);

            return true;

        } catch (\Exception $e) {
            $refund->update([
                'status' => 'failed',
                'admin_notes' => 'Processing failed: ' . $e->getMessage(),
            ]);

            Log::error('Refund processing failed', [
                'refund_id' => $refund->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Calculate refund amount based on cancellation policy
     */
    public function calculateCancellationRefund(Consultation $consultation, string $cancelledBy): array
    {
        // If lawyer cancels, always full refund
        if ($cancelledBy === 'lawyer') {
            return [
                'refund_type' => 'full',
                'refund_amount' => $consultation->total_amount,
                'refund_percentage' => 100,
            ];
        }

        // Client cancellation - check timing
        if (!$consultation->scheduled_at) {
            return [
                'refund_type' => 'full',
                'refund_amount' => $consultation->total_amount,
                'refund_percentage' => 100,
            ];
        }

        $hoursUntilConsultation = now()->diffInHours($consultation->scheduled_at, false);

        if ($hoursUntilConsultation > 24) {
            // More than 24 hours: full refund
            return [
                'refund_type' => 'full',
                'refund_amount' => $consultation->total_amount,
                'refund_percentage' => 100,
            ];
        } elseif ($hoursUntilConsultation > 12) {
            // 12-24 hours: 50% refund
            return [
                'refund_type' => 'partial',
                'refund_amount' => $consultation->total_amount * 0.5,
                'refund_percentage' => 50,
            ];
        } else {
            // Less than 12 hours: no refund
            return [
                'refund_type' => 'none',
                'refund_amount' => 0,
                'refund_percentage' => 0,
            ];
        }
    }

    /**
     * Check if transaction is eligible for refund
     */
    public function isEligibleForRefund(Transaction $transaction): bool
    {
        // Already has a refund
        if ($transaction->refund_id) {
            return false;
        }

        // Transaction must be completed/captured
        if (!in_array($transaction->status, ['completed', 'captured'])) {
            return false;
        }

        // CRITICAL: Cannot refund if already paid out to lawyer
        if ($transaction->payout_id) {
            Log::warning('Refund blocked: Transaction already paid out to lawyer', [
                'transaction_id' => $transaction->id,
                'payout_id' => $transaction->payout_id,
            ]);
            return false;
        }

        // Check if within refund window (30 days)
        if ($transaction->created_at->diffInDays(now()) > 30) {
            return false;
        }

        return true;
    }

    /**
     * Lawyer approves refund
     */
    public function lawyerApproveRefund(Refund $refund, int $lawyerId, ?string $notes = null): bool
    {
        if ($refund->lawyer_id !== $lawyerId) {
            return false;
        }

        if ($refund->lawyer_approval_status !== 'pending') {
            return false;
        }

        $refund->update([
            'lawyer_approval_status' => 'approved',
            'lawyer_notes' => $notes,
            'lawyer_responded_at' => now(),
        ]);

        // Notify client
        $refund->user->notify(new \App\Notifications\LawyerApprovedRefund($refund));

        Log::info('Lawyer approved refund', [
            'refund_id' => $refund->id,
            'lawyer_id' => $lawyerId,
        ]);

        return true;
    }

    /**
     * Lawyer rejects refund
     */
    public function lawyerRejectRefund(Refund $refund, int $lawyerId, string $notes): bool
    {
        if ($refund->lawyer_id !== $lawyerId) {
            return false;
        }

        if ($refund->lawyer_approval_status !== 'pending') {
            return false;
        }

        $refund->update([
            'lawyer_approval_status' => 'rejected',
            'lawyer_notes' => $notes,
            'lawyer_responded_at' => now(),
        ]);

        // Notify client
        $refund->user->notify(new \App\Notifications\LawyerRejectedRefund($refund));

        Log::info('Lawyer rejected refund', [
            'refund_id' => $refund->id,
            'lawyer_id' => $lawyerId,
            'notes' => $notes,
        ]);

        return true;
    }

    /**
     * Create PayMongo refund
     */
    private function createPayMongoRefund(Transaction $transaction, Refund $refund): array
    {
        // PayMongo refund API accepts payment_intent_id
        if (!$transaction->paymongo_payment_intent_id) {
            throw new \Exception('Transaction does not have a PayMongo payment intent ID. Refund can only be processed for completed payments.');
        }

        $secretKey = config('services.paymongo.secret_key');
        $baseUrl = 'https://api.paymongo.com/v1';

        // Convert amount to centavos (PayMongo uses smallest currency unit)
        $amountInCentavos = (int) ($refund->refund_amount * 100);

        $payload = [
            'data' => [
                'attributes' => [
                    'amount' => $amountInCentavos,
                    'payment_intent_id' => $transaction->paymongo_payment_intent_id,
                    'reason' => $this->getPayMongoRefundReason($refund->reason),
                    'notes' => $refund->detailed_reason ?? 'Refund requested by client',
                ],
            ],
        ];

        $response = \Http::withBasicAuth($secretKey, '')
            ->post("{$baseUrl}/refunds", $payload);

        if (!$response->successful()) {
            $error = $response->json();
            Log::error('PayMongo refund failed', [
                'refund_id' => $refund->id,
                'transaction_id' => $transaction->id,
                'payment_intent_id' => $transaction->paymongo_payment_intent_id,
                'error' => $error,
                'status' => $response->status(),
            ]);
            
            throw new \Exception('PayMongo refund failed: ' . ($error['errors'][0]['detail'] ?? 'Unknown error'));
        }

        $refundData = $response->json()['data'];

        Log::info('PayMongo refund created', [
            'refund_id' => $refund->id,
            'paymongo_refund_id' => $refundData['id'],
            'payment_intent_id' => $transaction->paymongo_payment_intent_id,
            'amount' => $refundData['attributes']['amount'],
            'status' => $refundData['attributes']['status'],
        ]);

        return $refundData;
    }

    /**
     * Map internal refund reason to PayMongo reason
     */
    private function getPayMongoRefundReason(string $reason): string
    {
        return match($reason) {
            'service_not_provided' => 'fraudulent',
            'service_unsatisfactory' => 'requested_by_customer',
            'lawyer_cancelled' => 'requested_by_customer',
            'document_not_delivered' => 'requested_by_customer',
            'technical_issues' => 'requested_by_customer',
            'duplicate_payment' => 'duplicate',
            'other' => 'requested_by_customer',
            default => 'requested_by_customer',
        };
    }
}

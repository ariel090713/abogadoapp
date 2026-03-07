<?php

namespace App\Services;

use App\Models\Payout;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayoutService
{
    /**
     * Hold period in days before transactions become eligible for payout
     */
    const HOLD_PERIOD_DAYS = 7;

    /**
     * Minimum payout amount
     */
    const MINIMUM_PAYOUT_AMOUNT = 1000;

    /**
     * Create payout for a lawyer
     */
    public function createPayout(
        int $lawyerId,
        array $transactionIds,
        int $adminId,
        ?string $notes = null
    ): ?Payout {
        return DB::transaction(function () use ($lawyerId, $transactionIds, $adminId, $notes) {
            // Get eligible transactions
            $transactions = Transaction::whereIn('id', $transactionIds)
                ->where('lawyer_id', $lawyerId)
                ->where('status', 'completed')
                ->whereNull('payout_id')
                ->whereNull('refund_id')
                ->where('created_at', '<=', now()->subDays(self::HOLD_PERIOD_DAYS))
                ->get();

            if ($transactions->isEmpty()) {
                Log::warning('No eligible transactions found for payout', [
                    'lawyer_id' => $lawyerId,
                    'transaction_ids' => $transactionIds,
                ]);
                return null;
            }

            // Calculate total amount
            $totalAmount = $transactions->sum('lawyer_payout');

            // Check minimum amount
            if ($totalAmount < self::MINIMUM_PAYOUT_AMOUNT) {
                Log::warning('Payout amount below minimum', [
                    'lawyer_id' => $lawyerId,
                    'amount' => $totalAmount,
                    'minimum' => self::MINIMUM_PAYOUT_AMOUNT,
                ]);
                return null;
            }

            // Create payout record
            $payout = Payout::create([
                'lawyer_id' => $lawyerId,
                'amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $notes,
                'processed_by' => $adminId,
            ]);

            // Link transactions to payout
            $transactions->each(function ($transaction) use ($payout) {
                $transaction->update(['payout_id' => $payout->id]);
            });

            Log::info('Payout created', [
                'payout_id' => $payout->id,
                'lawyer_id' => $lawyerId,
                'amount' => $totalAmount,
                'transaction_count' => $transactions->count(),
            ]);

            return $payout;
        });
    }

    /**
     * Create batch payouts for multiple lawyers
     */
    public function createBatchPayouts(
        array $lawyerIds,
        int $adminId,
        ?string $notes = null
    ): array {
        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($lawyerIds as $lawyerId) {
            try {
                // Get all eligible transactions for this lawyer
                $transactions = Payout::getEligibleTransactions($lawyerId, self::HOLD_PERIOD_DAYS);
                
                if ($transactions->isEmpty()) {
                    $results['failed'][] = [
                        'lawyer_id' => $lawyerId,
                        'reason' => 'No eligible transactions',
                    ];
                    continue;
                }

                $transactionIds = $transactions->pluck('id')->toArray();
                $payout = $this->createPayout($lawyerId, $transactionIds, $adminId, $notes);

                if ($payout) {
                    $results['success'][] = [
                        'lawyer_id' => $lawyerId,
                        'payout_id' => $payout->id,
                        'amount' => $payout->amount,
                    ];
                } else {
                    $results['failed'][] = [
                        'lawyer_id' => $lawyerId,
                        'reason' => 'Failed to create payout',
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Batch payout creation failed', [
                    'lawyer_id' => $lawyerId,
                    'error' => $e->getMessage(),
                ]);

                $results['failed'][] = [
                    'lawyer_id' => $lawyerId,
                    'reason' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Mark payout as processing
     */
    public function markAsProcessing(Payout $payout, int $adminId): bool
    {
        if ($payout->status !== 'pending') {
            return false;
        }

        $payout->update([
            'status' => 'processing',
            'processed_by' => $adminId,
        ]);

        Log::info('Payout marked as processing', [
            'payout_id' => $payout->id,
            'processed_by' => $adminId,
        ]);

        return true;
    }

    /**
     * Complete payout
     */
    public function completePayout(
        Payout $payout,
        int $adminId,
        string $method,
        string $referenceNumber,
        ?string $notes = null
    ): bool {
        if (!in_array($payout->status, ['pending', 'processing'])) {
            return false;
        }

        $payout->update([
            'status' => 'completed',
            'method' => $method,
            'reference_number' => $referenceNumber,
            'notes' => $notes ?? $payout->notes,
            'processed_by' => $adminId,
            'processed_at' => now(),
        ]);

        // Send notification to lawyer
        $payout->lawyer->notify(new \App\Notifications\PayoutCompleted($payout));

        Log::info('Payout completed', [
            'payout_id' => $payout->id,
            'lawyer_id' => $payout->lawyer_id,
            'amount' => $payout->amount,
            'method' => $method,
            'reference' => $referenceNumber,
        ]);

        return true;
    }

    /**
     * Mark payout as failed
     */
    public function markAsFailed(
        Payout $payout,
        int $adminId,
        string $reason
    ): bool {
        if (!in_array($payout->status, ['pending', 'processing'])) {
            return false;
        }

        return DB::transaction(function () use ($payout, $adminId, $reason) {
            // Unlink transactions
            Transaction::where('payout_id', $payout->id)
                ->update(['payout_id' => null]);

            $payout->update([
                'status' => 'failed',
                'notes' => $reason,
                'processed_by' => $adminId,
            ]);

            Log::info('Payout marked as failed', [
                'payout_id' => $payout->id,
                'reason' => $reason,
            ]);

            return true;
        });
    }

    /**
     * Get payout statistics
     */
    public function getPayoutStats(): array
    {
        return [
            'total_payouts' => Payout::count(),
            'pending_payouts' => Payout::where('status', 'pending')->count(),
            'processing_payouts' => Payout::where('status', 'processing')->count(),
            'completed_payouts' => Payout::where('status', 'completed')->count(),
            'failed_payouts' => Payout::where('status', 'failed')->count(),
            'total_amount_paid' => Payout::where('status', 'completed')->sum('amount'),
            'pending_amount' => Payout::where('status', 'pending')->sum('amount'),
        ];
    }

    /**
     * Get eligible lawyers for payout
     */
    public function getEligibleLawyers(): array
    {
        return Payout::getLawyersWithEligiblePayouts(
            self::MINIMUM_PAYOUT_AMOUNT,
            self::HOLD_PERIOD_DAYS
        );
    }

    /**
     * Check if transaction is eligible for payout
     */
    public function isTransactionEligible(Transaction $transaction): bool
    {
        // Must be completed
        if ($transaction->status !== 'completed') {
            return false;
        }

        // Must not be already paid out
        if ($transaction->payout_id) {
            return false;
        }

        // Must not be refunded
        if ($transaction->refund_id) {
            return false;
        }

        // Must be past hold period
        if (Payout::isInHoldPeriod($transaction, self::HOLD_PERIOD_DAYS)) {
            return false;
        }

        return true;
    }

    /**
     * Get hold period information for transaction
     */
    public function getHoldPeriodInfo(Transaction $transaction): array
    {
        $isInHold = Payout::isInHoldPeriod($transaction, self::HOLD_PERIOD_DAYS);
        $daysRemaining = Payout::getDaysRemainingInHold($transaction, self::HOLD_PERIOD_DAYS);
        $eligibleDate = $transaction->created_at->addDays(self::HOLD_PERIOD_DAYS);

        return [
            'is_in_hold' => $isInHold,
            'days_remaining' => $daysRemaining,
            'eligible_date' => $eligibleDate,
            'hold_period_days' => self::HOLD_PERIOD_DAYS,
        ];
    }
}

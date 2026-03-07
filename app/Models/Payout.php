<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payout extends Model
{
    protected $fillable = [
        'lawyer_id',
        'amount',
        'status',
        'method',
        'reference_number',
        'notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get eligible transactions for payout
     * 
     * @param int $lawyerId
     * @param int $holdDays Number of days to hold before payout (default: 7)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getEligibleTransactions(int $lawyerId, int $holdDays = 7)
    {
        return \App\Models\Transaction::where('lawyer_id', $lawyerId)
            ->where('status', 'completed')
            ->whereNull('payout_id')
            ->whereNull('refund_id')
            ->where('created_at', '<=', now()->subDays($holdDays))
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get all lawyers with eligible transactions
     * 
     * @param float $minimumAmount Minimum total amount to include lawyer (default: 1000)
     * @param int $holdDays Number of days to hold before payout (default: 7)
     * @return array
     */
    public static function getLawyersWithEligiblePayouts(float $minimumAmount = 1000, int $holdDays = 7)
    {
        $lawyers = \App\Models\User::whereHas('lawyerProfile')
            ->whereHas('lawyerTransactions', function ($query) use ($holdDays) {
                $query->where('status', 'completed')
                    ->whereNull('payout_id')
                    ->whereNull('refund_id')
                    ->where('created_at', '<=', now()->subDays($holdDays));
            })
            ->with(['lawyerProfile', 'lawyerTransactions' => function ($query) use ($holdDays) {
                $query->where('status', 'completed')
                    ->whereNull('payout_id')
                    ->whereNull('refund_id')
                    ->where('created_at', '<=', now()->subDays($holdDays))
                    ->orderBy('created_at', 'asc');
            }])
            ->get();

        $result = [];
        foreach ($lawyers as $lawyer) {
            $totalAmount = $lawyer->lawyerTransactions->sum('lawyer_payout');
            $transactionCount = $lawyer->lawyerTransactions->count();

            if ($totalAmount >= $minimumAmount) {
                $result[] = [
                    'lawyer' => $lawyer,
                    'total_amount' => $totalAmount,
                    'transaction_count' => $transactionCount,
                    'transactions' => $lawyer->lawyerTransactions,
                ];
            }
        }

        return $result;
    }

    /**
     * Check if transaction is in hold period
     * 
     * @param \App\Models\Transaction $transaction
     * @param int $holdDays
     * @return bool
     */
    public static function isInHoldPeriod(\App\Models\Transaction $transaction, int $holdDays = 7): bool
    {
        return $transaction->created_at->diffInDays(now()) < $holdDays;
    }

    /**
     * Get days remaining in hold period
     * 
     * @param \App\Models\Transaction $transaction
     * @param int $holdDays
     * @return int
     */
    public static function getDaysRemainingInHold(\App\Models\Transaction $transaction, int $holdDays = 7): int
    {
        $daysElapsed = $transaction->created_at->diffInDays(now());
        $remaining = $holdDays - $daysElapsed;
        return max(0, $remaining);
    }
}

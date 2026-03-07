<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'consultation_id',
        'document_request_id',
        'user_id',
        'lawyer_id',
        'type',
        'amount',
        'platform_fee',
        'lawyer_payout',
        'status',
        'payment_method',
        'paymongo_payment_id',
        'paymongo_payment_intent_id',
        'paymongo_payment_method_id',
        'refund_id',
        'payout_id',
        'payment_details',
        'failure_reason',
        'processed_at',
        'reference_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'lawyer_payout' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function documentRequest(): BelongsTo
    {
        return $this->belongsTo(DocumentDraftingRequest::class, 'document_request_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    public function refund(): BelongsTo
    {
        return $this->belongsTo(Refund::class);
    }

    public function payout(): BelongsTo
    {
        return $this->belongsTo(Payout::class);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'captured');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'held']);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}

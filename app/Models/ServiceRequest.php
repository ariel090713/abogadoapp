<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequest extends Model
{
    protected $fillable = [
        'consultation_id',
        'requested_by',
        'request_type',
        'service_type',
        'description',
        'proposed_date',
        'proposed_price',
        'status',
        'response_notes',
        'responded_at',
        'responded_by',
    ];

    protected $casts = [
        'proposed_date' => 'datetime',
        'responded_at' => 'datetime',
        'proposed_price' => 'decimal:2',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    // Status checks
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isDeclined(): bool
    {
        return $this->status === 'declined';
    }

    // Check if request has a fee
    public function isFree(): bool
    {
        return $this->proposed_price === null || $this->proposed_price == 0;
    }

    public function hasFee(): bool
    {
        return !$this->isFree();
    }
}

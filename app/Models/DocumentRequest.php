<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DocumentRequest extends Model
{
    protected $fillable = [
        'consultation_id',
        'requested_by',
        'document_type',
        'description',
        'deadline',
        'review_fee',
        'status',
        'submitted_at',
        'document_paths',
        'review_notes',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'submitted_at' => 'datetime',
        'review_fee' => 'decimal:2',
        'document_paths' => 'array',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    // Status checks
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function isReviewed(): bool
    {
        return $this->status === 'reviewed';
    }

    public function isDeclined(): bool
    {
        return $this->status === 'declined';
    }

    // Check if request has a review fee
    public function isFree(): bool
    {
        return $this->review_fee === null || $this->review_fee == 0;
    }

    public function hasFee(): bool
    {
        return !$this->isFree();
    }

    // Check if deadline has passed
    public function isOverdue(): bool
    {
        return $this->deadline && now()->greaterThan($this->deadline) && $this->isPending();
    }
}

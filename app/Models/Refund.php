<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refund extends Model
{
    protected $fillable = [
        'transaction_id',
        'consultation_id',
        'document_request_id',
        'user_id',
        'lawyer_id',
        'refund_type',
        'refund_amount',
        'original_amount',
        'reason',
        'detailed_reason',
        'status',
        'lawyer_approval_status',
        'lawyer_notes',
        'lawyer_responded_at',
        'lawyer_response_deadline',
        'admin_notes',
        'paymongo_refund_id',
        'processed_at',
        'approved_by',
        'approved_at',
        'rejected_at',
        'rejection_reason',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'original_amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'lawyer_responded_at' => 'datetime',
        'lawyer_response_deadline' => 'datetime',
    ];

    // Relationships
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function documentRequest(): BelongsTo
    {
        return $this->belongsTo(DocumentRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Helper Methods
    public function isAutomatic(): bool
    {
        return in_array($this->reason, [
            'expired_lawyer_response',
            'expired_quote',
            'expired_payment',
            'lawyer_declined',
        ]);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isLawyerApprovalPending(): bool
    {
        return $this->lawyer_approval_status === 'pending';
    }

    public function isLawyerApproved(): bool
    {
        return $this->lawyer_approval_status === 'approved';
    }

    public function isLawyerRejected(): bool
    {
        return $this->lawyer_approval_status === 'rejected';
    }

    public function getReasonLabel(): string
    {
        return match($this->reason) {
            'expired_lawyer_response' => 'Lawyer did not respond within deadline',
            'expired_quote' => 'Quote approval deadline expired',
            'expired_payment' => 'Payment deadline expired',
            'lawyer_declined' => 'Lawyer declined consultation',
            'lawyer_cancelled' => 'Lawyer cancelled consultation',
            'client_cancelled' => 'Client cancelled consultation',
            'document_not_delivered' => 'Document not delivered on time',
            'dispute' => 'Dispute/Complaint',
            'other' => 'Other reason',
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'rejected' => 'bg-red-100 text-red-800',
            'processing' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
        };
    }
}

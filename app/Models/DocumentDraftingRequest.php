<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentDraftingRequest extends Model
{
    protected $fillable = [
        'client_id',
        'lawyer_id',
        'lawyer_document_service_id',
        'document_name',
        'form_data',
        'price',
        'status',
        'revisions_used',
        'revisions_allowed',
        'revision_notes',
        'payment_status',
        'payment_intent_id',
        'payment_deadline',
        'completion_deadline',
        'draft_document_path',
        'client_notes',
        'lawyer_notes',
        'paid_at',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'form_data' => 'array',
        'price' => 'decimal:2',
        'payment_deadline' => 'datetime',
        'completion_deadline' => 'datetime',
        'paid_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the client who made the request
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the lawyer handling the request
     */
    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    /**
     * Get the document service
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(LawyerDocumentService::class, 'lawyer_document_service_id');
    }

    /**
     * Get the review for this document request
     */
    public function review()
    {
        return $this->hasOne(Review::class, 'document_request_id');
    }

    /**
     * Scope to get pending payment requests
     */
    public function scopePendingPayment($query)
    {
        return $query->where('status', 'pending_payment');
    }

    /**
     * Scope to get paid requests
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope to get in progress requests
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope to get completed requests
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}

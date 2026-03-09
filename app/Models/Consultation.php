<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Consultation extends Model
{
    protected $fillable = [
        'client_id',
        'lawyer_id',
        'parent_consultation_id',
        'initiated_by',
        'case_number',
        'session_number',
        'is_follow_up',
        'follow_up_type',
        'case_status',
        'case_closed_at',
        'case_closure_notes',
        'consultation_type',
        'title',
        'duration',
        'rate',
        'platform_fee',
        'total_amount',
        'quoted_price',
        'quote_notes',
        'quote_provided_at',
        'quote_accepted_at',
        'status',
        // payment_status - REMOVED: Now accessed via transaction relationship
        // payment_intent_id - REMOVED: Now accessed via transaction relationship
        'scheduled_at',
        'accepted_at',
        'payment_deadline',
        'lawyer_response_deadline',
        'quote_deadline',
        'payment_deadline_calculated',
        'review_completion_deadline',
        'estimated_turnaround_days',
        'started_at',
        'ended_at',
        'completed_at',
        'completion_updated_at',
        'reminder_24h_sent_at',
        'reminder_1h_sent_at',
        'reminder_15m_sent_at',
        'client_notes',
        'document_path',
        'reviewed_document_path',
        'reviewed_document_deleted_path',
        'reviewed_document_deleted_at',
        'lawyer_notes',
        'completion_notes',
        'suggested_times',
        'decline_reason',
        'cancel_reason',
        'video_room_sid',
        'recording_enabled',
        'original_scheduled_at',
        'reschedule_status',
        'reschedule_requested_by',
        'reschedule_requested_at',
        'proposed_scheduled_at',
        'reschedule_reason',
        'reschedule_decline_reason',
        'reschedule_count',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'accepted_at' => 'datetime',
        'payment_deadline' => 'datetime',
        'lawyer_response_deadline' => 'datetime',
        'quote_deadline' => 'datetime',
        'payment_deadline_calculated' => 'datetime',
        'review_completion_deadline' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'completed_at' => 'datetime',
        'completion_updated_at' => 'datetime',
        'reviewed_document_deleted_at' => 'datetime',
        'reminder_24h_sent_at' => 'datetime',
        'reminder_1h_sent_at' => 'datetime',
        'reminder_15m_sent_at' => 'datetime',
        'quote_provided_at' => 'datetime',
        'quote_accepted_at' => 'datetime',
        'original_scheduled_at' => 'datetime',
        'reschedule_requested_at' => 'datetime',
        'proposed_scheduled_at' => 'datetime',
        'case_closed_at' => 'datetime',
        'rate' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'quoted_price' => 'decimal:2',
        'recording_enabled' => 'boolean',
        'is_follow_up' => 'boolean',
        'suggested_times' => 'array',
    ];

    // ==========================================
    // PAYMENT ACCESSORS & HELPERS
    // ==========================================
    // These methods provide access to payment data via transaction relationship
    // since payment_status and payment_intent_id columns have been removed.
    
    /**
     * Get payment status from transaction (accessor)
     * Maps transaction.status to payment_status for backward compatibility
     * 
     * @return string
     */
    public function getPaymentStatusAttribute()
    {
        // Always load transaction if not loaded
        if (!$this->relationLoaded('transaction')) {
            $this->load('transaction');
        }
        
        // If we have a transaction, use its status as source of truth
        if ($this->transaction) {
            return match($this->transaction->status) {
                'pending' => 'pending',
                'processing' => 'processing',
                'completed' => 'paid',
                'failed' => 'failed',
                'refunded' => 'refunded',
                default => 'unpaid',
            };
        }
        
        // No transaction = unpaid (or free if total_amount is 0)
        return $this->total_amount == 0 ? 'free' : 'unpaid';
    }
    
    /**
     * Get payment intent ID from transaction (accessor)
     * 
     * @return string|null
     */
    public function getPaymentIntentIdAttribute()
    {
        // Always load transaction if not loaded
        if (!$this->relationLoaded('transaction')) {
            $this->load('transaction');
        }
        
        return $this->transaction?->paymongo_payment_intent_id;
    }
    
    /**
     * Check if consultation is paid
     * 
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->transaction && $this->transaction->status === 'completed';
    }
    
    /**
     * Check if payment is pending (initiated but not completed)
     * 
     * @return bool
     */
    public function isPaymentPending(): bool
    {
        if (!$this->transaction) {
            return false;
        }
        
        return in_array($this->transaction->status, ['pending', 'processing']);
    }
    
    /**
     * Check if payment is processing (user returned from PayMongo)
     * 
     * @return bool
     */
    public function isPaymentProcessing(): bool
    {
        return $this->transaction && $this->transaction->status === 'processing';
    }
    
    /**
     * Check if payment failed
     * 
     * @return bool
     */
    public function isPaymentFailed(): bool
    {
        return $this->transaction && $this->transaction->status === 'failed';
    }
    
    /**
     * Check if payment was refunded
     * 
     * @return bool
     */
    public function isPaymentRefunded(): bool
    {
        return $this->transaction && $this->transaction->status === 'refunded';
    }
    
    /**
     * Check if consultation is unpaid (no transaction or pending)
     * 
     * @return bool
     */
    public function isUnpaid(): bool
    {
        if (!$this->transaction) {
            return true;
        }
        
        return $this->transaction->status === 'pending';
    }
    
    /**
     * Check if consultation is free (no payment required)
     * 
     * @return bool
     */
    public function isFree(): bool
    {
        return $this->total_amount == 0;
    }
    
    /**
     * Get payment method from transaction
     * 
     * @return string|null
     */
    public function getPaymentMethod(): ?string
    {
        return $this->transaction?->payment_method;
    }
    
    /**
     * Get payment processed date from transaction
     * 
     * @return \Carbon\Carbon|null
     */
    public function getPaymentProcessedAt()
    {
        return $this->transaction?->processed_at;
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ConsultationMessage::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ConsultationDocument::class);
    }

    public function activeDocuments(): HasMany
    {
        return $this->hasMany(ConsultationDocument::class)->whereNull('deleted_at');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }


    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'pending_client_acceptance']);
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['accepted', 'scheduled'])
            ->where('scheduled_at', '>', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeAwaitingPayment($query)
    {
        return $query->where('status', 'payment_pending')
            ->where('payment_deadline', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'payment_pending')
            ->where('payment_deadline', '<=', now());
    }

    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId)
            ->whereHas('lawyer'); // Exclude consultations with soft-deleted lawyers
    }

    public function scopeForLawyer($query, $lawyerId)
    {
        return $query->where('lawyer_id', $lawyerId)
            ->whereHas('client'); // Exclude consultations with soft-deleted clients
    }
    
    // ==========================================
    // PAYMENT STATUS SCOPES
    // ==========================================
    // These scopes filter consultations by payment status using transaction relationship
    
    /**
     * Scope: Consultations with completed payment
     */
    public function scopePaid($query)
    {
        return $query->whereHas('transaction', function($q) {
            $q->where('status', 'completed');
        });
    }
    
    /**
     * Scope: Consultations with pending or processing payment
     */
    public function scopePaymentPending($query)
    {
        return $query->whereHas('transaction', function($q) {
            $q->whereIn('status', ['pending', 'processing']);
        });
    }
    
    /**
     * Scope: Consultations with processing payment (user returned from PayMongo)
     */
    public function scopePaymentProcessing($query)
    {
        return $query->whereHas('transaction', function($q) {
            $q->where('status', 'processing');
        });
    }
    
    /**
     * Scope: Consultations with failed payment
     */
    public function scopePaymentFailed($query)
    {
        return $query->whereHas('transaction', function($q) {
            $q->where('status', 'failed');
        });
    }
    
    /**
     * Scope: Consultations with refunded payment
     */
    public function scopePaymentRefunded($query)
    {
        return $query->whereHas('transaction', function($q) {
            $q->where('status', 'refunded');
        });
    }
    
    /**
     * Scope: Consultations without payment or with pending payment
     */
    public function scopeUnpaid($query)
    {
        return $query->where(function($q) {
            $q->whereDoesntHave('transaction')
              ->orWhereHas('transaction', function($subQ) {
                  $subQ->where('status', 'pending');
              });
        });
    }
    
    /**
     * Scope: Free consultations (no payment required)
     */
    public function scopeFree($query)
    {
        return $query->where('total_amount', 0);
    }

    /**
     * Get temporary signed URL for document (expires in 1 hour)
     * Only accessible by client or lawyer involved in consultation
     */
    public function getDocumentUrl(): ?string
    {
        if (!$this->document_path) {
            return null;
        }

        $fileService = app(\App\Services\FileUploadService::class);
        return $fileService->getPrivateUrl($this->document_path, 60);
    }

    /**
     * Check if user can access this consultation's document
     */
    public function canAccessDocument($userId): bool
    {
        return $this->document_path && 
               ($this->client_id === $userId || $this->lawyer_id === $userId);
    }

    /**
     * Get original filename from document path
     */
    public function getDocumentFilename(): ?string
    {
        if (!$this->document_path) {
            return null;
        }

        // Extract filename from path
        return basename($this->document_path);
    }

    /**
     * Get temporary signed URL for reviewed document (expires in 1 hour)
     */
    public function getReviewedDocumentUrl(): ?string
    {
        if (!$this->reviewed_document_path) {
            return null;
        }

        $fileService = app(\App\Services\FileUploadService::class);
        return $fileService->getPrivateUrl($this->reviewed_document_path, 60);
    }

    /**
     * Get original filename from reviewed document path
     */
    public function getReviewedDocumentFilename(): ?string
    {
        if (!$this->reviewed_document_path) {
            return null;
        }

        return basename($this->reviewed_document_path);
    }

    /**
     * Check if consultation has ended (time passed) but not completed
     */
    public function hasEnded(): bool
    {
        if (!$this->scheduled_at || !$this->duration) {
            return false;
        }

        $endTime = $this->scheduled_at->copy()->addMinutes($this->duration);
        return now()->gte($endTime) && $this->status !== 'completed';
    }

    /**
     * Get display status for consultation cards
     */
    public function getDisplayStatus(): string
    {
        // If ended but not completed, show special status
        if ($this->hasEnded() && in_array($this->status, ['scheduled', 'in_progress'])) {
            return 'ended';
        }

        // Return the actual status (payment_processing is now a real status)
        return $this->status;
    }

    /**
     * Relationship: User who requested reschedule
     */
    public function rescheduleRequestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reschedule_requested_by');
    }

    /**
     * Check if consultation can be rescheduled
     */
    public function canBeRescheduled(): bool
    {
        // Can only reschedule if status is scheduled or payment_pending
        if (!in_array($this->status, ['scheduled', 'payment_pending'])) {
            return false;
        }

        // Cannot reschedule if already at max limit
        if ($this->reschedule_count >= 2) {
            return false;
        }

        // Cannot reschedule if there's already a pending reschedule request
        if ($this->reschedule_status === 'pending') {
            return false;
        }

        // Cannot reschedule within 24 hours of consultation
        if ($this->scheduled_at && $this->scheduled_at->diffInHours(now()) < 24) {
            return false;
        }

        return true;
    }

    /**
     * Check if there's a pending reschedule request
     */
    public function isReschedulePending(): bool
    {
        return $this->reschedule_status === 'pending';
    }

    /**
     * Check if reschedule limit has been reached
     */
    public function hasReachedRescheduleLimit(): bool
    {
        return $this->reschedule_count >= 2;
    }

    /**
     * Get reschedules remaining
     */
    public function getReschedulesRemaining(): int
    {
        return max(0, 2 - $this->reschedule_count);
    }

    // ==========================================
    // CASE MANAGEMENT RELATIONSHIPS & METHODS
    // ==========================================

    /**
     * Parent consultation (main case)
     */
    public function parentConsultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class, 'parent_consultation_id');
    }

    /**
     * Child consultations (follow-up sessions)
     */
    public function childConsultations()
    {
        return $this->hasMany(Consultation::class, 'parent_consultation_id')->orderBy('session_number');
    }

    /**
     * Check if this is a main case (parent)
     */
    public function isMainCase(): bool
    {
        return $this->parent_consultation_id === null;
    }

    /**
     * Check if this is a follow-up session (child)
     */
    public function isFollowUpSession(): bool
    {
        return $this->parent_consultation_id !== null;
    }

    /**
     * Get all sessions in this case (including self)
     */
    public function getAllSessions()
    {
        if ($this->isMainCase()) {
            // If this is the parent, get all children plus self
            return collect([$this])->merge($this->childConsultations);
        }
        
        // If this is a child, get parent and all siblings
        return collect([$this->parentConsultation])
            ->merge($this->parentConsultation->childConsultations);
    }

    /**
     * Get case number (generates if not exists)
     */
    public function getCaseNumber(): string
    {
        if ($this->isMainCase()) {
            return $this->case_number ?? 'CASE-' . now()->format('Y') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
        }
        
        return $this->parentConsultation->case_number ?? $this->parentConsultation->getCaseNumber();
    }

    /**
     * Get thread number (alias for getCaseNumber for better naming)
     */
    public function getThreadNumber(): string
    {
        return $this->getCaseNumber();
    }

    /**
     * Get the main case consultation
     */
    public function getMainCase(): Consultation
    {
        return $this->isMainCase() ? $this : $this->parentConsultation;
    }

    /**
     * Get total sessions count in this case
     */
    public function getTotalSessionsCount(): int
    {
        if ($this->isMainCase()) {
            return 1 + $this->childConsultations()->count();
        }
        
        return $this->parentConsultation->getTotalSessionsCount();
    }

    /**
     * Check if case is active (has active sessions)
     */
    public function isCaseActive(): bool
    {
        $mainCase = $this->getMainCase();
        
        if ($mainCase->case_status === 'closed') {
            return false;
        }
        
        // Check if any session is in progress or scheduled
        $allSessions = $mainCase->getAllSessions();
        
        return $allSessions->contains(function ($session) {
            return in_array($session->status, ['pending', 'scheduled', 'in_progress', 'payment_pending', 'awaiting_quote_approval']);
        });
    }

    /**
     * Check if case can be closed
     */
    public function canCloseCase(): bool
    {
        $mainCase = $this->getMainCase();
        
        // Already closed
        if ($mainCase->case_status === 'closed') {
            return false;
        }
        
        // Check if all sessions are completed or cancelled
        $allSessions = $mainCase->getAllSessions();
        
        return $allSessions->every(function ($session) {
            return in_array($session->status, ['completed', 'cancelled', 'declined', 'expired']);
        });
    }

    /**
     * Close the case
     */
    public function closeCase(string $notes = null): bool
    {
        $mainCase = $this->getMainCase();
        
        if (!$mainCase->canCloseCase()) {
            return false;
        }
        
        $mainCase->update([
            'case_status' => 'closed',
            'case_closed_at' => now(),
            'case_closure_notes' => $notes,
        ]);
        
        return true;
    }

    // ==========================================
    // SERVICE & DOCUMENT REQUEST RELATIONSHIPS
    // ==========================================

    /**
     * Service requests for this consultation
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Document requests for this consultation
     */
    public function documentRequests()
    {
        return $this->hasMany(DocumentRequest::class);
    }

    /**
     * Pending service requests
     */
    public function pendingServiceRequests()
    {
        return $this->serviceRequests()->where('status', 'pending');
    }

    /**
     * Pending document requests
     */
    public function pendingDocumentRequests()
    {
        return $this->documentRequests()->where('status', 'pending');
    }

    /**
     * Check if consultation has any pending requests
     */
    public function hasPendingRequests(): bool
    {
        return $this->pendingServiceRequests()->exists() 
            || $this->pendingDocumentRequests()->exists();
    }
}



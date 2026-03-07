<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'lawyer_profile_id',
        'client_id',
        'consultation_id',
        'document_request_id',
        'service_request_id',
        'rating',
        'comment',
        'is_edited',
        'edited_at',
        'published_at',
    ];

    protected $casts = [
        'is_edited' => 'boolean',
        'edited_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function lawyerProfile(): BelongsTo
    {
        return $this->belongsTo(LawyerProfile::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function documentRequest(): BelongsTo
    {
        return $this->belongsTo(DocumentDraftingRequest::class, 'document_request_id');
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    // Check if review can be edited (within 7 days and not edited before)
    public function canEdit(): bool
    {
        if ($this->is_edited) {
            return false;
        }

        return $this->created_at->diffInDays(now()) <= 7;
    }

    // Get service type
    public function getServiceTypeAttribute(): string
    {
        if ($this->consultation_id) {
            return 'Consultation';
        }
        if ($this->document_request_id) {
            return 'Document';
        }
        if ($this->service_request_id) {
            return 'Service';
        }
        return 'Unknown';
    }

    // Get service name
    public function getServiceNameAttribute(): string
    {
        if ($this->consultation_id && $this->consultation) {
            return ucfirst($this->consultation->type);
        }
        if ($this->document_request_id && $this->documentRequest) {
            return $this->documentRequest->document_name ?? 'Document Request';
        }
        if ($this->service_request_id && $this->serviceRequest) {
            return $this->serviceRequest->service_type;
        }
        return 'Service';
    }
}

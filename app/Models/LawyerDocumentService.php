<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LawyerDocumentService extends Model
{
    protected $fillable = [
        'lawyer_id',
        'template_id',
        'name',
        'description',
        'category',
        'form_fields',
        'price',
        'estimated_client_time',
        'estimated_completion_days',
        'revisions_allowed',
        'is_active',
        'total_orders',
    ];

    protected $casts = [
        'form_fields' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the lawyer who owns this service
     */
    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    /**
     * Get the template this service is based on
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(DocumentTemplate::class, 'template_id');
    }

    /**
     * Get all requests for this service
     */
    public function requests(): HasMany
    {
        return $this->hasMany(DocumentDraftingRequest::class, 'lawyer_document_service_id');
    }

    /**
     * Increment total orders
     */
    public function incrementOrders()
    {
        $this->increment('total_orders');
    }

    /**
     * Scope to get only active services
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

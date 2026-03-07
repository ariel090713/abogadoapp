<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'form_fields',
        'sample_output',
        'is_active',
        'created_by',
        'usage_count',
    ];

    protected $casts = [
        'form_fields' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the admin who created this template
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get lawyer services using this template
     */
    public function lawyerServices(): HasMany
    {
        return $this->hasMany(LawyerDocumentService::class, 'template_id');
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }
}

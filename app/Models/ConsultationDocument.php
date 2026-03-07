<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationDocument extends Model
{
    protected $fillable = [
        'consultation_id',
        'uploaded_by',
        'original_filename',
        'stored_filename',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_at',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function isDeleted(): bool
    {
        return $this->deleted_at !== null;
    }

    public function getFileSizeFormatted(): string
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' bytes';
    }

    // Scope to get only non-deleted documents
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}

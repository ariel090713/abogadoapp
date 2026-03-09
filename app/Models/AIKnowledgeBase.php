<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AIKnowledgeBase extends Model
{
    protected $table = 'ai_knowledge_base';

    protected $fillable = [
        'title',
        'content',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
        'file_size' => 'integer',
    ];

    /**
     * Get all active knowledge base entries ordered by priority
     */
    public static function getActiveKnowledge()
    {
        return self::where('is_active', true)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get combined knowledge base content for AI context
     */
    public static function getCombinedContext()
    {
        $entries = self::getActiveKnowledge();
        
        $context = [];
        foreach ($entries as $entry) {
            $context[] = "=== {$entry->title} ===\n{$entry->content}";
        }

        return implode("\n\n", $context);
    }

    /**
     * Delete file when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($knowledge) {
            if ($knowledge->file_path && Storage::disk('s3-private')->exists($knowledge->file_path)) {
                Storage::disk('s3-private')->delete($knowledge->file_path);
            }
        });
    }
}

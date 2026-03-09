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
        'mime_type',
        'content_hash',
        'chunks',
        'chunk_size',
        'chunk_overlap',
        'is_active',
        'priority',
        'metadata',
        'processed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
        'file_size' => 'integer',
        'chunk_size' => 'integer',
        'chunk_overlap' => 'integer',
        'chunks' => 'array',
        'metadata' => 'array',
        'processed_at' => 'datetime',
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
     * Generate content hash for deduplication
     */
    public function generateContentHash(): string
    {
        return hash('sha256', $this->content);
    }

    /**
     * Check if content already exists (by hash)
     */
    public static function contentExists(string $content): bool
    {
        $hash = hash('sha256', $content);
        return self::where('content_hash', $hash)->exists();
    }

    /**
     * Chunk the content for RAG
     */
    public function chunkContent(int $chunkSize = 1000, int $overlap = 200): array
    {
        $content = $this->content;
        $chunks = [];
        $contentLength = mb_strlen($content);
        
        if ($contentLength <= $chunkSize) {
            return [$content];
        }
        
        $start = 0;
        while ($start < $contentLength) {
            $chunk = mb_substr($content, $start, $chunkSize);
            $chunks[] = $chunk;
            $start += ($chunkSize - $overlap);
        }
        
        return $chunks;
    }

    /**
     * Process and store chunks
     */
    public function processAndStoreChunks(int $chunkSize = 1000, int $overlap = 200): void
    {
        $chunks = $this->chunkContent($chunkSize, $overlap);
        
        $this->update([
            'chunks' => $chunks,
            'chunk_size' => $chunkSize,
            'chunk_overlap' => $overlap,
            'content_hash' => $this->generateContentHash(),
            'processed_at' => now(),
        ]);
    }

    /**
     * Get all active knowledge base entries with their chunks
     */
    public static function getActiveKnowledgeWithChunks(): array
    {
        return self::where('is_active', true)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($kb) {
                return [
                    'id' => $kb->id,
                    'title' => $kb->title,
                    'content' => $kb->content,
                    'chunks' => $kb->chunks ?? [$kb->content],
                    'type' => $kb->type,
                    'metadata' => $kb->metadata ?? [],
                ];
            })
            ->toArray();
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

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
        'chunk_embeddings',
        'embedding_model',
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
        'chunk_embeddings' => 'array',
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
     * Uses semantic search with embeddings + keyword matching (hybrid)
     */
    public static function getCombinedContext(string $userQuery = ''): string
    {
        $entries = self::getActiveKnowledge();
        
        if (empty($userQuery)) {
            // No query - return summary of all entries
            $context = [];
            foreach ($entries->take(3) as $entry) {
                $preview = mb_substr($entry->content, 0, 500);
                $context[] = "=== {$entry->title} ===\n{$preview}...";
            }
            return implode("\n\n", $context);
        }
        
        // Generate embedding for user query
        $embeddingService = app(\App\Services\EmbeddingService::class);
        $queryEmbedding = $embeddingService->generateEmbedding($userQuery);
        
        if (!$queryEmbedding) {
            // Fallback to keyword matching if embedding fails
            return self::getCombinedContextKeywordBased($userQuery);
        }
        
        // Collect all chunks with their embeddings
        $allChunks = [];
        
        foreach ($entries as $entry) {
            $chunks = $entry->chunks ?? [$entry->content];
            $embeddings = $entry->chunk_embeddings ?? [];
            
            foreach ($chunks as $index => $chunk) {
                $embedding = $embeddings[$index] ?? null;
                
                if ($embedding) {
                    $allChunks[] = [
                        'title' => $entry->title,
                        'chunk' => $chunk,
                        'embedding' => $embedding,
                        'priority' => $entry->priority,
                    ];
                }
            }
        }
        
        if (empty($allChunks)) {
            // No embeddings available, fallback to keyword matching
            return self::getCombinedContextKeywordBased($userQuery);
        }
        
        // Calculate similarity scores
        $scoredChunks = [];
        foreach ($allChunks as $item) {
            $semanticScore = $embeddingService->cosineSimilarity($queryEmbedding, $item['embedding']);
            $keywordScore = self::calculateKeywordScore($item['chunk'], $userQuery);
            
            // Hybrid score: 70% semantic + 30% keyword
            $hybridScore = ($semanticScore * 0.7) + ($keywordScore * 0.3);
            
            $scoredChunks[] = [
                'title' => $item['title'],
                'chunk' => $item['chunk'],
                'score' => $hybridScore,
                'semantic_score' => $semanticScore,
                'keyword_score' => $keywordScore,
                'priority' => $item['priority'],
            ];
        }
        
        // Sort by hybrid score and priority
        usort($scoredChunks, function($a, $b) {
            $scoreCompare = $b['score'] <=> $a['score'];
            if ($scoreCompare !== 0) return $scoreCompare;
            return $b['priority'] <=> $a['priority'];
        });
        
        // Take top 5 most relevant chunks
        $topChunks = array_slice($scoredChunks, 0, 5);
        
        $context = [];
        foreach ($topChunks as $item) {
            $context[] = "=== {$item['title']} (Relevance: " . round($item['score'] * 100, 1) . "%) ===\n{$item['chunk']}";
        }
        
        return implode("\n\n", $context);
    }
    
    /**
     * Fallback: Keyword-based context retrieval
     */
    private static function getCombinedContextKeywordBased(string $userQuery): string
    {
        $entries = self::getActiveKnowledge();
        $relevantChunks = [];
        $queryKeywords = self::extractKeywords($userQuery);
        
        foreach ($entries as $entry) {
            $chunks = $entry->chunks ?? [$entry->content];
            
            foreach ($chunks as $chunk) {
                $score = self::calculateRelevanceScore($chunk, $queryKeywords);
                
                if ($score > 0) {
                    $relevantChunks[] = [
                        'title' => $entry->title,
                        'chunk' => $chunk,
                        'score' => $score,
                        'priority' => $entry->priority,
                    ];
                }
            }
        }
        
        usort($relevantChunks, function($a, $b) {
            $scoreCompare = $b['score'] <=> $a['score'];
            if ($scoreCompare !== 0) return $scoreCompare;
            return $b['priority'] <=> $a['priority'];
        });
        
        $topChunks = array_slice($relevantChunks, 0, 5);
        
        $context = [];
        foreach ($topChunks as $item) {
            $context[] = "=== {$item['title']} ===\n{$item['chunk']}";
        }
        
        return implode("\n\n", $context);
    }
    
    /**
     * Calculate keyword-based score (normalized 0-1)
     */
    private static function calculateKeywordScore(string $chunk, string $query): float
    {
        $keywords = self::extractKeywords($query);
        $score = self::calculateRelevanceScore($chunk, $keywords);
        
        // Normalize to 0-1 range (assuming max 10 keyword matches)
        return min($score / 100, 1.0);
    }
    
    /**
     * Extract keywords from query
     */
    private static function extractKeywords(string $text): array
    {
        // Convert to lowercase and remove special characters
        $text = mb_strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/u', ' ', $text);
        
        // Split into words
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        // Remove common stop words
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'from', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might', 'can', 'ako', 'ko', 'ng', 'sa', 'ang', 'na', 'ay'];
        
        $keywords = array_filter($words, function($word) use ($stopWords) {
            return strlen($word) > 2 && !in_array($word, $stopWords);
        });
        
        return array_values($keywords);
    }
    
    /**
     * Calculate relevance score using keyword matching
     */
    private static function calculateRelevanceScore(string $chunk, array $keywords): int
    {
        $chunkLower = mb_strtolower($chunk);
        $score = 0;
        
        foreach ($keywords as $keyword) {
            // Count occurrences of keyword
            $count = substr_count($chunkLower, $keyword);
            $score += $count * 10; // 10 points per keyword match
        }
        
        return $score;
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
     * Process and store chunks with embeddings
     */
    public function processAndStoreChunks(int $chunkSize = 2000, int $overlap = 400): void
    {
        $chunks = $this->chunkContent($chunkSize, $overlap);
        
        // Generate embeddings for all chunks
        $embeddingService = app(\App\Services\EmbeddingService::class);
        $embeddings = $embeddingService->generateEmbeddings($chunks);
        
        $this->update([
            'chunks' => $chunks,
            'chunk_embeddings' => $embeddings,
            'embedding_model' => 'text-embedding-004',
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

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class EmbeddingService
{
    private string $apiKey;
    private string $model;
    private string $apiVersion = 'v1beta';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.embedding_model', 'gemini-embedding-001');
    }

    /**
     * Generate embedding for a single text
     */
    public function generateEmbedding(string $text): ?array
    {
        // Cache embeddings to avoid redundant API calls
        $cacheKey = 'embedding_' . md5($text);
        
        return Cache::remember($cacheKey, 86400, function () use ($text) {
            return $this->callEmbeddingAPI($text);
        });
    }

    /**
     * Generate embeddings for multiple texts (batch)
     */
    public function generateEmbeddings(array $texts): array
    {
        $embeddings = [];
        
        // Gemini supports batch embedding (up to 100 texts)
        $batches = array_chunk($texts, 100);
        
        foreach ($batches as $batch) {
            $batchEmbeddings = $this->callBatchEmbeddingAPI($batch);
            $embeddings = array_merge($embeddings, $batchEmbeddings);
        }
        
        return $embeddings;
    }

    /**
     * Call Gemini Embedding API for single text
     */
    private function callEmbeddingAPI(string $text): ?array
    {
        try {
            $response = Http::timeout(30)
                ->post("https://generativelanguage.googleapis.com/{$this->apiVersion}/models/{$this->model}:embedContent?key={$this->apiKey}", [
                    'model' => "models/{$this->model}",
                    'content' => [
                        'parts' => [
                            ['text' => $text]
                        ]
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['embedding']['values'] ?? null;
            }

            Log::error('Gemini Embedding API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Embedding Generation Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Call Gemini Embedding API for batch texts
     */
    private function callBatchEmbeddingAPI(array $texts): array
    {
        try {
            $requests = array_map(function ($text) {
                return [
                    'model' => "models/{$this->model}",
                    'content' => [
                        'parts' => [
                            ['text' => $text]
                        ]
                    ],
                ];
            }, $texts);

            $response = Http::timeout(60)
                ->post("https://generativelanguage.googleapis.com/{$this->apiVersion}/models/{$this->model}:batchEmbedContents?key={$this->apiKey}", [
                    'requests' => $requests,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $embeddings = [];
                
                foreach ($data['embeddings'] ?? [] as $embedding) {
                    $embeddings[] = $embedding['values'] ?? null;
                }
                
                return $embeddings;
            }

            Log::error('Gemini Batch Embedding API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return array_fill(0, count($texts), null);

        } catch (\Exception $e) {
            Log::error('Batch Embedding Generation Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return array_fill(0, count($texts), null);
        }
    }

    /**
     * Calculate cosine similarity between two vectors
     */
    public function cosineSimilarity(array $vector1, array $vector2): float
    {
        if (count($vector1) !== count($vector2)) {
            return 0.0;
        }

        $dotProduct = 0.0;
        $magnitude1 = 0.0;
        $magnitude2 = 0.0;

        for ($i = 0; $i < count($vector1); $i++) {
            $dotProduct += $vector1[$i] * $vector2[$i];
            $magnitude1 += $vector1[$i] * $vector1[$i];
            $magnitude2 += $vector2[$i] * $vector2[$i];
        }

        $magnitude1 = sqrt($magnitude1);
        $magnitude2 = sqrt($magnitude2);

        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0.0;
        }

        return $dotProduct / ($magnitude1 * $magnitude2);
    }

    /**
     * Find most similar chunks using cosine similarity
     */
    public function findSimilarChunks(array $queryEmbedding, array $chunkEmbeddings, int $topK = 5): array
    {
        $similarities = [];

        foreach ($chunkEmbeddings as $index => $chunkEmbedding) {
            if ($chunkEmbedding === null) {
                continue;
            }

            $similarity = $this->cosineSimilarity($queryEmbedding, $chunkEmbedding);
            $similarities[] = [
                'index' => $index,
                'similarity' => $similarity,
            ];
        }

        // Sort by similarity (descending)
        usort($similarities, function ($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        // Return top K
        return array_slice($similarities, 0, $topK);
    }
}

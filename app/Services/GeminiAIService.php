<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAIService
{
    protected $apiKey;
    protected $model;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model');
    }

    /**
     * Get the full API URL for the configured model
     */
    protected function getApiUrl()
    {
        return $this->baseUrl . $this->model . ':generateContent';
    }

    /**
     * Send message to Gemini AI and get response
     */
    public function chat(array $conversationHistory, string $systemPrompt = null)
    {
        try {
            $contents = [];
            
            // Add system prompt as first user message if provided
            if ($systemPrompt) {
                $contents[] = [
                    'role' => 'user',
                    'parts' => [['text' => $systemPrompt]]
                ];
                $contents[] = [
                    'role' => 'model',
                    'parts' => [['text' => 'Understood. I will help users find the right lawyer based on their legal concerns.']]
                ];
            }

            // Add conversation history
            foreach ($conversationHistory as $message) {
                $contents[] = [
                    'role' => $message['role'] === 'user' ? 'user' : 'model',
                    'parts' => [['text' => $message['content']]]
                ];
            }

            $response = Http::timeout(30)
                ->post($this->getApiUrl() . '?key=' . $this->apiKey, [
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 1024,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return [
                        'success' => true,
                        'message' => $data['candidates'][0]['content']['parts'][0]['text']
                    ];
                }
            }

            Log::error('Gemini API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'message' => 'Sorry, I encountered an error. Please try again.'
            ];

        } catch (\Exception $e) {
            Log::error('Gemini AI Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Sorry, I encountered an error. Please try again.'
            ];
        }
    }

    /**
     * Analyze legal concern and recommend specializations
     */
    public function analyzeLegalConcern(string $concern, array $availableSpecializations)
    {
        $specializationsList = collect($availableSpecializations)->map(function($spec) {
            return "- {$spec['name']}: {$spec['description']}";
        })->join("\n");

        $systemPrompt = "You are a legal assistant helping users find the right lawyer in the Philippines. 
        
Available legal specializations:
{$specializationsList}

Based on the user's legal concern, recommend the TOP 3 most relevant specializations from the list above.
Respond in JSON format ONLY with this structure:
{
    \"specializations\": [\"specialization1\", \"specialization2\", \"specialization3\"],
    \"explanation\": \"Brief explanation of why these specializations are recommended\"
}

Use the EXACT specialization names from the list above.";

        $conversationHistory = [
            ['role' => 'user', 'content' => "My legal concern is: {$concern}"]
        ];

        $response = $this->chat($conversationHistory, $systemPrompt);

        if ($response['success']) {
            try {
                // Extract JSON from response
                $text = $response['message'];
                
                // Try to find JSON in the response
                if (preg_match('/\{[\s\S]*\}/', $text, $matches)) {
                    $json = json_decode($matches[0], true);
                    
                    if ($json && isset($json['specializations'])) {
                        return [
                            'success' => true,
                            'specializations' => $json['specializations'],
                            'explanation' => $json['explanation'] ?? ''
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to parse Gemini response', [
                    'response' => $response['message'],
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'success' => false,
            'message' => 'Could not analyze your concern. Please try browsing lawyers manually.'
        ];
    }
}

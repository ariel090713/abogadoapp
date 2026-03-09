<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AISetting;
use App\Models\AIKnowledgeBase;
use App\Models\Specialization;
use App\Services\GeminiAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AIChatController extends Controller
{
    public function chat(Request $request, GeminiAIService $aiService)
    {
        // Validate request
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'conversation' => 'required|array|max:50', // Max 50 messages in conversation
            'conversation.*.role' => 'required|in:user,assistant',
            'conversation.*.content' => 'required|string|max:5000',
        ]);

        // Get conversation from validated data
        $conversation = $validated['conversation'];

        // Additional security: Check conversation length
        if (count($conversation) > 50) {
            return response()->json([
                'success' => false,
                'message' => 'Conversation too long. Please start a new conversation.',
            ], 400);
        }

        // Additional security: Validate last message is from user
        $conversationCopy = $conversation;
        $lastMessage = end($conversationCopy);
        if ($lastMessage['role'] !== 'user') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid conversation format.',
            ], 400);
        }

        try {
            
            // Get AI settings
            $aiName = AISetting::get('ai_name', 'Legal Assistant');
            $personality = AISetting::get('ai_personality', 'Professional and helpful legal assistant');
            $rules = AISetting::get('ai_rules', 'Be helpful and guide users to find the right lawyer');

            // Get user's latest message for context search
            $userMessage = $validated['message'];
            
            // Get knowledge base context (relevant chunks based on user query)
            $knowledgeContext = AIKnowledgeBase::getCombinedContext($userMessage);

            // Get available specializations
            $specializations = Specialization::all()->map(function($spec) {
                return [
                    'name' => $spec->name,
                    'slug' => $spec->slug,
                    'description' => $spec->description ?? ''
                ];
            })->toArray();

            // Build system prompt
            $systemPrompt = "You are {$aiName}, a {$personality}.

RULES:
{$rules}

KNOWLEDGE BASE:
{$knowledgeContext}

AVAILABLE SPECIALIZATIONS:
" . collect($specializations)->map(function($spec) {
    return "- {$spec['name']} (slug: {$spec['slug']}): {$spec['description']}";
})->join("\n") . "

Your task is to:
1. Ask clarifying questions about the user's legal concern
2. After understanding their concern, recommend the TOP 3 most relevant specializations
3. When ready to recommend, respond with JSON format:
{
    \"ready\": true,
    \"specializations\": [\"slug1\", \"slug2\", \"slug3\"],
    \"explanation\": \"Brief explanation\"
}

Otherwise, just have a natural conversation to understand their needs better.";

            // Call Gemini AI
            $response = $aiService->chat($conversation, $systemPrompt);

            if ($response['success']) {
                $aiMessage = $response['message'];
                $recommendations = [];

                // Check if AI is ready to recommend
                if (preg_match('/\{[\s\S]*"ready"[\s\S]*true[\s\S]*\}/', $aiMessage, $matches)) {
                    try {
                        $json = json_decode($matches[0], true);

                        if ($json && isset($json['specializations'])) {
                            $recommendations = $json['specializations'];
                            $aiMessage = $json['explanation'] ?? 'Based on your concern, I recommend these practice areas. Click "View Filtered Lawyers" to see lawyers who specialize in these areas.';
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to parse AI recommendation', ['error' => $e->getMessage()]);
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => $aiMessage,
                    'recommendations' => $recommendations,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'I apologize, but I encountered an error. Please try browsing lawyers manually or try again.',
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('AI Chat API Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'I apologize, but I encountered an error. Please try browsing lawyers manually.',
            ], 500);
        }
    }
}

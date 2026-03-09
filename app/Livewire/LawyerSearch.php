<?php

namespace App\Livewire;

use App\Models\LawyerProfile;
use App\Models\Specialization;
use Livewire\Component;
use Livewire\WithPagination;

class LawyerSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $specializations = []; // Changed from single to array
    public $specializationSearch = ''; // New: for filtering specializations list
    public $location = '';
    public $languages = []; // Multi-select languages
    public $sortBy = 'rating';
    public $viewMode = 'grid';

    // AI Assistant properties
    public $showAIModal = false;
    public $aiMode = false;
    public $conversation = [];
    public $userMessage = '';
    public $aiRecommendedSpecializations = [];
    public $isAIThinking = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'specializations' => ['except' => []], // Changed
        'location' => ['except' => ''],
        'languages' => ['except' => []],
        'sortBy' => ['except' => 'rating'],
    ];

    public function mount()
    {
        // Show AI modal on first visit (check if no filters are set)
        if (empty($this->search) && empty($this->specializations) && empty($this->location) && empty($this->languages)) {
            $this->showAIModal = true;
        }
    }

    public function closeModal()
    {
        $this->showAIModal = false;
        $this->aiMode = false;
        $this->conversation = [];
        $this->userMessage = '';
    }

    public function startAIChat()
    {
        $this->aiMode = true;

        // Get AI greeting from settings
        $aiName = \App\Models\AISetting::get('ai_name', 'Legal Assistant');
        $greeting = \App\Models\AISetting::get('ai_greeting', 'Hello! I\'m here to help you find the right lawyer. Can you describe your legal concern?');

        $this->conversation[] = [
            'role' => 'assistant',
            'content' => $greeting
        ];
    }

    public function sendMessage()
    {
        if (empty(trim($this->userMessage))) {
            return;
        }

        // Add user message to conversation
        $this->conversation[] = [
            'role' => 'user',
            'content' => $this->userMessage
        ];

        $userInput = $this->userMessage;
        $this->userMessage = '';
        $this->isAIThinking = true;

        try {
            // Get AI settings
            $aiName = \App\Models\AISetting::get('ai_name', 'Legal Assistant');
            $personality = \App\Models\AISetting::get('ai_personality', 'Professional and helpful legal assistant');
            $rules = \App\Models\AISetting::get('ai_rules', 'Be helpful and guide users to find the right lawyer');

            // Get knowledge base context
            $knowledgeContext = \App\Models\AIKnowledgeBase::getCombinedContext();

            // Get available specializations
            $specializations = \App\Models\Specialization::all()->map(function($spec) {
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
            $aiService = new \App\Services\GeminiAIService();
            $response = $aiService->chat($this->conversation, $systemPrompt);

            if ($response['success']) {
                $aiMessage = $response['message'];

                // Check if AI is ready to recommend
                if (preg_match('/\{[\s\S]*"ready"[\s\S]*true[\s\S]*\}/', $aiMessage, $matches)) {
                    try {
                        $json = json_decode($matches[0], true);

                        if ($json && isset($json['specializations'])) {
                            $this->aiRecommendedSpecializations = $json['specializations'];

                            // Add friendly message
                            $this->conversation[] = [
                                'role' => 'assistant',
                                'content' => $json['explanation'] ?? 'Based on your concern, I recommend these practice areas. Click "View Filtered Lawyers" to see lawyers who specialize in these areas.'
                            ];

                            $this->isAIThinking = false;
                            return;
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to parse AI recommendation', ['error' => $e->getMessage()]);
                    }
                }

                // Add AI response to conversation
                $this->conversation[] = [
                    'role' => 'assistant',
                    'content' => $aiMessage
                ];
            } else {
                $this->conversation[] = [
                    'role' => 'assistant',
                    'content' => 'I apologize, but I encountered an error. Please try browsing lawyers manually or try again.'
                ];
            }

        } catch (\Exception $e) {
            \Log::error('AI Chat Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->conversation[] = [
                'role' => 'assistant',
                'content' => 'I apologize, but I encountered an error. Please try browsing lawyers manually.'
            ];
        }

        $this->isAIThinking = false;
    }

    public function applyAIFilters()
    {
        if (!empty($this->aiRecommendedSpecializations)) {
            $this->specializations = $this->aiRecommendedSpecializations;
            $this->closeModal();
            $this->resetPage();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSpecializations()
    {
        $this->resetPage();
    }

    public function updatingLocation()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'specializations', 'location', 'languages']);
        $this->resetPage();
    }

    public function toggleSpecialization($slug)
    {
        if (in_array($slug, $this->specializations)) {
            $this->specializations = array_values(array_diff($this->specializations, [$slug]));
        } else {
            $this->specializations[] = $slug;
        }
        $this->resetPage();
    }

    public function toggleLanguage($language)
    {
        if (in_array($language, $this->languages)) {
            $this->languages = array_values(array_diff($this->languages, [$language]));
        } else {
            $this->languages[] = $language;
        }
        $this->resetPage();
    }

    public function render()
    {
        $query = LawyerProfile::with(['user', 'specializations', 'reviews'])
            ->where('is_verified', true)
            ->where('is_available', true)
            ->whereHas('user', function ($q) {
                $q->where('is_active', true); // Only active (not suspended) users
            });

        // Search by name
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by specializations (handles multiple selections)
        if (!empty($this->specializations)) {
            $query->whereHas('specializations', function ($q) {
                $selectedSpecs = \App\Models\Specialization::whereIn('slug', $this->specializations)->get();

                $allIds = [];
                foreach ($selectedSpecs as $spec) {
                    if ($spec->is_parent) {
                        // If parent selected, include parent and all children
                        $childIds = $spec->children->pluck('id')->toArray();
                        $allIds = array_merge($allIds, [$spec->id], $childIds);
                    } else {
                        // If child selected, include only that child
                        $allIds[] = $spec->id;
                    }
                }

                $q->whereIn('specializations.id', array_unique($allIds));
            });
        }

        // Filter by province
        if ($this->location) {
            $query->whereHas('user', function ($q) {
                $q->where('province', $this->location);
            });
        }

        // Filter by languages
        if (!empty($this->languages)) {
            $query->where(function ($q) {
                foreach ($this->languages as $language) {
                    $q->orWhereJsonContains('languages', $language);
                }
            });
        }

        // Sort
        switch ($this->sortBy) {
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'price_low':
                $query->orderBy('rate_per_15min', 'asc');
                break;
            case 'price_high':
                $query->orderBy('rate_per_15min', 'desc');
                break;
            case 'experience':
                $query->orderBy('years_experience', 'desc');
                break;
            case 'reviews':
                $query->orderBy('total_reviews', 'desc');
                break;
        }

        $lawyers = $query->paginate(30);

        // Get all specializations with children
        $allSpecializations = Specialization::with('children')
            ->where('is_parent', true)
            ->orderBy('name')
            ->get();

        // Filter specializations based on search
        if ($this->specializationSearch) {
            $searchTerm = strtolower($this->specializationSearch);
            $allSpecializations = $allSpecializations->filter(function ($parent) use ($searchTerm) {
                // Check if parent name matches
                if (str_contains(strtolower($parent->name), $searchTerm)) {
                    return true;
                }
                // Check if any child name matches
                return $parent->children->filter(function ($child) use ($searchTerm) {
                    return str_contains(strtolower($child->name), $searchTerm);
                })->isNotEmpty();
            })->map(function ($parent) use ($searchTerm) {
                // Filter children if parent doesn't match but children do
                if (!str_contains(strtolower($parent->name), $searchTerm)) {
                    $parent->setRelation('children', $parent->children->filter(function ($child) use ($searchTerm) {
                        return str_contains(strtolower($child->name), $searchTerm);
                    }));
                }
                return $parent;
            });
        }

        return view('livewire.lawyer-search', [
            'lawyers' => $lawyers,
            'allSpecializations' => $allSpecializations,
            'availableLanguages' => \App\Helpers\Languages::getLanguages(),
            'provinces' => \App\Helpers\PhilippineLocations::getProvincesList(),
        ])->layout('layouts.guest');
    }
}


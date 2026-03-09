<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="p-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">AI Assistant Settings</h1>
        <p class="text-gray-600">Configure AI personality, rules, and knowledge base</p>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button 
                    wire:click="$set('activeTab', 'personality')"
                    class="px-6 py-4 text-sm font-medium border-b-2 transition {{ $activeTab === 'personality' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                >
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Personality & Rules
                    </div>
                </button>
                <button 
                    wire:click="$set('activeTab', 'knowledge')"
                    class="px-6 py-4 text-sm font-medium border-b-2 transition {{ $activeTab === 'knowledge' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                >
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Knowledge Base (RAG)
                    </div>
                </button>
            </nav>
        </div>
    </div>

    <!-- Personality & Rules Tab -->
    @if($activeTab === 'personality')
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <form wire:submit.prevent="savePersonality" class="space-y-6">
                <!-- AI Enabled Toggle -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div>
                        <h3 class="font-semibold text-gray-900">Enable AI Assistant</h3>
                        <p class="text-sm text-gray-600">Turn on/off the AI-powered lawyer search assistant</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="ai_enabled" class="sr-only peer">
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>

                <!-- AI Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">AI Assistant Name</label>
                    <input 
                        type="text" 
                        wire:model="ai_name"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="e.g., Legal Assistant, AbogadoMo AI"
                    >
                    @error('ai_name') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- AI Personality -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">AI Personality</label>
                    <textarea 
                        wire:model="ai_personality"
                        rows="4"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Describe the AI's personality and tone..."
                    ></textarea>
                    <p class="text-sm text-gray-500 mt-1">Define how the AI should behave and communicate with users</p>
                    @error('ai_personality') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- AI Rules -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">AI Rules (Strict Guidelines)</label>
                    <textarea 
                        wire:model="ai_rules"
                        rows="8"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono text-sm"
                        placeholder="- Rule 1&#10;- Rule 2&#10;- Rule 3"
                    ></textarea>
                    <p class="text-sm text-gray-500 mt-1">AI will strictly follow these rules. Use bullet points for clarity.</p>
                    @error('ai_rules') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- AI Greeting -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Greeting Message</label>
                    <textarea 
                        wire:model="ai_greeting"
                        rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Hello! How can I help you today?"
                    ></textarea>
                    <p class="text-sm text-gray-500 mt-1">First message users will see when starting a conversation</p>
                    @error('ai_greeting') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Save Button -->
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button 
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-6 py-3 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition font-medium disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="savePersonality">Save Personality Settings</span>
                        <span wire:loading wire:target="savePersonality" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Knowledge Base Tab -->
    @if($activeTab === 'knowledge')
        <div class="space-y-6">
            <!-- Add/Edit Knowledge Form -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    {{ $editingKnowledgeId ? 'Edit' : 'Add' }} Knowledge Base Entry
                </h2>
                
                <form wire:submit.prevent="saveKnowledge" class="space-y-6" id="knowledge-form">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input 
                            type="text" 
                            wire:model="kb_title"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="e.g., Philippine Labor Law Basics"
                        >
                        @error('kb_title') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Type Dropdown -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Entry Type</label>
                        <select 
                            wire:model.live="kb_type"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        >
                            <option value="text">Text Content</option>
                            <option value="file">File Upload</option>
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Choose whether to enter text directly or upload a document</p>
                        @error('kb_type') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Content (shown only for text type) -->
                    @if($kb_type === 'text')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                            <textarea 
                                wire:model="kb_content"
                                rows="10"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono text-sm"
                                placeholder="Enter detailed information that the AI should know..."
                            ></textarea>
                            <p class="text-sm text-gray-500 mt-1">AI will use this information to answer questions accurately</p>
                            @error('kb_content') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <!-- File Upload (shown only for file type) -->
                    @if($kb_type === 'file')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Document</label>
                            <input 
                                type="file" 
                                wire:model="kb_file"
                                accept=".pdf,.doc,.docx,.txt"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            >
                            <p class="text-sm text-gray-500 mt-1">Supported: PDF, DOC, DOCX, TXT (Max 10MB)</p>
                            @error('kb_file') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                            
                            @if($kb_file)
                                <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center gap-2 text-sm text-green-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>File selected: {{ $kb_file->getClientOriginalName() }}</span>
                                    </div>
                                </div>
                            @endif
                            
                            <div wire:loading wire:target="kb_file" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center gap-2 text-sm text-blue-700">
                                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Uploading file...</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority (0-10)</label>
                        <input 
                            type="number" 
                            wire:model="kb_priority"
                            min="0"
                            max="10"
                            class="w-32 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        >
                        <p class="text-sm text-gray-500 mt-1">Higher priority entries are given more weight (10 = highest)</p>
                        @error('kb_priority') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        @if($editingKnowledgeId)
                            <button 
                                type="button"
                                wire:click="resetKnowledgeForm"
                                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium"
                            >
                                Cancel
                            </button>
                        @endif
                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-6 py-3 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition font-medium disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="saveKnowledge">
                                {{ $editingKnowledgeId ? 'Update' : 'Add' }} Entry
                            </span>
                            <span wire:loading wire:target="saveKnowledge" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Knowledge Base List -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Knowledge Base Entries</h2>
                
                @if($knowledgeBase->count() > 0)
                    <div class="space-y-4">
                        @foreach($knowledgeBase as $entry)
                            <div class="p-6 border-2 rounded-xl {{ $entry->is_active ? 'border-gray-200 bg-white' : 'border-gray-200 bg-gray-50 opacity-60' }}">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="font-bold text-lg text-gray-900">{{ $entry->title }}</h3>
                                            <span class="px-3 py-1 bg-primary-100 text-primary-700 text-xs font-bold rounded-full">
                                                Priority: {{ $entry->priority }}
                                            </span>
                                            @if($entry->type === 'document')
                                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                                                    📄 Document
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-gray-600 text-sm line-clamp-3">{{ $entry->content }}</p>
                                        @if($entry->file_name)
                                            <p class="text-sm text-gray-500 mt-2">
                                                File: {{ $entry->file_name }} ({{ number_format($entry->file_size / 1024, 2) }} KB)
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 ml-4">
                                        <button 
                                            wire:click="toggleKnowledgeStatus({{ $entry->id }})"
                                            class="p-2 {{ $entry->is_active ? 'text-green-600 hover:bg-green-50' : 'text-gray-400 hover:bg-gray-100' }} rounded-lg transition"
                                            title="{{ $entry->is_active ? 'Active' : 'Inactive' }}"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($entry->is_active)
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                @endif
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="editKnowledge({{ $entry->id }})"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="deleteKnowledge({{ $entry->id }})"
                                            wire:confirm="Are you sure you want to delete this entry?"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500">
                                    Added {{ $entry->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $knowledgeBase->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Knowledge Base Entries</h3>
                        <p class="text-gray-600">Add your first entry to start building the AI's knowledge base</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Scroll to form when editing
    document.addEventListener('livewire:init', () => {
        Livewire.on('scroll-to-form', () => {
            setTimeout(() => {
                const form = document.getElementById('knowledge-form');
                if (form) {
                    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        });
    });
</script>
@endpush

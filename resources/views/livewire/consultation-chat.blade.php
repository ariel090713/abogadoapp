<x-slot name="sidebar">
    @if(auth()->user()->isClient())
        <x-client-sidebar />
    @else
        <x-lawyer-sidebar />
    @endif
</x-slot>

<div class="md:p-8">
    <!-- Back Button (Desktop only) -->
    <div class="mb-6 hidden md:block">
        <a href="{{ auth()->user()->isClient() ? route('client.consultation.details', $consultation->id) : route('lawyer.consultation.details', $consultation->id) }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Consultation Details
        </a>
    </div>

    <!-- Chat Component -->
    <div class="md:max-w-5xl md:mx-auto">
        
        <!-- Pending Reschedule Banner -->
        <x-pending-reschedule-banner :consultation="$consultation" />
        
        @if($chatStatus === 'waiting')
            <!-- Waiting State -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-yellow-100 flex items-center justify-center">
                        <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Chat Not Yet Available</h2>
                    <p class="text-gray-600 mb-6">Your consultation is scheduled for:</p>
                    <div class="bg-blue-50 rounded-xl p-6 mb-6 inline-block">
                        <p class="text-3xl font-bold text-blue-900 mb-2">
                            {{ $consultation->scheduled_at->format('l, F j, Y') }}
                        </p>
                        <p class="text-xl font-semibold text-blue-700">
                            {{ $consultation->scheduled_at->format('g:i A') }}
                        </p>
                        <p class="text-sm text-blue-600 mt-2">
                            {{ $consultation->scheduled_at->diffForHumans() }}
                        </p>
                    </div>
                </div>

                <!-- Tips and Reminders -->
                <div class="max-w-2xl mx-auto space-y-4">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-green-900 mb-2">Preparation Tips</h3>
                                <ul class="text-sm text-green-800 space-y-1">
                                    <li>• Prepare your questions and concerns in advance</li>
                                    <li>• Gather relevant documents or information</li>
                                    <li>• Find a quiet place with stable internet connection</li>
                                    <li>• Have a pen and paper ready for notes</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-blue-900 mb-2">Important Reminders</h3>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Chat will be available at the scheduled time</li>
                                    <li>• Duration: {{ $consultation->duration }} minutes</li>
                                    <li>• You can send files and documents during the chat</li>
                                    <li>• All conversations are confidential and secure</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->isClient())
                        <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-purple-900 mb-2">What to Expect</h3>
                                    <ul class="text-sm text-purple-800 space-y-1">
                                        <li>• Your lawyer will provide legal advice and guidance</li>
                                        <li>• Ask questions freely - no question is too small</li>
                                        <li>• Take notes of important points discussed</li>
                                        <li>• Request clarification if something is unclear</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        @elseif($chatStatus === 'ended')
            <!-- Ended State - Read Only -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Consultation Ended</h2>
                            <p class="text-sm text-gray-600">Chat is now read-only.</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-4 bg-gray-50" style="max-height: 600px; overflow-y: auto;">
                    @forelse($messages as $message)
                        <div class="flex {{ $message['is_mine'] ? 'justify-end' : 'justify-start' }}">
                            <div class="flex gap-3 max-w-[70%] {{ $message['is_mine'] ? 'flex-row-reverse' : '' }}">
                                <div class="rounded-2xl px-4 py-3 {{ $message['is_mine'] ? 'bg-blue-900 text-white' : 'bg-white text-gray-900 shadow-md' }}">
                                    @if($message['message'])
                                        <p class="text-sm">{!! \App\Helpers\TextHelper::linkify($message['message']) !!}</p>
                                    @endif
                                    
                                    @if($message['attachments'])
                                        <div class="{{ $message['message'] ? 'mt-2' : '' }}">
                                            <a href="{{ $message['attachments'][0]['url'] }}" target="_blank" class="flex items-center gap-2 p-2 rounded-lg {{ $message['is_mine'] ? 'bg-blue-800' : 'bg-gray-100' }}">
                                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                                <span class="text-xs">📎 File attachment</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">No messages</p>
                    @endforelse
                </div>
            </div>

        @else
        <style>
                #mobile-chat-container {
                    display: flex; 
                    flex-direction: 
                    column; height: 
                    80vh;
                }
                @media (max-width: 767px) {
                    #mobile-chat-container {
                        display: flex; 
                        flex-direction: 
                        column; height: 
                        100vh;
                    }
                }
           
        </style>
            <!-- Active Chat -->
            <div id="mobile-chat-container" class="bg-white md:shadow-lg overflow-hidden" >
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-4 md:px-6 py-3 md:py-4" style="flex-shrink: 0;">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <button onclick="window.history.back()" class="md:hidden p-2 -ml-2 hover:bg-white/10 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </button>
                        
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center border-2 border-white">
                                <span class="font-bold">{{ substr($this->otherUser->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold">{{ $this->otherUser->name }}</h3>
                                <p class="text-xs text-blue-100">{{ ucfirst($consultation->consultation_type) }} Consultation</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <!-- Mobile: Green dot only -->
                            <span class="md:hidden w-3 h-3 bg-green-500 rounded-full"></span>
                            <!-- Desktop: Full badge -->
                            <span class="hidden md:inline-block px-3 py-1 bg-green-500 rounded-full text-sm font-medium">Active</span>
                        </div>
                    </div>
                    
                    @if($this->timeRemaining > 0)
                        <div class="bg-red-700 rounded-lg px-4 py-2 mt-3" x-data="countdown({{ $this->timeRemaining }})">
                            <div class="flex items-center justify-center gap-2 text-sm text-white">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Time: <strong x-text="hours + 'h ' + minutes + 'm ' + seconds + 's'"></strong></span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Messages Container -->
                <div id="messages-container" class="bg-gray-50" style="flex: 1 1 0%; min-height: 0; overflow-y: auto; overflow-x: hidden;">
                    <div class="px-4 py-4 space-y-3">
                    @forelse($messages as $message)
                        <div class="flex {{ $message['is_mine'] ? 'justify-end' : 'justify-start' }}">
                            <div class="flex gap-2 max-w-[85%] md:max-w-[70%] {{ $message['is_mine'] ? 'flex-row-reverse' : '' }}">
                                <div class="rounded-2xl px-4 py-3 {{ $message['is_mine'] ? 'bg-blue-900 text-white' : 'bg-white text-gray-900 shadow-md' }}">
                                    @if($message['message'])
                                        <p class="text-sm break-words">{!! \App\Helpers\TextHelper::linkify($message['message']) !!}</p>
                                    @endif
                                    
                                    @if($message['attachments'])
                                        <div class="{{ $message['message'] ? 'mt-2' : '' }}">
                                            <a href="{{ $message['attachments'][0]['url'] }}" target="_blank" class="flex items-center gap-2 p-2 rounded-lg {{ $message['is_mine'] ? 'bg-blue-800' : 'bg-gray-100' }}">
                                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                                <span class="text-xs">📎 File attachment</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-500">No messages yet. Start the conversation!</p>
                        </div>
                    @endforelse
                    </div>
                </div>

                <!-- Input -->
                <div class="border-t border-gray-200 bg-white p-3 md:p-4" style="flex-shrink: 0;">
                    <form wire:submit.prevent="sendMessage">
                        <div class="flex gap-2 items-end">
                            <!-- File Attachment Button -->
                            <label class="cursor-pointer w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center flex-shrink-0">
                                <input type="file" wire:model="attachments" multiple class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </label>
                            
                            <input type="text" 
                                   wire:model.live.debounce.500ms="newMessage"
                                   placeholder="Type your message..."
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-full text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-900">
                            
                            <button type="submit" class="w-10 h-10 rounded-full bg-blue-900 hover:bg-blue-800 text-white flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Show selected files -->
                        @if(!empty($attachments))
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($attachments as $index => $attachment)
                                    <div class="flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-1 text-sm">
                                        <span class="text-gray-700">{{ $attachment->getClientOriginalName() }}</span>
                                        <button type="button" wire:click="removeAttachment({{ $index }})" class="text-red-500 hover:text-red-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        @endif
    </div>

@script
<script>
    // Auto-scroll to bottom when new messages arrive
    function scrollToBottom() {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    // Scroll on load
    document.addEventListener('DOMContentLoaded', scrollToBottom);
    
    // Scroll when Livewire updates
    Livewire.hook('morph.updated', ({ el, component }) => {
        if (el.id === 'messages-container') {
            scrollToBottom();
        }
    });

    // Watch for new messages being added
    if (document.getElementById('messages-container')) {
        const observer = new MutationObserver(scrollToBottom);
        observer.observe(document.getElementById('messages-container'), {
            childList: true,
            subtree: true
        });
    }
</script>
@endscript

</div>

<!-- Completion Modal (Lawyer Only) -->
@if(auth()->user()->isLawyer() && $showCompleteModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100000] p-4" style="position: fixed;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Mark Consultation as Complete</h2>
                <p class="text-sm text-gray-600 mt-1">Provide completion notes and/or upload a reviewed document</p>
            </div>

            <form wire:submit.prevent="completeConsultation" class="p-6 space-y-6">
                <!-- Completion Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Completion Notes <span class="text-red-600">*</span>
                    </label>
                    <textarea 
                        wire:model="completionNotes"
                        rows="6"
                        placeholder="Provide any notes, recommendations, or summary of the consultation..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-900 focus:border-transparent resize-none"
                    ></textarea>
                    @error('completionNotes') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reviewed Document Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Reviewed Document (Optional)
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-900 transition">
                        <input 
                            type="file" 
                            wire:model="reviewedDocument"
                            accept=".pdf,.doc,.docx"
                            class="hidden"
                            id="reviewed-doc-upload"
                        >
                        <label for="reviewed-doc-upload" class="cursor-pointer">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="text-sm text-gray-600">Click to upload or drag and drop</p>
                            <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX (Max 10MB)</p>
                        </label>
                    </div>
                    
                    @if($reviewedDocument)
                        <div class="mt-3 flex items-center gap-2 bg-blue-50 rounded-lg px-4 py-2">
                            <svg class="w-5 h-5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm text-gray-700 flex-1">{{ $reviewedDocument->getClientOriginalName() }}</span>
                            <button type="button" wire:click="$set('reviewedDocument', null)" class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif
                    
                    @error('reviewedDocument') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Note -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">
                        <strong>Note:</strong> You must provide either completion notes or upload a document (or both).
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 justify-end pt-4 border-t border-gray-200">
                    <button 
                        type="button"
                        wire:click="$set('showCompleteModal', false)"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="px-6 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="completeConsultation">Mark as Complete</span>
                        <span wire:loading wire:target="completeConsultation" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif


@push('styles')
<style>
    /* Mobile: Full screen chat with FIXED height */
    @media (max-width: 767px) {
        /* Lock body */
        body.mobile-chat-active {
            overflow: hidden !important;
            position: fixed !important;
            width: 100% !important;
            height: 100vh !important;
        }
        
        /* Chat container - FLEXBOX layout, EXACT 100vh on mobile */
        #mobile-chat-container {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            max-height: 100vh !important;
            z-index: 99999 !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
        }
        
        /* Header - shrink to content */
        #mobile-chat-container > div:first-child {
            flex-shrink: 0;
        }
        
        /* Messages - grow to fill, scrollable */
        #messages-container {
            flex: 1 1 0%;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            -webkit-overflow-scrolling: touch;
            min-height: 0;
        }
        
        /* Input - shrink to content */
        #mobile-chat-container > div:last-child {
            flex-shrink: 0;
        }
    }
    
    /* Desktop: 80vh height, relative positioning */
    @media (min-width: 768px) {
        #mobile-chat-container {
            position: relative !important;
            top: auto !important;
            left: auto !important;
            right: auto !important;
            bottom: auto !important;
            width: auto !important;
            height: 80vh !important;
            max-height: 80vh !important;
            z-index: auto !important;
            display: flex !important;
            flex-direction: column !important;
        }
        
        #messages-container {
            flex: 1 1 0% !important;
            min-height: 0 !important;
            overflow-y: auto !important;
        }
    }
</style>
@endpush

@script
<script>
    // Set container height based on screen size
    function setContainerHeight() {
        const container = document.getElementById('mobile-chat-container');
        if (container) {
            if (window.innerWidth >= 768) {
                // Desktop: 80vh
                container.style.height = '80vh';
            } else {
                // Mobile: 100vh
                container.style.height = '100vh';
            }
        }
    }
    
    // Mobile full screen setup - wait for DOM
    function setupMobileChat() {
        // Set height first
        setContainerHeight();
        
        if (window.innerWidth < 768) {
            document.body.classList.add('mobile-chat-active');
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.width = '100%';
            document.body.style.height = '100vh';
            document.documentElement.style.overflow = 'hidden';
            
            // Hide navbar and sidebar
            const header = document.querySelector('header');
            if (header) header.style.display = 'none';
            
            const sidebar = document.querySelector('aside');
            if (sidebar) sidebar.style.display = 'none';
            
            const mainContent = document.querySelector('.lg\\:ml-64');
            if (mainContent) mainContent.style.marginLeft = '0';
        }
    }
    
    // Run immediately and after DOM loads
    setupMobileChat();
    document.addEventListener('DOMContentLoaded', setupMobileChat);
    document.addEventListener('livewire:navigated', setupMobileChat);
    
    // Handle window resize
    window.addEventListener('resize', setContainerHeight);
    
    // Countdown timer
    Alpine.data('countdown', (initialSeconds) => ({
        totalSeconds: initialSeconds,
        hours: 0,
        minutes: 0,
        seconds: 0,
        interval: null,

        init() {
            this.updateDisplay();
            this.interval = setInterval(() => {
                this.totalSeconds--;
                if (this.totalSeconds <= 0) {
                    clearInterval(this.interval);
                    // Just reload to show ended state with completion button
                    window.location.reload();
                    return;
                }
                this.updateDisplay();
            }, 1000);
        },

        updateDisplay() {
            this.hours = Math.floor(this.totalSeconds / 3600);
            this.minutes = Math.floor((this.totalSeconds % 3600) / 60);
            this.seconds = Math.floor(this.totalSeconds % 60);
            this.hours = String(this.hours).padStart(2, '0');
            this.minutes = String(this.minutes).padStart(2, '0');
            this.seconds = String(this.seconds).padStart(2, '0');
        }
    }));

    // Pusher real-time
    @if($chatStatus === 'active')
    const consultationId = {{ $consultation->id }};
    const currentUserId = {{ auth()->id() }};
    const channelName = 'private-consultation.' + consultationId;
    const channel = window.pusher.subscribe(channelName);
    
    channel.bind('message.sent', function(data) {
        if (data.sender_id !== currentUserId) {
            // Append message directly to DOM without Livewire re-render
            appendMessageToDOM(data);
            scrollToBottom();
        }
    });
    
    channel.bind('user.typing', function(data) {
        if (data.user_id !== currentUserId) {
            $wire.set('otherUserTyping', true);
            setTimeout(() => $wire.set('otherUserTyping', false), 3000);
        }
    });
    
    // Function to append message to DOM
    function appendMessageToDOM(message) {
        const container = document.getElementById('messages-container');
        if (!container) return;
        
        // Get the inner wrapper div (the one with space-y-3 class)
        const innerWrapper = container.querySelector('.space-y-3');
        if (!innerWrapper) return;
        
        const isMine = message.sender_id === currentUserId;
        const bubbleClass = isMine ? 'bg-blue-900 text-white' : 'bg-white text-gray-900 shadow-md';
        const justifyClass = isMine ? 'justify-end' : 'justify-start';
        const flexDirection = isMine ? 'flex-row-reverse' : '';
        
        let messageContent = '';
        
        // Add text message if exists
        if (message.message) {
            messageContent = `<p class="text-sm break-words">${linkifyText(escapeHtml(message.message))}</p>`;
        }
        
        // Add file attachment indicator if exists
        if (message.attachments && message.attachments.length > 0) {
            const attachmentBg = isMine ? 'bg-blue-800' : 'bg-gray-100';
            messageContent += `
                <div class="${message.message ? 'mt-2' : ''}">
                    <div class="flex items-center gap-2 p-2 rounded-lg ${attachmentBg}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        <span class="text-xs">📎 File attachment</span>
                    </div>
                </div>
            `;
        }
        
        // Create message div
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${justifyClass}`;
        messageDiv.innerHTML = `
            <div class="flex gap-2 max-w-[85%] md:max-w-[70%] ${flexDirection}">
                <div class="rounded-2xl px-4 py-3 ${bubbleClass}">
                    ${messageContent}
                </div>
            </div>
        `;
        
        // Append to wrapper (space-y-3 will handle spacing automatically)
        innerWrapper.appendChild(messageDiv);
        
        // Trigger scroll event
        window.dispatchEvent(new Event('message-appended'));
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Helper function to linkify URLs in text
    function linkifyText(text) {
        if (!text) return '';
        
        // URL regex pattern
        const urlPattern = /(https?:\/\/[^\s]+)/g;
        
        return text.replace(urlPattern, function(url) {
            return `<a href="${url}" target="_blank" rel="noopener noreferrer" class="underline hover:opacity-80">${url}</a>`;
        });
    }
    @endif

    // Auto scroll function - scroll to last message
    function scrollToBottom(force = false) {
        const container = document.getElementById('messages-container');
        if (!container) return;
        
        // Find the last message in the container
        const messages = container.querySelectorAll('.flex.justify-start, .flex.justify-end');
        const lastMessage = messages[messages.length - 1];
        
        if (lastMessage) {
            // Scroll the last message into view at the bottom
            lastMessage.scrollIntoView({ behavior: 'auto', block: 'nearest', inline: 'nearest' });
            
            // Add extra scroll to ensure it's fully visible with padding
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 10);
        } else {
            // Fallback to scrollTop
            container.scrollTop = container.scrollHeight;
        }
        
        // Additional attempts for reliability
        if (force) {
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 100);
        }
    }

    // Scroll on page load
    setTimeout(() => scrollToBottom(true), 200);
    setTimeout(() => scrollToBottom(true), 500);
    
    // Scroll when messages are sent or received
    $wire.on('message-sent', () => {
        setTimeout(() => scrollToBottom(true), 150);
    });
    
    $wire.on('message-received', () => {
        setTimeout(() => scrollToBottom(true), 150);
    });
    
    // Also scroll after Pusher message is appended
    window.addEventListener('message-appended', () => {
        setTimeout(() => scrollToBottom(true), 100);
    });
    
    // Use MutationObserver to detect when messages are added
    const container = document.getElementById('messages-container');
    if (container) {
        const observer = new MutationObserver(() => {
            setTimeout(() => scrollToBottom(false), 50);
        });
        
        observer.observe(container, {
            childList: true,
            subtree: true
        });
    }
</script>
@endscript

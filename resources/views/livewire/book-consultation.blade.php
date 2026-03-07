<x-slot name="sidebar">
    <x-client-sidebar />
</x-slot>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Parent Case Info (if additional service) -->
    @if($parentConsultation)
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl shadow-lg border-l-4 border-blue-600 p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-xs font-semibold uppercase tracking-wide">
                            Additional Service
                        </span>
                        <span class="text-xs text-gray-600">Thread #{{ $parentConsultation->getThreadNumber() }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $parentConsultation->title }}</h3>
                    <p class="text-sm text-gray-600 mb-3">
                        This booking will be linked to your existing case with {{ $parentConsultation->lawyer->name }}
                    </p>
                    <div class="flex items-center gap-4 text-sm">
                        <div class="flex items-center gap-1 text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Started {{ $parentConsultation->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="px-2 py-1 rounded text-xs font-medium
                                @if($parentConsultation->status === 'completed') bg-green-100 text-green-700
                                @elseif($parentConsultation->status === 'in_progress') bg-blue-100 text-blue-700
                                @elseif($parentConsultation->status === 'scheduled') bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $parentConsultation->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('client.consultation-thread.details', $parentConsultation->id) }}" 
                       class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-blue-700 hover:text-blue-800 hover:bg-blue-100 rounded-lg transition">
                        View Session
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Progress Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $step >= 1 ? 'bg-[#1E3A8A] text-white' : 'bg-gray-200 text-gray-600' }}">
                    1
                </div>
                <span class="text-sm font-medium {{ $step >= 1 ? 'text-[#1E3A8A]' : 'text-gray-600' }}">Select Service</span>
            </div>
            <div class="w-16 h-1 {{ $step >= 2 ? 'bg-[#1E3A8A]' : 'bg-gray-200' }}"></div>
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $step >= 2 ? 'bg-[#1E3A8A] text-white' : 'bg-gray-200 text-gray-600' }}">
                    2
                </div>
                <span class="text-sm font-medium {{ $step >= 2 ? 'text-primary-600' : 'text-gray-600' }}">Confirm & Request</span>
            </div>
        </div>
    </div>

    <!-- Step 1: Select Service -->
    @if($step === 1)
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">
                @if($parentConsultation)
                    Book Additional Service
                @else
                    Book Consultation
                @endif
            </h2>
            <p class="text-gray-600 mb-8">with {{ $lawyer->user->name }}</p>

            <div class="space-y-6">
                <!-- Service Type Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Service Type *</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($lawyer->offers_chat_consultation)
                            <label class="relative flex flex-col p-6 border-2 rounded-xl cursor-pointer transition {{ $serviceType === 'chat' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="serviceType" value="chat" class="sr-only">
                                <div class="flex items-center gap-3 mb-3">
                                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <span class="font-semibold text-gray-900">Chat Consultation</span>
                                </div>
                                <p class="text-sm text-gray-600">Text-based consultation</p>
                            </label>
                        @endif

                        @if($lawyer->offers_video_consultation)
                            <label class="relative flex flex-col p-6 border-2 rounded-xl cursor-pointer transition {{ $serviceType === 'video' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="serviceType" value="video" class="sr-only">
                                <div class="flex items-center gap-3 mb-3">
                                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-semibold text-gray-900">Video Consultation</span>
                                </div>
                                <p class="text-sm text-gray-600">Face-to-face video call</p>
                            </label>
                        @endif

                        @if($lawyer->offers_document_review)
                            <label class="relative flex flex-col p-6 border-2 rounded-xl cursor-pointer transition {{ $serviceType === 'document_review' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="serviceType" value="document_review" class="sr-only">
                                <div class="flex items-center gap-3 mb-3">
                                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="font-semibold text-gray-900">Document Review</span>
                                </div>
                                <p class="text-sm text-gray-600">Legal document analysis</p>
                            </label>
                        @endif
                    </div>
                    @error('serviceType')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration Selection (for chat/video) -->
                @if(in_array($serviceType, ['chat', 'video']))
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Select Duration *</label>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer transition {{ $duration === '15' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="duration" value="15" class="sr-only">
                                <span class="text-2xl font-bold text-gray-900 mb-1">15 min</span>
                                <span class="text-lg font-semibold text-primary-600">
                                    ₱{{ number_format($serviceType === 'chat' ? $lawyer->chat_rate_15min : $lawyer->video_rate_15min, 2) }}
                                </span>
                            </label>

                            <label class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer transition {{ $duration === '30' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="duration" value="30" class="sr-only">
                                <span class="text-2xl font-bold text-gray-900 mb-1">30 min</span>
                                <span class="text-lg font-semibold text-primary-600">
                                    ₱{{ number_format($serviceType === 'chat' ? $lawyer->chat_rate_30min : $lawyer->video_rate_30min, 2) }}
                                </span>
                            </label>

                            <label class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer transition {{ $duration === '60' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="duration" value="60" class="sr-only">
                                <span class="text-2xl font-bold text-gray-900 mb-1">60 min</span>
                                <span class="text-lg font-semibold text-primary-600">
                                    ₱{{ number_format($serviceType === 'chat' ? $lawyer->chat_rate_60min : $lawyer->video_rate_60min, 2) }}
                                </span>
                            </label>
                        </div>
                        @error('duration')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Schedule -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <flux:input 
                                wire:model.live="scheduledDate"
                                label="Preferred Date"
                                type="date"
                                :min="date('Y-m-d', strtotime('+1 day'))"
                                required
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Available Time Slots *</label>
                            
                            @if($scheduledDate && $duration)
                                @if(count($availableSlots) > 0)
                                    <div class="grid grid-cols-3 gap-2 max-h-48 overflow-y-auto p-2 border border-gray-200 rounded-lg">
                                        @foreach($availableSlots as $slot)
                                            <label class="flex items-center justify-center p-2 border rounded-lg cursor-pointer transition-all {{ $scheduledTime === $slot['time'] ? 'bg-primary-600 text-white border-primary-600' : 'bg-white hover:bg-gray-50 border-gray-300' }}">
                                                <input 
                                                    type="radio" 
                                                    wire:model.live="scheduledTime" 
                                                    value="{{ $slot['time'] }}"
                                                    class="sr-only"
                                                >
                                                <span class="text-sm font-medium">{{ $slot['formatted'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <p class="text-sm text-yellow-800">No available time slots for this date. Please select another date.</p>
                                    </div>
                                @endif
                            @else
                                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                    <p class="text-sm text-gray-600">Please select a date and duration first</p>
                                </div>
                            @endif
                            
                            @error('scheduledTime')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Auto-Accept Info for Chat/Video - Show immediately after service type selection -->
                    @if($lawyer->auto_accept_bookings && in_array($serviceType, ['chat', 'video']))
                        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-800 font-semibold mb-1">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Auto-Accept Enabled - Instant Booking!
                            </p>
                            <p class="text-xs text-green-700">
                                Your booking will be automatically accepted. Select your preferred date and time, then complete payment to confirm your consultation.
                            </p>
                        </div>
                    @endif
                @endif

                <!-- Document Upload (for document review) -->
                @if($serviceType === 'document_review')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Documents * (Max 5 files)</label>
                        <input 
                            type="file" 
                            wire:model="documents" 
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                            multiple
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                        >
                        <p class="mt-1 text-sm text-gray-500">PDF, JPG, PNG, DOC, DOCX - Max 10MB each</p>
                        
                        <div wire:loading wire:target="documents" class="mt-2 flex items-center gap-2 text-blue-600">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm">Uploading...</span>
                        </div>
                        
                        @if(!empty($documents))
                            <div class="mt-3 space-y-2">
                                @foreach($documents as $index => $doc)
                                    <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                                        <div class="flex items-center gap-2 flex-1 min-w-0">
                                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span class="text-sm text-green-800 truncate">{{ $doc->getClientOriginalName() }}</span>
                                            <span class="text-xs text-green-600 flex-shrink-0">({{ number_format($doc->getSize() / 1024 / 1024, 2) }} MB)</span>
                                        </div>
                                        <button 
                                            type="button"
                                            wire:click="removeDocument({{ $index }})"
                                            class="ml-2 p-1 text-red-600 hover:bg-red-100 rounded transition flex-shrink-0"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        @error('documents')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('documents.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-4 p-4 {{ $lawyer->auto_accept_bookings ? 'bg-green-50 border-green-200' : 'bg-blue-50 border-blue-200' }} border rounded-lg">
                            @if($lawyer->auto_accept_bookings)
                                <p class="text-sm text-green-800 font-semibold mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Auto-Accept Enabled - Instant Booking!
                                </p>
                                <p class="text-sm text-green-800">
                                    <strong>Fixed Price:</strong> ₱{{ number_format($lawyer->document_review_min_price, 2) }}
                                </p>
                                <p class="text-sm text-green-800 mt-1">
                                    <strong>Turnaround Time:</strong> 3-7 business days
                                </p>
                                <p class="text-xs text-green-700 mt-2">
                                    Your booking will be automatically accepted. Complete payment to start the review process.
                                </p>
                            @else
                                <p class="text-sm text-blue-800">
                                    <strong>Starting price:</strong> ₱{{ number_format($lawyer->document_review_min_price, 2) }}
                                </p>
                                <p class="text-xs text-blue-600 mt-1">
                                    The lawyer will review your documents and provide a final quote based on complexity.
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
                
                <!-- Supporting Documents (Optional - for chat/video only, not document review) -->
                @if($serviceType !== 'document_review')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Supporting Documents (Optional - Max 5 files)</label>
                        <input 
                            type="file" 
                            wire:model="supportingDocuments" 
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                            multiple
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                        >
                        <p class="mt-1 text-sm text-gray-500">PDF, JPG, PNG, DOC, DOCX - Max 10MB each</p>
                        
                        <div wire:loading wire:target="supportingDocuments" class="mt-2 flex items-center gap-2 text-blue-600">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm">Uploading...</span>
                        </div>
                        
                        @if(!empty($supportingDocuments))
                            <div class="mt-3 space-y-2">
                                @foreach($supportingDocuments as $index => $doc)
                                    <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                                        <div class="flex items-center gap-2 flex-1 min-w-0">
                                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span class="text-sm text-green-800 truncate">{{ $doc->getClientOriginalName() }}</span>
                                            <span class="text-xs text-green-600 flex-shrink-0">({{ number_format($doc->getSize() / 1024 / 1024, 2) }} MB)</span>
                                        </div>
                                        <button 
                                            type="button"
                                            wire:click="removeSupportingDocument({{ $index }})"
                                            class="ml-2 p-1 text-red-600 hover:bg-red-100 rounded transition flex-shrink-0"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        @error('supportingDocuments.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <!-- Consultation Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Title/Subject *</label>
                    <input 
                        type="text"
                        wire:model="title"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="e.g., Contract Review, Property Dispute, Employment Issue"
                        maxlength="100"
                    >
                    <p class="mt-1 text-sm text-gray-500">Brief title to identify this consultation (5-100 characters)</p>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Client Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Describe Your Legal Concern *</label>
                    <textarea 
                        wire:model="clientNotes"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Please provide details about your legal issue or question... (minimum 20 characters)"
                    ></textarea>
                    <p class="mt-1 text-sm text-gray-500">{{ strlen($clientNotes) }}/500 characters</p>
                    @error('clientNotes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-between pt-4">
                    <a href="{{ route('lawyers.show', $lawyer) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Profile
                    </a>
                    <flux:button wire:click="nextStep" variant="primary" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                        <span wire:loading.remove wire:target="nextStep">Continue</span>
                        <span wire:loading wire:target="nextStep">Processing...</span>
                    </flux:button>
                </div>
            </div>
        </div>
    @endif

    <!-- Step 2: Confirm & Request -->
    @if($step === 2)
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Confirm Booking Request</h2>
            <p class="text-gray-600 mb-8">Review your consultation details</p>

            <div class="space-y-6">
                <!-- Lawyer Info -->
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xl">
                        {{ $lawyer->user->initials() }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $lawyer->user->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $lawyer->ibp_number }}</p>
                    </div>
                </div>

                <!-- Service Details -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Service Details</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Service Type:</span>
                            <span class="font-medium text-gray-900">
                                {{ ucfirst(str_replace('_', ' ', $serviceType)) }}
                            </span>
                        </div>
                        @if($duration)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration:</span>
                                <span class="font-medium text-gray-900">{{ $duration }} minutes</span>
                            </div>
                        @endif
                        @if($scheduledDate && $scheduledTime)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Scheduled:</span>
                                <span class="font-medium text-gray-900">
                                    {{ date('M d, Y', strtotime($scheduledDate)) }} at {{ date('g:i A', strtotime($scheduledTime)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pricing -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Pricing</h3>
                    <div class="space-y-3">
                        @if($serviceType === 'document_review')
                            <div class="p-4 {{ $lawyer->auto_accept_bookings ? 'bg-green-50 border-green-200' : 'bg-blue-50 border-blue-200' }} border rounded-lg">
                                @if($lawyer->auto_accept_bookings)
                                    <p class="text-sm text-green-800 font-semibold mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Auto-Accept Enabled
                                    </p>
                                    <p class="text-sm text-green-800 font-medium mb-1">
                                        <strong>Fixed Price:</strong> ₱{{ number_format($lawyer->document_review_min_price, 2) }}
                                    </p>
                                    <p class="text-sm text-green-800 mb-2">
                                        <strong>Turnaround:</strong> 3-7 business days
                                    </p>
                                    <p class="text-xs text-green-700">
                                        Your booking will be automatically accepted. Complete payment to start the review.
                                    </p>
                                @else
                                    <p class="text-sm text-blue-800 font-medium mb-2">
                                        <strong>Starting price:</strong> ₱{{ number_format($lawyer->document_review_min_price, 2) }}
                                    </p>
                                    <p class="text-xs text-blue-700">
                                        The lawyer will review your document and provide a final quote based on complexity. Payment will be required only after you accept the quote.
                                    </p>
                                @endif
                            </div>
                        @else
                            @if($lawyer->auto_accept_bookings)
                                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-sm text-green-800 font-semibold mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Auto-Accept Enabled - Instant Booking!
                                    </p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-green-800 font-medium">Total Amount:</span>
                                        <span class="text-xl font-bold text-green-900">₱{{ number_format($totalAmount, 2) }}</span>
                                    </div>
                                    <p class="text-xs text-green-700 mt-2">
                                        Your booking will be automatically accepted. Complete payment to confirm your consultation.
                                    </p>
                                </div>
                            @else
                                <div class="flex justify-between text-lg font-bold">
                                    <span class="text-gray-900">Total Amount:</span>
                                    <span class="text-primary-600">₱{{ number_format($totalAmount, 2) }}</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Important Notice -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2">What happens next?</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        @if($lawyer->auto_accept_bookings)
                            <li>✓ Your booking will be automatically accepted</li>
                            <li>✓ You'll have 30 minutes to complete payment</li>
                            <li>✓ After payment, your consultation will be confirmed</li>
                        @else
                            <li>✓ Your request will be sent to the lawyer</li>
                            <li>✓ The lawyer will review and respond within 24 hours</li>
                            <li>✓ You'll be notified when the lawyer accepts</li>
                            <li>✓ Payment will be required only after lawyer accepts</li>
                        @endif
                    </ul>
                </div>

                <!-- Actions -->
                <div class="flex justify-between pt-4">
                    <button 
                        wire:click="previousStep" 
                        type="button"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back
                    </button>
                    <flux:button wire:click="submitRequest" variant="primary" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                        <span wire:loading.remove wire:target="submitRequest">
                            {{ $lawyer->auto_accept_bookings ? 'Confirm & Pay' : 'Send Request' }}
                        </span>
                        <span wire:loading wire:target="submitRequest" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Submitting...
                        </span>
                    </flux:button>
                </div>
            </div>
        </div>
    @endif
</div>

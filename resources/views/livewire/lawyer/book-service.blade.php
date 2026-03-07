<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Parent Case Info -->
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
                        Offering Additional Service
                    </span>
                    <span class="text-xs text-gray-600">Thread #{{ $parentCase->getThreadNumber() }}</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $parentCase->title }}</h3>
                <p class="text-sm text-gray-600 mb-3">
                    Client: {{ $parentCase->client->name }}
                </p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('lawyer.consultation-thread.details', $parentCase->id) }}" 
                   class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-blue-700 hover:text-blue-800 hover:bg-blue-100 rounded-lg transition">
                    View Session
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $step >= 1 ? 'bg-[#1E3A8A] text-white' : 'bg-gray-200 text-gray-600' }}">
                    1
                </div>
                <span class="text-sm font-medium {{ $step >= 1 ? 'text-[#1E3A8A]' : 'text-gray-600' }}">Service Details</span>
            </div>
            <div class="w-16 h-1 {{ $step >= 2 ? 'bg-[#1E3A8A]' : 'bg-gray-200' }}"></div>
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $step >= 2 ? 'bg-[#1E3A8A] text-white' : 'bg-gray-200 text-gray-600' }}">
                    2
                </div>
                <span class="text-sm font-medium {{ $step >= 2 ? 'text-primary-600' : 'text-gray-600' }}">Review & Send</span>
            </div>
        </div>
    </div>

    <!-- Step 1: Service Details -->
    @if($step === 1)
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Offer Additional Service</h2>
            <p class="text-gray-600 mb-8">Configure the service you want to offer to your client</p>

            <div class="space-y-6">
                <!-- Service Type Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Service Type *</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                            </label>

                            <label class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer transition {{ $duration === '30' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="duration" value="30" class="sr-only">
                                <span class="text-2xl font-bold text-gray-900 mb-1">30 min</span>
                            </label>

                            <label class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer transition {{ $duration === '60' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="duration" value="60" class="sr-only">
                                <span class="text-2xl font-bold text-gray-900 mb-1">60 min</span>
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
                                        <p class="text-sm text-yellow-800">No available time slots for this date.</p>
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
                @endif

                <!-- Pricing -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Pricing *</label>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition {{ $pricingType === 'free' ? 'border-green-600 bg-green-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" wire:model.live="pricingType" value="free" class="sr-only">
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-900 block">Free Service</span>
                                    <span class="text-sm text-gray-600">No charge to client</span>
                                </div>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition {{ $pricingType === 'quoted' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" wire:model.live="pricingType" value="quoted" class="sr-only">
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-900 block">Custom Price</span>
                                    <span class="text-sm text-gray-600">Set your price</span>
                                </div>
                            </div>
                        </label>
                    </div>

                    @if($pricingType === 'quoted')
                        <div>
                            <flux:input 
                                wire:model="quotedPrice"
                                label="Price Amount (₱)"
                                type="number"
                                min="1"
                                step="0.01"
                                placeholder="0.00"
                                required
                            />
                        </div>
                    @endif
                    @error('pricingType')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('quotedPrice')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Service Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Service Title *</label>
                    <input 
                        type="text"
                        wire:model="title"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="e.g., Additional Document Review"
                        maxlength="100"
                    >
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description/Notes for Client *</label>
                    <textarea 
                        wire:model="notes"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Explain why you're offering this service and what the client can expect..."
                    ></textarea>
                    <p class="mt-1 text-sm text-gray-500">{{ strlen($notes) }}/500 characters</p>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-between pt-4">
                    <a href="{{ route('lawyer.consultation-thread.details', $parentCase->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Session
                    </a>
                    <flux:button wire:click="nextStep" variant="primary" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                        <span wire:loading.remove wire:target="nextStep">Continue</span>
                        <span wire:loading wire:target="nextStep">Processing...</span>
                    </flux:button>
                </div>
            </div>
        </div>
    @endif

    <!-- Step 2: Review & Send -->
    @if($step === 2)
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Review Service Offer</h2>
            <p class="text-gray-600 mb-8">Confirm the details before sending to client</p>

            <div class="space-y-6">
                <!-- Client Info -->
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xl">
                        {{ $parentCase->client->initials() }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $parentCase->client->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $parentCase->client->email }}</p>
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
                        <div class="flex justify-between">
                            <span class="text-gray-600">Title:</span>
                            <span class="font-medium text-gray-900">{{ $title }}</span>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Pricing</h3>
                    @if($pricingType === 'free')
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-lg font-bold text-green-800">FREE SERVICE</p>
                            <p class="text-sm text-green-700 mt-1">No charge to client</p>
                        </div>
                    @else
                        <div class="flex justify-between text-lg font-bold">
                            <span class="text-gray-900">Price:</span>
                            <span class="text-primary-600">₱{{ number_format($quotedPrice, 2) }}</span>
                        </div>
                    @endif
                </div>

                <!-- Notes -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Your Notes to Client</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $notes }}</p>
                </div>

                <!-- Important Notice -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2">What happens next?</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>✓ Client will receive notification of your offer</li>
                        <li>✓ Client can accept or decline the offer</li>
                        @if($pricingType === 'quoted')
                            <li>✓ If accepted, client will be directed to payment</li>
                            <li>✓ Service starts after payment is completed</li>
                        @else
                            <li>✓ If accepted, service starts immediately</li>
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
                    <flux:button wire:click="submitOffer" variant="primary" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                        <span wire:loading.remove wire:target="submitOffer">Send Offer to Client</span>
                        <span wire:loading wire:target="submitOffer" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Sending...
                        </span>
                    </flux:button>
                </div>
            </div>
        </div>
    @endif
</div>

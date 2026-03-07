<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>

<div class="min-h-screen bg-white">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Info Panel with Header -->
        <div class="bg-gradient-to-r from-violet-50 to-purple-50 border border-violet-100 rounded-2xl p-4 sm:p-6 mb-6">
            <div class="flex items-start gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-violet-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Services & Pricing</h1>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Configure your service offerings and rates.
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <x-profile-nav type="lawyer" current="services" />

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg" x-data x-init="$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))">
                <p class="text-sm text-green-600 font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg" x-data x-init="$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))">
                <p class="text-sm text-red-600 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Form -->
        <form wire:submit="save" class="space-y-8">
            <!-- Service Pricing -->
            <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 space-y-6">
                <h3 class="text-lg font-semibold text-gray-900">Service Pricing</h3>
                
                <!-- Chat Consultation -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2">
                            <input 
                                type="checkbox" 
                                wire:model="offers_chat_consultation"
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                            >
                            <span class="text-sm font-medium text-gray-900">Offer Chat Consultation</span>
                        </label>
                    </div>
                    
                    @if($offers_chat_consultation)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pl-6">
                            <flux:input 
                                wire:model="chat_rate_15min" 
                                label="15 Minutes Rate (₱)" 
                                type="number" 
                                min="0"
                                step="0.01"
                                placeholder="500.00"
                            />
                            <flux:input 
                                wire:model="chat_rate_30min" 
                                label="30 Minutes Rate (₱)" 
                                type="number" 
                                min="0"
                                step="0.01"
                                placeholder="900.00"
                            />
                            <flux:input 
                                wire:model="chat_rate_60min" 
                                label="60 Minutes Rate (₱)" 
                                type="number" 
                                min="0"
                                step="0.01"
                                placeholder="1500.00"
                            />
                        </div>
                    @endif
                </div>

                <!-- Video Consultation -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2">
                            <input 
                                type="checkbox" 
                                wire:model="offers_video_consultation"
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                            >
                            <span class="text-sm font-medium text-gray-900">Offer Video Consultation</span>
                        </label>
                    </div>
                    
                    @if($offers_video_consultation)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pl-6">
                            <flux:input 
                                wire:model="video_rate_15min" 
                                label="15 Minutes Rate (₱)" 
                                type="number" 
                                min="0"
                                step="0.01"
                                placeholder="800.00"
                            />
                            <flux:input 
                                wire:model="video_rate_30min" 
                                label="30 Minutes Rate (₱)" 
                                type="number" 
                                min="0"
                                step="0.01"
                                placeholder="1400.00"
                            />
                            <flux:input 
                                wire:model="video_rate_60min" 
                                label="60 Minutes Rate (₱)" 
                                type="number" 
                                min="0"
                                step="0.01"
                                placeholder="2500.00"
                            />
                        </div>
                    @endif
                </div>

                <!-- Document Review -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2">
                            <input 
                                type="checkbox" 
                                wire:model="offers_document_review"
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                            >
                            <span class="text-sm font-medium text-gray-900">Offer Document Review</span>
                        </label>
                    </div>
                    
                    @if($offers_document_review)
                        <div class="pl-6">
                            <flux:input 
                                wire:model="document_review_min_price" 
                                label="Minimum Price (₱)" 
                                type="number" 
                                min="0"
                                step="0.01"
                                placeholder="1000.00"
                            />
                            <p class="mt-1 text-sm text-gray-500">Starting price for document review services</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Availability Settings -->
            <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 space-y-6">
                <h3 class="text-lg font-semibold text-gray-900">Availability Settings</h3>
                
                @if(!auth()->user()->lawyerProfile->is_verified)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-yellow-800">Account Verification Required</p>
                                <p class="text-sm text-yellow-700 mt-1">You must be verified before you can accept consultations. Your profile is currently under review.</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="space-y-4">
                    <label class="flex items-start gap-3 p-4 border border-gray-300 rounded-lg {{ auth()->user()->lawyerProfile->is_verified ? 'hover:bg-gray-50 cursor-pointer' : 'bg-gray-50 cursor-not-allowed opacity-60' }} transition">
                        <input 
                            type="checkbox" 
                            wire:model="is_available"
                            {{ !auth()->user()->lawyerProfile->is_verified ? 'disabled' : '' }}
                            class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500 {{ !auth()->user()->lawyerProfile->is_verified ? 'cursor-not-allowed' : '' }}"
                        >
                        <div class="flex-1">
                            <span class="text-sm font-medium text-gray-900 block">Available for Consultations</span>
                            <span class="text-sm text-gray-500">When disabled, your profile will be hidden from search and you won't receive new bookings</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                        <input 
                            type="checkbox" 
                            wire:model="auto_accept_bookings"
                            class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                        >
                        <div class="flex-1">
                            <span class="text-sm font-medium text-gray-900 block">Auto-Accept Bookings</span>
                            <span class="text-sm text-gray-500">Automatically accept consultation requests without manual approval</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <flux:button 
                    type="submit" 
                    variant="primary"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="save">Save Changes</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </flux:button>
            </div>
        </form>
    </div>
</div>

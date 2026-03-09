<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>


<div class="p-4 sm:p-6 lg:p-8">
    <!-- Info Panel with Header -->
    <div class="bg-gradient-to-r from-primary-50 to-blue-50 border border-primary-100 rounded-2xl p-4 sm:p-6 mb-6">
        <div class="flex items-start gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-primary-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Consultations</h1>
                <p class="text-sm text-gray-700 leading-relaxed">
                    Individual consultation bookings - each represents a single service request (chat, video, or document review).
                </p>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white rounded-2xl shadow-lg mb-6">
        <!-- Search Row -->
        <div class="p-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" 
                               wire:model.live.debounce.300ms="search"
                               placeholder="Search by client name, title, or notes..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <!-- Type Filter -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            type="button"
                            class="w-full sm:w-auto px-4 py-2.5 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 transition flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">
                                @if($typeFilter) {{ ucfirst(str_replace('_', ' ', $typeFilter)) }}
                                @else All Types
                                @endif
                            </span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="display: none;"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                        <button wire:click="$set('typeFilter', ''); open = false" 
                                type="button"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition {{ $typeFilter === '' ? 'text-primary-700 font-medium' : 'text-gray-700' }}">
                            All Types
                        </button>
                        <button wire:click="$set('typeFilter', 'video'); open = false" 
                                type="button"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition {{ $typeFilter === 'video' ? 'text-primary-700 font-medium' : 'text-gray-700' }}">
                            Video Call
                        </button>
                        <button wire:click="$set('typeFilter', 'chat'); open = false" 
                                type="button"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition {{ $typeFilter === 'chat' ? 'text-primary-700 font-medium' : 'text-gray-700' }}">
                            Chat
                        </button>
                        <button wire:click="$set('typeFilter', 'document_review'); open = false" 
                                type="button"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition {{ $typeFilter === 'document_review' ? 'text-primary-700 font-medium' : 'text-gray-700' }}">
                            Document Review
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="px-4 pb-4">
            <div class="flex flex-wrap gap-2">
                <button wire:click="setFilter('pending')" class="px-4 py-2 rounded-full text-sm font-medium transition {{ $filter === 'pending' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    Pending ({{ $counts['pending'] }})
                </button>
                <button wire:click="setFilter('awaiting_quote')" class="px-4 py-2 rounded-full text-sm font-medium transition {{ $filter === 'awaiting_quote' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    Awaiting Quote ({{ $counts['awaiting_quote'] }})
                </button>
                <button wire:click="setFilter('payment_pending')" class="px-4 py-2 rounded-full text-sm font-medium transition {{ $filter === 'payment_pending' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    Payment Pending ({{ $counts['payment_pending'] }})
                </button>
                <button wire:click="setFilter('scheduled')" class="px-4 py-2 rounded-full text-sm font-medium transition {{ $filter === 'scheduled' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    Scheduled ({{ $counts['scheduled'] }})
                </button>
                <button wire:click="setFilter('in_progress')" class="px-4 py-2 rounded-full text-sm font-medium transition {{ $filter === 'in_progress' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    In Progress ({{ $counts['in_progress'] }})
                </button>
                <button wire:click="setFilter('completed')" class="px-4 py-2 rounded-full text-sm font-medium transition {{ $filter === 'completed' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    Completed ({{ $counts['completed'] }})
                </button>
                <button wire:click="setFilter('cancelled')" class="px-4 py-2 rounded-full text-sm font-medium transition {{ $filter === 'cancelled' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    Cancelled ({{ $counts['cancelled'] }})
                </button>
                <button wire:click="setFilter('all')" class="px-4 py-2 rounded-full text-sm font-medium transition {{ $filter === 'all' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    All ({{ $counts['all'] }})
                </button>
            </div>
        </div>

        <!-- Active Filters & Results Count -->
        <div class="px-4 bg-gray-50 rounded-b-2xl">
            <div class="flex flex-wrap items-center gap-3">
                
                @if($search)
                    <button wire:click="$set('search', '')" 
                            type="button"
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-gray-200 rounded-full text-sm text-gray-700 hover:bg-gray-50 transition mb-6">
                        <span>Search: "{{ $search }}"</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif

                @if($typeFilter)
                    <button wire:click="$set('typeFilter', '')" 
                            type="button"
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-gray-200 rounded-full text-sm text-gray-700 hover:bg-gray-50 transition mb-6">
                        <span>Type: {{ ucfirst(str_replace('_', ' ', $typeFilter)) }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif

                @if($search || $typeFilter)
                    <button wire:click="clearFilters" 
                            type="button"
                            class="text-sm text-primary-700 hover:text-primary-800 font-medium mb-6">
                        Clear all
                    </button>
                @endif
            </div>
        </div>
    </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <!-- Consultations List -->
        @if($consultations->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($consultations as $consultation)
                    <a href="{{ route('lawyer.consultation.details', $consultation->id) }}" class="bg-white rounded-xl shadow-md hover:shadow-xl transition border border-gray-100 hover:border-primary-300 flex flex-col cursor-pointer">
                        <!-- Status Badge & Type Icon -->
                        <div class="px-4 pt-4 pb-2 flex items-center justify-between">
                            <!-- Type Icon -->
                            <div class="flex items-center gap-2">
                                @if($consultation->consultation_type === 'video')
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @elseif($consultation->consultation_type === 'chat')
                                    <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                    </div>
                                @elseif($consultation->consultation_type === 'document_review')
                                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Status Badge -->
                            @php
                                $displayStatus = $consultation->getDisplayStatus();
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $displayStatus === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $displayStatus === 'scheduled' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $displayStatus === 'in_progress' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $displayStatus === 'ended' ? 'bg-gray-100 text-gray-700' : '' }}
                                {{ $displayStatus === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $displayStatus === 'pending_client_acceptance' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $displayStatus === 'awaiting_quote_approval' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $displayStatus === 'payment_pending' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $displayStatus === 'payment_processing' ? 'bg-blue-100 text-blue-700 animate-pulse' : '' }}
                                {{ $displayStatus === 'payment_failed' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $displayStatus === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $displayStatus === 'declined' ? 'bg-gray-100 text-gray-700' : '' }}
                            ">
                                @if($displayStatus === 'ended')
                                    Ended - Waiting to be Completed
                                @elseif($displayStatus === 'pending_client_acceptance')
                                    Pending Client Approval
                                @elseif($displayStatus === 'payment_processing')
                                    Payment Processing...
                                @elseif($displayStatus === 'payment_failed')
                                    Payment Failed - Retry
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $displayStatus)) }}
                                @endif
                            </span>
                        </div>

                        <!-- Title -->
                        @if($consultation->title)
                            <div class="px-4 py-2">
                                <h3 class="font-bold text-gray-900 text-base line-clamp-2">{{ $consultation->title }}</h3>
                            </div>
                        @endif

                        <!-- Client Info -->
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-accent-100 flex items-center justify-center text-accent-700 font-bold text-sm">
                                    {{ $consultation->client->initials() }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 truncate text-sm">{{ $consultation->client->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">Client</p>
                                </div>
                            </div>
                        </div>

                        <!-- Consultation Details -->
                        <div class="px-4 py-3 space-y-2 text-sm flex-grow">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Type:</span>
                                <span class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $consultation->consultation_type)) }}</span>
                            </div>
                            @if($consultation->duration)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="font-medium text-gray-900">{{ $consultation->duration }} min</span>
                                </div>
                            @endif
                            @if($consultation->scheduled_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Date:</span>
                                    <span class="font-medium text-gray-900">{{ $consultation->scheduled_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Time:</span>
                                    <span class="font-medium text-gray-900">{{ $consultation->scheduled_at->format('g:i A') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between pt-2 border-t border-gray-100">
                                <span class="text-gray-600">Fee:</span>
                                <span class="font-bold text-primary-600">₱{{ number_format($consultation->rate, 2) }}</span>
                            </div>
                        </div>

                        <!-- Client Notes Preview -->
                        @if($consultation->client_notes)
                            <div class="px-4 py-2 bg-gray-50 border-t border-gray-100">
                                <p class="text-xs text-gray-600 line-clamp-2">{{ $consultation->client_notes }}</p>
                            </div>
                        @endif

                        <!-- Countdown Timers -->
                        @if($consultation->status === 'pending' && $consultation->lawyer_response_deadline)
                            <div class="px-4 py-2 border-t border-yellow-200">
                                @livewire('components.countdown-timer', [
                                    'deadline' => $consultation->lawyer_response_deadline,
                                    'label' => 'Respond within',
                                    'type' => 'lawyer_response',
                                    'consultationId' => $consultation->id
                                ], key('lawyer-response-timer-'.$consultation->id))
                            </div>
                        @endif
                        
                        @if($consultation->status === 'awaiting_quote_approval' && $consultation->quote_deadline)
                            <div class="px-4 py-2 border-t border-purple-200">
                                @livewire('components.countdown-timer', [
                                    'deadline' => $consultation->quote_deadline,
                                    'label' => 'Client responds within',
                                    'type' => 'quote_response',
                                    'consultationId' => $consultation->id
                                ], key('quote-timer-'.$consultation->id))
                            </div>
                        @endif
                        
                        @if($consultation->status === 'payment_pending' && $consultation->payment_deadline)
                            <div class="px-4 py-2 border-t border-orange-200">
                                @livewire('components.countdown-timer', [
                                    'deadline' => $consultation->payment_deadline,
                                    'label' => 'Payment due within',
                                    'type' => 'payment',
                                    'consultationId' => $consultation->id
                                ], key('payment-timer-'.$consultation->id))
                            </div>
                        @endif

                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $consultations->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-gray-100">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No consultations found</h3>
                <p class="text-gray-600">You don't have any consultations matching this filter.</p>
            </div>
        @endif

    <!-- Accept Consultation Modal -->
    <!-- DEBUG: showAcceptModal = {{ $showAcceptModal ? 'TRUE' : 'FALSE' }} -->
    @if($showAcceptModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="closeModals"></div>

                <!-- Modal panel -->
                <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-50">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <!-- Centered Icon -->
                        <div class="flex justify-center mb-4">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="text-center">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Accept Consultation Request</h3>
                            <div class="text-left space-y-3">
                                    <p class="text-sm text-gray-700">
                                        By accepting this consultation request, you are committing to:
                                    </p>
                                    <ul class="list-disc list-inside text-sm text-gray-700 space-y-2 ml-2">
                                        <li>Be available at the scheduled time</li>
                                        <li>Provide professional legal consultation services</li>
                                        <li>Maintain client confidentiality</li>
                                        <li>Uphold the highest standards of legal ethics</li>
                                    </ul>
                                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-sm text-blue-800">
                                            <strong>Note:</strong> The client will have 30 minutes to complete payment. Once paid, the consultation will be confirmed.
                                        </p>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end">
                        <button 
                            type="button"
                            wire:click="closeModals"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button 
                            type="button"
                            wire:click="acceptRequest"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-[#1E40AF]"
                        >
                            <span wire:loading.remove wire:target="acceptRequest">Accept Request</span>
                            <span wire:loading wire:target="acceptRequest" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Decline Consultation Modal -->
    @if($showDeclineModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="closeModals"></div>

                <!-- Modal panel -->
                <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-50">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <!-- Centered Icon -->
                        <div class="flex justify-center mb-4">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-red-100">
                                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="text-center">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Decline Consultation Request</h3>
                            <div class="text-left space-y-3">
                                    <p class="text-sm text-gray-700">
                                        Please consider carefully before declining. This affects your acceptance rate and client trust.
                                    </p>
                                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <p class="text-sm text-yellow-800">
                                            <strong>Reminder:</strong> Be respectful and professional. Consider providing a brief reason to help the client understand.
                                        </p>
                                    </div>
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Reason for declining (optional)
                                        </label>
                                        <textarea 
                                            wire:model="declineReason"
                                            rows="3"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                            placeholder="e.g., Schedule conflict, outside my area of expertise..."
                                        ></textarea>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end">
                        <button 
                            type="button"
                            wire:click="closeModals"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button 
                            type="button"
                            wire:click="declineRequest"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
                        >
                            <span wire:loading.remove wire:target="declineRequest">Decline Request</span>
                            <span wire:loading wire:target="declineRequest" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Provide Quote Modal -->
    @if($showQuoteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="closeModals"></div>

                <!-- Modal panel -->
                <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-50">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <!-- Centered Icon -->
                        <div class="flex justify-center mb-4">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-100">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="text-center">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Provide Custom Quote</h3>
                            <div class="text-left space-y-4">
                                <p class="text-sm text-gray-700">
                                    Review the consultation request and provide a fair quote for your services.
                                </p>
                                
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-sm text-blue-800">
                                        <strong>Note:</strong> Your quote should reflect the complexity of the request, time required, and your expertise. The platform fee (10%) will be added automatically.
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Quote Amount (₱) <span class="text-red-600">*</span>
                                    </label>
                                    <input 
                                        type="number"
                                        wire:model="quotedPrice"
                                        step="0.01"
                                        min="1"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                        placeholder="e.g., 1500.00"
                                    >
                                    @error('quotedPrice') 
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Quote Explanation <span class="text-red-600">*</span>
                                    </label>
                                    <textarea 
                                        wire:model="quoteNotes"
                                        rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                        placeholder="Explain your quote: complexity of the request, estimated time, specific services included..."
                                    ></textarea>
                                    @error('quoteNotes') 
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">
                                        Minimum 10 characters. Be clear and professional.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end">
                        <button 
                            type="button"
                            wire:click="closeModals"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button 
                            type="button"
                            wire:click="provideQuote"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                        >
                            <span wire:loading.remove wire:target="provideQuote">Send Quote</span>
                            <span wire:loading wire:target="provideQuote" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>




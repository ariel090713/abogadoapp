@if($consultation->isReschedulePending())
    @php
        $isRequester = $consultation->reschedule_requested_by === auth()->id();
        $proposedDate = $consultation->proposed_scheduled_at;
    @endphp

    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-6 rounded-r-lg">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            
            <div class="flex-1">
                @if($isRequester)
                    <!-- Requester View -->
                    <h3 class="text-lg font-semibold text-yellow-900 mb-2">
                        Reschedule Request Pending
                    </h3>
                    <p class="text-sm text-yellow-800 mb-3">
                        You requested to reschedule this consultation. Waiting for 
                        {{ $consultation->reschedule_requested_by === $consultation->client_id ? 'lawyer' : 'client' }} 
                        approval.
                    </p>
                    <div class="bg-white rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Current Schedule</p>
                                <p class="font-semibold text-gray-900">
                                    {{ $consultation->scheduled_at->format('M d, Y') }}
                                </p>
                                <p class="text-sm text-gray-700">
                                    {{ $consultation->scheduled_at->format('g:i A') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Proposed Schedule</p>
                                <p class="font-semibold text-green-900">
                                    {{ $proposedDate->format('M d, Y') }}
                                </p>
                                <p class="text-sm text-green-700">
                                    {{ $proposedDate->format('g:i A') }}
                                </p>
                            </div>
                        </div>
                        @if($consultation->reschedule_reason)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-xs text-gray-500 mb-1">Reason</p>
                                <p class="text-sm text-gray-700">{{ $consultation->reschedule_reason }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="flex gap-3">
                        <flux:button 
                            size="sm" 
                            variant="ghost"
                            wire:click="cancelRescheduleRequest"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                        >
                            <span wire:loading.remove wire:target="cancelRescheduleRequest">Cancel Request</span>
                            <span wire:loading wire:target="cancelRescheduleRequest">Cancelling...</span>
                        </flux:button>
                    </div>
                @else
                    <!-- Receiver View -->
                    <h3 class="text-lg font-semibold text-yellow-900 mb-2">
                        Reschedule Request Received
                    </h3>
                    <p class="text-sm text-yellow-800 mb-3">
                        {{ $consultation->rescheduleRequestedBy->name }} has requested to reschedule this consultation.
                    </p>
                    <div class="bg-white rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Current Schedule</p>
                                <p class="font-semibold text-gray-900">
                                    {{ $consultation->scheduled_at->format('M d, Y') }}
                                </p>
                                <p class="text-sm text-gray-700">
                                    {{ $consultation->scheduled_at->format('g:i A') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Proposed Schedule</p>
                                <p class="font-semibold text-green-900">
                                    {{ $proposedDate->format('M d, Y') }}
                                </p>
                                <p class="text-sm text-green-700">
                                    {{ $proposedDate->format('g:i A') }}
                                </p>
                            </div>
                        </div>
                        @if($consultation->reschedule_reason)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-xs text-gray-500 mb-1">Reason</p>
                                <p class="text-sm text-gray-700">{{ $consultation->reschedule_reason }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="flex gap-3">
                        <flux:button 
                            size="sm" 
                            variant="primary"
                            wire:click="approveReschedule"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                        >
                            <span wire:loading.remove wire:target="approveReschedule">Approve</span>
                            <span wire:loading wire:target="approveReschedule">Approving...</span>
                        </flux:button>
                        <flux:button 
                            size="sm" 
                            variant="danger"
                            x-on:click="$flux.modal('decline-reschedule-modal').show()"
                        >
                            Decline
                        </flux:button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

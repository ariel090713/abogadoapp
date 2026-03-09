<!-- Reschedule Request Modal -->
<flux:modal name="reschedule-modal" class="space-y-6 max-w-2xl">
    <div>
        <flux:heading size="lg">Request Reschedule</flux:heading>
        <flux:subheading>Select a new date and time for your consultation</flux:subheading>
    </div>

    <!-- Reschedule Info -->
    <x-reschedule-info :consultation="$consultation" />

    <!-- Date Selection -->
    <div class="space-y-2">
        <flux:label>Select Date</flux:label>
        <flux:input 
            type="date" 
            wire:model.live="selectedDate"
            :min="now()->addDay()->format('Y-m-d')"
            :max="now()->addDays(30)->format('Y-m-d')"
            placeholder="Choose a date"
        />
        <flux:error name="selectedDate" />
        <p class="text-xs text-gray-500">Must be at least 24 hours from now</p>
    </div>

    <!-- Time Slots -->
    @if($selectedDate && count($availableSlots) > 0)
        <div class="space-y-2">
            <flux:label>Available Time Slots</flux:label>
            <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 max-h-64 overflow-y-auto p-2 bg-gray-50 rounded-lg">
                @foreach($availableSlots as $slot)
                    <button 
                        type="button"
                        wire:click="$set('selectedSlot', '{{ $slot['time'] }}')"
                        class="px-3 py-2 text-sm rounded-lg border transition
                            {{ $selectedSlot === $slot['time'] 
                                ? 'bg-primary-500 text-white border-primary-600' 
                                : 'bg-white text-gray-700 border-gray-300 hover:border-primary-500 hover:bg-primary-50' 
                            }}"
                    >
                        {{ $slot['display'] }}
                    </button>
                @endforeach
            </div>
            <flux:error name="selectedSlot" />
        </div>
    @elseif($selectedDate && count($availableSlots) === 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-yellow-900">No available time slots</p>
                    <p class="text-xs text-yellow-700 mt-1">The lawyer has no available slots on this date. Please select another date.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Reason -->
    <div class="space-y-2">
        <flux:label>Reason for Rescheduling <span class="text-red-500">*</span></flux:label>
        <flux:textarea 
            wire:model="rescheduleReason"
            rows="3"
            placeholder="Please explain why you need to reschedule..."
        />
        <flux:error name="rescheduleReason" />
        <p class="text-xs text-gray-500">Minimum 10 characters</p>
    </div>

    <!-- Actions -->
    <div class="flex gap-3 justify-end">
        <flux:button variant="ghost" x-on:click="$flux.close('reschedule-modal')">
            Cancel
        </flux:button>
        <flux:button 
            variant="primary" 
            wire:click="requestReschedule"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-50 cursor-not-allowed"
        >
            <span wire:loading.remove wire:target="requestReschedule">Submit Request</span>
            <span wire:loading wire:target="requestReschedule" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            </span>
        </flux:button>
    </div>
</flux:modal>

<!-- Decline Reschedule Modal -->
<flux:modal name="decline-reschedule-modal" class="space-y-6">
    <div>
        <flux:heading size="lg">Decline Reschedule Request</flux:heading>
        <flux:subheading>Please provide a reason for declining</flux:subheading>
    </div>

    <div class="space-y-2">
        <flux:label>Reason for Declining <span class="text-red-500">*</span></flux:label>
        <flux:textarea 
            wire:model="declineReason"
            rows="4"
            placeholder="Explain why you cannot accept this reschedule..."
        />
        <flux:error name="declineReason" />
        <p class="text-xs text-gray-500">Minimum 10 characters</p>
    </div>

    <div class="flex gap-3 justify-end">
        <flux:button variant="ghost" x-on:click="$flux.close('decline-reschedule-modal')">
            Cancel
        </flux:button>
        <flux:button 
            variant="danger" 
            wire:click="declineReschedule"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-50 cursor-not-allowed"
        >
            <span wire:loading.remove wire:target="declineReschedule">Decline Request</span>
            <span wire:loading wire:target="declineReschedule" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            </span>
        </flux:button>
    </div>
</flux:modal>

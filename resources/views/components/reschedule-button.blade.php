@if($consultation->canBeRescheduled())
    <button 
        wire:click="$set('showRescheduleModal', true)"
        class="w-full bg-orange-500 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-orange-600 transition flex items-center justify-center gap-2"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        Reschedule Consultation
        <span class="ml-1 px-2 py-0.5 bg-orange-600 rounded-full text-xs">
            {{ $consultation->getReschedulesRemaining() }} left
        </span>
    </button>
@elseif($consultation->hasReachedRescheduleLimit())
    <div class="w-full bg-gray-100 text-gray-500 px-4 py-2.5 rounded-lg text-sm font-medium flex items-center justify-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        Reschedule limit reached
    </div>
@endif

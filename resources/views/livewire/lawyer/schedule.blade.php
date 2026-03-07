<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>


<div class="p-4 sm:p-6 lg:p-8" x-data="{ showBlockModal: @entangle('showBlockModal') }">
    <!-- Info Panel -->
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 rounded-2xl p-4 sm:p-6 mb-6">
        <div class="flex items-start gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Schedule & Availability</h3>
                <p class="text-sm text-gray-700 leading-relaxed">
                    Set your weekly availability and block specific dates. Clients can only book during your available hours.
                </p>
            </div>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="mb-6 flex gap-2 w-full sm:w-auto overflow-x-auto">
        <button 
            wire:click="setView('weekly')"
            class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap {{ $view === 'weekly' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}"
        >
            <span class="hidden sm:inline">Weekly Schedule</span>
            <span class="sm:hidden">Weekly</span>
        </button>
        <button 
            wire:click="setView('calendar')"
            class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap {{ $view === 'calendar' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}"
        >
            <span class="hidden sm:inline">Calendar View</span>
            <span class="sm:hidden">Calendar</span>
        </button>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($view === 'weekly')
        <!-- 2-Column Layout (stacks on mobile) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Left Column: Weekly Schedule Editor -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Weekly Availability</h2>
            
            <form wire:submit="save">
                <div class="space-y-4 sm:space-y-6">
                    @foreach($days as $dayNum => $dayName)
                        <div class="border border-gray-200 rounded-xl p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 mb-4">
                                <div class="flex items-center gap-3 sm:gap-4">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            wire:click="toggleDay({{ $dayNum }})"
                                            @checked($schedules[$dayNum]['is_available'])
                                            class="sr-only peer"
                                        >
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                                    </label>
                                    <span class="text-base sm:text-lg font-semibold text-gray-900">{{ $dayName }}</span>
                                </div>
                                
                                @if($schedules[$dayNum]['is_available'])
                                    <span class="text-xs sm:text-sm text-green-600 font-medium">Available</span>
                                @else
                                    <span class="text-xs sm:text-sm text-gray-400 font-medium">Unavailable</span>
                                @endif
                            </div>

                            @if($schedules[$dayNum]['is_available'])
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                                        <select 
                                            wire:model="schedules.{{ $dayNum }}.start_time"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                        >
                                            @for($hour = 0; $hour < 24; $hour++)
                                                @for($min = 0; $min < 60; $min += 15)
                                                    @php
                                                        $time24 = sprintf('%02d:%02d', $hour, $min);
                                                        $hour12 = $hour % 12 ?: 12;
                                                        $ampm = $hour < 12 ? 'AM' : 'PM';
                                                        $time12 = sprintf('%d:%02d %s', $hour12, $min, $ampm);
                                                    @endphp
                                                    <option value="{{ $time24 }}">{{ $time12 }}</option>
                                                @endfor
                                            @endfor
                                        </select>
                                        @error("schedules.{$dayNum}.start_time")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                                        <select 
                                            wire:model="schedules.{{ $dayNum }}.end_time"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                        >
                                            @for($hour = 0; $hour < 24; $hour++)
                                                @for($min = 0; $min < 60; $min += 15)
                                                    @php
                                                        $time24 = sprintf('%02d:%02d', $hour, $min);
                                                        $hour12 = $hour % 12 ?: 12;
                                                        $ampm = $hour < 12 ? 'AM' : 'PM';
                                                        $time12 = sprintf('%d:%02d %s', $hour12, $min, $ampm);
                                                    @endphp
                                                    <option value="{{ $time24 }}">{{ $time12 }}</option>
                                                @endfor
                                            @endfor
                                        </select>
                                        @error("schedules.{$dayNum}.end_time")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <x-button type="button" variant="secondary" href="{{ route('lawyer.dashboard') }}">
                        Cancel
                    </x-button>
                    <x-button 
                        type="submit" 
                        variant="primary"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="save">Save Schedule</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </x-button>
                </div>
            </form>
        </div>

        <!-- Tips Section -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex gap-3">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-blue-900 mb-2">Schedule Tips</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Set realistic availability to avoid burnout</li>
                        <li>• Leave buffer time between consultations</li>
                        <li>• Update your schedule regularly to reflect changes</li>
                        <li>• Clients can only book during your available hours</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
        
    <!-- Right Column: Blocked Dates List (stacks below on mobile) -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:sticky lg:top-4">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <h3 class="text-base sm:text-lg font-bold text-gray-900">Blocked Dates</h3>
                <button 
                    type="button"
                    wire:click="openBlockModal"
                    class="px-2 sm:px-3 py-1.5 sm:py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm"
                >
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Block
                </button>
            </div>
            
            @if(count($blockedDates) > 0)
                <div class="space-y-3 max-h-96 lg:max-h-[calc(100vh-200px)] overflow-y-auto">
                    @foreach($blockedDates as $blocked)
                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm text-gray-900 truncate">
                                        {{ \Carbon\Carbon::parse($blocked['blocked_date'])->format('M j, Y') }}
                                    </p>
                                    @if($blocked['is_full_day'])
                                        <p class="text-xs text-gray-600">Full Day</p>
                                    @else
                                        @php
                                            $start = \Carbon\Carbon::parse($blocked['start_time']);
                                            $end = \Carbon\Carbon::parse($blocked['end_time']);
                                        @endphp
                                        <p class="text-xs text-gray-600">
                                            {{ $start->format('g:i A') }} - {{ $end->format('g:i A') }}
                                        </p>
                                    @endif
                                    @if($blocked['reason'])
                                        <p class="text-xs text-gray-500 mt-1 truncate" title="{{ $blocked['reason'] }}">
                                            {{ $blocked['reason'] }}
                                        </p>
                                    @endif
                                </div>
                                <button 
                                    wire:click="unblockDate({{ $blocked['id'] }})"
                                    class="flex-shrink-0 p-1 text-red-600 hover:bg-red-100 rounded transition"
                                    title="Remove"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-gray-500">No blocked dates</p>
                    <p class="text-xs text-gray-400 mt-1">Click "Block" to add</p>
                </div>
            @endif
        </div>
    </div>
</div>
    @else
        <!-- Calendar View -->
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8">
            <!-- Calendar Header -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                    {{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->format('F Y') }}
                </h2>
                <div class="flex gap-2">
                    <button 
                        wire:click="previousMonth"
                        class="p-2 hover:bg-gray-100 rounded-lg transition"
                        aria-label="Previous month"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button 
                        wire:click="nextMonth"
                        class="p-2 hover:bg-gray-100 rounded-lg transition"
                        aria-label="Next month"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-2">
                <!-- Day Headers -->
                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                    <div class="text-center font-semibold text-gray-600 text-sm py-2">
                        {{ $day }}
                    </div>
                @endforeach

                <!-- Calendar Days -->
                @foreach($calendarDays as $day)
                    @if($day === null)
                        <div class="aspect-square"></div>
                    @else
                        <div class="aspect-square border rounded-lg p-2 relative {{ $day['isBlocked'] && $day['isFullDayBlock'] ? 'bg-red-50 border-red-300' : ($day['isBlocked'] ? 'bg-orange-50 border-orange-300' : ($day['isToday'] ? 'border-primary-600 bg-primary-50' : ($day['isPast'] ? 'bg-gray-50 border-gray-200' : 'border-gray-200 hover:border-primary-300'))) }} {{ $day['isAvailable'] && !($day['isBlocked'] && $day['isFullDayBlock']) ? '' : ($day['isBlocked'] && $day['isFullDayBlock'] ? 'opacity-50' : '') }}">
                            <div class="flex flex-col h-full">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium {{ $day['isToday'] ? 'text-primary-700' : ($day['isPast'] ? 'text-gray-400' : 'text-gray-900') }}">
                                        {{ $day['day'] }}
                                    </span>
                                    @if($day['isBlocked'])
                                        <button 
                                            wire:click="unblockDate({{ $day['blockedId'] }})"
                                            class="text-xs {{ $day['isFullDayBlock'] ? 'text-red-600 hover:text-red-800' : 'text-orange-600 hover:text-orange-800' }}"
                                            title="Unblock"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    @elseif(!$day['isPast'])
                                        <button 
                                            wire:click="openBlockModal('{{ $day['date']->format('Y-m-d') }}')"
                                            class="text-xs text-gray-400 hover:text-gray-600"
                                            title="Block date"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                        </button>
                                    @elseif(!$day['isAvailable'])
                                        <span class="text-xs text-gray-400">Off</span>
                                    @endif
                                </div>
                                
                                @if($day['isBlocked'])
                                    <div class="mt-auto hidden lg:block">
                                        <div class="text-xs {{ $day['isFullDayBlock'] ? 'bg-red-600' : 'bg-orange-600' }} text-white rounded px-2 py-1 text-center">
                                            Blocked
                                        </div>
                                        @if($day['blockReason'])
                                            <div class="text-xs {{ $day['isFullDayBlock'] ? 'text-red-700' : 'text-orange-700' }} mt-1 truncate" title="{{ $day['blockReason'] }}">
                                                {{ $day['blockReason'] }}
                                            </div>
                                        @endif
                                    </div>
                                @elseif($day['consultations'] > 0)
                                    <div class="mt-auto hidden lg:block">
                                        @if($day['consultations'] > 0 && count($day['consultationDetails']) > 0)
                                            <div class="text-xs text-primary-700 space-y-0.5">
                                                @foreach($day['consultationDetails'] as $consultation)
                                                    <div class="truncate bg-primary-100 px-1 py-0.5 rounded" title="{{ $consultation['time_range'] }} - {{ $consultation['client_name'] }}">
                                                        {{ $consultation['time_range'] }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Legend -->
            <div class="mt-6 flex flex-wrap gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 border-2 border-primary-600 bg-primary-50 rounded"></div>
                    <span class="text-gray-600">Today</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 border border-gray-200 rounded"></div>
                    <span class="text-gray-600">Available</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 border border-gray-200 bg-gray-50 rounded opacity-50"></div>
                    <span class="text-gray-600">Unavailable</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-primary-600 rounded"></div>
                    <span class="text-gray-600">Has Bookings</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-red-600 rounded"></div>
                    <span class="text-gray-600">Blocked (Full Day)</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-orange-600 rounded"></div>
                    <span class="text-gray-600">Blocked (Partial)</span>
                </div>
            </div>
            
            <!-- Events & Blocks List (Mobile only - hidden on desktop) -->
            <div class="mt-8 space-y-4 lg:hidden">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">This Month's Schedule</h3>
                    <button 
                        type="button"
                        wire:click="openBlockModal"
                        class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2 text-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Block Date
                    </button>
                </div>
                
                @php
                    $monthEvents = collect($calendarDays)
                        ->filter(fn($day) => $day !== null && ($day['consultations'] > 0 || $day['isBlocked']))
                        ->sortBy(fn($day) => $day['date'])
                        ->values();
                @endphp
                
                @if($monthEvents->count() > 0)
                    <div class="space-y-3">
                        @foreach($monthEvents as $event)
                            <div class="p-4 rounded-xl border {{ $event['isBlocked'] ? ($event['isFullDayBlock'] ? 'bg-red-50 border-red-200' : 'bg-orange-50 border-orange-200') : 'bg-primary-50 border-primary-200' }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            @if($event['isBlocked'])
                                                <svg class="w-5 h-5 {{ $event['isFullDayBlock'] ? 'text-red-600' : 'text-orange-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            @endif
                                            <p class="font-semibold text-gray-900">
                                                {{ $event['date']->format('F j, Y (l)') }}
                                            </p>
                                        </div>
                                        
                                        @if($event['isBlocked'])
                                            <p class="text-sm {{ $event['isFullDayBlock'] ? 'text-red-700' : 'text-orange-700' }} font-medium">
                                                @if($event['isFullDayBlock'])
                                                    Blocked - Full Day
                                                @else
                                                    @php
                                                        $start = \Carbon\Carbon::parse($event['blockStartTime']);
                                                        $end = \Carbon\Carbon::parse($event['blockEndTime']);
                                                    @endphp
                                                    Blocked - {{ $start->format('g:i A') }} to {{ $end->format('g:i A') }}
                                                @endif
                                            </p>
                                            @if($event['blockReason'])
                                                <p class="text-sm text-gray-600 mt-1">{{ $event['blockReason'] }}</p>
                                            @endif
                                        @else
                                            <p class="text-sm text-primary-700 font-medium mb-2">
                                                {{ $event['consultations'] }} {{ $event['consultations'] === 1 ? 'Consultation' : 'Consultations' }}
                                            </p>
                                            @if(count($event['consultationDetails']) > 0)
                                                <div class="space-y-2 mt-2">
                                                    @foreach($event['consultationDetails'] as $consultation)
                                                        <div class="bg-white rounded-lg p-2 border border-primary-200">
                                                            <div class="flex items-start justify-between gap-2">
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $consultation['title'] }}</p>
                                                                    <p class="text-xs text-gray-600 truncate">{{ $consultation['client_name'] }}</p>
                                                                    <p class="text-xs text-primary-600 font-medium mt-1">{{ $consultation['time_range'] }}</p>
                                                                </div>
                                                                <span class="px-2 py-1 text-xs rounded-full {{ $consultation['status'] === 'scheduled' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                                    {{ ucfirst($consultation['status']) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    
                                    @if($event['isBlocked'])
                                        <button 
                                            wire:click="unblockDate({{ $event['blockedId'] }})"
                                            class="p-2 {{ $event['isFullDayBlock'] ? 'text-red-600 hover:bg-red-100' : 'text-orange-600 hover:bg-orange-100' }} rounded-lg transition"
                                            title="Remove block"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-xl">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-500">No consultations or blocked dates this month</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
    
    <!-- Block Date Modal -->
    <div x-show="showBlockModal" 
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         style="display: none;"
         @click.self="$wire.closeBlockModal()">
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full mx-4" @click.stop>
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Block Date</h3>
            
            <form wire:submit="saveBlockedDate" class="space-y-4" x-on:submit="console.log('Form submitted!')">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input 
                        type="date" 
                        wire:model="blockDate"
                        min="{{ now()->format('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        required
                    >
                    @error('blockDate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input 
                            type="checkbox" 
                            wire:model.live="isFullDay"
                            class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                        >
                        <span class="text-sm font-medium text-gray-700">Block entire day</span>
                    </label>
                </div>
                
                @if(!$isFullDay)
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                            <select 
                                wire:model="blockStartTime"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            >
                                @for($hour = 0; $hour < 24; $hour++)
                                    @for($min = 0; $min < 60; $min += 15)
                                        @php
                                            $time24 = sprintf('%02d:%02d', $hour, $min);
                                            $hour12 = $hour % 12 ?: 12;
                                            $ampm = $hour < 12 ? 'AM' : 'PM';
                                            $time12 = sprintf('%d:%02d %s', $hour12, $min, $ampm);
                                        @endphp
                                        <option value="{{ $time24 }}">{{ $time12 }}</option>
                                    @endfor
                                @endfor
                            </select>
                            @error('blockStartTime')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                            <select 
                                wire:model="blockEndTime"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            >
                                @for($hour = 0; $hour < 24; $hour++)
                                    @for($min = 0; $min < 60; $min += 15)
                                        @php
                                            $time24 = sprintf('%02d:%02d', $hour, $min);
                                            $hour12 = $hour % 12 ?: 12;
                                            $ampm = $hour < 12 ? 'AM' : 'PM';
                                            $time12 = sprintf('%d:%02d %s', $hour12, $min, $ampm);
                                        @endphp
                                        <option value="{{ $time24 }}">{{ $time12 }}</option>
                                    @endfor
                                @endfor
                            </select>
                            @error('blockEndTime')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason (Optional)</label>
                    <input 
                        type="text" 
                        wire:model="blockReason"
                        placeholder="e.g., Personal appointment, Holiday"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @error('blockReason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex gap-4 mt-6">
                    <button 
                        type="button" 
                        wire:click="closeBlockModal" 
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50"
                        x-on:click="console.log('Button clicked!')"
                    >
                        <span wire:loading.remove wire:target="saveBlockedDate">Block Date</span>
                        <span wire:loading wire:target="saveBlockedDate" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Blocking...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



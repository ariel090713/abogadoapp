<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>

<div class="p-4 sm:p-6 lg:p-8">
    <!-- Info Panel with Header -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-100 rounded-2xl p-4 sm:p-6 mb-6">
        <div class="flex items-start gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Consultation Threads</h1>
                <p class="text-sm text-gray-700 leading-relaxed">
                    Grouped consultations with the same client - a thread can contain multiple related sessions (initial + follow-ups).
                </p>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white rounded-2xl shadow-lg mb-6">
        <!-- Search Row -->
        <div class="p-4">
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by client name or email..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="px-4 pb-4">
            <div class="flex flex-wrap gap-2">
                <button 
                    wire:click="$set('statusFilter', 'all')"
                    class="px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap
                        {{ $statusFilter === 'all' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    All Sessions
                </button>
                <button 
                    wire:click="$set('statusFilter', 'active')"
                    class="px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap
                        {{ $statusFilter === 'active' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    Active
                </button>
                <button 
                    wire:click="$set('statusFilter', 'completed')"
                    class="px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap
                        {{ $statusFilter === 'completed' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    Completed
                </button>
                <button 
                    wire:click="$set('statusFilter', 'cancelled')"
                    class="px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap
                        {{ $statusFilter === 'cancelled' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    Cancelled
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
            </div>
        </div>
    </div>

    <!-- Cases Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($cases as $case)
            @php
                $totalSessions = 1 + $case->childConsultations()->count();
                $completedSessions = $case->childConsultations()->where('status', 'completed')->count();
                if ($case->status === 'completed') $completedSessions++;
                
                // Count declined sessions to exclude from pending
                $declinedSessions = $case->childConsultations()->where('status', 'declined')->count();
                if ($case->status === 'declined') $declinedSessions++;
                
                // Pending = Total - Completed - Declined
                $pendingSessions = $totalSessions - $completedSessions - $declinedSessions;
                
                // Use the same display status logic as consultations
                $displayStatus = $case->getDisplayStatus();
            @endphp

            <a href="{{ route('lawyer.consultation-thread.details', $case->id) }}" 
               class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl hover:border-primary-300 transition flex flex-col cursor-pointer">
                <div class="p-6 flex-1">
                    <!-- Case Header -->
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="text-lg font-bold text-gray-900 truncate">{{ $case->title }}</h3>
                            </div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $displayStatus === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $displayStatus === 'scheduled' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $displayStatus === 'in_progress' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $displayStatus === 'ended' ? 'bg-gray-100 text-gray-700' : '' }}
                                    {{ $displayStatus === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $displayStatus === 'awaiting_quote_approval' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $displayStatus === 'payment_pending' ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $displayStatus === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $displayStatus === 'declined' ? 'bg-gray-100 text-gray-700' : '' }}
                                ">
                                    @if($displayStatus === 'ended')
                                        Ended - Waiting to be Completed
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $displayStatus)) }}
                                    @endif
                                </span>
                                <span class="text-xs text-gray-500">Thread #{{ $case->getThreadNumber() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Client Info -->
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-primary-600 font-semibold text-sm">{{ substr($case->client->name, 0, 1) }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $case->client->name }}</p>
                            <p class="text-xs text-gray-500">Client</p>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center gap-2 mb-4 flex-wrap">
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                            <span class="font-bold">{{ $totalSessions }}</span> Sessions
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                            <span class="font-bold">{{ $completedSessions }}</span> Done
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                            <span class="font-bold">{{ $pendingSessions }}</span> Pending
                        </span>
                    </div>

                    <!-- Date -->
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Created {{ $case->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No sessions found</h3>
                <p class="text-gray-600 mb-6">You don't have any consultation sessions yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($cases->hasPages())
        <div class="mt-6">
            {{ $cases->links() }}
        </div>
    @endif
</div>

<x-slot name="sidebar">
    <x-client-sidebar />
</x-slot>
<div class="w-full px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Info Panel with Header -->
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-100 rounded-2xl p-4 sm:p-6 mb-6">
        <div class="flex items-start gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">My Document Requests</h1>
                <p class="text-sm text-gray-700 leading-relaxed">
                    Track your document drafting requests. After payment, your lawyer will draft based on your information.
                </p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex flex-wrap gap-2">
        <button wire:click="$set('filter', 'pending_payment')" 
            class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $filter === 'pending_payment' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
            Pending Payment ({{ $counts['pending_payment'] }})
        </button>
        <button wire:click="$set('filter', 'paid')" 
            class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $filter === 'paid' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
            Paid ({{ $counts['paid'] }})
        </button>
        <button wire:click="$set('filter', 'in_progress')" 
            class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $filter === 'in_progress' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
            In Progress ({{ $counts['in_progress'] }})
        </button>
        <button wire:click="$set('filter', 'completed')" 
            class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $filter === 'completed' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
            Completed ({{ $counts['completed'] }})
        </button>
        <button wire:click="$set('filter', 'cancelled')" 
            class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $filter === 'cancelled' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
            Cancelled ({{ $counts['cancelled'] }})
        </button>
        <button wire:click="$set('filter', 'all')" 
            class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $filter === 'all' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
            All ({{ $counts['all'] }})
        </button>
    </div>

    <!-- Requests Grid -->
    @if($requests->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($requests as $request)
                <a href="{{ route('client.document.details', $request->id) }}" 
                    class="block bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-xl hover:border-primary-300 transition-all">
                    <!-- Header -->
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-1 line-clamp-2">{{ $request->document_name }}</h3>
                        <p class="text-sm text-gray-500">Request #{{ $request->id }} • {{ $request->created_at->format('M d, Y') }}</p>
                    </div>

                    <!-- Status Badge -->
                    <div class="mb-4">
                        @if($request->status === 'pending_payment')
                            <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                Pending Payment
                            </span>
                        @elseif($request->status === 'paid')
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                Paid
                            </span>
                        @elseif($request->status === 'in_progress')
                            <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">
                                In Progress
                            </span>
                        @elseif($request->status === 'revision_requested')
                            <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                Revision Requested
                            </span>
                        @elseif($request->status === 'completed')
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                Completed
                            </span>
                        @elseif($request->status === 'cancelled')
                            <span class="inline-block px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                Cancelled
                            </span>
                        @endif
                    </div>

                    <!-- Lawyer Info -->
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                        @if($request->lawyer->lawyerProfile->profile_photo_url)
                            <img src="{{ $request->lawyer->lawyerProfile->profile_photo_url }}" 
                                alt="{{ $request->lawyer->name }}"
                                class="w-10 h-10 rounded-lg object-cover">
                        @else
                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                <span class="text-primary-600 font-semibold text-sm">
                                    {{ substr($request->lawyer->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $request->lawyer->name }}</p>
                            <p class="text-xs text-gray-500">Lawyer</p>
                        </div>
                    </div>

                    <!-- Price & Deadlines -->
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Amount:</span>
                            <span class="font-bold text-gray-900">₱{{ number_format($request->price, 0) }}</span>
                        </div>
                        
                        @if($request->payment_deadline && $request->status === 'pending_payment')
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Pay by:</span>
                                <span class="font-medium {{ $request->payment_deadline->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $request->payment_deadline->format('M d, Y') }}
                                </span>
                            </div>
                        @endif

                        @if($request->completion_deadline && in_array($request->status, ['paid', 'in_progress']))
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Expected:</span>
                                <span class="font-medium text-gray-900">
                                    {{ $request->completion_deadline->format('M d, Y') }}
                                </span>
                            </div>
                        @endif

                        @if($request->completed_at)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Completed:</span>
                                <span class="font-medium text-gray-900">{{ $request->completed_at->format('M d, Y') }}</span>
                            </div>
                        @endif

                        @if($request->status === 'completed' && $request->revisions_allowed > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Revisions:</span>
                                <span class="font-medium text-gray-900">
                                    {{ $request->revisions_used }}/{{ $request->revisions_allowed }} used
                                </span>
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $requests->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No document requests yet</h3>
            <p class="text-gray-600 mb-6">Browse available document services and request one to get started</p>
            <a href="{{ route('documents.browse') }}" 
                class="inline-block px-6 py-3 bg-primary-700 text-white rounded-lg hover:bg-[#1E40AF] font-medium">
                Browse Documents
            </a>
        </div>
    @endif
</div>

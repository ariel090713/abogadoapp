<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>
<div class="w-full px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Info Panel with Header -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 rounded-2xl p-4 sm:p-6 mb-6">
        <div class="flex items-start gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Document Requests</h1>
                <p class="text-sm text-gray-700 leading-relaxed">
                    Client requests for your document services. Review their information, draft the document, and upload for review.
                </p>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session('info'))
        <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg">
            {{ session('info') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Search -->
            <div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search by document name or client..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <!-- Category Dropdown -->
            @if($categories->count() > 0)
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            type="button"
                            class="w-full px-4 py-2 text-left bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 flex items-center justify-between">
                        <span class="text-gray-700">
                            @if($categoryFilter)
                                {{ $categories->firstWhere('slug', $categoryFilter)?->name ?? 'All Categories' }}
                            @else
                                All Categories
                            @endif
                        </span>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute z-10 mt-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 py-1 max-h-60 overflow-y-auto">
                        
                        <button wire:click="$set('categoryFilter', null)" 
                                @click="open = false"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 {{ $categoryFilter === null ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-700' }}">
                            All Categories
                        </button>

                        @foreach($categories as $category)
                            <button wire:click="$set('categoryFilter', '{{ $category->slug }}')" 
                                    @click="open = false"
                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 {{ $categoryFilter === $category->slug ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-700' }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Status Filters -->
        <div>
            <div class="flex flex-wrap gap-2">
                <button wire:click="$set('filter', 'pending_payment')" 
                    class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $filter === 'pending_payment' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                    Pending Payment ({{ $counts['pending_payment'] ?? 0 }})
                </button>
                <button wire:click="$set('filter', 'paid')" 
                    class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $filter === 'paid' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                    Paid ({{ $counts['paid'] }})
                </button>
                <button wire:click="$set('filter', 'in_progress')" 
                    class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $filter === 'in_progress' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                    In Progress ({{ $counts['in_progress'] }})
                </button>
                <button wire:click="$set('filter', 'revision_requested')" 
                    class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $filter === 'revision_requested' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                    Revision Requested ({{ $counts['revision_requested'] }})
                </button>
                <button wire:click="$set('filter', 'completed')" 
                    class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $filter === 'completed' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                    Completed ({{ $counts['completed'] }})
                </button>
                <button wire:click="$set('filter', 'all')" 
                    class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $filter === 'all' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                    All ({{ $counts['all'] }})
                </button>
            </div>
        </div>
    </div>

    <!-- Requests Grid -->
    @if($requests->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($requests as $request)
                <a href="{{ route('lawyer.document-request.details', $request->id) }}" 
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
                                Paid - Ready to Start
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
                        @endif
                    </div>

                    <!-- Client Info -->
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                        @if($request->client->profile_photo_url)
                            <img src="{{ $request->client->profile_photo_url }}" 
                                alt="{{ $request->client->name }}"
                                class="w-10 h-10 rounded-lg object-cover">
                        @else
                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                <span class="text-primary-600 font-semibold text-sm">
                                    {{ substr($request->client->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $request->client->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $request->client->email }}</p>
                        </div>
                    </div>

                    <!-- Price & Deadlines -->
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Amount:</span>
                            <span class="font-bold text-gray-900">₱{{ number_format($request->price, 0) }}</span>
                        </div>
                        
                        @if($request->paid_at)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Paid:</span>
                                <span class="font-medium text-gray-900">{{ $request->paid_at->format('M d, Y') }}</span>
                            </div>
                        @endif

                        @if($request->completion_deadline && in_array($request->status, ['paid', 'in_progress']))
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Due:</span>
                                <span class="font-medium {{ $request->completion_deadline->isPast() ? 'text-red-600' : 'text-gray-900' }}">
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
            <p class="text-gray-600 mb-6">Requests will appear here when clients order your document services</p>
            <a href="{{ route('lawyer.documents') }}" 
                class="inline-block px-6 py-3 bg-primary-700 text-white rounded-lg hover:bg-[#1E40AF] font-medium">
                Manage Document Services
            </a>
        </div>
    @endif
</div>

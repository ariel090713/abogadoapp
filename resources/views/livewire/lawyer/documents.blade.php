<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>

<div class="p-4 sm:p-6 lg:p-8">
    <!-- Info Panel with Header -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl p-4 sm:p-6 mb-6">
        <div class="flex items-start gap-3 sm:gap-4 mb-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Documents Forms</h1>
                <p class="text-sm text-gray-700 leading-relaxed">
                    Create custom document templates with forms for clients. Set pricing, turnaround time, and revision policy.
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
                               placeholder="Search documents..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <!-- Create Button -->
                <a href="{{ route('lawyer.documents.create') }}" 
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition text-sm font-medium whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Create New</span>
                </a>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="px-4 pb-4">
            <div class="flex flex-wrap gap-2">
                <button wire:click="$set('filter', 'all')" 
                        class="px-4 py-2 rounded-full text-sm font-medium transition {{ $filter === 'all' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    All Documents
                </button>
                <button wire:click="$set('filter', 'active')" 
                        class="px-4 py-2 rounded-full text-sm font-medium transition {{ $filter === 'active' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    Active
                </button>
                <button wire:click="$set('filter', 'inactive')" 
                        class="px-4 py-2 rounded-full text-sm font-medium transition {{ $filter === 'inactive' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}">
                    Inactive
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

                @if($filter !== 'all')
                    <button wire:click="$set('filter', 'all')" 
                            type="button"
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-gray-200 rounded-full text-sm text-gray-700 hover:bg-gray-50 transition mb-6">
                        <span>Status: {{ ucfirst($filter) }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif

                @if($search || $filter !== 'all')
                    <button wire:click="clearFilters" 
                            type="button"
                            class="text-sm text-primary-700 hover:text-primary-800 font-medium mb-6">
                        Clear all
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Documents Grid -->
    @if($documents->isEmpty())
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No documents yet</h3>
            <p class="text-gray-600 mb-6">Create your first document service to start offering document drafting to clients</p>
            <a href="{{ route('lawyer.documents.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-primary-700 text-white rounded-lg hover:bg-[#1E40AF] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Document Service
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($documents as $document)
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition flex flex-col relative">
                <!-- Actions Dropdown - Positioned at top right -->
                <div class="absolute top-4 right-4 z-10" x-data="{ open: false }">
                    <button @click="open = !open; $wire.toggleDropdown({{ $document->id }})" 
                            class="p-2 hover:bg-gray-100 rounded-lg transition"
                            type="button">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="display: none;"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1">
                        
                        <a href="{{ route('lawyer.documents.edit', $document->id) }}"
                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>

                        <button wire:click="toggleStatus({{ $document->id }}); open = false"
                                type="button"
                                class="w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                            @if($document->is_active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                                Deactivate
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Activate
                            @endif
                        </button>

                        <div class="border-t border-gray-100 my-1"></div>

                        <button @click="open = false; $wire.confirmDelete({{ $document->id }})"
                                type="button"
                                class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="flex-1 pr-8">
                    <div class="mb-4">
                        <h3 class="text-xl font-bold text-gray-900 line-clamp-2 mb-2">{{ $document->name }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @if($document->is_active)
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Active</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">Inactive</span>
                            @endif
                            @if($document->template)
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Template</span>
                            @endif
                        </div>
                    </div>

                    @if($document->description)
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $document->description }}</p>
                    @endif

                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-semibold">₱{{ number_format($document->price, 2) }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $document->estimated_completion_days }} days</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>{{ $document->total_orders }} orders</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    <!-- Pagination -->
    @if($documents->hasPages())
        <div class="mt-6">
            {{ $documents->links() }}
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
@if($showDeleteModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Delete Document Service</h3>
                    <p class="text-sm text-gray-600 mt-1">This action cannot be undone</p>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6">
            <p class="text-gray-700">
                Are you sure you want to delete this document service? All associated data will be permanently removed.
            </p>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-gray-200 flex gap-3 justify-end">
            <button 
                wire:click="$set('showDeleteModal', false)"
                type="button"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Cancel
            </button>
            <button 
                wire:click="deleteDocument"
                type="button"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                Delete Document
            </button>
        </div>
    </div>
</div>
@endif


<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Content Management</h1>
        <p class="mt-2 text-gray-600">Manage website content, resources, and media</p>
    </div>

    <!-- Content Type Tabs -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-wrap gap-2">
            <button wire:click="$set('contentType', 'legal_guides')" 
                class="px-4 py-2 rounded-lg transition {{ $contentType === 'legal_guides' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Legal Guides
            </button>
            <button wire:click="$set('contentType', 'news')" 
                class="px-4 py-2 rounded-lg transition {{ $contentType === 'news' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                News
            </button>
            <button wire:click="$set('contentType', 'blogs')" 
                class="px-4 py-2 rounded-lg transition {{ $contentType === 'blogs' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Blogs
            </button>
            <button wire:click="$set('contentType', 'events')" 
                class="px-4 py-2 rounded-lg transition {{ $contentType === 'events' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Events
            </button>
            <button wire:click="$set('contentType', 'galleries')" 
                class="px-4 py-2 rounded-lg transition {{ $contentType === 'galleries' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Galleries
            </button>
            <button wire:click="$set('contentType', 'downloadables')" 
                class="px-4 py-2 rounded-lg transition {{ $contentType === 'downloadables' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Downloadables
            </button>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <input type="text" wire:model.live.debounce.300ms="search" 
                    placeholder="Search by title..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <!-- Status Filter -->
            <div>
                <select wire:model.live="filterStatus" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="all">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>

            <!-- Create Button -->
            <div>
                <button wire:click="create" 
                    class="w-full px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition">
                    Create New
                </button>
            </div>
        </div>
        
        <!-- Category Management Button (for applicable content types) -->
        @if(in_array($contentType, ['legal_guides', 'blogs', 'downloadables']))
            <div class="mt-4 pt-4 border-t border-gray-200">
                <button wire:click="createCategory" 
                    class="px-4 py-2 bg-accent-600 text-white rounded-lg hover:bg-accent-700 transition text-sm">
                    Manage Categories
                </button>
                
                <!-- Category List -->
                @if($categories->count() > 0)
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($categories as $cat)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $cat->name }}</p>
                                    @if($cat->description)
                                        <p class="text-xs text-gray-500">{{ Str::limit($cat->description, 50) }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 ml-3">
                                    <button wire:click="toggleCategoryStatus({{ $cat->id }})" 
                                        class="px-2 py-1 text-xs rounded {{ $cat->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $cat->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                    <button wire:click="editCategory({{ $cat->id }})" 
                                        class="p-1 text-primary-600 hover:text-primary-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $cat->id }}, 'category')"
                                        class="p-1 text-red-600 hover:text-red-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Content Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        @if(in_array($contentType, ['legal_guides', 'blogs', 'downloadables']))
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        @endif
                        @if($contentType === 'events')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        @endif
                        @if($contentType === 'galleries')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                        @endif
                        @if($contentType === 'downloadables')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Downloads</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($items as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $item->title }}</div>
                                <div class="text-sm text-gray-500">{{ $item->created_at->format('M d, Y') }}</div>
                            </td>
                            @if(in_array($contentType, ['legal_guides', 'blogs', 'downloadables']))
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ ucfirst(str_replace('_', ' ', $item->category)) }}
                                </td>
                            @endif
                            @if($contentType === 'events')
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $item->event_date->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ ucfirst($item->event_type) }}
                                </td>
                            @endif
                            @if($contentType === 'galleries')
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ ucfirst($item->type) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $item->items->count() }}
                                </td>
                            @endif
                            @if($contentType === 'downloadables')
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ number_format($item->downloads) }}
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ number_format($item->views) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="togglePublish({{ $item->id }})" 
                                    class="px-3 py-1 text-xs rounded-full {{ $item->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $item->is_published ? 'Published' : 'Draft' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex gap-2">
                                    <button wire:click="edit({{ $item->id }})" 
                                        class="px-3 py-1 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition">
                                        Edit
                                    </button>
                                    <button wire:click="confirmDelete({{ $item->id }}, 'content')"
                                        class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                No content found. Create your first item!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $items->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ $editMode ? 'Edit' : 'Create' }} {{ ucfirst(str_replace('_', ' ', $contentType)) }}
                    </h2>
                </div>

                <form wire:submit="save" class="p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" wire:model="title" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Excerpt (for legal guides, news, blogs) -->
                    @if(in_array($contentType, ['legal_guides', 'news', 'blogs']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                            <textarea wire:model="excerpt" rows="3" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea>
                            @error('excerpt') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <!-- Description (for events, galleries, downloadables) -->
                    @if(in_array($contentType, ['events', 'galleries', 'downloadables']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea wire:model="description" rows="3" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea>
                            @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <!-- Content (for legal guides, news, blogs, events) -->
                    @if(in_array($contentType, ['legal_guides', 'news', 'blogs', 'events']))
                        <div wire:ignore>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                            <textarea id="content-editor" class="w-full"></textarea>
                            @error('content') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <!-- Category (for legal guides, blogs) -->
                    @if(in_array($contentType, ['legal_guides', 'blogs']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select wire:model="category" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Select Category</option>
                                @foreach($categories->where('is_active', true) as $cat)
                                    <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <!-- Event specific fields -->
                    @if($contentType === 'events')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Event Type</label>
                                <select wire:model="event_type" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                    <option value="">Select Type</option>
                                    <option value="webinar">Webinar</option>
                                    <option value="seminar">Seminar</option>
                                    <option value="workshop">Workshop</option>
                                </select>
                                @error('event_type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Event Date</label>
                                <input type="datetime-local" wire:model="event_date" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('event_date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Location (Optional)</label>
                                <input type="text" wire:model="location" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meeting Link (Optional)</label>
                                <input type="url" wire:model="meeting_link" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Participants (Optional)</label>
                                <input type="number" wire:model="max_participants" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            </div>
                        </div>
                    @endif

                    <!-- Gallery Type -->
                    @if($contentType === 'galleries')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Type</label>
                            <select wire:model="gallery_type" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="photos">Photo Gallery</option>
                                <option value="videos">Video Gallery</option>
                            </select>
                            @error('gallery_type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Gallery Items Management -->
                        @if($editMode && $selectedId)
                            <div class="border border-gray-200 rounded-lg p-4" wire:key="gallery-items-{{ $selectedId }}-{{ now()->timestamp }}">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Gallery Items</h3>
                                    <button type="button" wire:click="$set('showAddItemModal', true)"
                                        class="px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition text-sm">
                                        Add Item
                                    </button>
                                </div>
                                
                                @php
                                    $gallery = \App\Models\Gallery::with('items')->find($selectedId);
                                @endphp
                                
                                @if($gallery && $gallery->items->count() > 0)
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach($gallery->items->sortBy('order') as $item)
                                            <div class="relative group" wire:key="item-{{ $item->id }}">
                                                <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden">
                                                    @if($item->file_path && (str_starts_with($item->file_path, 'http') || str_starts_with($item->file_path, 'https')))
                                                        <img src="{{ $item->file_path }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-600 to-primary-800">
                                                            <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="mt-2">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $item->title }}</p>
                                                    <p class="text-xs text-gray-500">Order: {{ $item->order }}</p>
                                                </div>
                                                <button type="button" wire:click="confirmDelete({{ $item->id }}, 'gallery-item')"
                                                    class="absolute top-2 right-2 p-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition opacity-0 group-hover:opacity-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-center text-gray-500 py-8">No items yet. Click "Add Item" to add photos or videos.</p>
                                @endif
                            </div>
                        @else
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Save the gallery first, then you can add items to it.
                                </p>
                            </div>
                        @endif
                    @endif

                    <!-- Downloadable specific fields -->
                    @if($contentType === 'downloadables')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select wire:model="file_category" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Select Category</option>
                                @foreach($categories->where('is_active', true) as $cat)
                                    <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('file_category') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                File {{ $editMode ? '(Optional - leave empty to keep current)' : '' }}
                            </label>
                            
                            @if($editMode && $selectedId)
                                @php
                                    $downloadable = \App\Models\Downloadable::find($selectedId);
                                @endphp
                                @if($downloadable && $downloadable->file_path)
                                    <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 bg-accent-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Current File:</p>
                                                    <p class="text-xs text-gray-600">{{ strtoupper($downloadable->file_type) }} • {{ $downloadable->getFileSizeFormatted() }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">{{ number_format($downloadable->downloads) }} downloads</p>
                                                </div>
                                            </div>
                                            <button type="button" wire:click="confirmDelete({{ $downloadable->id }}, 'downloadable-file')"
                                                class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                                                Delete File
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            
                            <input type="file" wire:model="file" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            @error('file') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            
                            @if($file)
                                <div class="mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">New File Preview:</p>
                                            <p class="text-xs text-gray-600">{{ $file->getClientOriginalName() }}</p>
                                            <p class="text-xs text-gray-500">{{ round($file->getSize() / 1024 / 1024, 2) }} MB</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Featured Image -->
                    @if($contentType !== 'downloadables')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Featured Image {{ $editMode ? '(Optional - leave empty to keep current)' : '(Optional)' }}
                            </label>
                            
                            @if($editMode && $selectedId)
                                @php
                                    $item = $this->getModel()->find($selectedId);
                                @endphp
                                @if($item && $item->featured_image)
                                    <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                                        <img src="{{ $item->featured_image }}" alt="Current featured image" class="w-32 h-32 object-cover rounded-lg">
                                    </div>
                                @endif
                            @endif
                            
                            <input type="file" wire:model="featured_image" accept="image/*" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            @error('featured_image') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            
                            @if($featured_image)
                                <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-600 mb-2">New Image Preview:</p>
                                    <img src="{{ $featured_image->temporaryUrl() }}" alt="Preview" class="w-32 h-32 object-cover rounded-lg">
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Published Status -->
                    <div class="flex items-center gap-2">
                        <input type="checkbox" wire:model="is_published" id="is_published" 
                            class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <label for="is_published" class="text-sm font-medium text-gray-700">Publish immediately</label>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 justify-end pt-4 border-t border-gray-200">
                        <button type="button" wire:click="$set('showModal', false)" 
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="px-6 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed">
                            <span wire:loading.remove wire:target="save">{{ $editMode ? 'Update' : 'Create' }}</span>
                            <span wire:loading wire:target="save">Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Add Gallery Item Modal -->
    @if($showAddItemModal ?? false)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900">Add Gallery Item</h2>
                </div>

                <form wire:submit="addGalleryItem" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" wire:model="newItemTitle" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('newItemTitle') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image/Video File</label>
                        <input type="file" wire:model="newItemFile" accept="image/*,video/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('newItemFile') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        
                        @if($newItemFile)
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600 mb-2">Preview:</p>
                                @if(str_starts_with($newItemFile->getMimeType(), 'image/'))
                                    <img src="{{ $newItemFile->temporaryUrl() }}" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                                @else
                                    <div class="flex items-center gap-3 p-4 bg-white rounded-lg">
                                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $newItemFile->getClientOriginalName() }}</p>
                                            <p class="text-xs text-gray-500">{{ round($newItemFile->getSize() / 1024 / 1024, 2) }} MB</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                        <input type="number" wire:model="newItemOrder" min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('newItemOrder') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-4 justify-end pt-4 border-t border-gray-200">
                        <button type="button" wire:click="$set('showAddItemModal', false)"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed">
                            <span wire:loading.remove wire:target="addGalleryItem">Add Item</span>
                            <span wire:loading wire:target="addGalleryItem">Adding...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    
    <!-- Category Management Modal -->
    @if($showCategoryModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ $categoryEditMode ? 'Edit' : 'Create' }} Category
                    </h2>
                </div>

                <form wire:submit="saveCategory" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                        <input type="text" wire:model="categoryName" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="e.g., Family Law, Contracts, etc.">
                        @error('categoryName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                        <textarea wire:model="categoryDescription" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Brief description of this category"></textarea>
                        @error('categoryDescription') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                        <input type="number" wire:model="categoryOrder" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="0">
                        @error('categoryOrder') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                    </div>

                    <div class="flex gap-4 justify-end pt-4 border-t border-gray-200">
                        <button type="button" wire:click="closeCategoryModal"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed">
                            <span wire:loading.remove wire:target="saveCategory">{{ $categoryEditMode ? 'Update' : 'Create' }}</span>
                            <span wire:loading wire:target="saveCategory">Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    
    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Confirm Deletion</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                @if($deleteItemType === 'category')
                                    Are you sure you want to delete this category? This action cannot be undone.
                                @elseif($deleteItemType === 'gallery-item')
                                    Are you sure you want to delete this gallery item?
                                @elseif($deleteItemType === 'downloadable-file')
                                    Are you sure you want to delete this file? This action cannot be undone.
                                @else
                                    Are you sure you want to delete this item? This action cannot be undone.
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3 justify-end">
                        <button type="button" wire:click="cancelDelete"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="button" wire:click="executeDelete"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed">
                            <span wire:loading.remove wire:target="executeDelete">Delete</span>
                            <span wire:loading wire:target="executeDelete">Deleting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    let contentEditor;
    
    function initTinyMCE() {
        if (contentEditor) {
            tinymce.remove('#content-editor');
        }
        
        // Wait for TinyMCE to be fully loaded
        if (typeof tinymce === 'undefined') {
            console.error('TinyMCE not loaded');
            return;
        }
        
        tinymce.init({
            selector: '#content-editor',
            height: 500,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | help',
            content_style: 'body { font-family:Arial,sans-serif; font-size:14px }',
            images_upload_handler: function (blobInfo, progress) {
                return new Promise(function(resolve, reject) {
                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{ route('admin.tinymce.upload') }}');
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                    
                    xhr.upload.onprogress = function(e) {
                        progress(e.loaded / e.total * 100);
                    };
                    
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                const result = JSON.parse(xhr.responseText);
                                if (result.location) {
                                    resolve(result.location);
                                } else {
                                    reject('Upload failed: ' + (result.error || 'Unknown error'));
                                }
                            } catch (e) {
                                reject('Upload failed: Invalid response');
                            }
                        } else {
                            reject('Upload failed: HTTP ' + xhr.status);
                        }
                    };
                    
                    xhr.onerror = function() {
                        reject('Upload failed: Network error');
                    };
                    
                    xhr.send(formData);
                });
            },
            setup: function(ed) {
                contentEditor = ed;
                ed.on('change', function() {
                    @this.set('content', ed.getContent());
                });
                ed.on('init', function() {
                    ed.setContent(@this.content || '');
                });
            }
        });
    }
    
    // Initialize when modal opens
    document.addEventListener('livewire:init', () => {
        Livewire.on('modal-opened', () => {
            setTimeout(() => {
                initTinyMCE();
            }, 100);
        });
    });
    
    // Sync content before save
    window.addEventListener('beforeunload', function() {
        if (contentEditor) {
            @this.set('content', contentEditor.getContent());
        }
    });
</script>
@endpush

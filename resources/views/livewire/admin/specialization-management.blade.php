<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Specialization Management</h1>
        <p class="mt-2 text-gray-600">Manage lawyer specializations and sub-specializations</p>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Filters & Actions -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <div>
                <input type="text" wire:model.live.debounce.300ms="search" 
                    placeholder="Search specializations..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <!-- Create Button -->
            <div>
                <button wire:click="create" 
                    class="w-full px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition">
                    Create New Specialization
                </button>
            </div>
        </div>
    </div>

    <!-- Specializations Grouped Display -->
    <div class="space-y-6">
        @if($search && $searchResults)
            <!-- Search Results -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Search Results ({{ $searchResults->count() }})</h3>
                <div class="space-y-2">
                    @foreach($searchResults as $spec)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-primary-300 transition">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <p class="font-medium text-gray-900">{{ $spec->name }}</p>
                                        @if($spec->is_parent)
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800">Parent</span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">
                                                Sub of: {{ $spec->parent?->name }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($spec->description)
                                        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($spec->description, 80) }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                    @if($spec->is_parent)
                                        <span>{{ $spec->children->count() }} sub-specs</span>
                                    @endif
                                    <span>{{ $spec->lawyerProfiles->count() }} lawyers</span>
                                </div>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <button wire:click="edit({{ $spec->id }})" 
                                    class="px-3 py-1.5 bg-primary-700 text-white text-sm rounded-lg hover:bg-primary-800 transition">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $spec->id }})"
                                    class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                                    Delete
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Grouped by Parent -->
            @forelse($parentSpecializations as $parent)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <!-- Parent Header -->
                    <div class="bg-gradient-to-r from-primary-700 to-primary-600 p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-white">{{ $parent->name }}</h3>
                                    @if($parent->description)
                                        <p class="text-primary-100 mt-1">{{ $parent->description }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right text-white">
                                    <p class="text-sm opacity-90">Sub-specializations</p>
                                    <p class="text-3xl font-bold">{{ $parent->children->count() }}</p>
                                </div>
                                <div class="text-right text-white">
                                    <p class="text-sm opacity-90">Lawyers</p>
                                    <p class="text-3xl font-bold">{{ $parent->lawyerProfiles->count() }}</p>
                                </div>
                                <div class="flex gap-2 ml-4">
                                    <button wire:click="edit({{ $parent->id }})" 
                                        class="px-4 py-2 bg-white text-primary-700 rounded-lg hover:bg-primary-50 transition font-medium">
                                        Edit
                                    </button>
                                    <button wire:click="confirmDelete({{ $parent->id }})"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Children List -->
                    @if($parent->children->count() > 0)
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($parent->children as $child)
                                    <div class="group relative p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-primary-300 hover:shadow-md transition">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium text-gray-900 truncate">{{ $child->name }}</p>
                                                @if($child->description && $child->description !== $child->name . ' under ' . $parent->name)
                                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $child->description }}</p>
                                                @endif
                                                <p class="text-xs text-gray-400 mt-2">
                                                    {{ $child->lawyerProfiles->count() }} {{ Str::plural('lawyer', $child->lawyerProfiles->count()) }}
                                                </p>
                                            </div>
                                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition">
                                                <button wire:click="edit({{ $child->id }})" 
                                                    class="p-1.5 bg-primary-700 text-white rounded hover:bg-primary-800 transition"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <button wire:click="confirmDelete({{ $child->id }})"
                                                    class="p-1.5 bg-red-600 text-white rounded hover:bg-red-700 transition"
                                                    title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="p-6 text-center text-gray-500">
                            <p>No sub-specializations yet. Click "Create New Specialization" to add one.</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Specializations Yet</h3>
                    <p class="text-gray-600 mb-6">Get started by creating your first specialization</p>
                    <button wire:click="create" 
                        class="px-6 py-3 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition">
                        Create First Specialization
                    </button>
                </div>
            @endforelse
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ $editMode ? 'Edit' : 'Create' }} Specialization
                    </h2>
                </div>

                <form wire:submit="save" class="p-6 space-y-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" wire:model="name" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="e.g., Family Law, Annulment">
                        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea wire:model="description" rows="3" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Brief description of this specialization"></textarea>
                        @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Specialization Image</label>
                        
                        @if($editMode && $image_url)
                            <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                                <img src="{{ $image_url }}" alt="Current image" class="w-32 h-32 object-cover rounded-lg">
                            </div>
                        @endif
                        
                        <input type="file" wire:model="image" accept="image/*" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('image') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        
                        @if($image)
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600 mb-2">New Image Preview:</p>
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-32 h-32 object-cover rounded-lg">
                            </div>
                        @endif
                        
                        <p class="text-xs text-gray-500 mt-1">Recommended: 800x600px or similar aspect ratio. Max 5MB.</p>
                    </div>

                    <!-- Parent Specialization -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Parent Specialization (Optional)</label>
                        <select wire:model="parent_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">None (This is a parent specialization)</option>
                            @foreach($allParentSpecializations as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Leave empty to create a parent specialization</p>
                    </div>

                    <!-- Is Parent Checkbox (only show if no parent selected) -->
                    @if(!$parent_id)
                        <div class="flex items-center gap-2">
                            <input type="checkbox" wire:model="is_parent" id="is_parent" 
                                class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                            <label for="is_parent" class="text-sm font-medium text-gray-700">This is a parent specialization</label>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex gap-4 justify-end pt-4 border-t border-gray-200">
                        <button type="button" wire:click="closeModal" 
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
                                Are you sure you want to delete this specialization? This action cannot be undone.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3 justify-end">
                        <button type="button" wire:click="cancelDelete"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="button" wire:click="delete"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed">
                            <span wire:loading.remove wire:target="delete">Delete</span>
                            <span wire:loading wire:target="delete">Deleting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

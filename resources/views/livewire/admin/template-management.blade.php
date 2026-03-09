<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Document Template Management</h1>
        <p class="mt-2 text-gray-600">Manage document templates for lawyers to use when creating legal documents</p>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters & Actions -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
            <!-- Search & Filters -->
            <div class="flex flex-col sm:flex-row gap-3 flex-1 w-full lg:w-auto">
                <!-- Search -->
                <div class="flex-1 min-w-0">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search templates..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                </div>

                <!-- Category Filter -->
                <select 
                    wire:model.live="categoryFilter"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>

                <!-- Status Filter -->
                <select 
                    wire:model.live="statusFilter"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Create Button -->
            <button 
                wire:click="openCreateModal"
                class="px-6 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition whitespace-nowrap"
            >
                + Create Template
            </button>
        </div>
    </div>

    <!-- Templates List -->
    <div class="space-y-6">
        @forelse($templates as $template)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Template Info -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $template->name }}</h3>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm font-medium">
                                        {{ $template->category }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        Used by {{ $template->usage_count }} {{ Str::plural('lawyer', $template->usage_count) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($template->description)
                            <p class="text-gray-600 mb-4">{{ $template->description }}</p>
                        @endif

                        <!-- Form Fields Preview -->
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Form Fields ({{ count($template->form_fields) }}):</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($template->form_fields as $field)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">
                                        {{ $field['label'] }}
                                        @if($field['required'])
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Meta Info -->
                        <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                            @if($template->creator)
                                <span>Created by: {{ $template->creator->name }}</span>
                            @endif
                            <span>Created: {{ $template->created_at->format('M d, Y') }}</span>
                            <span>Updated: {{ $template->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex lg:flex-col gap-2 justify-end">
                        <!-- Status Toggle -->
                        <button 
                            wire:click="toggleStatus({{ $template->id }})"
                            class="px-3 py-1 text-xs rounded-full {{ $template->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}"
                        >
                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                        </button>

                        <!-- Edit Button -->
                        <button 
                            wire:click="openEditModal({{ $template->id }})"
                            class="px-3 py-1 bg-primary-700 text-white rounded-lg hover:bg-primary-800 text-sm"
                        >
                            Edit
                        </button>

                        <!-- Delete Button -->
                        <button 
                            wire:click="confirmDelete({{ $template->id }})"
                            class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No templates found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new template.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($templates->hasPages())
        <div class="mt-6">
            {{ $templates->links() }}
        </div>
    @endif

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ $editMode ? 'Edit Template' : 'Create New Template' }}
                        </h2>
                        <button 
                            wire:click="closeModal"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Form -->
                    <form wire:submit.prevent="save" class="space-y-6">
                        <!-- Template Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Template Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="e.g., Affidavit of Loss"
                            >
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="category"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="e.g., Affidavits, Contracts, Deeds"
                                list="categories"
                            >
                            <datalist id="categories">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                            @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea 
                                wire:model="description"
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Brief description of this template..."
                            ></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Form Fields -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-medium text-gray-700">
                                    Form Fields <span class="text-red-500">*</span>
                                </label>
                                <button 
                                    type="button"
                                    wire:click="addField"
                                    class="px-3 py-1 bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 text-sm"
                                >
                                    + Add Field
                                </button>
                            </div>

                            <div class="space-y-3">
                                @foreach($formFields as $index => $field)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <!-- Field Name -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Field Name</label>
                                                <input 
                                                    type="text" 
                                                    wire:model="formFields.{{ $index }}.name"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                                    placeholder="e.g., full_name"
                                                >
                                                @error("formFields.{$index}.name") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>

                                            <!-- Field Label -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Field Label</label>
                                                <input 
                                                    type="text" 
                                                    wire:model="formFields.{{ $index }}.label"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                                    placeholder="e.g., Full Name"
                                                >
                                                @error("formFields.{$index}.label") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>

                                            <!-- Field Type -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Field Type</label>
                                                <select 
                                                    wire:model="formFields.{{ $index }}.type"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                                >
                                                    <option value="text">Text</option>
                                                    <option value="textarea">Textarea</option>
                                                    <option value="date">Date</option>
                                                    <option value="number">Number</option>
                                                    <option value="email">Email</option>
                                                </select>
                                                @error("formFields.{$index}.type") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>

                                            <!-- Required & Remove -->
                                            <div class="flex items-end gap-2">
                                                <label class="flex items-center gap-2 flex-1">
                                                    <input 
                                                        type="checkbox" 
                                                        wire:model="formFields.{{ $index }}.required"
                                                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                                    >
                                                    <span class="text-xs text-gray-600">Required</span>
                                                </label>
                                                @if(count($formFields) > 1)
                                                    <button 
                                                        type="button"
                                                        wire:click="removeField({{ $index }})"
                                                        class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 text-xs"
                                                    >
                                                        Remove
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('formFields') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Sample Output -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Sample Output Template
                                <span class="text-gray-500 text-xs">(Optional - use {{field_name}} for placeholders)</span>
                            </label>
                            <textarea 
                                wire:model="sampleOutput"
                                rows="6"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono text-sm"
                                placeholder="e.g., I, {{full_name}}, of legal age, hereby declare..."
                            ></textarea>
                            @error('sampleOutput') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="flex items-center gap-2">
                                <input 
                                    type="checkbox" 
                                    wire:model="isActive"
                                    class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                >
                                <span class="text-sm font-medium text-gray-700">Active (visible to lawyers)</span>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3 justify-end pt-4 border-t">
                            <button 
                                type="button"
                                wire:click="closeModal"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                class="px-6 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-not-allowed"
                            >
                                <span wire:loading.remove wire:target="save">
                                    {{ $editMode ? 'Update Template' : 'Create Template' }}
                                </span>
                                <span wire:loading wire:target="save" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Delete Template</h3>
                <p class="text-gray-600 text-center mb-6">
                    Are you sure you want to delete this template? This action cannot be undone.
                </p>
                <div class="flex gap-3">
                    <button 
                        wire:click="cancelDelete"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="executeDelete"
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="executeDelete">Delete</span>
                        <span wire:loading wire:target="executeDelete" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Deleting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

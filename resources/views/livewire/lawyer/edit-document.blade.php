<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('lawyer.documents') }}" class="text-gray-600 hover:text-primary-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Document Service</h1>
                <p class="text-gray-600 mt-1">Update your document service details</p>
            </div>
        </div>
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

    <form wire:submit.prevent="save" class="space-y-8">
        <!-- Basic Information -->
        <div class="bg-white rounded-2xl shadow-lg p-6 space-y-6">
            <h2 class="text-xl font-bold text-gray-900">Basic Information</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Document Name</label>
                <input type="text" wire:model="name" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select wire:model="category"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Select a category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea wire:model="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    <span class="text-sm font-medium text-gray-700">Active (visible to clients)</span>
                </label>
            </div>
        </div>

        <!-- Form Builder -->
        <div class="bg-white rounded-2xl shadow-lg p-6 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Form Fields</h2>
                <div class="flex items-center gap-2">
                    <select wire:model="newFieldType" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="text">Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                        <option value="select">Select</option>
                    </select>
                    <button type="button" wire:click="addField" 
                        class="px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-[#1E40AF] text-sm">
                        Add Field
                    </button>
                </div>
            </div>

            @if(isset($formFields['fields']) && count($formFields['fields']) > 0)
                <div class="space-y-4">
                    @foreach($formFields['fields'] as $index => $field)
                        <div class="border border-gray-200 rounded-lg p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Field {{ $index + 1 }} ({{ ucfirst($field['type']) }})</span>
                                <div class="flex items-center gap-2">
                                    @if($index > 0)
                                        <button type="button" wire:click="moveFieldUp({{ $index }})" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                            </svg>
                                        </button>
                                    @endif
                                    @if($index < count($formFields['fields']) - 1)
                                        <button type="button" wire:click="moveFieldDown({{ $index }})" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    @endif
                                    <button type="button" wire:click="removeField({{ $index }})" class="text-red-600 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Label</label>
                                    <input type="text" wire:model="formFields.fields.{{ $index }}.label" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Placeholder</label>
                                    <input type="text" wire:model="formFields.fields.{{ $index }}.placeholder" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Help Text</label>
                                <input type="text" wire:model="formFields.fields.{{ $index }}.help_text" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>

                            <!-- Options for Select Field -->
                            @if($field['type'] === 'select')
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                        Dropdown Options (one per line)
                                    </label>
                                    <textarea 
                                        wire:model="formFields.fields.{{ $index }}.options"
                                        rows="3"
                                        placeholder="Option 1&#10;Option 2&#10;Option 3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono"></textarea>
                                    <p class="text-xs text-gray-500 mt-1">Enter each option on a new line</p>
                                </div>
                            @endif

                            <div>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" wire:model="formFields.fields.{{ $index }}.required" 
                                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    <span class="text-xs font-medium text-gray-600">Required field</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>No fields added yet. Click "Add Field" to start building your form.</p>
                </div>
            @endif

            @error('formFields.fields') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Pricing & Estimates -->
        <div class="bg-white rounded-2xl shadow-lg p-6 space-y-6">
            <h2 class="text-xl font-bold text-gray-900">Pricing & Estimates</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (₱)</label>
                    <input type="number" wire:model="price" step="0.01" min="100" max="100000"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client Time (minutes)</label>
                    <input type="number" wire:model="estimatedClientTime" min="5" max="120"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Time to fill the form</p>
                    @error('estimatedClientTime') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Completion (days)</label>
                    <input type="number" wire:model="estimatedCompletionDays" min="1" max="30"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Time to complete document</p>
                    @error('estimatedCompletionDays') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Revisions Allowed</label>
                <input type="number" wire:model="revisionsAllowed" min="0" max="5"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <p class="mt-1 text-xs text-gray-500">Number of times client can request revisions (0-5)</p>
                @error('revisionsAllowed') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('lawyer.documents') }}" 
                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                class="px-6 py-3 bg-primary-700 text-white rounded-lg hover:bg-[#1E40AF]"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed">
                <span wire:loading.remove wire:target="save">Update Document Service</span>
                <span wire:loading wire:target="save" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Updating...
                </span>
            </button>
        </div>
    </form>
</div>

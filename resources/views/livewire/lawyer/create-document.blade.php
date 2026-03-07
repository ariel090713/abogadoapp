<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>

<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('lawyer.documents') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Documents
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Create Document Service</h1>
        <p class="text-gray-600 mt-1">Set up a new document drafting service for your clients</p>
    </div>

    <!-- Progress Steps -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 mb-6">
        <!-- Mobile: Vertical Steps -->
        <div class="flex flex-col gap-4 sm:hidden">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full flex-shrink-0 {{ $step >= 1 ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                    1
                </div>
                <span class="font-semibold text-sm {{ $step >= 1 ? 'text-gray-900' : 'text-gray-500' }}">Choose Template</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full flex-shrink-0 {{ $step >= 2 ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                    2
                </div>
                <span class="font-semibold text-sm {{ $step >= 2 ? 'text-gray-900' : 'text-gray-500' }}">Configure Form</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full flex-shrink-0 {{ $step >= 3 ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                    3
                </div>
                <span class="font-semibold text-sm {{ $step >= 3 ? 'text-gray-900' : 'text-gray-500' }}">Set Pricing</span>
            </div>
        </div>

        <!-- Desktop: Horizontal Steps -->
        <div class="hidden sm:flex items-center justify-between">
            <div class="flex items-center gap-3 flex-1">
                <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $step >= 1 ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                    1
                </div>
                <span class="font-semibold {{ $step >= 1 ? 'text-gray-900' : 'text-gray-500' }}">Choose Template</span>
            </div>
            <div class="w-16 h-1 {{ $step >= 2 ? 'bg-primary-600' : 'bg-gray-200' }}"></div>
            <div class="flex items-center gap-3 flex-1">
                <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $step >= 2 ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                    2
                </div>
                <span class="font-semibold {{ $step >= 2 ? 'text-gray-900' : 'text-gray-500' }}">Configure Form</span>
            </div>
            <div class="w-16 h-1 {{ $step >= 3 ? 'bg-primary-600' : 'bg-gray-200' }}"></div>
            <div class="flex items-center gap-3 flex-1">
                <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $step >= 3 ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                    3
                </div>
                <span class="font-semibold {{ $step >= 3 ? 'text-gray-900' : 'text-gray-500' }}">Set Pricing</span>
            </div>
        </div>
    </div>

    <!-- Step 1: Choose Template -->
    @if($step === 1)
        <div class="space-y-6">
            <!-- Create from Scratch Option -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-dashed border-gray-300 hover:border-primary-500 transition cursor-pointer"
                 wire:click="createFromScratch">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl bg-primary-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Create from Scratch</h3>
                        <p class="text-gray-600">Build your own custom document form from the ground up</p>
                    </div>
                </div>
            </div>

            <!-- Templates by Category -->
            @foreach($templates as $category => $categoryTemplates)
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $category }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($categoryTemplates as $template)
                            <div class="border border-gray-200 rounded-xl p-4 hover:border-primary-500 hover:shadow-md transition cursor-pointer"
                                 wire:click="selectTemplate({{ $template->id }})">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-semibold text-gray-900">{{ $template->name }}</h3>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                                        {{ count($template->form_fields['fields'] ?? []) }} fields
                                    </span>
                                </div>
                                @if($template->description)
                                    <p class="text-sm text-gray-600 mb-3">{{ $template->description }}</p>
                                @endif
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Used {{ $template->usage_count }} times
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Step 2: Configure Form -->
    @if($step === 2)
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Configure Document Form</h2>

            <!-- Document Info -->
            <div class="space-y-4 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Document Name</label>
                    <input type="text" 
                           wire:model="name"
                           placeholder="e.g., Affidavit of Loss"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select wire:model="category"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Select a category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                    <textarea wire:model="description"
                              rows="3"
                              placeholder="Brief description of this document"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                    @error('description') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Form Fields -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Form Fields</h3>
                    <div class="flex items-center gap-2">
                        <select wire:model="newFieldType" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="text">Text Input</option>
                            <option value="textarea">Text Area</option>
                            <option value="number">Number</option>
                            <option value="date">Date</option>
                            <option value="select">Dropdown</option>
                        </select>
                        <button wire:click="addField" 
                                class="px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-[#1E40AF] transition text-sm">
                            Add Field
                        </button>
                    </div>
                </div>

                @if(empty($formFields['fields']))
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-600">No fields yet. Click "Add Field" to start building your form.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($formFields['fields'] as $index => $field)
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="flex items-start gap-4">
                                    <!-- Field Number & Type -->
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center font-semibold">
                                            {{ $index + 1 }}
                                        </div>
                                        <p class="text-xs text-gray-500 text-center mt-1">{{ ucfirst($field['type']) }}</p>
                                    </div>

                                    <!-- Field Configuration -->
                                    <div class="flex-1 space-y-3">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Field Label</label>
                                                <input type="text" 
                                                       wire:model="formFields.fields.{{ $index }}.label"
                                                       placeholder="e.g., Full Name"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Placeholder</label>
                                                <input type="text" 
                                                       wire:model="formFields.fields.{{ $index }}.placeholder"
                                                       placeholder="e.g., Juan Dela Cruz"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Help Text (Optional)</label>
                                            <input type="text" 
                                                   wire:model="formFields.fields.{{ $index }}.help_text"
                                                   placeholder="Additional instructions for this field"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>

                                        <!-- Options for Select Field -->
                                        @if($field['type'] === 'select')
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">
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

                                        <div class="flex items-center gap-4">
                                            <label class="flex items-center gap-2">
                                                <input type="checkbox" 
                                                       wire:model="formFields.fields.{{ $index }}.required"
                                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                                <span class="text-sm text-gray-700">Required field</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex flex-col gap-2">
                                        <button wire:click="moveFieldUp({{ $index }})" 
                                                class="p-2 text-gray-600 hover:bg-gray-200 rounded-lg transition"
                                                title="Move up">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        </button>
                                        <button wire:click="moveFieldDown({{ $index }})" 
                                                class="p-2 text-gray-600 hover:bg-gray-200 rounded-lg transition"
                                                title="Move down">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <button wire:click="removeField({{ $index }})" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Remove">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                @error('formFields.fields') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Navigation -->
            <div class="flex justify-between pt-6 border-t">
                <button wire:click="previousStep" 
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Previous
                </button>
                <button wire:click="nextStep" 
                        class="px-6 py-3 bg-primary-700 text-white rounded-lg hover:bg-[#1E40AF] transition">
                    Next: Set Pricing
                </button>
            </div>
        </div>
    @endif

    <!-- Step 3: Set Pricing -->
    @if($step === 3)
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Set Pricing & Estimates</h2>

            <div class="space-y-6">
                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (PHP)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                        <input type="number" 
                               wire:model="price"
                               placeholder="1000"
                               min="100"
                               max="100000"
                               step="50"
                               class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    @error('price') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    <p class="text-sm text-gray-500 mt-1">Fixed price for this document service</p>
                </div>

                <!-- Estimated Client Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Time to Fill Form (minutes)</label>
                    <input type="number" 
                           wire:model="estimatedClientTime"
                           placeholder="15"
                           min="5"
                           max="120"
                           step="5"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    @error('estimatedClientTime') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    <p class="text-sm text-gray-500 mt-1">How long will it take clients to complete the form?</p>
                </div>

                <!-- Estimated Completion Days -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Completion Time (business days)</label>
                    <input type="number" 
                           wire:model="estimatedCompletionDays"
                           placeholder="3"
                           min="1"
                           max="30"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    @error('estimatedCompletionDays') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    <p class="text-sm text-gray-500 mt-1">How many business days to complete and deliver the document?</p>
                </div>

                <!-- Revisions Allowed -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Revisions Allowed</label>
                    <input type="number" 
                           wire:model="revisionsAllowed"
                           placeholder="1"
                           min="0"
                           max="5"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    @error('revisionsAllowed') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    <p class="text-sm text-gray-500 mt-1">How many times can the client request revisions? (0-5)</p>
                </div>

                <!-- Summary -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 mb-2">Service Summary</h3>
                    <div class="space-y-1 text-sm text-blue-800">
                        <p><span class="font-medium">Document:</span> {{ $name ?: 'Not set' }}</p>
                        <p><span class="font-medium">Fields:</span> {{ count($formFields['fields'] ?? []) }} fields</p>
                        <p><span class="font-medium">Price:</span> ₱{{ number_format($price ?: 0, 2) }}</p>
                        <p><span class="font-medium">Client Time:</span> ~{{ $estimatedClientTime }} minutes</p>
                        <p><span class="font-medium">Completion:</span> {{ $estimatedCompletionDays }} business days</p>
                        <p><span class="font-medium">Revisions:</span> {{ $revisionsAllowed }} {{ Str::plural('revision', $revisionsAllowed) }} allowed</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex justify-between pt-6 border-t mt-6">
                <button wire:click="previousStep" 
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Previous
                </button>
                <button wire:click="save" 
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="px-6 py-3 bg-primary-700 text-white rounded-lg hover:bg-[#1E40AF] transition">
                    <span wire:loading.remove wire:target="save">Create Document Service</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Creating...
                    </span>
                </button>
            </div>
        </div>
    @endif
</div>

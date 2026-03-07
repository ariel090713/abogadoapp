<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('documents.browse') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Documents
            </a>
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

        <!-- Document Info Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $document->name }}</h1>
                    @if($document->description)
                        <p class="text-gray-600">{{ $document->description }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Price</p>
                    <p class="text-3xl font-bold text-primary-600">₱{{ number_format($document->price, 0) }}</p>
                </div>
            </div>

            <!-- Lawyer Info -->
            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                @if($document->lawyer->profile_photo_url)
                    <img src="{{ $document->lawyer->profile_photo_url }}" 
                        alt="{{ $document->lawyer->name }}"
                        class="w-12 h-12 rounded-lg object-cover">
                @else
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-600 font-semibold">
                            {{ substr($document->lawyer->name, 0, 1) }}
                        </span>
                    </div>
                @endif
                <div class="flex-1">
                    <p class="font-medium text-gray-900">{{ $document->lawyer->name }}</p>
                    @if($document->lawyer->lawyerProfile)
                        <p class="text-sm text-gray-500">
                            {{ $document->lawyer->lawyerProfile->years_of_experience }} years of experience
                        </p>
                    @endif
                </div>
                <div class="grid grid-cols-2 gap-4 text-right">
                    <div>
                        <p class="text-sm text-gray-500">Delivery</p>
                        <p class="font-medium text-gray-900">{{ $document->estimated_completion_days }}d</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Revisions</p>
                        <p class="font-medium text-gray-900">{{ $document->revisions_allowed }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="submit" class="space-y-6">
            <!-- Form Fields -->
            <div class="bg-white rounded-2xl shadow-lg p-6 space-y-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900">Document Information</h2>
                    <span class="text-sm text-gray-500">
                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Est. {{ $document->estimated_client_time }} minutes to complete
                    </span>
                </div>

                @foreach($document->form_fields['fields'] as $field)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $field['label'] }}
                            @if($field['required'])
                                <span class="text-red-600">*</span>
                            @endif
                        </label>

                        @if($field['type'] === 'textarea')
                            <textarea wire:model="formData.{{ $field['id'] }}" 
                                rows="{{ $field['rows'] ?? 3 }}"
                                placeholder="{{ $field['placeholder'] ?? '' }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea>
                        
                        @elseif($field['type'] === 'select')
                            <select wire:model="formData.{{ $field['id'] }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Select an option</option>
                                @if(isset($field['options']))
                                    @foreach($field['options'] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                @endif
                            </select>
                        
                        @elseif($field['type'] === 'number')
                            <input type="number" wire:model="formData.{{ $field['id'] }}" 
                                placeholder="{{ $field['placeholder'] ?? '' }}"
                                min="{{ $field['min'] ?? '' }}"
                                max="{{ $field['max'] ?? '' }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        
                        @elseif($field['type'] === 'date')
                            <input type="date" wire:model="formData.{{ $field['id'] }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        
                        @else
                            <input type="text" wire:model="formData.{{ $field['id'] }}" 
                                placeholder="{{ $field['placeholder'] ?? '' }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @endif

                        @if(isset($field['help_text']) && $field['help_text'])
                            <p class="mt-1 text-sm text-gray-500">{{ $field['help_text'] }}</p>
                        @endif

                        @error('formData.' . $field['id']) 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>
                @endforeach

                <!-- Client Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                    <textarea wire:model="clientNotes" rows="3"
                        placeholder="Any additional information or special requests..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea>
                    @error('clientNotes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Terms & Submit -->
            <div class="bg-white rounded-2xl shadow-lg p-6 space-y-6">
                <div>
                    <label class="flex items-start gap-3">
                        <input type="checkbox" wire:model="agreedToTerms" 
                            class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm text-gray-700">
                            I agree that the information provided is accurate and I understand that payment is required to proceed with the document drafting. 
                            The lawyer will complete the document within {{ $document->estimated_completion_days }} {{ Str::plural('day', $document->estimated_completion_days) }} after payment confirmation.
                        </span>
                    </label>
                    @error('agreedToTerms') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div>
                        <p class="text-sm text-gray-500">Total Amount</p>
                        <p class="text-2xl font-bold text-gray-900">₱{{ number_format($document->price, 2) }}</p>
                    </div>
                    <button type="submit" 
                        class="px-8 py-3 bg-primary-700 text-white rounded-lg hover:bg-[#1E40AF] font-medium"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed">
                        <span wire:loading.remove wire:target="submit">Proceed to Payment</span>
                        <span wire:loading wire:target="submit" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

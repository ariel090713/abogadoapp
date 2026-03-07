<div class="min-h-screen bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Logout Button -->
        <div class="flex justify-end mb-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium border border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Step {{ $step }} of {{ $totalSteps }}</span>
                <span class="text-sm text-gray-500">{{ round(($step / $totalSteps) * 100) }}% Complete</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-[#1E3A8A] h-2 rounded-full transition-all duration-300" style="width: {{ ($step / $totalSteps) * 100 }}%"></div>
            </div>
        </div>

        <!-- Step 1: Basic Information -->
        @if($step === 1)
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Basic Information</h2>
                <p class="text-gray-600 mb-8">Let's start with some basic details about you</p>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <div class="flex gap-2">
                            <div class="flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 font-medium">
                                +63
                            </div>
                            <flux:input 
                                wire:model="phone"
                                type="text"
                                placeholder="9171234567"
                                required
                                class="flex-1"
                            />
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Enter 10 digits starting with 9 (e.g., 9171234567)</p>
                    </div>

                    <div>
                        <flux:select 
                            wire:model.live="province"
                            label="Province"
                            placeholder="Select province"
                            required
                        >
                            @foreach($provinces as $prov)
                                <option value="{{ $prov }}">{{ $prov }}</option>
                            @endforeach
                        </flux:select>
                    </div>

                    <div>
                        <flux:select 
                            wire:model="city"
                            label="City/Municipality"
                            placeholder="Select city"
                            required
                            :disabled="!$province"
                        >
                            @foreach($cities as $cityOption)
                                <option value="{{ $cityOption }}">{{ $cityOption }}</option>
                            @endforeach
                        </flux:select>
                        @if(!$province)
                            <p class="mt-1 text-sm text-gray-500">Please select a province first</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Languages Spoken *</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-48 overflow-y-auto pr-2 border border-gray-200 rounded-lg p-4">
                            @foreach($availableLanguages as $lang)
                                <label class="flex items-center p-3 rounded-lg cursor-pointer transition {{ in_array($lang, $languages) ? 'bg-primary-50 border-2 border-primary-500' : 'bg-white border-2 border-gray-200 hover:border-gray-300' }}">
                                    <input 
                                        type="checkbox" 
                                        wire:model="languages"
                                        value="{{ $lang }}"
                                        class="w-4 h-4 text-primary-600 focus:ring-primary-500 rounded"
                                    >
                                    <span class="ml-3 text-sm font-medium text-gray-900">{{ $lang }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Select all languages you can communicate in</p>
                        @error('languages')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between items-center">
                        <x-button 
                            wire:click="previousStep" 
                            type="button"
                            variant="secondary"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span wire:loading.remove wire:target="previousStep">Back to role selection</span>
                            <span wire:loading wire:target="previousStep">Going back...</span>
                        </x-button>
                        <flux:button wire:click="nextStep" variant="primary" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                            <span wire:loading.remove wire:target="nextStep">Continue</span>
                            <span wire:loading wire:target="nextStep" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Step 2: Legal Interests -->
        @if($step === 2)
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Legal Interests</h2>
                <p class="text-gray-600 mb-8">Select areas of law you're interested in (optional)</p>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($specializations as $spec)
                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition {{ in_array($spec->id, $interests) ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input 
                                    type="checkbox" 
                                    wire:model="interests"
                                    value="{{ $spec->id }}"
                                    class="w-4 h-4 text-primary-600 focus:ring-primary-500 rounded"
                                >
                                <span class="ml-3 text-sm font-medium text-gray-900">{{ $spec->icon }} {{ $spec->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="flex justify-between">
                        <x-button 
                            wire:click="previousStep" 
                            type="button"
                            variant="secondary"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span wire:loading.remove wire:target="previousStep">Back</span>
                            <span wire:loading wire:target="previousStep">Going back...</span>
                        </x-button>
                        <flux:button wire:click="nextStep" variant="primary" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                            <span wire:loading.remove wire:target="nextStep">Continue</span>
                            <span wire:loading wire:target="nextStep" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Step 3: Review & Confirm -->
        @if($step === 3)
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Review Your Information</h2>
                <p class="text-gray-600 mb-8">Please review your details before completing setup</p>

                <div class="space-y-6">
                    <!-- Contact Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Phone Number</p>
                                <p class="text-base font-medium text-gray-900">+63{{ $phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Location</p>
                                <p class="text-base font-medium text-gray-900">{{ $city }}, {{ $province }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Languages -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Languages</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($languages as $lang)
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-medium">{{ $lang }}</span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Legal Interests -->
                    @if(count($interests) > 0)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Legal Interests</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($specializations->whereIn('id', $interests) as $spec)
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $spec->icon }} {{ $spec->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-between pt-4">
                        <x-button 
                            wire:click="previousStep" 
                            type="button"
                            variant="secondary"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span wire:loading.remove wire:target="previousStep">Back</span>
                            <span wire:loading wire:target="previousStep">Going back...</span>
                        </x-button>
                        <flux:button wire:click="complete" variant="primary" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                            <span wire:loading.remove wire:target="complete">Complete Setup</span>
                            <span wire:loading wire:target="complete" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Completing...
                            </span>
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>

<div class="min-h-screen bg-white">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Info Panel with Header -->
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-100 rounded-2xl p-4 sm:p-6 mb-6">
            <div class="flex items-start gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Professional Information</h1>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Manage your professional credentials and expertise.
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <x-profile-nav type="lawyer" current="professional" />

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg" x-data x-init="$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))">
                <p class="text-sm text-green-600 font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg" x-data x-init="$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))">
                <p class="text-sm text-red-600 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Form -->
        <form wire:submit="save" class="space-y-8">
            <!-- Professional Details -->
            <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 space-y-6">
                <h3 class="text-lg font-semibold text-gray-900">Professional Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <flux:input 
                            wire:model="ibp_number" 
                            label="IBP Number" 
                            type="text" 
                            placeholder="1234567"
                        />
                        <p class="mt-1 text-sm text-gray-500">
                            Your Integrated Bar of the Philippines membership number
                        </p>
                    </div>

                    <div>
                        <flux:input 
                            wire:model.live.debounce.500ms="username" 
                            label="Username (Public Profile URL)" 
                            type="text" 
                            placeholder="juan-delacruz"
                        />
                        <div class="mt-1 space-y-1">
                            <p class="text-sm text-gray-500">
                                Your profile: {{ config('app.url') }}/lawyer/<span class="font-medium text-primary-600">{{ $username ?: 'username' }}</span>
                            </p>
                            @if($usernameInvalid)
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Only letters, numbers, dashes and underscores allowed
                                </p>
                            @elseif($usernameAvailable === true)
                                <p class="text-sm text-green-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Username is available
                                </p>
                            @elseif($usernameAvailable === false)
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Username is already taken
                                </p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <flux:input 
                            wire:model="law_school" 
                            label="Law School" 
                            type="text" 
                            placeholder="University of the Philippines"
                        />
                    </div>

                    <div>
                        <flux:input 
                            wire:model="law_firm" 
                            label="Law Firm (Optional)" 
                            type="text" 
                            placeholder="e.g. Cruz & Associates Law Firm"
                        />
                    </div>

                    <div>
                        <flux:input 
                            wire:model="graduation_year" 
                            label="Graduation Year" 
                            type="number" 
                            min="1950"
                            max="{{ date('Y') }}"
                            placeholder="{{ date('Y') }}"
                            description="Year you graduated from law school"
                        />
                    </div>

                    <div>
                        <flux:select 
                            wire:model="years_experience" 
                            label="Years of Experience"
                        >
                            <option value="">Select years</option>
                            @for($i = 1; $i <= 50; $i++)
                                <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'year' : 'years' }}</option>
                            @endfor
                        </flux:select>
                    </div>
                </div>
            </div>

            <!-- Bio & Languages -->
            <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 space-y-6">
                <h3 class="text-lg font-semibold text-gray-900">Bio & Languages</h3>
                
                <div>
                    <flux:textarea 
                        wire:model="bio" 
                        label="Professional Bio" 
                        rows="5"
                        placeholder="Brief introduction about your practice, expertise, and achievements..."
                    />
                    <p class="mt-1 text-sm text-gray-500">
                        {{ strlen($bio ?? '') }}/1000 characters
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Languages Spoken *</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($this->availableLanguages as $lang)
                            <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition {{ in_array($lang, $languages) ? 'border-primary-600 bg-primary-50' : 'border-gray-300' }}">
                                <input 
                                    type="checkbox" 
                                    wire:model="languages" 
                                    value="{{ $lang }}"
                                    class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                >
                                <span class="text-sm">{{ $lang }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Specializations -->
            <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 space-y-6">
                <h3 class="text-lg font-semibold text-gray-900">Practice Areas / Specializations</h3>
                
                <div class="space-y-6">
                    @foreach($this->allSpecializations as $parent)
                        <div class="border border-gray-200 rounded-xl p-4">
                            <!-- Parent Category -->
                            <label class="flex items-center gap-3 p-3 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ in_array($parent->id, $selectedSpecializations) ? 'bg-primary-50' : '' }}">
                                <input 
                                    type="checkbox" 
                                    wire:model="selectedSpecializations" 
                                    value="{{ $parent->id }}"
                                    class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                >
                                <div class="flex items-center gap-2 flex-1">
                                    @if($parent->icon)
                                        <span class="text-xl">{{ $parent->icon }}</span>
                                    @endif
                                    <span class="text-base font-semibold text-gray-900">{{ $parent->name }}</span>
                                </div>
                            </label>
                            
                            <!-- Sub-specializations -->
                            @if($parent->children->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3 pl-10">
                                    @foreach($parent->children as $spec)
                                        <label class="flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer hover:bg-gray-50 transition {{ in_array($spec->id, $selectedSpecializations) ? 'border-primary-600 bg-primary-50' : 'border-gray-300' }}">
                                            <input 
                                                type="checkbox" 
                                                wire:model="selectedSpecializations" 
                                                value="{{ $spec->id }}"
                                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                            >
                                            <span class="text-sm text-gray-700">{{ $spec->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <flux:button 
                    type="submit" 
                    variant="primary"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="save">Save Changes</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </flux:button>
            </div>
        </form>
    </div>
</div>

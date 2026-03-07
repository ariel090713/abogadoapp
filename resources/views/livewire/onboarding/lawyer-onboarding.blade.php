<div class="min-h-screen bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
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
                <div class="bg-accent-600 h-2 rounded-full transition-all duration-300" style="width: {{ ($step / $totalSteps) * 100 }}%"></div>
            </div>
        </div>

        <!-- Step 1: Personal Information -->
        @if($step === 1)
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Personal Information</h2>
                <p class="text-gray-600 mb-8">Let's start with your basic details</p>

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

        <!-- Step 2: Professional Credentials -->
        @if($step === 2)
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Professional Credentials</h2>
                <p class="text-gray-600 mb-8">Verify your legal credentials</p>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">IBP Number *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-700 font-medium">IBP-</span>
                            <input 
                                type="text"
                                wire:model="ibpNumber"
                                placeholder="Enter your IBP number"
                                maxlength="20"
                                class="w-full pl-14 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                required
                            />
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Maximum 20 characters</p>
                        @error('ibpNumber')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">IBP Card / Certificate *</label>
                        <div class="relative">
                            <input 
                                type="file" 
                                wire:model="ibpCard" 
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                                x-on:livewire-upload-start="console.log('Upload started')"
                                x-on:livewire-upload-finish="console.log('Upload finished')"
                                x-on:livewire-upload-error="console.error('Upload error:', $event.detail); alert('Upload failed: ' + ($event.detail.message || 'Unknown error'))"
                                x-on:livewire-upload-progress="console.log('Upload progress:', $event.detail.progress + '%')"
                            >
                            
                            <!-- Loading indicator -->
                            <div wire:loading wire:target="ibpCard" class="mt-2 flex items-center gap-2 text-primary-600">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-sm">Uploading file...</span>
                            </div>
                        </div>
                        
                        <!-- Success indicator -->
                        @if($ibpCard && !$errors->has('ibpCard'))
                            <div class="mt-2 flex items-center gap-2 text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm">File uploaded successfully</span>
                            </div>
                        @endif
                        
                        <p class="mt-1 text-sm text-gray-500">Upload your IBP card or certificate (PDF, JPG, PNG - Max 10MB)</p>
                        
                        @error('ibpCard')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <flux:input 
                                wire:model="yearsExperience"
                                label="Years of Experience"
                                type="number"
                                min="0"
                                max="50"
                                required
                            />
                        </div>
                        <div>
                            <flux:input 
                                wire:model="graduationYear"
                                label="Graduation Year"
                                type="number"
                                min="1950"
                                :max="date('Y')"
                                required
                            />
                        </div>
                    </div>

                    <div>
                        <flux:input 
                            wire:model="lawSchool"
                            label="Law School"
                            type="text"
                            placeholder="University of the Philippines College of Law"
                            required
                        />
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
                        <flux:button 
                            wire:click="nextStep" 
                            variant="primary" 
                            wire:loading.attr="disabled" 
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            wire:target="nextStep,ibpCard"
                            :disabled="!$ibpCard"
                        >
                            <span wire:loading.remove wire:target="nextStep,ibpCard">
                                @if($ibpCard)
                                    Continue
                                @else
                                    Upload IBP Card to Continue
                                @endif
                            </span>
                            <span wire:loading wire:target="ibpCard" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading, please wait...
                            </span>
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

        <!-- Step 3: Practice Areas & Bio -->
        @if($step === 3)
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Practice Areas & Bio</h2>
                <p class="text-gray-600 mb-8">Tell clients about your expertise</p>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Select Your Practice Areas *</label>
                        <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                            @foreach($allSpecializations as $spec)
                                <div class="border-l-2 border-gray-200 pl-2 space-y-1">
                                    <label class="flex items-center p-3 rounded-lg hover:bg-gray-50 cursor-pointer {{ in_array($spec->id, $specializations) ? 'bg-primary-50' : '' }}">
                                        <input 
                                            type="checkbox" 
                                            wire:model="specializations"
                                            value="{{ $spec->id }}"
                                            class="w-4 h-4 text-primary-600 focus:ring-primary-500 rounded"
                                        >
                                        <span class="ml-3 text-sm font-semibold text-gray-900">{{ $spec->icon }} {{ $spec->name }}</span>
                                    </label>
                                    
                                    @if($spec->children->count() > 0)
                                        @foreach($spec->children as $child)
                                            <label class="flex items-center p-2 pl-8 rounded-lg hover:bg-gray-50 cursor-pointer {{ in_array($child->id, $specializations) ? 'bg-primary-50' : '' }}">
                                                <input 
                                                    type="checkbox" 
                                                    wire:model="specializations"
                                                    value="{{ $child->id }}"
                                                    class="w-4 h-4 text-primary-600 focus:ring-primary-500 rounded"
                                                >
                                                <span class="ml-3 text-sm text-gray-700">{{ $child->name }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @error('specializations')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Professional Bio *</label>
                        <textarea 
                            wire:model.live="bio"
                            rows="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Tell potential clients about your experience, expertise, and approach to legal practice... (minimum 100 characters)"
                        ></textarea>
                        <p class="mt-1 text-sm text-gray-500">{{ strlen($bio) }}/1000 characters</p>
                        @error('bio')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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

        <!-- Step 4: Service Pricing -->
        @if($step === 4)
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Service Pricing</h2>
                <p class="text-gray-600 mb-8">Set your rates for different consultation types</p>

                <div class="space-y-8">
                    @error('services')
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        </div>
                    @enderror

                    <!-- Chat Consultation -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <input 
                                type="checkbox" 
                                wire:model.live="offersChat"
                                id="offersChat"
                                class="w-5 h-5 text-primary-600 focus:ring-primary-500 rounded"
                            >
                            <label for="offersChat" class="flex items-center gap-2 text-lg font-semibold text-gray-900 cursor-pointer">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Chat Consultation
                            </label>
                        </div>
                        
                        @if($offersChat)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 ml-8">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">15 Minutes</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-500">₱</span>
                                        <input 
                                            type="number" 
                                            wire:model="chatRate15min"
                                            min="100"
                                            max="10000"
                                            step="50"
                                            class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                            placeholder="500"
                                        >
                                    </div>
                                    @error('chatRate15min')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">30 Minutes</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-500">₱</span>
                                        <input 
                                            type="number" 
                                            wire:model="chatRate30min"
                                            min="100"
                                            max="20000"
                                            step="50"
                                            class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                            placeholder="900"
                                        >
                                    </div>
                                    @error('chatRate30min')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">60 Minutes</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-500">₱</span>
                                        <input 
                                            type="number" 
                                            wire:model="chatRate60min"
                                            min="100"
                                            max="40000"
                                            step="50"
                                            class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                            placeholder="1600"
                                        >
                                    </div>
                                    @error('chatRate60min')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Video Consultation -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <input 
                                type="checkbox" 
                                wire:model.live="offersVideo"
                                id="offersVideo"
                                class="w-5 h-5 text-primary-600 focus:ring-primary-500 rounded"
                            >
                            <label for="offersVideo" class="flex items-center gap-2 text-lg font-semibold text-gray-900 cursor-pointer">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Video Consultation
                            </label>
                        </div>
                        
                        @if($offersVideo)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 ml-8">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">15 Minutes</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-500">₱</span>
                                        <input 
                                            type="number" 
                                            wire:model="videoRate15min"
                                            min="100"
                                            max="10000"
                                            step="50"
                                            class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                            placeholder="800"
                                        >
                                    </div>
                                    @error('videoRate15min')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">30 Minutes</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-500">₱</span>
                                        <input 
                                            type="number" 
                                            wire:model="videoRate30min"
                                            min="100"
                                            max="20000"
                                            step="50"
                                            class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                            placeholder="1400"
                                        >
                                    </div>
                                    @error('videoRate30min')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">60 Minutes</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-500">₱</span>
                                        <input 
                                            type="number" 
                                            wire:model="videoRate60min"
                                            min="100"
                                            max="40000"
                                            step="50"
                                            class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                            placeholder="2500"
                                        >
                                    </div>
                                    @error('videoRate60min')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Document Review -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <input 
                                type="checkbox" 
                                wire:model.live="offersDocumentReview"
                                id="offersDocumentReview"
                                class="w-5 h-5 text-primary-600 focus:ring-primary-500 rounded"
                            >
                            <label for="offersDocumentReview" class="flex items-center gap-2 text-lg font-semibold text-gray-900 cursor-pointer">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Document Review
                            </label>
                        </div>
                        
                        @if($offersDocumentReview)
                            <div class="ml-8">
                                <div class="max-w-md">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Price</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-500">₱</span>
                                        <input 
                                            type="number" 
                                            wire:model="documentReviewMinPrice"
                                            min="100"
                                            max="50000"
                                            step="100"
                                            class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                            placeholder="1500"
                                        >
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Starting price for document review services</p>
                                    @error('documentReviewMinPrice')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between">
                        <button 
                            wire:click="previousStep" 
                            type="button"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition font-medium"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span wire:loading.remove wire:target="previousStep">Back</span>
                            <span wire:loading wire:target="previousStep">Going back...</span>
                        </button>
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

        <!-- Step 5: Availability Schedule -->
        @if($step === 5)
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Availability Schedule</h2>
                <p class="text-gray-600 mb-8">Set your weekly availability for consultations</p>

                <div class="space-y-4">
                    @error('schedule')
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        </div>
                    @enderror

                    @php
                        $days = [
                            'monday' => 'Monday',
                            'tuesday' => 'Tuesday',
                            'wednesday' => 'Wednesday',
                            'thursday' => 'Thursday',
                            'friday' => 'Friday',
                            'saturday' => 'Saturday',
                            'sunday' => 'Sunday',
                        ];
                        
                        $timeSlots = [];
                        for ($hour = 0; $hour < 24; $hour++) {
                            for ($minute = 0; $minute < 60; $minute += 15) {
                                $time = sprintf('%02d:%02d', $hour, $minute);
                                $timeSlots[] = $time;
                            }
                        }
                    @endphp

                    @foreach($days as $dayKey => $dayLabel)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center min-w-[120px]">
                                    <input 
                                        type="checkbox" 
                                        wire:model.live="schedule.{{ $dayKey }}.enabled"
                                        id="day_{{ $dayKey }}"
                                        class="w-5 h-5 text-primary-600 focus:ring-primary-500 rounded"
                                    >
                                    <label for="day_{{ $dayKey }}" class="ml-3 text-base font-medium text-gray-900 cursor-pointer">
                                        {{ $dayLabel }}
                                    </label>
                                </div>

                                @if($schedule[$dayKey]['enabled'])
                                    <div class="flex items-center gap-4 flex-1">
                                        <div class="flex-1">
                                            <label class="block text-sm text-gray-600 mb-1">Start Time</label>
                                            <select 
                                                wire:model="schedule.{{ $dayKey }}.start"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                            >
                                                @foreach($timeSlots as $time)
                                                    <option value="{{ $time }}">{{ date('g:i A', strtotime($time)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex-1">
                                            <label class="block text-sm text-gray-600 mb-1">End Time</label>
                                            <select 
                                                wire:model="schedule.{{ $dayKey }}.end"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                            >
                                                @foreach($timeSlots as $time)
                                                    <option value="{{ $time }}">{{ date('g:i A', strtotime($time)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500 italic">Not available</span>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                        <h4 class="font-semibold text-blue-900 mb-2">💡 Tip</h4>
                        <p class="text-sm text-blue-800">You can update your availability anytime from your profile settings after onboarding.</p>
                    </div>

                    <div class="flex justify-between pt-4">
                        <button 
                            wire:click="previousStep" 
                            type="button"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition font-medium"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span wire:loading.remove wire:target="previousStep">Back</span>
                            <span wire:loading wire:target="previousStep">Going back...</span>
                        </button>
                        <flux:button wire:click="nextStep" variant="primary" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                            <span wire:loading.remove wire:target="nextStep">Continue to Review</span>
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

        <!-- Step 6: Review & Confirm -->
        @if($step === 6)
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Review Your Information</h2>
                <p class="text-gray-600 mb-8">Please review your details before submitting for verification</p>

                <div class="space-y-6">
                    <!-- Personal Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
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

                    <!-- Professional Credentials -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Professional Credentials</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">IBP Number</p>
                                <p class="text-base font-medium text-gray-900">IBP-{{ $ibpNumber }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Years of Experience</p>
                                <p class="text-base font-medium text-gray-900">{{ $yearsExperience }} years</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Law School</p>
                                <p class="text-base font-medium text-gray-900">{{ $lawSchool }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Graduation Year</p>
                                <p class="text-base font-medium text-gray-900">{{ $graduationYear }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Practice Areas -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Practice Areas</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($allSpecializations as $parent)
                                @foreach($parent->children as $child)
                                    @if(in_array($child->id, $specializations))
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $child->name }}</span>
                                    @endif
                                @endforeach
                                @if(in_array($parent->id, $specializations))
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">{{ $parent->icon }} {{ $parent->name }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Bio -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Professional Bio</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $bio }}</p>
                    </div>

                    <!-- Service Pricing -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Pricing</h3>
                        <div class="space-y-3">
                            @if($offersChat)
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Chat Consultation</p>
                                    <p class="text-sm text-gray-600">15 min: ₱{{ number_format($chatRate15min, 2) }} | 30 min: ₱{{ number_format($chatRate30min, 2) }} | 60 min: ₱{{ number_format($chatRate60min, 2) }}</p>
                                </div>
                            @endif
                            @if($offersVideo)
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Video Consultation</p>
                                    <p class="text-sm text-gray-600">15 min: ₱{{ number_format($videoRate15min, 2) }} | 30 min: ₱{{ number_format($videoRate30min, 2) }} | 60 min: ₱{{ number_format($videoRate60min, 2) }}</p>
                                </div>
                            @endif
                            @if($offersDocumentReview)
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Document Review</p>
                                    <p class="text-sm text-gray-600">Starting at ₱{{ number_format($documentReviewMinPrice, 2) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Availability Schedule -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Availability Schedule</h3>
                        <div class="space-y-2">
                            @php
                                $dayLabels = [
                                    'monday' => 'Monday',
                                    'tuesday' => 'Tuesday',
                                    'wednesday' => 'Wednesday',
                                    'thursday' => 'Thursday',
                                    'friday' => 'Friday',
                                    'saturday' => 'Saturday',
                                    'sunday' => 'Sunday',
                                ];
                            @endphp
                            @foreach($schedule as $day => $times)
                                @if($times['enabled'])
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-700 min-w-[100px]">{{ $dayLabels[$day] }}:</span>
                                        <span class="text-sm text-gray-600">{{ date('g:i A', strtotime($times['start'])) }} - {{ date('g:i A', strtotime($times['end'])) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                        <h4 class="font-semibold text-blue-900 mb-2">What happens next?</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Your profile will be reviewed by our admin team</li>
                            <li>• We'll verify your IBP credentials</li>
                            <li>• You'll receive an email notification once approved (usually within 24-48 hours)</li>
                            <li>• After approval, you can start accepting consultations</li>
                        </ul>
                    </div>

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
                            <span wire:loading.remove wire:target="complete">Submit for Verification</span>
                            <span wire:loading wire:target="complete" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Submitting...
                            </span>
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

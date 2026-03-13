<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="min-h-screen bg-white">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Info Panel with Header -->
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-100 rounded-2xl p-4 sm:p-6 mb-6">
            <div class="flex items-start gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Admin Profile</h1>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Manage your administrator account settings.
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <x-profile-nav type="admin" current="profile" />

        <!-- Flash Messages -->
        <div id="flash-messages">
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
        </div>

        <!-- Profile Form -->
        <form wire:submit="save" class="space-y-8">
                
            <!-- Profile Photo (Left) & Basic Information (Right) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Photo Section (1/3 width) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900">Profile Photo</h3>
                        
                        <div class="flex flex-col items-center gap-4">
                            <!-- Current Photo -->
                            <div class="relative">
                                @if($new_profile_photo)
                                    <img src="{{ $new_profile_photo->temporaryUrl() }}" 
                                         alt="Preview" 
                                         class="w-32 h-32 rounded-2xl object-cover border-4 border-purple-100">
                                @elseif($profile_photo)
                                    <img src="{{ $profile_photo }}" 
                                         alt="{{ $name }}" 
                                         class="w-32 h-32 rounded-2xl object-cover border-4 border-purple-100">
                                @else
                                    <div class="w-32 h-32 rounded-2xl bg-purple-100 flex items-center justify-center border-4 border-purple-200">
                                        <span class="text-4xl font-bold text-purple-700">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Upload Button -->
                            <div class="w-full text-center">
                                <label for="photo-upload" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition cursor-pointer w-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Choose Photo
                                </label>
                                <input id="photo-upload" type="file" wire:model="new_profile_photo" accept="image/*" class="hidden">
                                
                                <p class="mt-2 text-xs text-gray-500">JPG, PNG. Max 5MB.</p>
                                
                                @error('new_profile_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                <div wire:loading wire:target="new_profile_photo" class="mt-2 text-sm text-primary-600">
                                    Uploading...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information (2/3 width) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="md:col-span-2">
                                <flux:input 
                                    wire:model="name" 
                                    label="Full Name" 
                                    type="text" 
                                    required 
                                    placeholder="Administrator Name"
                                />
                            </div>

                            <!-- Email -->
                            <div>
                                <flux:input 
                                    wire:model="email" 
                                    label="Email Address" 
                                    type="email" 
                                    required 
                                    placeholder="admin@example.com"
                                />
                                @if(auth()->user()->email_verified_at)
                                    <p class="mt-1 text-sm text-green-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified
                                    </p>
                                @else
                                    <p class="mt-1 text-sm text-amber-600">Email not verified</p>
                                @endif
                            </div>

                            <!-- Phone -->
                            <div>
                                <flux:input 
                                    wire:model="phone" 
                                    label="Phone Number (Optional)" 
                                    type="text" 
                                    placeholder="09171234567"
                                />
                            </div>
                        </div>
                    </div>
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

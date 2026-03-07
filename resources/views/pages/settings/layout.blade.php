@props(['heading', 'subheading', 'title' => 'Settings'])

<x-layouts.dashboard :title="$title">
    <x-slot name="sidebar">
        @if(auth()->user()->isClient())
            <x-client-sidebar />
        @elseif(auth()->user()->isLawyer())
            <x-lawyer-sidebar />
        @elseif(auth()->user()->isAdmin())
            {{-- Admin sidebar here if needed --}}
        @endif
    </x-slot>

    <div class="p-8">
        <div class="max-w-4xl">
            <!-- Settings Navigation Tabs -->
            <div class="mb-8">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('profile.edit') }}" wire:navigate class="px-4 py-2 rounded-full text-sm font-medium transition {{ request()->routeIs('profile.edit') ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                        Profile
                    </a>
                    <a href="{{ route('user-password.edit') }}" wire:navigate class="px-4 py-2 rounded-full text-sm font-medium transition {{ request()->routeIs('user-password.edit') ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                        Password
                    </a>
                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        <a href="{{ route('two-factor.show') }}" wire:navigate class="px-4 py-2 rounded-full text-sm font-medium transition {{ request()->routeIs('two-factor.show') ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                            Two-Factor Auth
                        </a>
                    @endif
                    <a href="{{ route('appearance.edit') }}" wire:navigate class="px-4 py-2 rounded-full text-sm font-medium transition {{ request()->routeIs('appearance.edit') ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                        Appearance
                    </a>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                @if($heading ?? false)
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $heading }}</h2>
                @endif
                @if($subheading ?? false)
                    <p class="text-gray-600 mb-6">{{ $subheading }}</p>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>
</x-layouts.dashboard>

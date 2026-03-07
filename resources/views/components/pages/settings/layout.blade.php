@props(['heading', 'subheading', 'title' => 'Settings'])

<div class="bg-white min-h-screen">
    <!-- Settings Tabs -->
    <div class="px-4 sm:px-6 lg:px-8 pt-8">
        <div class="flex flex-wrap gap-2 mb-8">
            <a href="{{ route('user-password.edit') }}" 
               class="px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap {{ request()->routeIs('user-password.edit') ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                Password
            </a>
            <a href="{{ route('two-factor.show') }}" 
               class="px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap {{ request()->routeIs('two-factor.show') ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                Two-Factor Auth
            </a>
        </div>
    </div>

    <!-- Settings Content -->
    <div class="px-4 sm:px-6 lg:px-8 pb-8">
        <div class="max-w-2xl">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">{{ $heading }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ $subheading }}</p>
            </div>
            
            {{ $slot }}
        </div>
    </div>
</div>

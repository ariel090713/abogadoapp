@props(['type', 'current'])

<div class="mb-8">
    <!-- Mobile & Desktop: Button-style tabs -->
    <div class="flex flex-wrap gap-2 overflow-x-auto">
        <a href="{{ route($type . '.profile') }}" 
           class="{{ $current === 'profile' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }} px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap">
            <span class="hidden sm:inline">Profile Information</span>
            <span class="sm:hidden">Profile</span>
        </a>
        
        @if($type === 'lawyer')
            <a href="{{ route('lawyer.profile.professional') }}" 
               class="{{ $current === 'professional' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }} px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap">
                <span class="hidden sm:inline">Professional Info</span>
                <span class="sm:hidden">Professional</span>
            </a>
            <a href="{{ route('lawyer.profile.services') }}" 
               class="{{ $current === 'services' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }} px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap">
                <span class="hidden sm:inline">Services & Pricing</span>
                <span class="sm:hidden">Services</span>
            </a>
        @endif
        
        <a href="{{ route($type . '.profile.security') }}" 
           class="{{ $current === 'security' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }} px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap">
            Security
        </a>
        <a href="{{ route($type . '.profile.notifications') }}" 
           class="{{ $current === 'notifications' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700' }} px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap">
            Notifications
        </a>
    </div>
</div>

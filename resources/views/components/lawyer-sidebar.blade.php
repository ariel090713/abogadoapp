<a href="{{ route('lawyer.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lawyer.dashboard') ? 'bg-accent-600 text-white shadow-lg shadow-accent-600/30' : 'text-gray-300 hover:bg-primary-700 hover:text-white' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    Dashboard
</a>

<a href="{{ route('lawyer.consultations') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lawyer.consultations*') ? 'bg-accent-600 text-white shadow-lg shadow-accent-600/30' : 'text-gray-300 hover:bg-primary-700 hover:text-white' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    Consultations
</a>

<a href="{{ route('lawyer.cases') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lawyer.cases*') || request()->routeIs('lawyer.consultation-thread.details') ? 'bg-accent-600 text-white shadow-lg shadow-accent-600/30' : 'text-gray-300 hover:bg-primary-700 hover:text-white' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
    </svg>
    Consultation Threads
</a>

<a href="{{ route('lawyer.schedule') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lawyer.schedule') ? 'bg-accent-600 text-white shadow-lg shadow-accent-600/30' : 'text-gray-300 hover:bg-primary-700 hover:text-white' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    Schedule
</a>

<a href="{{ route('lawyer.documents') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lawyer.documents*') ? 'bg-accent-600 text-white shadow-lg shadow-accent-600/30' : 'text-gray-300 hover:bg-primary-700 hover:text-white' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    Documents Forms
</a>

<a href="{{ route('lawyer.document-requests') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lawyer.document-requests*') || request()->routeIs('lawyer.document-request.details') ? 'bg-accent-600 text-white shadow-lg shadow-accent-600/30' : 'text-gray-300 hover:bg-primary-700 hover:text-white' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
    </svg>
    Document Requests
</a>

<a href="{{ route('lawyer.transactions') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lawyer.transactions') ? 'bg-accent-600 text-white shadow-lg shadow-accent-600/30' : 'text-gray-300 hover:bg-primary-700 hover:text-white' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
    </svg>
    Transactions
</a>

{{-- Earnings feature coming soon --}}
{{-- <a href="{{ route('lawyer.earnings') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lawyer.earnings') ? 'bg-accent-600 text-white shadow-lg shadow-accent-600/30' : 'text-gray-300 hover:bg-primary-700 hover:text-white' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    Earnings
</a> --}}

{{-- Clients feature coming soon --}}
{{-- <a href="{{ route('lawyer.clients') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lawyer.clients') ? 'bg-accent-600 text-white shadow-lg shadow-accent-600/30' : 'text-gray-300 hover:bg-primary-700 hover:text-white' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
    </svg>
    Clients
</a> --}}

{{-- Messages feature coming soon --}}
{{-- <a href="{{ route('lawyer.messages') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lawyer.messages') ? 'bg-accent-600 text-white shadow-lg shadow-accent-600/30' : 'text-gray-300 hover:bg-primary-700 hover:text-white' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
    </svg>
    Messages
</a> --}}

<!-- Divider -->
<div class="my-4 border-t border-primary-700"></div>

<a href="{{ route('lawyer.profile') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lawyer.profile*') ? 'bg-accent-600 text-white shadow-lg shadow-accent-600/30' : 'text-gray-300 hover:bg-primary-700 hover:text-white' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
    </svg>
    Profile
</a>

<a href="{{ route('user-password.edit') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('user-password.edit') || request()->routeIs('two-factor.show') ? 'bg-accent-600 text-white shadow-lg shadow-accent-600/30' : 'text-gray-300 hover:bg-primary-700 hover:text-white' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
    Settings
</a>

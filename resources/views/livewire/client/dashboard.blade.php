<x-slot name="sidebar">
    <x-client-sidebar />
</x-slot>

<div class="p-4 sm:p-6 lg:p-8">
    <!-- Welcome Message -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600 mt-1">Manage your legal consultations and connect with lawyers.</p>
    </div>

    <!-- Info Panel -->
    <div class="bg-gradient-to-r from-primary-50 to-blue-50 border border-primary-100 rounded-2xl p-4 sm:p-6 mb-8">
        <div class="flex items-start gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-primary-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">Consultations</h3>
                <p class="text-sm text-gray-700 leading-relaxed">
                    Track your consultation bookings with lawyers. Each represents a single request for chat, video call, or document review services.
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <!-- Total Consultations -->
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Total Consultations</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_consultations'] }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Upcoming -->
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Upcoming</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">{{ $stats['upcoming'] }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Pending Requests</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Document Requests -->
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Document Requests</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">{{ $stats['document_requests'] }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('lawyers.search') }}" class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl hover:border-primary-500 hover:bg-primary-50 transition group">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center group-hover:bg-primary-200 transition">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Find a Lawyer</h3>
                        <p class="text-sm text-gray-600">Browse available lawyers</p>
                    </div>
                </a>

                <a href="{{ route('client.consultations') }}" class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl hover:border-primary-500 hover:bg-primary-50 transition group">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">My Consultations</h3>
                        <p class="text-sm text-gray-600">View all consultations</p>
                    </div>
                </a>

                <a href="{{ route('client.messages') }}" class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl hover:border-primary-500 hover:bg-primary-50 transition group">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-200 transition">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Messages</h3>
                        <p class="text-sm text-gray-600">Chat with lawyers</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Consultations Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-6">
            <!-- Pending Requests -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Pending Requests</h2>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">{{ $pendingRequests->count() }}</span>
                </div>

                @if($pendingRequests->count() > 0)
                    <div class="space-y-2">
                        @foreach($pendingRequests->take(3) as $consultation)
                            <a href="{{ route('client.consultation.details', $consultation->id) }}" class="block p-3 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-gray-50 transition">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 text-xs font-bold">
                                        {{ $consultation->lawyer->initials() }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 truncate">{{ $consultation->lawyer->name }}</span>
                                </div>
                                <p class="text-xs text-gray-600 truncate">{{ $consultation->title }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $consultation->created_at->diffForHumans() }}</p>
                            </a>
                        @endforeach
                    </div>
                    @if($pendingRequests->count() > 3)
                        <a href="{{ route('client.consultations', ['filter' => 'pending']) }}" class="block text-center text-sm text-primary-600 hover:text-primary-700 font-medium mt-3">
                            View all {{ $pendingRequests->count() }} pending
                        </a>
                    @endif
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-gray-600">No pending requests</p>
                    </div>
                @endif
            </div>

            <!-- Today's Schedule -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Today's Schedule</h2>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">{{ $upcomingConsultations->where('scheduled_at', '>=', now()->startOfDay())->where('scheduled_at', '<=', now()->endOfDay())->count() }}</span>
                </div>

                @php
                    $todayConsultations = $upcomingConsultations->where('scheduled_at', '>=', now()->startOfDay())->where('scheduled_at', '<=', now()->endOfDay());
                @endphp

                @if($todayConsultations->count() > 0)
                    <div class="space-y-2">
                        @foreach($todayConsultations->take(3) as $consultation)
                            <a href="{{ route('client.consultation.details', $consultation->id) }}" class="block p-3 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 text-xs font-bold">
                                            {{ $consultation->lawyer->initials() }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 truncate">{{ $consultation->lawyer->name }}</span>
                                    </div>
                                    <span class="text-xs font-semibold text-blue-600">{{ $consultation->scheduled_at->format('g:i A') }}</span>
                                </div>
                                <p class="text-xs text-gray-600 truncate">{{ $consultation->title }}</p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-600">No consultations today</p>
                    </div>
                @endif
            </div>

            <!-- Document Requests -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Document Requests</h2>
                    <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">{{ $documentRequests->count() }}</span>
                </div>

                @if($documentRequests->count() > 0)
                    <div class="space-y-2">
                        @foreach($documentRequests->take(3) as $request)
                            <a href="{{ route('client.document.details', $request->id) }}" class="block p-3 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 text-xs font-bold">
                                            {{ $request->lawyer->initials() }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 truncate">{{ $request->lawyer->name }}</span>
                                    </div>
                                    @if($request->status === 'pending_payment')
                                        <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">Payment</span>
                                    @elseif($request->status === 'paid')
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">Paid</span>
                                    @elseif($request->status === 'in_progress')
                                        <span class="px-2 py-0.5 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">In Progress</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600 truncate">{{ $request->document_name }}</p>
                                <p class="text-xs text-gray-500 mt-1">₱{{ number_format($request->price, 2) }} • {{ $request->created_at->diffForHumans() }}</p>
                            </a>
                        @endforeach
                    </div>
                    @if($documentRequests->count() > 3)
                        <a href="{{ route('client.documents') }}" class="block text-center text-sm text-primary-600 hover:text-primary-700 font-medium mt-3">
                            View all {{ $documentRequests->count() }} requests
                        </a>
                    @endif
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm text-gray-600">No document requests</p>
                    </div>
                @endif
            </div>

            <!-- Upcoming -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Upcoming</h2>
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">{{ $upcomingConsultations->count() }}</span>
                </div>

                @if($upcomingConsultations->count() > 0)
                    <div class="space-y-2">
                        @foreach($upcomingConsultations->take(3) as $consultation)
                            <a href="{{ route('client.consultation.details', $consultation->id) }}" class="block p-3 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-gray-50 transition">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 text-xs font-bold">
                                        {{ $consultation->lawyer->initials() }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 truncate">{{ $consultation->lawyer->name }}</span>
                                </div>
                                <p class="text-xs text-gray-600 truncate">{{ $consultation->title }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $consultation->scheduled_at->format('M d, g:i A') }}</p>
                            </a>
                        @endforeach
                    </div>
                    @if($upcomingConsultations->count() > 3)
                        <a href="{{ route('client.consultations', ['filter' => 'scheduled']) }}" class="block text-center text-sm text-primary-600 hover:text-primary-700 font-medium mt-3">
                            View all {{ $upcomingConsultations->count() }} upcoming
                        </a>
                    @endif
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-600">No upcoming consultations</p>
                        <a href="{{ route('lawyers.search') }}" class="inline-block mt-2 text-sm text-primary-600 hover:text-primary-700 font-medium">
                            Find a Lawyer
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

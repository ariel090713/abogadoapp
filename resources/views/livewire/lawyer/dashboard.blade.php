<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>

<div class="p-4 sm:p-6 lg:p-8">
    <!-- Welcome Message -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-2">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
            
            <!-- Verification Status Badge -->
            @if(auth()->user()->lawyerProfile->is_verified)
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 border border-green-200 rounded-full">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-semibold text-green-700">Verified Lawyer</span>
                </div>
            @elseif(auth()->user()->lawyerProfile->is_rejected)
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 border border-red-200 rounded-full">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span class="text-sm font-semibold text-red-700">Application Rejected</span>
                </div>
            @else
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-100 border border-yellow-200 rounded-full">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-semibold text-yellow-700">Pending Verification</span>
                </div>
            @endif
        </div>
        <p class="text-gray-600 mt-1">Here's what's happening with your practice today.</p>
    </div>

    <!-- Rejection Notice -->
    @if(auth()->user()->lawyerProfile->is_rejected)
        <div class="bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-300 rounded-2xl p-4 sm:p-6 mb-6">
            <div class="flex items-start gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-base sm:text-lg font-bold text-red-900 mb-2">Profile Application Rejected</h3>
                    <p class="text-sm text-gray-700 leading-relaxed mb-3">
                        Your lawyer profile application was not approved. If you believe this is an error, please contact us or wait for our team to reach out via email with further instructions.
                    </p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Verification Notice -->
    @if(!auth()->user()->lawyerProfile->is_verified && !auth()->user()->lawyerProfile->is_rejected)
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-2xl p-4 sm:p-6 mb-6">
            <div class="flex items-start gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">Account Under Review</h3>
                    <p class="text-sm text-gray-700 leading-relaxed mb-3">
                        Your lawyer profile is currently being reviewed by our admin team. This process typically takes 1-3 business days. You'll receive an email notification once your account is verified.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <div class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Profile submitted</span>
                        </div>
                        <div class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>IBP credentials uploaded</span>
                        </div>
                        <div class="inline-flex items-center gap-2 text-sm text-yellow-600 font-medium">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Admin review in progress</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                    Each consultation represents a single service request (chat, video, or document review). Manage requests, schedule sessions, and provide legal assistance.
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <!-- Pending Requests -->
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Pending Requests</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_requests'] }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Schedule -->
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Today's Schedule</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">{{ $stats['today_consultations'] }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Document Requests -->
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Document Requests</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_documents'] }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Month Earnings -->
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">₱{{ number_format($stats['month_earnings'], 0) }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-accent-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings Summary -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Earnings Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-4 bg-gray-50 rounded-xl">
                <p class="text-sm font-medium text-gray-600 mb-2">Today</p>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($stats['today_earnings'], 2) }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl">
                <p class="text-sm font-medium text-gray-600 mb-2">This Week</p>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($stats['week_earnings'], 2) }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl">
                <p class="text-sm font-medium text-gray-600 mb-2">This Month</p>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($stats['month_earnings'], 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Consultations & Documents Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Pending Requests -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Pending Requests</h2>
                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">{{ $pendingRequests->count() }}</span>
            </div>

            @if($pendingRequests->count() > 0)
                <div class="space-y-2">
                    @foreach($pendingRequests->take(3) as $consultation)
                        <a href="{{ route('lawyer.consultation.details', $consultation->id) }}" class="block p-3 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-6 h-6 rounded-full bg-accent-100 flex items-center justify-center text-accent-700 text-xs font-bold">
                                    {{ $consultation->client->initials() }}
                                </div>
                                <span class="text-sm font-medium text-gray-900 truncate">{{ $consultation->client->name }}</span>
                            </div>
                            <p class="text-xs text-gray-600 truncate">{{ $consultation->title }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $consultation->created_at->diffForHumans() }}</p>
                        </a>
                    @endforeach
                </div>
                @if($pendingRequests->count() > 3)
                    <a href="{{ route('lawyer.consultations', ['filter' => 'pending']) }}" class="block text-center text-sm text-primary-600 hover:text-primary-700 font-medium mt-3">
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
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">{{ $todayConsultations->count() }}</span>
            </div>

            @if($todayConsultations->count() > 0)
                <div class="space-y-2">
                    @foreach($todayConsultations->take(3) as $consultation)
                        <a href="{{ route('lawyer.consultation.details', $consultation->id) }}" class="block p-3 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-accent-100 flex items-center justify-center text-accent-700 text-xs font-bold">
                                        {{ $consultation->client->initials() }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 truncate">{{ $consultation->client->name }}</span>
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
                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">{{ $pendingDocumentRequests->count() }}</span>
            </div>

            @if($pendingDocumentRequests->count() > 0)
                <div class="space-y-2">
                    @foreach($pendingDocumentRequests->take(3) as $request)
                        <a href="{{ route('lawyer.document-request.details', $request->id) }}" class="block p-3 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 text-xs font-bold">
                                        {{ $request->client->initials() }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 truncate">{{ $request->client->name }}</span>
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
                @if($pendingDocumentRequests->count() > 3)
                    <a href="{{ route('lawyer.document-requests') }}" class="block text-center text-sm text-primary-600 hover:text-primary-700 font-medium mt-3">
                        View all {{ $pendingDocumentRequests->count() }} requests
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
                        <a href="{{ route('lawyer.consultation.details', $consultation->id) }}" class="block p-3 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-6 h-6 rounded-full bg-accent-100 flex items-center justify-center text-accent-700 text-xs font-bold">
                                    {{ $consultation->client->initials() }}
                                </div>
                                <span class="text-sm font-medium text-gray-900 truncate">{{ $consultation->client->name }}</span>
                            </div>
                            <p class="text-xs text-gray-600 truncate">{{ $consultation->title }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $consultation->scheduled_at->format('M d, g:i A') }}</p>
                        </a>
                    @endforeach
                </div>
                @if($upcomingConsultations->count() > 3)
                    <a href="{{ route('lawyer.consultations', ['filter' => 'scheduled']) }}" class="block text-center text-sm text-primary-600 hover:text-primary-700 font-medium mt-3">
                        View all {{ $upcomingConsultations->count() }} upcoming
                    </a>
                @endif
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-gray-600">No upcoming consultations</p>
                </div>
            @endif
        </div>
    </div>
</div>

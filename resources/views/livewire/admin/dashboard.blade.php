<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="p-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
        <p class="text-gray-600">Monitor platform performance and manage operations</p>
    </div>

    <!-- Stats Grid - 8 Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_users']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $stats['total_lawyers'] }} lawyers, {{ $stats['total_clients'] }} clients
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Verifications -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Verifications</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_verifications'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Lawyers awaiting approval</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Consultations -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Consultations</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_consultations']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $stats['active_consultations'] }} active, {{ $stats['completed_consultations'] }} completed
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Platform Revenue -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Platform Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">₱{{ number_format($stats['total_revenue'], 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        Today: ₱{{ number_format($stats['today_revenue'], 0) }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-accent-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Refunds -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Refunds</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_refunds'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Awaiting review</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Reviews -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Reviews</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_reviews']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        Avg: {{ number_format($stats['avg_rating'], 1) }} ⭐
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Sessions -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Sessions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_sessions'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Currently in progress</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- New Users (Week) -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">New Users (7 Days)</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['new_users_week'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        Today: {{ $stats['new_users_today'] }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Revenue Overview Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Revenue Trend (Last 7 Days)</h2>
            <div class="space-y-4">
                @foreach($revenueTrend as $day)
                    <div class="flex items-center gap-4">
                        <div class="w-20 text-sm font-medium text-gray-600">{{ $day['date'] }}</div>
                        <div class="flex-1">
                            <div class="h-8 bg-gray-100 rounded-lg overflow-hidden">
                                @php
                                    $maxRevenue = collect($revenueTrend)->max('amount');
                                    $percentage = $maxRevenue > 0 ? ($day['amount'] / $maxRevenue) * 100 : 0;
                                @endphp
                                <div class="h-full bg-gradient-to-r from-primary-500 to-accent-500 rounded-lg transition-all duration-500" 
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        <div class="w-32 text-right text-sm font-bold text-gray-900">
                            ₱{{ number_format($day['amount'], 2) }}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-200">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Today</p>
                    <p class="text-xl font-bold text-gray-900">₱{{ number_format($stats['today_revenue'], 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">This Month</p>
                    <p class="text-xl font-bold text-gray-900">₱{{ number_format($stats['month_revenue'], 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">All Time</p>
                    <p class="text-xl font-bold text-gray-900">₱{{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions Panel -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('admin.users') }}" 
                   class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-primary-50 rounded-xl transition group">
                    <div class="w-10 h-10 bg-primary-100 group-hover:bg-primary-200 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900 group-hover:text-primary-700">Manage Users</p>
                        <p class="text-xs text-gray-600">View all users</p>
                    </div>
                </a>

                <a href="{{ route('admin.lawyers') }}" 
                   class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-yellow-50 rounded-xl transition group">
                    <div class="w-10 h-10 bg-yellow-100 group-hover:bg-yellow-200 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900 group-hover:text-yellow-700">Verify Lawyers</p>
                        <p class="text-xs text-gray-600">{{ $stats['pending_verifications'] }} pending</p>
                    </div>
                </a>

                <a href="{{ route('admin.payouts') }}" 
                   class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-green-50 rounded-xl transition group">
                    <div class="w-10 h-10 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900 group-hover:text-green-700">Process Payouts</p>
                        <p class="text-xs text-gray-600">{{ $stats['pending_payouts'] }} pending</p>
                    </div>
                </a>

                <a href="{{ route('admin.refunds') }}" 
                   class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-orange-50 rounded-xl transition group">
                    <div class="w-10 h-10 bg-orange-100 group-hover:bg-orange-200 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900 group-hover:text-orange-700">Review Refunds</p>
                        <p class="text-xs text-gray-600">{{ $stats['pending_refunds'] }} pending</p>
                    </div>
                </a>

                <a href="{{ route('admin.consultations') }}" 
                   class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-blue-50 rounded-xl transition group">
                    <div class="w-10 h-10 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900 group-hover:text-blue-700">Consultations</p>
                        <p class="text-xs text-gray-600">{{ $stats['active_consultations'] }} active</p>
                    </div>
                </a>

                <a href="{{ route('admin.reports') }}" 
                   class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-purple-50 rounded-xl transition group">
                    <div class="w-10 h-10 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900 group-hover:text-purple-700">View Reports</p>
                        <p class="text-xs text-gray-600">Analytics & insights</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- User Growth & Consultation Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- User Growth Chart -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">User Growth (Last 7 Days)</h2>
            <div class="space-y-3">
                @foreach($userGrowth as $day)
                    <div class="flex items-center gap-4">
                        <div class="w-16 text-sm font-medium text-gray-600">{{ $day['date'] }}</div>
                        <div class="flex-1">
                            <div class="h-6 bg-gray-100 rounded-lg overflow-hidden">
                                @php
                                    $maxUsers = collect($userGrowth)->max('count');
                                    $percentage = $maxUsers > 0 ? ($day['count'] / $maxUsers) * 100 : 0;
                                @endphp
                                <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg transition-all duration-500" 
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        <div class="w-12 text-right text-sm font-bold text-gray-900">{{ $day['count'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Consultation Status Breakdown -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Consultation Status</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <span class="font-medium text-gray-900">Pending</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $consultationBreakdown['pending'] }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="font-medium text-gray-900">Scheduled</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $consultationBreakdown['scheduled'] }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="font-medium text-gray-900">In Progress</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $consultationBreakdown['in_progress'] }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-gray-500 rounded-full"></div>
                        <span class="font-medium text-gray-900">Completed</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $consultationBreakdown['completed'] }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-red-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="font-medium text-gray-900">Cancelled</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $consultationBreakdown['cancelled'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health & Activity Timeline -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- System Health -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">System Health</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                        </svg>
                        <span class="font-medium text-gray-900">Database</span>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                        {{ strtoupper($systemHealth['database']) }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                        <span class="font-medium text-gray-900">Storage</span>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                        {{ strtoupper($systemHealth['storage']) }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        <span class="font-medium text-gray-900">Queue</span>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                        {{ strtoupper($systemHealth['queue']) }}
                    </span>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-2">Platform Status</p>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm font-semibold text-green-700">All Systems Operational</span>
                </div>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Recent Activity</h2>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @foreach($activities as $activity)
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                        <div class="w-10 h-10 bg-{{ $activity['color'] }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            @if($activity['icon'] === 'user-plus')
                                <svg class="w-5 h-5 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            @elseif($activity['icon'] === 'calendar')
                                <svg class="w-5 h-5 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @elseif($activity['icon'] === 'currency')
                                <svg class="w-5 h-5 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900">{{ $activity['title'] }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $activity['description'] }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $activity['time']->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Pending Lawyer Verifications -->
    @if($pendingVerifications->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Pending Lawyer Verifications</h2>
            <div class="space-y-4">
                @foreach($pendingVerifications as $lawyer)
                    <div class="p-6 border-2 border-yellow-200 bg-yellow-50 rounded-xl">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-700 font-bold text-lg">
                                    {{ $lawyer->user->initials() }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-lg">{{ $lawyer->user->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $lawyer->user->email }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-yellow-200 text-yellow-800 text-xs font-medium rounded-full">
                                Pending Verification
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-600">IBP Number</p>
                                <p class="font-medium text-gray-900">{{ $lawyer->ibp_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Experience</p>
                                <p class="font-medium text-gray-900">{{ $lawyer->years_experience }} years</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Law School</p>
                                <p class="font-medium text-gray-900">{{ $lawyer->law_school }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Graduation Year</p>
                                <p class="font-medium text-gray-900">{{ $lawyer->graduation_year }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Specializations:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($lawyer->specializations as $spec)
                                    <span class="px-3 py-1 bg-white text-gray-700 text-sm rounded-full border border-gray-200">
                                        {{ $spec->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button 
                                wire:click="verifyLawyer({{ $lawyer->id }})"
                                wire:loading.attr="disabled"
                                class="flex-1 px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition font-medium disabled:opacity-50"
                            >
                                <span wire:loading.remove wire:target="verifyLawyer({{ $lawyer->id }})">✓ Verify Lawyer</span>
                                <span wire:loading wire:target="verifyLawyer({{ $lawyer->id }})">Processing...</span>
                            </button>
                            <a href="{{ route('admin.lawyers') }}" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-center">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Pending Payouts -->
    @if($pendingPayouts->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Pending Payouts</h2>
            <div class="space-y-4">
                @foreach($pendingPayouts as $payout)
                    <div class="p-6 border border-gray-200 rounded-xl hover:border-primary-300 transition">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-accent-100 flex items-center justify-center text-accent-700 font-bold text-lg">
                                    {{ $payout->lawyer->initials() }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-lg">{{ $payout->lawyer->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $payout->lawyer->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($payout->amount, 2) }}</p>
                                <p class="text-sm text-gray-600">{{ $payout->requested_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-600">Bank Name</p>
                                <p class="font-medium text-gray-900">{{ $payout->bank_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Account Number</p>
                                <p class="font-medium text-gray-900">{{ $payout->account_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Account Name</p>
                                <p class="font-medium text-gray-900">{{ $payout->account_name }}</p>
                            </div>
                        </div>

                        <button 
                            wire:click="approvePayout({{ $payout->id }})"
                            wire:loading.attr="disabled"
                            class="w-full px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition font-medium disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="approvePayout({{ $payout->id }})">✓ Approve Payout</span>
                            <span wire:loading wire:target="approvePayout({{ $payout->id }})">Processing...</span>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Users -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Recent Users</h2>
                <a href="{{ route('admin.users') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">View All</a>
            </div>

            <div class="space-y-4">
                @foreach($recentUsers as $user)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:border-primary-300 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-700 font-bold">
                                {{ $user->initials() }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Consultations -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Recent Consultations</h2>
                <a href="{{ route('admin.consultations') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">View All</a>
            </div>

            <div class="space-y-4">
                @foreach($recentConsultations as $consultation)
                    <div class="p-4 border border-gray-200 rounded-xl hover:border-primary-300 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $consultation->client->name }}</p>
                                <p class="text-sm text-gray-600">with {{ $consultation->lawyer->name }}</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                                {{ ucfirst($consultation->status) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span>{{ ucfirst($consultation->consultation_type) }}</span>
                            <span>₱{{ number_format($consultation->total_amount, 2) }}</span>
                            <span>{{ $consultation->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

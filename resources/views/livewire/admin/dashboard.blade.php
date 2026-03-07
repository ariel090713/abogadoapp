<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="p-8">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
    </div>

    <!-- Revenue Summary -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Revenue Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-4 bg-gray-50 rounded-xl">
                <p class="text-sm font-medium text-gray-600 mb-2">Today</p>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($stats['today_revenue'], 2) }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl">
                <p class="text-sm font-medium text-gray-600 mb-2">This Month</p>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($stats['month_revenue'], 2) }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl">
                <p class="text-sm font-medium text-gray-600 mb-2">All Time</p>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($stats['total_revenue'], 2) }}</p>
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

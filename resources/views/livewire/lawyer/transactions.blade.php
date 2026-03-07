<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Transactions & Billing</h1>
            <p class="text-gray-600 mt-1">Manage your earnings and subscription</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button 
                wire:click="setTab('transactions')"
                class="@if($activeTab === 'transactions') border-primary-700 text-primary-700 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition"
            >
                Transactions
            </button>
            <button 
                wire:click="setTab('subscription')"
                class="@if($activeTab === 'subscription') border-primary-700 text-primary-700 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition"
            >
                Manage Subscription
            </button>
        </nav>
    </div>

    @if($activeTab === 'transactions')
        <!-- Earnings Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Earnings</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">₱{{ number_format($totalEarnings, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending Earnings</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">₱{{ number_format($pendingEarnings, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex items-center gap-3">
            <button 
                wire:click="setFilter('all')"
                class="@if($filterType === 'all') bg-primary-700 text-white @else bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700 @endif px-4 py-2 rounded-full text-sm font-medium transition"
            >
                All
            </button>
            <button 
                wire:click="setFilter('consultation_payment')"
                class="@if($filterType === 'consultation_payment') bg-primary-700 text-white @else bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700 @endif px-4 py-2 rounded-full text-sm font-medium transition"
            >
                Consultations
            </button>
            <button 
                wire:click="setFilter('document_drafting')"
                class="@if($filterType === 'document_drafting') bg-primary-700 text-white @else bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-700 @endif px-4 py-2 rounded-full text-sm font-medium transition"
            >
                Documents
            </button>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            @if($transactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Your Earnings</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($transaction->type === 'consultation_payment')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Consultation
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Document
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ₱{{ number_format($transaction->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                        ₱{{ number_format($transaction->lawyer_payout, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($transaction->status === 'completed' || $transaction->status === 'captured')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        @elseif($transaction->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('lawyer.transactions.details', $transaction->id) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-primary-700 text-white text-xs font-medium rounded-lg hover:bg-primary-800 transition">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Your transaction history will appear here</p>
                </div>
            @endif
        </div>

    @else
        <!-- Subscription Management -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="max-w-3xl mx-auto text-center">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Subscription Management</h2>
                <p class="text-gray-600 mb-8">
                    Manage your subscription plan, billing information, and payment methods
                </p>
                
                <!-- Current Plan -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-left">
                            <p class="text-sm text-gray-600">Current Plan</p>
                            <p class="text-xl font-bold text-gray-900">Free Plan</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 text-left">
                        You're currently on the free plan. Upgrade to unlock premium features and increase your visibility.
                    </p>
                </div>

                <!-- Coming Soon Message -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <p class="text-blue-800 font-medium">
                        Premium subscription plans coming soon!
                    </p>
                    <p class="text-blue-600 text-sm mt-2">
                        We're working on exciting premium features to help you grow your practice.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

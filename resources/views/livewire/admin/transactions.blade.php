<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Transactions</h1>
            <p class="text-sm text-gray-600 mt-1">Monitor all platform transactions</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-sm text-gray-600 mb-1">Total Transactions</div>
            <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-sm text-gray-600 mb-1">Completed</div>
            <div class="text-2xl font-bold text-green-600">{{ number_format($stats['completed']) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-sm text-gray-600 mb-1">Pending</div>
            <div class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending']) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-sm text-gray-600 mb-1">Refunded</div>
            <div class="text-2xl font-bold text-red-600">{{ number_format($stats['refunded']) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-sm text-gray-600 mb-1">Total Revenue</div>
            <div class="text-2xl font-bold text-primary-600">₱{{ number_format($stats['total_amount'], 2) }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by name, email, or payment ID..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select wire:model.live="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="refunded">Refunded</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select wire:model.live="typeFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="all">All Types</option>
                    <option value="consultation_payment">Consultation</option>
                    <option value="document_drafting">Document Drafting</option>
                    <option value="refund">Refund</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lawyer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PayMongo ID</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $transaction->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $transaction->created_at->format('M d, Y') }}<br>
                                <span class="text-xs text-gray-400">{{ $transaction->created_at->format('g:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->user)
                                    <div class="text-sm font-medium text-gray-900">{{ $transaction->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $transaction->user->email }}</div>
                                @else
                                    <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->lawyer)
                                    <div class="text-sm font-medium text-gray-900">{{ $transaction->lawyer->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $transaction->lawyer->email }}</div>
                                @else
                                    <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($transaction->type === 'consultation_payment') bg-blue-100 text-blue-800
                                    @elseif($transaction->type === 'document_drafting') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ str_replace('_', ' ', ucfirst($transaction->type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ₱{{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($transaction->status === 'completed') bg-green-100 text-green-800
                                    @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($transaction->status === 'refunded') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $transaction->payment_method ? ucfirst(str_replace('_', ' ', $transaction->payment_method)) : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->paymongo_payment_id)
                                    <div class="text-xs font-mono text-gray-600" title="{{ $transaction->paymongo_payment_id }}">
                                        {{ substr($transaction->paymongo_payment_id, 0, 20) }}...
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Not yet captured</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                No transactions found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $transactions->links() }}
        </div>
    </div>
</div>

<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Lawyer Payouts</h1>
            <p class="text-sm text-gray-600 mt-1">Manage lawyer earnings and payouts</p>
        </div>
        @if(count($eligibleLawyers) > 0)
            <button 
                wire:click="openCreateModal"
                class="inline-flex items-center px-4 py-2 bg-primary-700 hover:bg-primary-800 text-white rounded-lg transition"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Payout Batch
            </button>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-sm text-gray-600 mb-1">Total Payouts</div>
            <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_payouts']) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-sm text-gray-600 mb-1">Pending</div>
            <div class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_payouts']) }}</div>
            <div class="text-xs text-gray-500 mt-1">₱{{ number_format($stats['pending_amount'], 2) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-sm text-gray-600 mb-1">Completed</div>
            <div class="text-2xl font-bold text-green-600">{{ number_format($stats['completed_payouts']) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-sm text-gray-600 mb-1">Total Paid Out</div>
            <div class="text-2xl font-bold text-primary-600">₱{{ number_format($stats['total_amount_paid'], 2) }}</div>
        </div>
    </div>

    <!-- Eligible Lawyers Notice -->
    @if(count($eligibleLawyers) > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-blue-900">{{ count($eligibleLawyers) }} Lawyer(s) Ready for Payout</h3>
                    <p class="text-sm text-blue-700 mt-1">
                        Total amount: ₱{{ number_format(array_sum(array_column($eligibleLawyers, 'total_amount')), 2) }}
                        ({{ array_sum(array_column($eligibleLawyers, 'transaction_count')) }} transactions)
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Lawyer</label>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by lawyer name or email..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select wire:model.live="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Payouts Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lawyer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payouts as $payout)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">#{{ $payout->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $payout->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $payout->lawyer->name }}</div>
                                <div class="text-xs text-gray-500">{{ $payout->lawyer->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                ₱{{ number_format($payout->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $payout->method ? ucfirst(str_replace('_', ' ', $payout->method)) : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($payout->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($payout->status === 'processing')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Processing
                                    </span>
                                @elseif($payout->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Failed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <button 
                                        wire:click="viewDetails({{ $payout->id }})"
                                        class="text-primary-700 hover:text-primary-800"
                                    >
                                        View
                                    </button>
                                    @if($payout->status === 'pending')
                                        <button 
                                            wire:click="openProcessModal({{ $payout->id }})"
                                            class="text-green-600 hover:text-green-700"
                                        >
                                            Process
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                No payouts found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $payouts->links() }}
        </div>
    </div>

    <!-- Create Payout Batch Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Create Payout Batch</h3>
                    <p class="text-sm text-gray-600 mt-1">Select lawyers to include in this payout batch</p>
                </div>

                <div class="p-6">
                    <!-- Select All Button -->
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-sm text-gray-600">{{ count($eligibleLawyers) }} eligible lawyer(s)</p>
                        <button 
                            wire:click="selectAllLawyers"
                            class="text-sm text-primary-700 hover:text-primary-800 font-medium"
                        >
                            Select All
                        </button>
                    </div>

                    <!-- Lawyers List -->
                    <div class="space-y-3 mb-6">
                        @forelse($eligibleLawyers as $item)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 cursor-pointer"
                                 wire:click="toggleLawyer({{ $item['lawyer']->id }})">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <input 
                                            type="checkbox" 
                                            @if(in_array($item['lawyer']->id, $selectedLawyers)) checked @endif
                                            class="w-4 h-4 text-primary-600 rounded"
                                        >
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $item['lawyer']->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $item['lawyer']->email }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-gray-900">₱{{ number_format($item['total_amount'], 2) }}</div>
                                        <div class="text-xs text-gray-500">{{ $item['transaction_count'] }} transaction(s)</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p>No eligible lawyers found</p>
                                <p class="text-sm mt-1">Debug: {{ count($eligibleLawyers) }} items</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea 
                            wire:model="batchNotes"
                            rows="3"
                            placeholder="Add any notes about this payout batch..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        ></textarea>
                    </div>

                    <!-- Summary -->
                    @if(count($selectedLawyers) > 0)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="text-sm font-medium text-blue-900">
                                Selected: {{ count($selectedLawyers) }} lawyer(s)
                            </div>
                            <div class="text-sm text-blue-700 mt-1">
                                Total amount: ₱{{ number_format(
                                    array_sum(array_map(function($item) {
                                        return in_array($item['lawyer']->id, $this->selectedLawyers) ? $item['total_amount'] : 0;
                                    }, $eligibleLawyers)), 2
                                ) }}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
                    <button 
                        wire:click="$set('showCreateModal', false)"
                        class="px-4 py-2 text-gray-700 hover:text-gray-900"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="createBatchPayouts"
                        class="px-4 py-2 bg-primary-700 hover:bg-primary-800 text-white rounded-lg transition"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="createBatchPayouts">Create Payouts</span>
                        <span wire:loading wire:target="createBatchPayouts" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Process Payout Modal -->
    @if($showProcessModal && $processingPayout)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full">
                <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Process Payout</h3>
                    <p class="text-sm text-gray-600 mt-1">Complete the payout to {{ $processingPayout->lawyer->name }}</p>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Payout Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-600">Lawyer</div>
                                <div class="font-medium text-gray-900">{{ $processingPayout->lawyer->name }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Amount</div>
                                <div class="font-semibold text-gray-900">₱{{ number_format($processingPayout->amount, 2) }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Transactions</div>
                                <div class="font-medium text-gray-900">{{ $processingPayout->transactions->count() }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Created</div>
                                <div class="font-medium text-gray-900">{{ $processingPayout->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                        <select wire:model="payoutMethod" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="gcash">GCash</option>
                            <option value="paymaya">PayMaya</option>
                            <option value="other">Other</option>
                        </select>
                        @error('payoutMethod') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Reference Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reference Number *</label>
                        <input 
                            type="text" 
                            wire:model="referenceNumber"
                            placeholder="Enter transaction reference number"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        >
                        @error('referenceNumber') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea 
                            wire:model="processNotes"
                            rows="3"
                            placeholder="Add any notes about this payout..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        ></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
                    <button 
                        wire:click="$set('showProcessModal', false)"
                        class="px-4 py-2 text-gray-700 hover:text-gray-900"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="completePayout"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="completePayout">Complete Payout</span>
                        <span wire:loading wire:target="completePayout" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- View Details Modal -->
    @if($showDetailsModal && $viewingPayout)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Payout Details #{{ $viewingPayout->id }}</h3>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Payout Info -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Lawyer</div>
                            <div class="font-medium text-gray-900">{{ $viewingPayout->lawyer->name }}</div>
                            <div class="text-sm text-gray-500">{{ $viewingPayout->lawyer->email }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Amount</div>
                            <div class="text-2xl font-bold text-gray-900">₱{{ number_format($viewingPayout->amount, 2) }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Status</div>
                            @if($viewingPayout->status === 'completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Completed
                                </span>
                            @elseif($viewingPayout->status === 'processing')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Processing
                                </span>
                            @elseif($viewingPayout->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Failed
                                </span>
                            @endif
                        </div>
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Created</div>
                            <div class="font-medium text-gray-900">{{ $viewingPayout->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        @if($viewingPayout->method)
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Payment Method</div>
                                <div class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $viewingPayout->method)) }}</div>
                            </div>
                        @endif
                        @if($viewingPayout->reference_number)
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Reference Number</div>
                                <div class="font-mono text-sm text-gray-900">{{ $viewingPayout->reference_number }}</div>
                            </div>
                        @endif
                        @if($viewingPayout->processed_at)
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Processed At</div>
                                <div class="font-medium text-gray-900">{{ $viewingPayout->processed_at->format('M d, Y h:i A') }}</div>
                            </div>
                        @endif
                        @if($viewingPayout->processedBy)
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Processed By</div>
                                <div class="font-medium text-gray-900">{{ $viewingPayout->processedBy->name }}</div>
                            </div>
                        @endif
                    </div>

                    @if($viewingPayout->notes)
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Notes</div>
                            <div class="text-sm text-gray-900 bg-gray-50 rounded-lg p-3">{{ $viewingPayout->notes }}</div>
                        </div>
                    @endif

                    <!-- Transactions -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Included Transactions ({{ $viewingPayout->transactions->count() }})</h4>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Type</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Client</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($viewingPayout->transactions as $transaction)
                                        <tr>
                                            <td class="px-4 py-2 text-gray-600">{{ $transaction->created_at->format('M d, Y') }}</td>
                                            <td class="px-4 py-2">
                                                @if($transaction->type === 'consultation_payment')
                                                    <span class="text-blue-600">Consultation</span>
                                                @else
                                                    <span class="text-purple-600">Document</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-gray-900">{{ $transaction->user->name }}</td>
                                            <td class="px-4 py-2 text-right font-medium text-gray-900">₱{{ number_format($transaction->lawyer_payout, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-end">
                    <button 
                        wire:click="$set('showDetailsModal', false)"
                        class="px-4 py-2 text-gray-700 hover:text-gray-900"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

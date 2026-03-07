<div>
    <x-slot name="sidebar">
        <x-admin-sidebar />
    </x-slot>

    <div class="p-4 sm:p-6 space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Refund Management</h1>
        <p class="text-gray-600 mt-1">Review and process refund requests</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Approved</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['approved'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['completed'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Rejected</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['rejected'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Amount</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($stats['total_amount'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by user name, email, or transaction reference..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select 
                    wire:model.live="statusFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="rejected">Rejected</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Refunds Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($refunds as $refund)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $refund->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $refund->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $refund->transaction->reference_number }}</div>
                            <div class="text-xs text-gray-500">
                                @if($refund->consultation_id)
                                    Consultation #{{ $refund->consultation_id }}
                                @elseif($refund->document_request_id)
                                    Document #{{ $refund->document_request_id }}
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">₱{{ number_format($refund->refund_amount, 2) }}</div>
                            <div class="text-xs text-gray-500">of ₱{{ number_format($refund->original_amount, 2) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $refund->getReasonLabel() }}</div>
                            @if($refund->isAutomatic())
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                                    Auto
                                </span>
                            @endif
                            @if($refund->lawyer_id)
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $refund->lawyer_approval_status === 'approved' ? 'bg-green-100 text-green-800' : ($refund->lawyer_approval_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        Lawyer: {{ ucfirst($refund->lawyer_approval_status) }}
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $refund->getStatusBadgeClass() }}">
                                {{ ucfirst($refund->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $refund->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                @if($refund->status === 'pending')
                                    <button 
                                        wire:click="selectRefundForApproval({{ $refund->id }})"
                                        class="px-3 py-1.5 text-white bg-green-600 hover:bg-green-700 rounded text-xs font-medium transition"
                                        title="Approve"
                                    >
                                        Approve
                                    </button>
                                    <button 
                                        wire:click="selectRefundForRejection({{ $refund->id }})"
                                        class="px-3 py-1.5 text-white bg-red-600 hover:bg-red-700 rounded text-xs font-medium transition"
                                        title="Reject"
                                    >
                                        Reject
                                    </button>
                                @elseif($refund->status === 'approved')
                                    <button 
                                        wire:click="selectRefundForProcessing({{ $refund->id }})"
                                        class="px-3 py-1.5 text-white bg-blue-600 hover:bg-blue-700 rounded text-xs font-medium transition"
                                        title="Process Refund"
                                    >
                                        Process
                                    </button>
                                @elseif($refund->status === 'failed')
                                    <button 
                                        wire:click="selectRefundForProcessing({{ $refund->id }})"
                                        class="px-3 py-1.5 text-white bg-orange-600 hover:bg-orange-700 rounded text-xs font-medium transition"
                                        title="Retry Processing"
                                    >
                                        Retry
                                    </button>
                                @endif
                                <button 
                                    wire:click="viewRefundDetails({{ $refund->id }})"
                                    class="px-3 py-1.5 text-white bg-primary-700 hover:bg-primary-800 rounded text-xs font-medium transition"
                                    title="View Details"
                                >
                                    View
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="mt-4 text-sm text-gray-500">No refunds found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($refunds->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $refunds->links() }}
        </div>
        @endif
    </div>

    <!-- Approve Modal -->
    @if($showApproveModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full">
            <div class="px-6 pt-6 pb-4">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Approve Refund</h3>
                <p class="text-sm text-gray-600 mb-4">Confirm that you want to approve this refund request.</p>
                
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-900">
                            Once approved, the refund will be queued for processing. The client will be notified of the approval.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes (Optional)</label>
                        <textarea 
                            wire:model="adminNotes"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Add any internal notes about this approval..."
                        ></textarea>
                        @error('adminNotes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 flex gap-3 justify-end rounded-b-2xl">
                <button 
                    wire:click="$set('showApproveModal', false)"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    Cancel
                </button>
                <button 
                    wire:click="approveRefund"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700"
                >
                    <span wire:loading.remove wire:target="approveRefund">Approve Refund</span>
                    <span wire:loading wire:target="approveRefund" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Approving...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Reject Modal -->
    @if($showRejectModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full">
            <div class="px-6 pt-6 pb-4">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Reject Refund</h3>
                <p class="text-sm text-gray-600 mb-4">Please provide a reason for rejecting this refund request.</p>
                
                <div class="space-y-4">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm text-red-900">
                            The client will be notified of the rejection along with your reason. Please be clear and professional.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                        <textarea 
                            wire:model="rejectionReason"
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Explain why this refund request is being rejected..."
                        ></textarea>
                        @error('rejectionReason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Minimum 10 characters required</p>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 flex gap-3 justify-end rounded-b-2xl">
                <button 
                    wire:click="$set('showRejectModal', false)"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    Cancel
                </button>
                <button 
                    wire:click="rejectRefund"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
                >
                    <span wire:loading.remove wire:target="rejectRefund">Reject Refund</span>
                    <span wire:loading wire:target="rejectRefund" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Rejecting...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Refund Details Modal -->
    @if($showDetailsModal && $selectedRefund)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <h2 class="text-2xl font-bold text-gray-900">Refund Details</h2>
                <button 
                    wire:click="closeDetailsModal"
                    class="text-gray-400 hover:text-gray-600 transition"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <!-- Status Badge -->
                <div class="flex items-center justify-between">
                    <span class="px-4 py-2 rounded-full text-sm font-medium {{ $selectedRefund->getStatusBadgeClass() }}">
                        {{ ucfirst($selectedRefund->status) }}
                    </span>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Refund Amount</p>
                        <p class="text-2xl font-bold text-gray-900">₱{{ number_format($selectedRefund->refund_amount, 2) }}</p>
                    </div>
                </div>

                <!-- User Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">User Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Name</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Transaction Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Transaction Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Reference Number</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->transaction->reference_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Original Amount</p>
                            <p class="font-medium text-gray-900">₱{{ number_format($selectedRefund->original_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Refund Type</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($selectedRefund->refund_type) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Transaction Date</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->transaction->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Refund Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Refund Information</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Reason</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->getReasonLabel() }}</p>
                        </div>
                        @if($selectedRefund->detailed_reason)
                        <div>
                            <p class="text-sm text-gray-600">Detailed Explanation</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->detailed_reason }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-600">Request Date</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @if($selectedRefund->approved_at)
                        <div>
                            <p class="text-sm text-gray-600">Approved Date</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->approved_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @endif
                        @if($selectedRefund->approvedBy)
                        <div>
                            <p class="text-sm text-gray-600">Approved By</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->approvedBy->name }}</p>
                        </div>
                        @endif
                        @if($selectedRefund->admin_notes)
                        <div>
                            <p class="text-sm text-gray-600">Admin Notes</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->admin_notes }}</p>
                        </div>
                        @endif
                        @if($selectedRefund->status === 'rejected' && $selectedRefund->rejection_reason)
                        <div>
                            <p class="text-sm text-red-600">Rejection Reason</p>
                            <p class="font-medium text-red-900">{{ $selectedRefund->rejection_reason }}</p>
                        </div>
                        @endif
                        @if($selectedRefund->processed_at)
                        <div>
                            <p class="text-sm text-gray-600">Processed Date</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->processed_at ? $selectedRefund->processed_at->format('M d, Y h:i A') : 'Not yet processed' }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Lawyer Approval Information -->
                @if($selectedRefund->lawyer_id)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Lawyer Response</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Lawyer</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->lawyer->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Approval Status</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $selectedRefund->lawyer_approval_status === 'approved' ? 'bg-green-100 text-green-800' : ($selectedRefund->lawyer_approval_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($selectedRefund->lawyer_approval_status) }}
                            </span>
                        </div>
                        @if($selectedRefund->lawyer_notes)
                        <div>
                            <p class="text-sm text-gray-600">Lawyer's Notes</p>
                            <div class="mt-2 p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-900">{{ $selectedRefund->lawyer_notes }}</p>
                            </div>
                        </div>
                        @endif
                        @if($selectedRefund->lawyer_responded_at)
                        <div>
                            <p class="text-sm text-gray-600">Response Date</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->lawyer_responded_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @endif
                        @if($selectedRefund->lawyer_approval_status === 'pending')
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                ⏳ Waiting for lawyer's response. The lawyer has been notified and will review this refund request.
                            </p>
                        </div>
                        @elseif($selectedRefund->lawyer_approval_status === 'rejected')
                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-800">
                                ⚠️ Lawyer has rejected this refund request. Please review both sides and make a final decision.
                            </p>
                        </div>
                        @elseif($selectedRefund->lawyer_approval_status === 'approved')
                        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-800">
                                ✓ Lawyer has approved this refund request. You can proceed with processing.
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Related Service -->
                @if($selectedRefund->consultation_id)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Related Consultation</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Consultation ID</p>
                            <p class="font-medium text-gray-900">#{{ $selectedRefund->consultation_id }}</p>
                        </div>
                        @if($selectedRefund->consultation)
                        <div>
                            <p class="text-sm text-gray-600">Type</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($selectedRefund->consultation->consultation_type) }}</p>
                        </div>
                        @endif
                    </div>
                    <div class="mt-4">
                        <a 
                            href="{{ route('admin.consultation.details', $selectedRefund->consultation_id) }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition text-sm font-medium"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            View Consultation Details
                        </a>
                    </div>
                </div>
                @endif

                @if($selectedRefund->document_request_id)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Related Document Request</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Request ID</p>
                            <p class="font-medium text-gray-900">#{{ $selectedRefund->document_request_id }}</p>
                        </div>
                        @if($selectedRefund->documentRequest)
                        <div>
                            <p class="text-sm text-gray-600">Document Type</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->documentRequest->documentTemplate->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($selectedRefund->documentRequest->status) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Request Date</p>
                            <p class="font-medium text-gray-900">{{ $selectedRefund->documentRequest->created_at->format('M d, Y') }}</p>
                        </div>
                        @endif
                    </div>
                    @if($selectedRefund->documentRequest)
                    <div class="mt-4">
                        <a 
                            href="{{ route('lawyer.document-request.details', $selectedRefund->document_request_id) }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition text-sm font-medium"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            View Document Request
                        </a>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-end rounded-b-2xl">
                <button 
                    wire:click="closeDetailsModal"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

    {{-- Process Confirmation Modal --}}
    @if($showProcessModal && $selectedRefund)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Confirm Refund Processing</h3>
                        <p class="text-sm text-gray-600 mt-1">This will initiate the refund with PayMongo</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 space-y-2 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Client:</span>
                        <span class="font-medium text-gray-900">{{ $selectedRefund->user->name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Amount:</span>
                        <span class="font-semibold text-gray-900">₱{{ number_format($selectedRefund->refund_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Transaction:</span>
                        <span class="font-mono text-xs text-gray-700">{{ $selectedRefund->transaction->reference_number }}</span>
                    </div>
                </div>

                <p class="text-sm text-gray-600 mb-6">
                    The refund will be processed through PayMongo and credited back to the client's payment method within 5-10 business days.
                </p>

                <div class="flex gap-3">
                    <button
                        wire:click="closeProcessModal"
                        class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="confirmProcessRefund"
                        class="flex-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="confirmProcessRefund">Process Refund</span>
                        <span wire:loading wire:target="confirmProcessRefund">Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

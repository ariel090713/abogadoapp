<x-slot name="sidebar">
    <x-client-sidebar />
</x-slot>

<div class="p-4 sm:p-6 space-y-6">
    <!-- Header with Back Button -->
    <div class="flex items-center gap-4">
        <a href="{{ route('client.transactions') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Transaction Details</h1>
            <p class="text-gray-600 mt-1">Reference: {{ $transaction->reference_number }}</p>
        </div>
    </div>

    <!-- Transaction Info Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 space-y-6">
            <!-- Status Badge -->
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    @if($transaction->status === 'completed' || $transaction->status === 'captured')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mt-1">
                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Completed
                        </span>
                    @elseif($transaction->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 mt-1">
                            <svg class="w-4 h-4 mr-1.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Pending
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 mt-1">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Amount Paid</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">₱{{ number_format($transaction->amount, 2) }}</p>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Transaction Date -->
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Transaction Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $transaction->created_at->format('F d, Y h:i A') }}</dd>
                    </div>

                    <!-- Transaction Type -->
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Type</dt>
                        <dd class="mt-1">
                            @if($transaction->type === 'consultation_payment')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Consultation Payment
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    Document Drafting
                                </span>
                            @endif
                        </dd>
                    </div>

                    <!-- Lawyer -->
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Lawyer</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($transaction->consultation)
                                {{ $transaction->consultation->lawyer->name }}
                            @elseif($transaction->documentRequest)
                                {{ $transaction->documentRequest->lawyer->name }}
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Payment Method</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $transaction->payment_method ?? 'N/A')) }}</dd>
                    </div>

                    <!-- Reference Number -->
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Reference Number</dt>
                        <dd class="mt-1 text-sm font-mono text-gray-900">{{ $transaction->reference_number }}</dd>
                    </div>

                    <!-- PayMongo Payment Intent ID -->
                    @if($transaction->paymongo_payment_intent_id)
                    <div>
                        <dt class="text-sm font-medium text-gray-600">PayMongo Payment Intent ID</dt>
                        <dd class="mt-1 text-xs font-mono text-gray-900 break-all">{{ $transaction->paymongo_payment_intent_id }}</dd>
                    </div>
                    @endif

                    <!-- PayMongo Payment ID -->
                    @if($transaction->paymongo_payment_id)
                    <div>
                        <dt class="text-sm font-medium text-gray-600">PayMongo Payment ID</dt>
                        <dd class="mt-1 text-xs font-mono text-gray-900 break-all">{{ $transaction->paymongo_payment_id }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Download Actions -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row gap-3">
                <button 
                    wire:click="downloadInvoice"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="downloadInvoice">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" wire:loading wire:target="downloadInvoice">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="downloadInvoice">Download Invoice</span>
                    <span wire:loading wire:target="downloadInvoice">Generating...</span>
                </button>
                
                @if($transaction->status === 'completed' || $transaction->status === 'captured')
                <button 
                    wire:click="downloadReceipt"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-primary-700 hover:bg-primary-800 transition"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="downloadReceipt">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" wire:loading wire:target="downloadReceipt">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="downloadReceipt">Download Receipt</span>
                    <span wire:loading wire:target="downloadReceipt">Generating...</span>
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Refund Information -->
    @if($transaction->refund)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                @if($transaction->refund->status === 'completed')
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                @elseif($transaction->refund->status === 'rejected')
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                @else
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Refund Request</h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->refund->getStatusBadgeClass() }}">
                            {{ ucfirst($transaction->refund->status) }}
                        </span>
                    </div>
                    @if($transaction->refund->lawyer_id)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Lawyer Response:</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->refund->lawyer_approval_status === 'approved' ? 'bg-green-100 text-green-800' : ($transaction->refund->lawyer_approval_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($transaction->refund->lawyer_approval_status) }}
                        </span>
                    </div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Refund Amount:</span>
                        <span class="font-semibold text-gray-900">₱{{ number_format($transaction->refund->refund_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Reason:</span>
                        <span class="text-gray-900">{{ $transaction->refund->getReasonLabel() }}</span>
                    </div>
                    @if($transaction->refund->detailed_reason)
                    <div class="pt-2 border-t border-gray-200">
                        <p class="text-sm text-gray-600 mb-1">Details:</p>
                        <p class="text-sm text-gray-900">{{ $transaction->refund->detailed_reason }}</p>
                    </div>
                    @endif
                    @if($transaction->refund->lawyer_notes)
                    <div class="pt-2 border-t border-gray-200">
                        <p class="text-sm text-gray-600 mb-1">Lawyer's Response:</p>
                        <p class="text-sm text-gray-900">{{ $transaction->refund->lawyer_notes }}</p>
                    </div>
                    @endif
                    @if($transaction->refund->status === 'rejected' && $transaction->refund->rejection_reason)
                    <div class="pt-2 border-t border-gray-200">
                        <p class="text-sm text-red-600 mb-1">Rejection Reason:</p>
                        <p class="text-sm text-red-900">{{ $transaction->refund->rejection_reason }}</p>
                    </div>
                    @endif
                    
                    {{-- Only show status messages if refund is not rejected by admin --}}
                    @if($transaction->refund->status !== 'rejected')
                        @if($transaction->refund->status === 'completed')
                        <div class="pt-2 border-t border-gray-200">
                            <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-xs text-green-800">
                                    ✅ Your refund has been completed! The amount has been credited back to your payment method. Please allow 5-10 business days for it to reflect in your account.
                                </p>
                            </div>
                        </div>
                        @elseif($transaction->refund->status === 'processing')
                        <div class="pt-2 border-t border-gray-200">
                            <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-xs text-blue-800">
                                    🔄 Your refund is being processed. The amount will be credited back to your payment method within 5-10 business days.
                                </p>
                            </div>
                        </div>
                        @elseif($transaction->refund->status === 'approved')
                        <div class="pt-2 border-t border-gray-200">
                            <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-xs text-green-800">
                                    ✓ Admin has approved your refund request! The refund will be processed shortly.
                                </p>
                            </div>
                        </div>
                        @elseif($transaction->refund->lawyer_approval_status === 'pending')
                        <div class="pt-2 border-t border-gray-200">
                            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-xs text-yellow-800">
                                    ⏳ Waiting for lawyer's response. You will be notified once the lawyer reviews your request.
                                </p>
                            </div>
                        </div>
                        @elseif($transaction->refund->lawyer_approval_status === 'rejected')
                        <div class="pt-2 border-t border-gray-200">
                            <div class="p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                <p class="text-xs text-orange-800">
                                    ℹ️ The lawyer has concerns about this refund. Our admin team is reviewing both sides and will make a final decision.
                                </p>
                            </div>
                        </div>
                        @elseif($transaction->refund->lawyer_approval_status === 'approved')
                        <div class="pt-2 border-t border-gray-200">
                            <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-xs text-green-800">
                                    ✓ The lawyer has approved your refund request. Admin is processing it now.
                                </p>
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    @elseif($this->canRequestRefund)
    <!-- Request Refund Button -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Need a Refund?</h3>
                <p class="text-sm text-gray-600 mt-1">If you have issues with this transaction, you can request a refund.</p>
            </div>
            <button 
                wire:click="$set('showRefundModal', true)"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-accent-600 hover:bg-accent-700 transition"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
                Request Refund
            </button>
        </div>
    </div>
    @elseif($this->refundIneligibilityReason)
    <!-- Refund Not Available Notice -->
    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Refund Not Available</h3>
                <p class="text-sm text-gray-600 mt-1">{{ $this->refundIneligibilityReason }}</p>
                @if($transaction->payout_id)
                    <p class="text-sm text-gray-600 mt-2">
                        For assistance with this transaction, please 
                        <a href="{{ route('contact-us') }}" class="text-primary-600 hover:text-primary-700 font-medium">contact our support team</a>.
                    </p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Refund Request Modal -->
    @if($showRefundModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
                <div class="px-6 pt-6 pb-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Request Refund</h3>
                    <p class="text-sm text-gray-600 mb-4">Please provide details about why you're requesting a refund.</p>
                    
                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-sm font-medium text-blue-900 mb-2">📋 Refund Review Process</p>
                        <p class="text-sm text-blue-800">Your refund request will be reviewed by our admin team. You'll be notified once a decision is made. Approved refunds are typically processed within 5-10 business days.</p>
                    </div>
                    
                    <!-- Refund Reason -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Refund</label>
                        <select 
                            wire:model="refundReason"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        >
                            <option value="">Select a reason</option>
                            <option value="document_not_delivered">Document not delivered on time</option>
                            <option value="dispute">Dispute/Complaint with service</option>
                            <option value="other">Other reason</option>
                        </select>
                        @error('refundReason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Detailed Explanation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Detailed Explanation</label>
                        <textarea 
                            wire:model="refundDetails"
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Please explain your situation in detail..."
                        ></textarea>
                        @error('refundDetails')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Minimum 20 characters required</p>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 flex gap-3 justify-end rounded-b-2xl">
                    <button 
                        type="button"
                        wire:click="$set('showRefundModal', false)"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="submitRefundRequest"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700"
                    >
                        <span wire:loading.remove wire:target="submitRefundRequest">Submit Request</span>
                        <span wire:loading wire:target="submitRefundRequest" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Submitting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Related Information -->
    @if($transaction->consultation)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Consultation Details</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Consultation ID</span>
                <span class="text-sm font-medium text-gray-900">#{{ $transaction->consultation->id }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Type</span>
                <span class="text-sm font-medium text-gray-900">{{ ucfirst($transaction->consultation->type) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Status</span>
                <span class="text-sm font-medium text-gray-900">{{ ucfirst($transaction->consultation->status) }}</span>
            </div>
            <div class="pt-3 border-t border-gray-200">
                <a href="{{ route('client.consultation.details', $transaction->consultation->id) }}" class="text-sm text-primary-700 hover:text-primary-800 font-medium">
                    View Consultation →
                </a>
            </div>
        </div>
    </div>
    @endif

    @if($transaction->documentRequest)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Document Request Details</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Request ID</span>
                <span class="text-sm font-medium text-gray-900">#{{ $transaction->documentRequest->id }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Document Type</span>
                <span class="text-sm font-medium text-gray-900">{{ $transaction->documentRequest->documentTemplate->name ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Status</span>
                <span class="text-sm font-medium text-gray-900">{{ ucfirst($transaction->documentRequest->status) }}</span>
            </div>
            <div class="pt-3 border-t border-gray-200">
                <a href="{{ route('client.document.details', $transaction->documentRequest->id) }}" class="text-sm text-primary-700 hover:text-primary-800 font-medium">
                    View Document Request →
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

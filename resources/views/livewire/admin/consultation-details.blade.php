<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="p-4 sm:p-6 lg:p-8">
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.consultations') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Consultation #{{ $consultation->id }}</h1>
            </div>
            <p class="mt-1 text-sm sm:text-base text-gray-600">Complete details and admin actions</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
            @if($consultation->client)
                <button 
                    wire:click="openNotifyModal('client')" 
                    class="w-full sm:w-auto px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm sm:text-base"
                >
                    Notify Client
                </button>
            @endif
            
            @if($consultation->lawyer)
                <button 
                    wire:click="openNotifyModal('lawyer')" 
                    class="w-full sm:w-auto px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm sm:text-base"
                >
                    Notify Lawyer
                </button>
            @endif
            
            @if(in_array($consultation->status, ['pending', 'accepted', 'scheduled', 'payment_pending']))
                <button 
                    wire:click="openCancelModal" 
                    class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm sm:text-base"
                >
                    Cancel Consultation
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Information --}}
            <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Consultation ID</p>
                        <p class="text-base font-medium text-gray-900">#{{ $consultation->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Type</p>
                        <p class="text-base font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $consultation->consultation_type)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="inline-block px-3 py-1 text-sm font-medium rounded-full
                            {{ $consultation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $consultation->status === 'accepted' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $consultation->status === 'scheduled' ? 'bg-indigo-100 text-indigo-800' : '' }}
                            {{ $consultation->status === 'in_progress' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $consultation->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $consultation->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $consultation->status === 'payment_pending' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $consultation->status === 'payment_processing' ? 'bg-blue-100 text-blue-800 animate-pulse' : '' }}
                        ">
                            @if($consultation->status === 'payment_processing')
                                Payment Processing...
                            @else
                                {{ ucfirst(str_replace('_', ' ', $consultation->status)) }}
                            @endif
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Amount</p>
                        <p class="text-base font-medium text-gray-900">₱{{ number_format($consultation->total_amount ?? 0, 2) }}</p>
                    </div>
                    @if($consultation->scheduled_at)
                        <div>
                            <p class="text-sm text-gray-600">Scheduled At</p>
                            <p class="text-base font-medium text-gray-900">{{ $consultation->scheduled_at->format('M d, Y h:i A') }}</p>
                        </div>
                    @endif
                    @if($consultation->payment_deadline)
                        <div>
                            <p class="text-sm text-gray-600">Payment Deadline</p>
                            <p class="text-base font-medium text-gray-900">{{ $consultation->payment_deadline->format('M d, Y h:i A') }}</p>
                        </div>
                    @endif
                    @if($consultation->lawyer_response_deadline)
                        <div>
                            <p class="text-sm text-gray-600">Lawyer Response Deadline</p>
                            <p class="text-base font-medium text-gray-900">{{ $consultation->lawyer_response_deadline->format('M d, Y h:i A') }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600">Created At</p>
                        <p class="text-base font-medium text-gray-900">{{ $consultation->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>

            {{-- Client Notes --}}
            @if($consultation->client_notes)
                <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Client Notes</h2>
                    <p class="text-sm sm:text-base text-gray-700">{{ $consultation->client_notes }}</p>
                </div>
            @endif

            {{-- Lawyer Notes --}}
            @if($consultation->lawyer_notes)
                <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Lawyer Notes</h2>
                    <p class="text-sm sm:text-base text-gray-700">{{ $consultation->lawyer_notes }}</p>
                </div>
            @endif

            {{-- Cancellation Info --}}
            @if($consultation->status === 'cancelled' && $consultation->cancellation_reason)
                <div class="bg-red-50 border border-red-200 rounded-2xl p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-bold text-red-900 mb-4">Cancellation Information</h2>
                    <p class="text-sm sm:text-base text-red-700">{{ $consultation->cancellation_reason }}</p>
                    @if($consultation->cancelled_at)
                        <p class="text-sm text-red-600 mt-2">Cancelled at: {{ $consultation->cancelled_at->format('M d, Y h:i A') }}</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="space-y-6">
            {{-- Client Information --}}
            @if($consultation->client)
                <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Client</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Name</p>
                            <p class="text-base font-medium text-gray-900">{{ $consultation->client->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-base font-medium text-gray-900">{{ $consultation->client->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Phone</p>
                            <p class="text-base font-medium text-gray-900">{{ $consultation->client->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Lawyer Information --}}
            @if($consultation->lawyer)
                <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Lawyer</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Name</p>
                            <p class="text-base font-medium text-gray-900">{{ $consultation->lawyer->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-base font-medium text-gray-900">{{ $consultation->lawyer->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Phone</p>
                            <p class="text-base font-medium text-gray-900">{{ $consultation->lawyer->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Transaction Information --}}
            @if($consultation->transaction)
                <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Transaction</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Transaction ID</p>
                            <p class="text-base font-medium text-gray-900">#{{ $consultation->transaction->id }}</p>
                        </div>
                        @if($consultation->transaction->paymongo_payment_intent_id)
                            <div>
                                <p class="text-sm text-gray-600">PayMongo ID</p>
                                <p class="text-xs sm:text-sm font-mono text-gray-900 break-all">{{ $consultation->transaction->paymongo_payment_intent_id }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-600">Amount</p>
                            <p class="text-base font-medium text-gray-900">₱{{ number_format($consultation->transaction->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded-full
                                {{ $consultation->transaction->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $consultation->transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $consultation->transaction->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                            ">
                                {{ ucfirst($consultation->transaction->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Cancel Modal --}}
    @if($showCancelModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>
                
                <div class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full p-4 sm:p-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Cancel Consultation</h2>
                    <p class="text-sm sm:text-base text-gray-600 mb-6">Provide a reason for cancelling this consultation</p>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cancellation Reason</label>
                        <textarea 
                            wire:model="cancelReason" 
                            rows="4" 
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Enter the reason for cancellation..."
                        ></textarea>
                        @error('cancelReason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-end">
                        <button wire:click="closeModal" class="w-full sm:w-auto px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm sm:text-base">
                            Cancel
                        </button>
                        <button 
                            wire:click="cancelConsultation"
                            class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm sm:text-base"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                        >
                            <span wire:loading.remove wire:target="cancelConsultation">Confirm Cancellation</span>
                            <span wire:loading wire:target="cancelConsultation">Processing...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Notify Modal --}}
    @if($showNotifyModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>
                
                <div class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full p-4 sm:p-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Send Notification</h2>
                    <p class="text-sm sm:text-base text-gray-600 mb-6">Send a message to the {{ $notifyType === 'client' ? 'client' : 'lawyer' }}</p>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea 
                            wire:model="notifyMessage" 
                            rows="4" 
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Enter your message..."
                        ></textarea>
                        @error('notifyMessage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-end">
                        <button wire:click="closeModal" class="w-full sm:w-auto px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm sm:text-base">
                            Cancel
                        </button>
                        <button 
                            wire:click="sendNotification"
                            class="w-full sm:w-auto px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm sm:text-base"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                        >
                            <span wire:loading.remove wire:target="sendNotification">Send Notification</span>
                            <span wire:loading wire:target="sendNotification">Sending...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

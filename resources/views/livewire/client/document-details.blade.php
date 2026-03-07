<x-slot name="sidebar">
    <x-client-sidebar />
</x-slot>
<div class="w-full px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('client.documents') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Documents Requests
        </a>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $request->document_name }}</h1>
                <p class="text-sm text-gray-500">Request #{{ $request->id }} • {{ $request->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div>
                @if($request->status === 'pending_payment')
                    <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                        Pending Payment
                    </span>
                @elseif($request->status === 'paid')
                    <span class="inline-block px-4 py-2 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                        Paid - Awaiting Start
                    </span>
                @elseif($request->status === 'in_progress')
                    <span class="inline-block px-4 py-2 bg-purple-100 text-purple-800 text-sm font-medium rounded-full">
                        In Progress
                    </span>
                @elseif($request->status === 'completed')
                    <span class="inline-block px-4 py-2 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                        Completed
                    </span>
                @elseif($request->status === 'revision_requested')
                    <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                        Revision Requested
                    </span>
                @elseif($request->status === 'cancelled')
                    <span class="inline-block px-4 py-2 bg-gray-100 text-gray-800 text-sm font-medium rounded-full">
                        Cancelled
                    </span>
                @elseif($request->status === 'expired')
                    <span class="inline-block px-4 py-2 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                        Expired
                    </span>
                @endif
            </div>
        </div>

        <!-- Lawyer Info -->
        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('lawyers.show', $request->lawyer->lawyerProfile->username) }}" 
               class="flex items-center gap-4 flex-1 p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                @if($request->lawyer->profile_photo_url)
                    <img src="{{ $request->lawyer->profile_photo_url }}" 
                        alt="{{ $request->lawyer->name }}"
                        class="w-12 h-12 rounded-lg object-cover">
                @else
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                        <span class="text-primary-600 font-semibold">
                            {{ substr($request->lawyer->name, 0, 1) }}
                        </span>
                    </div>
                @endif
                <div class="flex-1">
                    <p class="font-medium text-gray-900 group-hover:text-primary-700 transition-colors">{{ $request->lawyer->name }}</p>
                    @if($request->lawyer->lawyerProfile)
                        <p class="text-sm text-gray-500">{{ $request->lawyer->lawyerProfile->years_experience }} years of experience</p>
                    @endif
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            <div class="text-right">
                <p class="text-sm text-gray-500">Amount</p>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($request->price, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Payment Pending Alert -->
    @if($request->status === 'pending_payment')
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-yellow-900 mb-2">Payment Required</h3>
                    <p class="text-yellow-800 mb-4">
                        Please complete payment to proceed with your document request. 
                        @if($request->payment_deadline)
                            Payment deadline: <span class="font-medium">{{ $request->payment_deadline->format('M d, Y h:i A') }}</span>
                        @endif
                    </p>
                    <button wire:click="proceedToPayment" 
                        class="px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium">
                        Proceed to Payment
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Completed Document -->
    @if($request->status === 'completed' && $request->draft_document_path)
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-green-900 mb-2">Document Ready!</h3>
                    <p class="text-green-800 mb-4">
                        Your document has been completed and is ready for download.
                        @if($request->completed_at)
                            Completed on {{ $request->completed_at->format('M d, Y h:i A') }}
                        @endif
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button wire:click="downloadDocument" 
                            class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium inline-flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download Document
                        </button>
                        
                        @if($request->revisions_used < $request->revisions_allowed)
                            <button wire:click="$set('showRevisionModal', true)" 
                                class="w-full sm:w-auto px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium inline-flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Request Revision ({{ $request->revisions_allowed - $request->revisions_used }} left)
                            </button>
                        @endif
                    </div>
                    
                    @if($request->revisions_allowed > 0)
                        <p class="text-sm text-green-700 mt-3">
                            Revisions: {{ $request->revisions_used }} of {{ $request->revisions_allowed }} used
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Revision Requested Alert -->
    @if($request->status === 'revision_requested')
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-yellow-900 mb-2">Revision Requested</h3>
                    <p class="text-yellow-800 mb-2">
                        Your revision request has been sent to the lawyer. They will review your notes and provide an updated document.
                    </p>
                    @if($request->revision_notes)
                        <div class="bg-white rounded-lg p-3 mt-3">
                            <p class="text-sm font-medium text-gray-700 mb-1">Your Revision Notes:</p>
                            <p class="text-gray-900">{{ $request->revision_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Revision Request Modal -->
    @if($showRevisionModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Request Document Revision</h3>
                        <button wire:click="$set('showRevisionModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="mb-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-blue-800">
                                <span class="font-medium">Revisions remaining:</span> 
                                {{ $request->revisions_allowed - $request->revisions_used }} of {{ $request->revisions_allowed }}
                            </p>
                        </div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            What changes would you like? <span class="text-red-600">*</span>
                        </label>
                        <textarea wire:model="revisionNotes" rows="6"
                            placeholder="Please describe the changes you need in detail..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea>
                        @error('revisionNotes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        <p class="mt-2 text-sm text-gray-500">Be specific about what needs to be changed or corrected.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button wire:click="$set('showRevisionModal', false)" 
                            class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                            Cancel
                        </button>
                        <button wire:click="requestRevision" 
                            class="w-full sm:w-auto px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed">
                            <span wire:loading.remove wire:target="requestRevision">Submit Revision Request</span>
                            <span wire:loading wire:target="requestRevision" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Submitting...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Timeline -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Timeline</h2>
        
        <div class="space-y-4">
            <!-- Requested -->
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    @if($request->paid_at || $request->started_at || $request->completed_at)
                        <div class="w-0.5 h-12 bg-gray-200"></div>
                    @endif
                </div>
                <div class="flex-1 pb-4">
                    <p class="font-medium text-gray-900">Request Submitted</p>
                    <p class="text-sm text-gray-500">{{ $request->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>

            <!-- Paid -->
            @if($request->paid_at)
                <div class="flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        @if($request->started_at || $request->completed_at)
                            <div class="w-0.5 h-12 bg-gray-200"></div>
                        @endif
                    </div>
                    <div class="flex-1 pb-4">
                        <p class="font-medium text-gray-900">Payment Received</p>
                        <p class="text-sm text-gray-500">{{ $request->paid_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            @endif

            <!-- Started -->
            @if($request->started_at)
                <div class="flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        @if($request->completed_at)
                            <div class="w-0.5 h-12 bg-gray-200"></div>
                        @endif
                    </div>
                    <div class="flex-1 pb-4">
                        <p class="font-medium text-gray-900">Work Started</p>
                        <p class="text-sm text-gray-500">{{ $request->started_at->format('M d, Y h:i A') }}</p>
                        @if($request->completion_deadline && !$request->completed_at)
                            <p class="text-sm text-gray-500 mt-1">
                                Expected completion: {{ $request->completion_deadline->format('M d, Y') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Completed -->
            @if($request->completed_at)
                <div class="flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">Document Completed</p>
                        <p class="text-sm text-gray-500">{{ $request->completed_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Form Data -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Submitted Information</h2>
        
        <div class="space-y-4">
            @foreach($request->form_data as $fieldId => $value)
                @php
                    // Find field definition from service
                    $field = collect($request->service->form_fields['fields'] ?? [])->firstWhere('id', $fieldId);
                @endphp
                
                @if($field)
                    <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                        <p class="text-sm font-medium text-gray-700 mb-1">{{ $field['label'] }}</p>
                        <p class="text-gray-900">{{ $value ?: '(Not provided)' }}</p>
                    </div>
                @endif
            @endforeach
        </div>

        @if($request->client_notes)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-2">Additional Notes</p>
                <p class="text-gray-900">{{ $request->client_notes }}</p>
            </div>
        @endif
    </div>

    <!-- Lawyer Notes (if any) -->
    @if($request->lawyer_notes)
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-6">
            <h3 class="text-lg font-bold text-blue-900 mb-2">Lawyer's Notes</h3>
            <p class="text-blue-800">{{ $request->lawyer_notes }}</p>
        </div>
    @endif

    <!-- Review Section -->
    @if($request->status === 'completed')
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Review</h2>
            
            @if($request->review)
                <!-- Existing Review -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-6 h-6 {{ $i <= $request->review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        @if($request->review->is_edited)
                            <span class="text-xs px-2 py-1 bg-gray-200 text-gray-600 rounded-full font-medium">Edited</span>
                        @endif
                    </div>
                    
                    @if($request->review->comment)
                        <p class="text-gray-700 mb-4">{{ $request->review->comment }}</p>
                    @endif
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500">
                            Reviewed {{ $request->review->created_at->diffForHumans() }}
                        </p>
                        
                        @if($request->review->canEdit())
                            <a href="{{ route('client.review.document', $request->id) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Review
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <!-- Leave Review Button -->
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Share Your Experience</h3>
                    <p class="text-gray-600 mb-6">Help others by reviewing this document service</p>
                    <a href="{{ route('client.review.document', $request->id) }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-primary-700 text-white rounded-lg hover:bg-primary-800 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        Leave a Review
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>

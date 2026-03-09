<x-slot name="sidebar">
    <x-client-sidebar />
</x-slot>

<div>
<div class="p-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ $consultation->parent_consultation_id ? route('client.consultation-thread.details', $consultation->parent_consultation_id) : route('client.consultations') }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ $consultation->parent_consultation_id ? 'Back to Case' : 'Back to My Consultations' }}
        </a>
    </div>

    <!-- Pending Reschedule Banner -->
    <x-pending-reschedule-banner :consultation="$consultation" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-4 flex-1">
                        <!-- Type Icon -->
                        @if($consultation->consultation_type === 'video')
                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @elseif($consultation->consultation_type === 'chat')
                            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                        @elseif($consultation->consultation_type === 'document_review')
                            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        @endif
                        
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $consultation->title }}</h1>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-4 py-2 rounded-full text-sm font-medium
                                    {{ $consultation->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $consultation->status === 'scheduled' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $consultation->status === 'in_progress' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $consultation->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $consultation->status === 'pending_client_acceptance' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $consultation->status === 'awaiting_quote_approval' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $consultation->status === 'payment_pending' ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $consultation->status === 'payment_processing' ? 'bg-blue-100 text-blue-700 animate-pulse' : '' }}
                                    {{ $consultation->status === 'payment_failed' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $consultation->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $consultation->status === 'declined' ? 'bg-gray-100 text-gray-700' : '' }}
                                ">
                                    @if($consultation->status === 'pending_client_acceptance')
                                        Pending Your Approval
                                    @elseif($consultation->status === 'payment_processing')
                                        Payment Processing...
                                    @elseif($consultation->status === 'payment_failed')
                                        Payment Failed
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $consultation->status)) }}
                                    @endif
                                </span>
                                
                                @if(in_array($consultation->status, ['scheduled', 'in_progress']) && 
                                    $consultation->scheduled_at && 
                                    now()->greaterThan($consultation->scheduled_at->copy()->addMinutes($consultation->duration)))
                                    <span class="px-4 py-2 rounded-full text-sm font-medium bg-orange-100 text-orange-700">
                                        Ended - Waiting to be Completed
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Case Link -->
                            @php
                                $mainCase = $consultation->getMainCase();
                            @endphp
                            <div class="mt-3">
                                <a href="{{ route('client.consultation-thread.details', $mainCase->id) }}" 
                                   class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                    View Full Thread
                                </a>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Countdown Timer -->
                @if($consultation->status === 'awaiting_quote_approval' && $consultation->quote_deadline)
                    <div class="mb-4">
                        @livewire('components.countdown-timer', [
                            'deadline' => $consultation->quote_deadline,
                            'label' => 'Respond to quote within',
                            'type' => 'quote_response',
                            'consultationId' => $consultation->id
                        ], key('quote-deadline-'.$consultation->id))
                    </div>
                @endif

                @if($consultation->status === 'payment_pending' && $consultation->payment_deadline)
                    <div class="mb-4">
                        @livewire('components.countdown-timer', [
                            'deadline' => $consultation->payment_deadline,
                            'label' => 'Complete payment within',
                            'type' => 'payment',
                            'consultationId' => $consultation->id
                        ], key('payment-deadline-'.$consultation->id))
                    </div>
                @endif

                @if($consultation->consultation_type === 'document_review' && 
                    in_array($consultation->status, ['scheduled', 'in_progress']) && 
                    $consultation->review_completion_deadline)
                    <div class="mb-4">
                        @livewire('components.countdown-timer', [
                            'deadline' => $consultation->review_completion_deadline,
                            'label' => 'Review completion deadline',
                            'type' => 'review_completion',
                            'consultationId' => $consultation->id
                        ], key('review-deadline-'.$consultation->id))
                    </div>
                @endif

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Type</p>
                        <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $consultation->consultation_type)) }}</p>
                    </div>
                    @if($consultation->duration)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Duration</p>
                            <p class="font-semibold text-gray-900">{{ $consultation->duration }} minutes</p>
                        </div>
                    @endif
                    @if($consultation->scheduled_at)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Scheduled Date</p>
                            <p class="font-semibold text-gray-900">{{ $consultation->scheduled_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Scheduled Time</p>
                            <p class="font-semibold text-gray-900">{{ $consultation->scheduled_at->format('g:i A') }}</p>
                        </div>
                    @endif
                </div>

                @if($consultation->scheduled_at && in_array($consultation->status, ['scheduled', 'payment_pending']))
                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Consultation scheduled for:</p>
                                <p class="text-lg font-bold text-blue-700">{{ $consultation->scheduled_at->format('l, F j, Y') }}</p>
                                <p class="text-base font-semibold text-blue-600">{{ $consultation->scheduled_at->format('g:i A') }} ({{ $consultation->scheduled_at->diffForHumans() }})</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Your Legal Concern -->
            @if($consultation->client_notes)
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Your Legal Concern</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $consultation->client_notes }}</p>
                </div>
            @endif

            <!-- Additional Service Request Info (if this is a follow-up) -->
            @if($consultation->parent_consultation_id)
                @php
                    // Get the service request from the parent consultation that created this follow-up
                    $serviceRequest = \App\Models\ServiceRequest::where('consultation_id', $consultation->parent_consultation_id)
                        ->where('status', 'accepted')
                        ->with('requester')
                        ->orderBy('created_at', 'desc')
                        ->first();
                @endphp
                @if($serviceRequest)
                    <div class="bg-purple-50 rounded-2xl shadow-lg p-6 border border-purple-200">
                        <div class="flex items-start gap-3 mb-4">
                            <svg class="w-6 h-6 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="flex-1">
                                <h2 class="text-xl font-bold text-purple-900 mb-2">Additional Service Request</h2>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-sm font-semibold text-purple-700">Requested by:</span>
                                        <span class="text-sm text-purple-900">{{ $serviceRequest->requester->name }} ({{ $serviceRequest->requester->isLawyer() ? 'Lawyer' : 'Client' }})</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-semibold text-purple-700">Service Type:</span>
                                        <span class="text-sm text-purple-900">{{ ucfirst(str_replace('_', ' ', $serviceRequest->service_type)) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-semibold text-purple-700">Reason:</span>
                                        <p class="text-sm text-purple-900 mt-1 leading-relaxed">{{ $serviceRequest->description }}</p>
                                    </div>
                                    @if($serviceRequest->proposed_price)
                                        <div>
                                            <span class="text-sm font-semibold text-purple-700">Proposed Price:</span>
                                            <span class="text-sm text-purple-900">₱{{ number_format($serviceRequest->proposed_price, 2) }}</span>
                                        </div>
                                    @endif
                                    @if($serviceRequest->proposed_date)
                                        <div>
                                            <span class="text-sm font-semibold text-purple-700">Proposed Date:</span>
                                            <span class="text-sm text-purple-900">{{ $serviceRequest->proposed_date->format('M d, Y g:i A') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Quote Information (if provided) -->
            @if($consultation->quoted_price && $consultation->quote_provided_at)
                <div class="bg-blue-50 rounded-2xl shadow-lg p-6 border border-blue-200">
                    <h2 class="text-xl font-bold text-blue-900 mb-4">Custom Quote from Lawyer</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700 font-medium">Quoted Price:</span>
                            <span class="text-2xl font-bold text-blue-900">₱{{ number_format($consultation->quoted_price, 2) }}</span>
                        </div>
                        @if($consultation->estimated_turnaround_days)
                            <div class="flex justify-between items-center">
                                <span class="text-blue-700 font-medium">Estimated Turnaround:</span>
                                @if($consultation->payment_status === 'paid' && $consultation->review_completion_deadline)
                                    <span class="text-lg font-semibold text-blue-900">
                                        Due: {{ $consultation->review_completion_deadline->format('M d, Y') }}
                                        <span class="text-sm text-blue-600">({{ $consultation->review_completion_deadline->diffForHumans() }})</span>
                                    </span>
                                @else
                                    <span class="text-lg font-semibold text-blue-900">{{ $consultation->estimated_turnaround_days }} {{ $consultation->estimated_turnaround_days == 1 ? 'day' : 'days' }}</span>
                                @endif
                            </div>
                        @endif
                        @if($consultation->quote_notes)
                            <div class="pt-3 border-t border-blue-200">
                                <p class="text-sm text-blue-700 font-medium mb-2">Quote Explanation:</p>
                                <p class="text-blue-800 leading-relaxed whitespace-pre-wrap">{{ $consultation->quote_notes }}</p>
                            </div>
                        @endif
                        <div class="pt-3 border-t border-blue-200">
                            <p class="text-xs text-blue-600">
                                Quote provided: {{ $consultation->quote_provided_at->format('M d, Y g:i A') }}
                            </p>
                            @if($consultation->quote_accepted_at)
                                <p class="text-xs text-green-600 mt-1">
                                    ✓ Quote accepted: {{ $consultation->quote_accepted_at->format('M d, Y g:i A') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Lawyer Notes (if any) -->
            @if($consultation->lawyer_notes)
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Lawyer's Notes</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $consultation->lawyer_notes }}</p>
                </div>
            @endif

            <!-- Decline/Cancel Reason -->
            @if($consultation->decline_reason)
                <div class="bg-red-50 rounded-2xl shadow-lg p-6 border border-red-200">
                    <h2 class="text-xl font-bold text-red-900 mb-4">Decline Reason</h2>
                    <p class="text-red-700 leading-relaxed">{{ $consultation->decline_reason }}</p>
                </div>
            @endif

            @if($consultation->cancel_reason)
                <div class="bg-red-50 rounded-2xl shadow-lg p-6 border border-red-200">
                    <h2 class="text-xl font-bold text-red-900 mb-4">Cancellation Reason</h2>
                    <p class="text-red-700 leading-relaxed">{{ $consultation->cancel_reason }}</p>
                </div>
            @endif
            
            <!-- Document Section (for all consultation types) -->
            @if($uploadedDocuments->count() > 0 || $consultation->consultation_type === 'document_review')
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">
                        @if($consultation->consultation_type === 'document_review')
                            Documents
                        @else
                            Supporting Documents
                        @endif
                    </h2>
                    
                    <!-- Uploaded Documents List -->
                    @if($uploadedDocuments->count() > 0)
                        <div class="mb-6" wire:key="documents-list-{{ $consultation->id }}">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Uploaded Files ({{ $uploadedDocuments->where('deleted_at', null)->count() }})
                                @if($uploadedDocuments->whereNotNull('deleted_at')->count() > 0)
                                    <span class="text-red-600">• {{ $uploadedDocuments->whereNotNull('deleted_at')->count() }} deleted</span>
                                @endif
                            </h3>
                            <div class="space-y-2">
                                @foreach($uploadedDocuments as $doc)
                                    <div class="flex items-center justify-between p-3 rounded-lg border {{ $doc->deleted_at ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200' }}" wire:key="doc-{{ $doc->id }}">
                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                            <svg class="w-8 h-8 {{ $doc->deleted_at ? 'text-red-600' : 'text-blue-600' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($doc->deleted_at)
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                @endif
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium {{ $doc->deleted_at ? 'text-red-900 line-through' : 'text-gray-900' }} truncate">{{ $doc->original_filename }}</p>
                                                @if($doc->deleted_at)
                                                    <p class="text-xs text-red-700">Deleted {{ $doc->deleted_at->format('M d, Y g:i A') }}</p>
                                                @else
                                                    <p class="text-xs text-gray-500">{{ $doc->getFileSizeFormatted() }} • Uploaded {{ $doc->uploaded_at->diffForHumans() }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if(!$doc->deleted_at)
                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                <button 
                                                    wire:click="getDocumentDownloadUrl({{ $doc->id }})"
                                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition cursor-pointer"
                                                    title="Download"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                </button>
                                                @if($consultation->status !== 'completed')
                                                    <button 
                                                        wire:click="confirmDelete({{ $doc->id }})"
                                                        type="button"
                                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer"
                                                        title="Delete"
                                                    >
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">No documents uploaded yet. Please upload your documents for review.</p>
                        </div>
                    @endif
                    
                    <!-- Upload New Documents -->
                    @if(in_array($consultation->status, ['scheduled', 'in_progress', 'pending', 'payment_pending']))
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Upload Documents</h3>
                            <div class="space-y-4">
                                <div>
                                    <input 
                                        type="file" 
                                        wire:model="documents" 
                                        multiple
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                                    >
                                    <p class="mt-2 text-xs text-gray-500">
                                        Accepted formats: PDF, DOC, DOCX, JPG, PNG • Max 10MB per file
                                    </p>
                                    @error('documents.*') 
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div wire:loading wire:target="documents" class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-sm text-blue-800 font-medium">Loading files...</p>
                                </div>
                                
                                @if(!empty($documents))
                                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-sm text-blue-800 font-medium mb-2">Selected files ({{ count($documents) }}):</p>
                                        <ul class="text-xs text-blue-700 space-y-1">
                                            @foreach($documents as $doc)
                                                <li>• {{ $doc->getClientOriginalName() }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                <button 
                                    wire:click="uploadDocuments"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                    wire:target="uploadDocuments"
                                    class="w-full px-4 py-3 bg-primary-700 text-white rounded-lg hover:bg-[#1E40AF] transition font-medium cursor-pointer"
                                    @if(empty($documents)) disabled class="w-full px-4 py-3 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed font-medium" @endif
                                >
                                    <span wire:loading.remove wire:target="uploadDocuments">Upload Documents</span>
                                    <span wire:loading wire:target="uploadDocuments" class="flex items-center justify-center gap-2">
                                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Uploading...
                                    </span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Reviewed Document & Completion Notes (if completed) -->
            @if($consultation->status === 'completed' && ($consultation->reviewed_document_path || $consultation->completion_notes))
                <div class="bg-green-50 rounded-2xl shadow-lg p-6 border border-green-200">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <h2 class="text-lg sm:text-xl font-bold text-green-900">Review Completed</h2>
                    </div>

                    @if($consultation->completed_at)
                        <p class="text-sm text-green-700 mb-4">
                            Completed on {{ $consultation->completed_at->format('M d, Y g:i A') }}
                        </p>
                    @endif

                    @if($consultation->reviewed_document_path)
                        <div class="mb-4">
                            <p class="text-sm font-medium text-green-900 mb-2">Reviewed Document:</p>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 p-4 bg-white rounded-lg border border-green-300">
                                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900">Reviewed Document</p>
                                    <p class="text-sm text-gray-500">Reviewed by {{ $consultation->lawyer->name }}</p>
                                </div>
                                <a href="{{ $consultation->getReviewedDocumentUrl() }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition w-full sm:w-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($consultation->completion_notes)
                        <div>
                            <p class="text-sm font-medium text-green-900 mb-2">Lawyer's Notes:</p>
                            <div class="bg-white rounded-lg border border-green-300 p-4">
                                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $consultation->completion_notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Review Section (if completed) -->
            @if($consultation->status === 'completed')
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    @if($consultation->review)
                        <!-- Existing Review -->
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 mb-1">Your Review</h2>
                                @if($consultation->review->is_edited)
                                    <span class="text-xs text-gray-500">Edited {{ $consultation->review->edited_at->diffForHumans() }}</span>
                                @endif
                            </div>
                            @if($consultation->review->canEdit())
                                <a href="{{ route('client.review.consultation', $consultation->id) }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                                    Edit Review
                                </a>
                            @endif
                        </div>
                        
                        <!-- Rating -->
                        <div class="flex items-center gap-2 mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $consultation->review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="text-sm font-semibold text-gray-700 ml-1">{{ $consultation->review->rating }} out of 5</span>
                        </div>
                        
                        <!-- Comment -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700 leading-relaxed">{{ $consultation->review->comment }}</p>
                        </div>
                        
                        <p class="text-xs text-gray-500 mt-3">
                            Posted {{ $consultation->review->created_at->diffForHumans() }}
                        </p>
                    @else
                        <!-- No Review Yet -->
                        <div class="text-center py-6">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Share Your Experience</h3>
                            <p class="text-gray-600 mb-6">Help others by leaving a review for this consultation</p>
                            <a href="{{ route('client.review.consultation', $consultation->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-700 text-white font-semibold rounded-lg hover:bg-primary-800 transition-colors shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                Leave a Review
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Lawyer Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Lawyer Information</h2>
                <a href="{{ route('lawyers.show', $consultation->lawyer->lawyerProfile->username) }}" 
                   class="flex items-center gap-4 mb-4 p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                    <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xl group-hover:bg-primary-200 transition-colors">
                        {{ $consultation->lawyer->initials() }}
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 group-hover:text-primary-700 transition-colors">{{ $consultation->lawyer->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $consultation->lawyer->lawyerProfile->ibp_number }}</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <div class="pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-500 italic">🚫 No direct contact - All communication through platform</p>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Payment Details</h2>
                <div class="space-y-3">
                    @if($consultation->consultation_type === 'document_review' && !$consultation->quoted_price)
                        <!-- Document Review - Check if auto-accepted or awaiting quote -->
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ $consultation->status === 'payment_pending' && $consultation->estimated_turnaround_days ? 'Fixed Price' : 'Starting Price' }}</span>
                            <span class="font-semibold text-gray-900">₱{{ number_format($consultation->rate, 2) }}</span>
                        </div>
                        @if($consultation->status === 'payment_pending' && $consultation->estimated_turnaround_days)
                            <!-- Auto-accepted document review -->
                            <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                                <p class="text-sm text-green-800 font-semibold mb-1">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Auto-Accepted - Fixed Price
                                </p>
                                <p class="text-sm text-green-800">
                                    Turnaround: {{ $consultation->estimated_turnaround_days }} business days
                                </p>
                                <p class="text-xs text-green-700 mt-1">
                                    Complete payment to start the review process.
                                </p>
                            </div>
                        @else
                            <!-- Manual review - awaiting quote -->
                            <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-sm text-blue-800">
                                    Lawyer will provide final quote after reviewing your document.
                                </p>
                            </div>
                        @endif
                    @elseif($consultation->quoted_price)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Original Fee</span>
                            <span class="text-gray-500 line-through">₱{{ number_format($consultation->rate, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Quoted Fee</span>
                            <span class="font-semibold text-blue-700">₱{{ number_format($consultation->quoted_price, 2) }}</span>
                        </div>
                    @else
                        <div class="flex justify-between">
                            <span class="text-gray-600">Consultation Fee</span>
                            <span class="font-semibold text-gray-900">₱{{ number_format($consultation->rate, 2) }}</span>
                        </div>
                    @endif
                    
                    @if($consultation->total_amount > 0)
                        <div class="flex justify-between pt-3 border-t border-gray-200">
                            <span class="font-semibold text-gray-900">Total Amount</span>
                            <span class="font-bold text-primary-600 text-lg">₱{{ number_format($consultation->total_amount, 2) }}</span>
                        </div>
                    @endif
                    
                    @if($consultation->payment_status === 'paid' && $consultation->transaction)
                        <div class="pt-3 border-t border-gray-200 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Payment Method</span>
                                <span class="font-medium text-gray-900">{{ $consultation->transaction->payment_method ? strtoupper($consultation->transaction->payment_method) : 'Processing...' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Transaction ID</span>
                                <span class="font-mono text-xs text-gray-700">{{ substr($consultation->transaction->paymongo_payment_intent_id, 0, 20) }}...</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Paid At</span>
                                <span class="text-gray-900">{{ $consultation->transaction->processed_at ? $consultation->transaction->processed_at->format('M d, Y g:i A') : 'Processing...' }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                @if($consultation->status === 'payment_pending' && $consultation->payment_deadline)
                    <div class="mt-4 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                        <p class="text-sm text-orange-800 mb-2">
                            <strong>Payment Required</strong>
                        </p>
                        <p class="text-xs text-orange-700 mb-1">
                            Pay until: <strong>{{ $consultation->payment_deadline->format('M d, Y g:i A') }}</strong>
                        </p>
                        <p class="text-xs text-orange-600">
                            Time remaining: <strong>{{ $consultation->payment_deadline->diffForHumans() }}</strong>
                        </p>
                    </div>
                @elseif($consultation->status === 'accepted' && $consultation->payment_status === 'unpaid' && $consultation->payment_deadline)
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-xs text-blue-700 mb-1">
                            Pay until: <strong>{{ $consultation->payment_deadline->format('M d, Y g:i A') }}</strong>
                        </p>
                        <p class="text-xs text-blue-600">
                            Time remaining: <strong>{{ $consultation->payment_deadline->diffForHumans() }}</strong>
                        </p>
                    </div>
                @elseif($consultation->payment_status === 'paid')
                    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p class="text-sm font-medium text-green-800">Payment Completed</p>
                        </div>
                    </div>
                @endif
                
                <!-- Lawyer Offer - Accept/Decline Buttons -->
                @if($consultation->status === 'pending_client_acceptance' && $consultation->initiated_by === 'lawyer')
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm font-medium text-gray-700 mb-3">Actions Required</p>
                        <div class="space-y-2">
                            <button 
                                wire:click="$set('showAcceptQuoteModal', true)"
                                class="w-full bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition"
                            >
                                @if(empty($consultation->quoted_price) || $consultation->quoted_price == 0)
                                    Accept Free Service
                                @else
                                    Accept & Pay
                                @endif
                            </button>
                            <button 
                                wire:click="$set('showDeclineQuoteModal', true)"
                                class="w-full bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition"
                            >
                                Decline Offer
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Chat/Video Button (for scheduled/in_progress consultations) -->
                @if(in_array($consultation->status, ['scheduled', 'in_progress', 'accepted']) && 
                    in_array($consultation->consultation_type, ['chat', 'video']))
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        @if($consultation->consultation_type === 'video')
                        <a 
                            href="{{ route('client.consultation.video', $consultation) }}"
                            class="flex items-center justify-center gap-2 w-full bg-blue-900 text-white px-4 py-3 rounded-lg text-sm font-medium hover:bg-blue-800 transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Join Video Consultation
                        </a>
                        <p class="text-xs text-gray-500 text-center mt-2">
                            Video call with chat available
                        </p>
                        @else
                        <a 
                            href="{{ route('client.consultation.chat', $consultation) }}"
                            class="flex items-center justify-center gap-2 w-full bg-blue-900 text-white px-4 py-3 rounded-lg text-sm font-medium hover:bg-blue-800 transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Open Chat
                        </a>
                        <p class="text-xs text-gray-500 text-center mt-2">
                            Message your lawyer in real-time
                        </p>
                        @endif
                    </div>
                @endif

                <!-- Countdown Timers -->
                @if($consultation->status === 'awaiting_quote_approval' && $consultation->quote_deadline)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        @livewire('components.countdown-timer', [
                            'deadline' => $consultation->quote_deadline,
                            'label' => 'Respond to quote within',
                            'type' => 'quote_response',
                            'consultationId' => $consultation->id
                        ], key('quote-deadline-sidebar-'.$consultation->id))
                    </div>
                @endif

                @if($consultation->status === 'payment_pending' && $consultation->payment_deadline)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        @livewire('components.countdown-timer', [
                            'deadline' => $consultation->payment_deadline,
                            'label' => 'Complete payment within',
                            'type' => 'payment',
                            'consultationId' => $consultation->id
                        ], key('payment-deadline-sidebar-'.$consultation->id))
                    </div>
                @endif

                <!-- Action Buttons -->
                @if($consultation->status === 'awaiting_quote_approval')
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm font-medium text-gray-700 mb-3">Actions Required</p>
                        <div class="space-y-2">
                            <button 
                                wire:click="$set('showAcceptQuoteModal', true)"
                                class="w-full bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition"
                            >
                                @if(empty($consultation->quoted_price) || $consultation->quoted_price == 0)
                                    Accept Free Consultation
                                @else
                                    Accept Quote & Pay
                                @endif
                            </button>
                            <button 
                                wire:click="$set('showDeclineQuoteModal', true)"
                                class="w-full bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition"
                            >
                                Decline Quote
                            </button>
                        </div>
                    </div>
                @endif

                @if($consultation->status === 'payment_pending')
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm font-medium text-gray-700 mb-3">Payment Required</p>
                        <a 
                            href="{{ route('payment.checkout', $consultation) }}"
                            class="block w-full bg-primary-700 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-[#1E40AF] transition text-center"
                        >
                            Pay Now
                        </a>
                    </div>
                @endif

                @if($consultation->status === 'payment_failed')
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-3">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-red-800">Payment Failed</p>
                                    <p class="text-xs text-red-700 mt-1">Your payment could not be processed. Please try again.</p>
                                </div>
                            </div>
                        </div>
                        <a 
                            href="{{ route('payment.checkout', $consultation) }}"
                            class="block w-full bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition text-center"
                        >
                            Retry Payment
                        </a>
                    </div>
                @endif

                <!-- Reschedule Button -->
                @if(in_array($consultation->status, ['scheduled', 'payment_pending']))
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <x-reschedule-button :consultation="$consultation" />
                    </div>
                @endif

                @if(in_array($consultation->status, ['pending', 'payment_pending', 'payment_failed', 'awaiting_quote_approval']))
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <button 
                            wire:click="$set('showCancelModal', true)"
                            class="w-full bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition"
                        >
                            Cancel Consultation
                        </button>
                    </div>
                @endif
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Timeline</h2>
                <div class="space-y-4">
                    <!-- Service Request Events (if this is a follow-up) -->
                    @if($consultation->parent_consultation_id)
                        @php
                            // Get the service request from the parent consultation
                            $serviceRequest = \App\Models\ServiceRequest::where('consultation_id', $consultation->parent_consultation_id)
                                ->where('status', 'accepted')
                                ->with('requester')
                                ->orderBy('created_at', 'desc')
                                ->first();
                        @endphp
                        @if($serviceRequest)
                            <div class="flex gap-3">
                                <div class="w-2 h-2 rounded-full bg-purple-500 mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Additional Service Requested</p>
                                    <p class="text-xs text-gray-600">by {{ $serviceRequest->requester->name }} ({{ $serviceRequest->requester->isLawyer() ? 'Lawyer' : 'Client' }})</p>
                                    <p class="text-xs text-gray-600">{{ $serviceRequest->created_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>
                            @if($serviceRequest->responded_at)
                                <div class="flex gap-3">
                                    <div class="w-2 h-2 rounded-full bg-green-500 mt-2"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Service Request Accepted</p>
                                        @if($serviceRequest->responder)
                                            <p class="text-xs text-gray-600">by {{ $serviceRequest->responder->name }} ({{ $serviceRequest->responder->isLawyer() ? 'Lawyer' : 'Client' }})</p>
                                        @endif
                                        <p class="text-xs text-gray-600">{{ $serviceRequest->responded_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                    
                    <div class="flex gap-3">
                        <div class="w-2 h-2 rounded-full bg-gray-400 mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $consultation->parent_consultation_id ? 'Session Created' : 'Request Created' }}</p>
                            <p class="text-xs text-gray-600">by {{ $consultation->client->name }} (Client)</p>
                            <p class="text-xs text-gray-600">{{ $consultation->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @if($consultation->quote_provided_at)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-blue-500 mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Quote Provided</p>
                                <p class="text-xs text-gray-600">by {{ $consultation->lawyer->name }} (Lawyer)</p>
                                <p class="text-xs text-gray-600">{{ $consultation->quote_provided_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($consultation->quote_accepted_at)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-blue-500 mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Quote Accepted</p>
                                <p class="text-xs text-gray-600">by {{ $consultation->client->name }} (Client)</p>
                                <p class="text-xs text-gray-600">{{ $consultation->quote_accepted_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($consultation->accepted_at)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-green-500 mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Request Accepted</p>
                                <p class="text-xs text-gray-600">by {{ $consultation->lawyer->name }} (Lawyer)</p>
                                <p class="text-xs text-gray-600">{{ $consultation->accepted_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($consultation->payment_status === 'paid' && $consultation->transaction)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-green-500 mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Payment Completed</p>
                                <p class="text-xs text-gray-600">by {{ $consultation->client->name }} (Client)</p>
                                <p class="text-xs text-gray-600">{{ $consultation->transaction->processed_at ? $consultation->transaction->processed_at->format('M d, Y g:i A') : 'Processing...' }}</p>
                            </div>
                        </div>
                    @endif
                    @if($consultation->started_at)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-blue-500 mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Consultation Started</p>
                                <p class="text-xs text-gray-600">with {{ $consultation->lawyer->name }} (Lawyer)</p>
                                <p class="text-xs text-gray-600">{{ $consultation->started_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($consultation->ended_at)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-gray-500 mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Consultation Ended</p>
                                <p class="text-xs text-gray-600">with {{ $consultation->lawyer->name }} (Lawyer)</p>
                                <p class="text-xs text-gray-600">{{ $consultation->ended_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($consultation->completed_at)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-green-600 mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Consultation Completed</p>
                                <p class="text-xs text-gray-600">with {{ $consultation->lawyer->name }} (Lawyer)</p>
                                <p class="text-xs text-gray-600">{{ $consultation->completed_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Cancel Consultation Modal -->
@if($showCancelModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
        <div class="px-6 pt-6 pb-4">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Cancel Consultation</h3>
            <p class="text-sm text-gray-600 mb-4">Are you sure you want to cancel this consultation?</p>
            
            <!-- Important Reminders -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <p class="text-sm font-medium text-yellow-900 mb-2">⚠️ Important Reminders:</p>
                <ul class="text-sm text-yellow-800 space-y-1 list-disc list-inside">
                    <li>Frequent cancellations may affect your account standing</li>
                    <li>The lawyer has reserved this time for you</li>
                    <li>Consider rescheduling instead of cancelling</li>
                    <li>Providing a reason helps improve our service</li>
                </ul>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for cancellation (optional)</label>
                <textarea 
                    wire:model="cancelReason" 
                    rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="e.g., Schedule conflict, need to reschedule, found another lawyer..."
                ></textarea>
                <p class="text-xs text-gray-500 mt-1">Your feedback helps us improve our service</p>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 flex gap-3 justify-end rounded-b-2xl">
            <button 
                type="button"
                wire:click="$set('showCancelModal', false)"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                Keep Consultation
            </button>
            <button 
                wire:click="cancelConsultation"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
            >
                <span wire:loading.remove wire:target="cancelConsultation">Cancel Consultation</span>
                <span wire:loading wire:target="cancelConsultation" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                </span>
            </button>
        </div>
    </div>
</div>
@endif

<!-- Accept Quote Modal -->
@if($showAcceptQuoteModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="px-6 pt-6 pb-4">
            @if(empty($consultation->quoted_price) || $consultation->quoted_price == 0)
                <h3 class="text-xl font-bold text-gray-900 mb-2">Accept Free Consultation</h3>
                <p class="text-sm text-gray-600 mb-4">Confirm to accept this free consultation offer</p>
            @else
                <h3 class="text-xl font-bold text-gray-900 mb-2">Accept Quote & Proceed to Payment</h3>
                <p class="text-sm text-gray-600 mb-4">Review the quote details before proceeding</p>
            @endif
            
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <p class="text-sm font-medium text-green-900 mb-2">Quote Summary:</p>
                <div class="space-y-1 text-sm text-green-800">
                    @if(empty($consultation->quoted_price) || $consultation->quoted_price == 0)
                        <p>• Amount: <strong>FREE</strong></p>
                    @else
                        <p>• Amount to Pay: <strong>₱{{ number_format($consultation->quoted_price, 2) }}</strong></p>
                    @endif
                    @if($consultation->estimated_turnaround_days)
                        <p>• Estimated Turnaround: <strong>{{ $consultation->estimated_turnaround_days }} {{ $consultation->estimated_turnaround_days == 1 ? 'day' : 'days' }}</strong></p>
                    @endif
                    @if($consultation->consultation_type === 'document_review')
                        <p>• Service: <strong>Document Review</strong></p>
                    @else
                        <p>• Duration: <strong>{{ $consultation->duration }} minutes</strong></p>
                    @endif
                </div>
            </div>
            
            @if(empty($consultation->quoted_price) || $consultation->quoted_price == 0)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-blue-800">
                        <strong>✓ What happens next:</strong><br>
                        The consultation will be scheduled and the lawyer will contact you to arrange the session.
                    </p>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-blue-800">
                        <strong>✓ What happens next:</strong><br>
                        You'll be redirected to the payment page to complete your payment securely via PayMongo.
                    </p>
                </div>
            @endif
        </div>
        
        <div class="px-6 py-4 bg-gray-50 flex gap-3 justify-end rounded-b-2xl">
            <button 
                type="button"
                wire:click="$set('showAcceptQuoteModal', false)"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                Cancel
            </button>
            <button 
                wire:click="{{ $consultation->status === 'pending_client_acceptance' ? 'acceptOffer' : 'acceptQuote' }}"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
            >
                @if(empty($consultation->quoted_price) || $consultation->quoted_price == 0)
                    <span wire:loading.remove wire:target="acceptQuote,acceptOffer">Accept</span>
                @else
                    <span wire:loading.remove wire:target="acceptQuote,acceptOffer">Accept & Pay</span>
                @endif
                <span wire:loading wire:target="acceptQuote,acceptOffer" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                </span>
            </button>
        </div>
    </div>
</div>
@endif

<!-- Decline Quote Modal -->
@if($showDeclineQuoteModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="px-6 pt-6 pb-4">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Decline Quote</h3>
            <p class="text-sm text-gray-600 mb-4">Are you sure you want to decline this quote?</p>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-yellow-800">
                    <strong>⚠️ Important:</strong> Declining this quote will end the consultation request. You'll need to create a new booking if you change your mind.
                </p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <p class="text-sm font-medium text-gray-900 mb-2">Quote Details:</p>
                <div class="space-y-1 text-sm text-gray-700">
                    <p>• Quoted Price: ₱{{ number_format($consultation->quoted_price, 2) }}</p>
                    @if($consultation->estimated_turnaround_days)
                        <p>• Turnaround: {{ $consultation->estimated_turnaround_days }} days</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 flex gap-3 justify-end rounded-b-2xl">
            <button 
                type="button"
                wire:click="$set('showDeclineQuoteModal', false)"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                Cancel
            </button>
            <button 
                wire:click="{{ $consultation->status === 'pending_client_acceptance' ? 'declineOffer' : 'declineQuote' }}"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
            >
                <span wire:loading.remove wire:target="declineQuote,declineOffer">
                    {{ $consultation->status === 'pending_client_acceptance' ? 'Decline Offer' : 'Decline Quote' }}
                </span>
                <span wire:loading wire:target="declineQuote,declineOffer" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                </span>
            </button>
        </div>
    </div>
</div>
@endif

<!-- Delete Document Modal -->
@if($showDeleteDocumentModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="px-6 pt-6 pb-4">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Delete Document</h3>
            <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete this document?</p>
            
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-red-800">
                    <strong>⚠️ Warning:</strong> This action cannot be undone. The document will be permanently removed from this consultation.
                </p>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 flex gap-3 justify-end rounded-b-2xl">
            <button 
                type="button"
                wire:click="$set('showDeleteDocumentModal', false)"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer"
            >
                Cancel
            </button>
            <button 
                wire:click="deleteDocument"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 cursor-pointer"
            >
                <span wire:loading.remove wire:target="deleteDocument">Delete Document</span>
                <span wire:loading wire:target="deleteDocument" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Deleting...
                </span>
            </button>
        </div>
    </div>
</div>
@endif
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('open-url', (event) => {
            window.open(event.url, '_blank');
        });
    });
</script>


<!-- Reschedule Modals -->
<x-reschedule-modal :consultation="$consultation" />

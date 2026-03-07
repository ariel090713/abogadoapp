<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>

<div>
<div class="p-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('lawyer.consultations') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Consultations
        </a>
    </div>

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
                                    {{ $consultation->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $consultation->status === 'declined' ? 'bg-gray-100 text-gray-700' : '' }}
                                ">
                                    @if($consultation->status === 'pending_client_acceptance')
                                        Pending Client Approval
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
                                <a href="{{ route('lawyer.consultation-thread.details', $mainCase->id) }}" 
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



                <!-- Countdown Timers -->
                @if($consultation->status === 'pending' && $consultation->lawyer_response_deadline)
                    <div class="mb-4">
                        @livewire('components.countdown-timer', [
                            'deadline' => $consultation->lawyer_response_deadline,
                            'label' => 'Respond to request within',
                            'type' => 'lawyer_response',
                            'consultationId' => $consultation->id
                        ], key('lawyer-response-deadline-'.$consultation->id))
                    </div>
                @endif

                @if($consultation->consultation_type === 'document_review' && 
                    in_array($consultation->status, ['scheduled', 'in_progress']) && 
                    $consultation->review_completion_deadline)
                    <div class="mb-4">
                        @livewire('components.countdown-timer', [
                            'deadline' => $consultation->review_completion_deadline,
                            'label' => 'Complete review within',
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

            <!-- Client's Concern -->
            @if($consultation->client_notes)
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Client's Legal Concern</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $consultation->client_notes }}</p>
                </div>
            @endif
            
            <!-- Quote Information (if provided) -->
            @if($consultation->quoted_price && $consultation->quote_provided_at)
                <div class="bg-blue-50 rounded-2xl shadow-lg p-6 border border-blue-200">
                    <h2 class="text-xl font-bold text-blue-900 mb-4">Your Custom Quote</h2>
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
                                <p class="text-sm text-blue-700 font-medium mb-2">Your Explanation:</p>
                                <p class="text-blue-800 leading-relaxed whitespace-pre-wrap">{{ $consultation->quote_notes }}</p>
                            </div>
                        @endif
                        <div class="pt-3 border-t border-blue-200">
                            <p class="text-xs text-blue-600">
                                Quote provided: {{ $consultation->quote_provided_at->format('M d, Y g:i A') }}
                            </p>
                            @if($consultation->quote_accepted_at)
                                <p class="text-xs text-green-600 mt-1">
                                    ✓ Quote accepted by client: {{ $consultation->quote_accepted_at->format('M d, Y g:i A') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Supporting Documents (for all consultation types) -->
            @if($uploadedDocuments->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">
                        @if($consultation->consultation_type === 'document_review')
                            Client Documents ({{ $uploadedDocuments->where('deleted_at', null)->count() }})
                        @else
                            Supporting Documents ({{ $uploadedDocuments->where('deleted_at', null)->count() }})
                        @endif
                        @if($uploadedDocuments->whereNotNull('deleted_at')->count() > 0)
                            <span class="text-red-600">• {{ $uploadedDocuments->whereNotNull('deleted_at')->count() }} deleted</span>
                        @endif
                    </h2>
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
                                    <button 
                                        wire:click="getDocumentDownloadUrl({{ $doc->id }})"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition cursor-pointer flex-shrink-0"
                                        title="Download"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
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


            <!-- Lawyer Notes (if any) -->
            @if($consultation->lawyer_notes)
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Your Notes</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $consultation->lawyer_notes }}</p>
                </div>
            @endif

            <!-- Uploaded Document (if any) -->
            @if($consultation->document_path)
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Client's Document</h2>
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Uploaded Document</p>
                            <p class="text-sm text-gray-500">Document submitted for review</p>
                        </div>
                        <a href="{{ $consultation->getDocumentUrl() }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-[#1E3A8A] text-white rounded-lg hover:bg-[#1E40AF] transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            @endif

            <!-- Reviewed Document & Completion Management (for completed consultations) -->
            @if($consultation->status === 'completed')
                <div class="bg-green-50 rounded-2xl shadow-lg p-6 border border-green-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <h2 class="text-lg sm:text-xl font-bold text-green-900">Your Review (Completed)</h2>
                        </div>
                        <button 
                            wire:click="openUpdateModal"
                            type="button"
                            class="px-3 py-1.5 text-sm bg-[#1E3A8A] text-white rounded-lg hover:bg-[#1E40AF] transition w-full sm:w-auto"
                        >
                            Edit Review
                        </button>
                    </div>

                    @if($consultation->completed_at)
                        <p class="text-sm text-green-700 mb-4">
                            Completed: {{ $consultation->completed_at->format('M d, Y g:i A') }}
                            @if($consultation->completion_updated_at)
                                <span class="text-green-600">(Updated: {{ $consultation->completion_updated_at->format('M d, Y g:i A') }})</span>
                            @endif
                        </p>
                    @endif

                    <!-- Reviewed Document -->
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
                                    <p class="text-sm text-gray-500">Your reviewed document</p>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                    <a href="{{ $consultation->getReviewedDocumentUrl() }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm w-full sm:w-auto">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download
                                    </a>
                                    <button 
                                        wire:click="$set('showDeleteDocumentModal', true)"
                                        class="inline-flex items-center justify-center gap-2 px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm w-full sm:w-auto"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Deleted Document History -->
                    @if($consultation->reviewed_document_deleted_path)
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-700 break-words">
                                <span class="font-medium">Previous document deleted:</span> 
                                <span class="block sm:inline mt-1 sm:mt-0">{{ basename($consultation->reviewed_document_deleted_path) }}</span>
                            </p>
                            <p class="text-xs text-red-600 mt-1">
                                {{ $consultation->reviewed_document_deleted_at->format('M d, Y g:i A') }}
                            </p>
                        </div>
                    @endif

                    <!-- Completion Notes -->
                    @if($consultation->completion_notes)
                        <div>
                            <p class="text-sm font-medium text-green-900 mb-2">Your Notes:</p>
                            <div class="bg-white rounded-lg border border-green-300 p-4">
                                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $consultation->completion_notes }}</p>
                            </div>
                        </div>
                    @endif
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
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Client Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Client Information</h2>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-full bg-accent-100 flex items-center justify-center text-accent-700 font-bold text-xl">
                        {{ $consultation->client->initials() }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $consultation->client->name }}</h3>
                        <p class="text-sm text-gray-600">Client</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-500 italic">🚫 No direct contact - All communication through platform</p>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Payment Details</h2>
                <div class="space-y-3">
                    @if($consultation->quoted_price)
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
                    <div class="flex justify-between pt-3 border-t border-gray-200">
                        <span class="font-semibold text-gray-900">Total Amount</span>
                        <span class="font-bold text-primary-600 text-lg">₱{{ number_format($consultation->total_amount, 2) }}</span>
                    </div>
                    
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
                    
                    @if($consultation->payment_status === 'paid')
                        <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <p class="text-sm font-medium text-green-800">Payment Received</p>
                            </div>
                        </div>
                    @elseif($consultation->status === 'payment_pending')
                        <div class="mt-3 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                            <p class="text-xs text-orange-700">
                                Waiting for client payment...
                            </p>
                        </div>
                    @endif

                    <!-- Countdown Timers -->
                    @if($consultation->status === 'awaiting_quote_approval' && $consultation->quote_deadline)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            @livewire('components.countdown-timer', [
                                'deadline' => $consultation->quote_deadline,
                                'label' => 'Client must respond within',
                                'type' => 'quote_response',
                                'consultationId' => $consultation->id
                            ], key('quote-deadline-'.$consultation->id))
                        </div>
                    @endif

                    @if($consultation->status === 'payment_pending' && $consultation->payment_deadline)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            @livewire('components.countdown-timer', [
                                'deadline' => $consultation->payment_deadline,
                                'label' => 'Client must pay within',
                                'type' => 'payment',
                                'consultationId' => $consultation->id
                            ], key('payment-deadline-'.$consultation->id))
                        </div>
                    @endif

                    <!-- Chat/Video Button (for scheduled/in_progress consultations) -->
                    @if(in_array($consultation->status, ['scheduled', 'in_progress', 'accepted']) && 
                        in_array($consultation->consultation_type, ['chat', 'video']))
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            @if($consultation->consultation_type === 'video')
                            <a 
                                href="{{ route('lawyer.consultation.video', $consultation) }}"
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
                                href="{{ route('lawyer.consultation.chat', $consultation) }}"
                                class="flex items-center justify-center gap-2 w-full bg-blue-900 text-white px-4 py-3 rounded-lg text-sm font-medium hover:bg-blue-800 transition"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Open Chat
                            </a>
                            <p class="text-xs text-gray-500 text-center mt-2">
                                Message your client in real-time
                            </p>
                            @endif
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    @if($consultation->status === 'pending')
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-3">Actions Required</p>
                            <div class="space-y-2">
                                @if($consultation->consultation_type !== 'document_review')
                                    <button 
                                        wire:click="$set('showAcceptModal', true)"
                                        class="w-full bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition"
                                    >
                                        Accept Request
                                    </button>
                                @endif
                                <button 
                                    wire:click="$set('showQuoteModal', true)"
                                    class="w-full bg-[#1E3A8A] text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-[#1E40AF] transition"
                                >
                                    {{ $consultation->consultation_type === 'document_review' ? 'Send Quote' : 'Provide Custom Quote' }}
                                </button>
                                <button 
                                    wire:click="$set('showDeclineModal', true)"
                                    class="w-full bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition"
                                >
                                    Decline Request
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Cancel Offer Button (for pending client acceptance) -->
                    @if($consultation->status === 'pending_client_acceptance' && $consultation->initiated_by === 'lawyer')
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-3">Pending Client Response</p>
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-3">
                                <p class="text-sm text-orange-800">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Waiting for client to accept or decline your service offer.
                                </p>
                            </div>
                            <button 
                                wire:click="$set('showCancelOfferModal', true)"
                                class="w-full bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition"
                            >
                                Cancel Offer
                            </button>
                            <p class="text-xs text-gray-500 mt-2">This will cancel the service offer and notify the client.</p>
                        </div>
                    @endif

                    <!-- Mark as Completed Button (for document reviews) -->
                    @if($consultation->consultation_type === 'document_review' && 
                        in_array($consultation->status, ['scheduled', 'in_progress']))
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-3">Complete Review</p>
                            <button 
                                wire:click="$set('showCompleteModal', true)"
                                class="w-full bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition"
                            >
                                Mark as Completed
                            </button>
                            <p class="text-xs text-gray-500 mt-2">Upload reviewed document or provide completion notes</p>
                        </div>
                    @endif

                    <!-- Mark as Completed Button (for chat/video consultations after time expires) -->
                    @if(in_array($consultation->consultation_type, ['chat', 'video']) && 
                        $consultation->scheduled_at && 
                        now()->greaterThan($consultation->scheduled_at->copy()->addMinutes($consultation->duration)) &&
                        in_array($consultation->status, ['scheduled', 'in_progress']))
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-3">Consultation Ended</p>
                            <button 
                                wire:click="$set('showCompleteModal', true)"
                                class="w-full bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Mark as Completed
                            </button>
                            <p class="text-xs text-gray-500 mt-2">Provide completion notes and/or upload documents</p>
                        </div>
                    @endif
                </div>
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
                            <p class="text-sm font-medium text-gray-900">{{ $consultation->parent_consultation_id ? 'Session Created' : 'Request Received' }}</p>
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
                                <p class="text-sm font-medium text-gray-900">Payment Received</p>
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
                                <p class="text-xs text-gray-600">with {{ $consultation->client->name }} (Client)</p>
                                <p class="text-xs text-gray-600">{{ $consultation->started_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($consultation->ended_at)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-gray-500 mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Consultation Ended</p>
                                <p class="text-xs text-gray-600">with {{ $consultation->client->name }} (Client)</p>
                                <p class="text-xs text-gray-600">{{ $consultation->ended_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($consultation->completed_at)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-green-600 mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Consultation Completed</p>
                                <p class="text-xs text-gray-600">with {{ $consultation->client->name }} (Client)</p>
                                <p class="text-xs text-gray-600">{{ $consultation->completed_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Provide Quote Modal -->
@if($showQuoteModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 pt-6 pb-4">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Provide Custom Quote</h3>
            <p class="text-sm text-gray-600 mb-4">Offer a custom price for this consultation</p>
            
            <!-- Pricing Guidelines -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <p class="text-sm font-medium text-blue-900 mb-2">💡 Pricing Tips:</p>
                <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                    <li>Consider the complexity of the case</li>
                    <li>Factor in preparation and research time</li>
                    <li>Be transparent about what's included</li>
                    <li>Provide clear explanation for your pricing</li>
                </ul>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quoted Price (₱)</label>
                    <input 
                        type="number" 
                        wire:model="quotedPrice" 
                        step="0.01" 
                        min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Enter price or leave empty for free consultation"
                    >
                    @error('quotedPrice') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Leave empty or enter 0 for free consultation. No platform fee deducted.</p>
                </div>
                
                @if($consultation->consultation_type === 'document_review')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Turnaround (Days) <span class="text-red-500">*</span></label>
                        <input 
                            type="number" 
                            wire:model="estimatedTurnaroundDays" 
                            min="1"
                            max="30"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="e.g., 3"
                        >
                        @error('estimatedTurnaroundDays') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">How many days will you need to complete the review?</p>
                    </div>
                @endif
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Explanation <span class="text-red-500">*</span></label>
                    <textarea 
                        wire:model="quoteNotes" 
                        rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Explain your pricing: What's included? Why this amount? Any special considerations?"
                    ></textarea>
                    @error('quoteNotes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Min 10 characters. Be clear and professional.</p>
                </div>
            </div>
            
            <!-- Important Note -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-4">
                <p class="text-xs text-yellow-800">
                    <strong>Note:</strong> Client will have time to review and accept/decline your quote. Once accepted, they must complete payment within the deadline.
                </p>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 flex gap-3 justify-end rounded-b-2xl">
            <button 
                type="button"
                wire:click="$set('showQuoteModal', false)"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                Cancel
            </button>
            <button 
                wire:click="provideQuote"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-[#1E40AF]"
            >
                <span wire:loading.remove wire:target="provideQuote">Send Quote</span>
                <span wire:loading wire:target="provideQuote" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending...
                </span>
            </button>
        </div>
    </div>
</div>
@endif

<!-- Accept Request Modal -->
@if($showAcceptModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
        <div class="px-6 pt-6 pb-4">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Accept Consultation Request</h3>
            <p class="text-sm text-gray-600 mb-4">Review the details before accepting</p>
            
            <!-- Consultation Details -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-blue-800">
                    <strong>Consultation Fee:</strong> ₱{{ number_format($consultation->rate, 2) }}
                </p>
                <p class="text-sm text-blue-800 mt-1">
                    <strong>Your Earnings:</strong> ₱{{ number_format($consultation->rate, 2) }}
                </p>
            </div>
            
            <!-- Professional Commitments -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <p class="text-sm font-medium text-green-900 mb-2">✓ By accepting, you commit to:</p>
                <ul class="text-sm text-green-800 space-y-1 list-disc list-inside">
                    <li>Be available at the scheduled time</li>
                    <li>Provide professional legal consultation</li>
                    <li>Maintain client confidentiality</li>
                    <li>Uphold the highest standards of legal ethics</li>
                </ul>
            </div>
            
            <!-- Next Steps -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <p class="text-xs text-gray-700">
                    <strong>Next:</strong> Client will be notified and must complete payment within the deadline. Once paid, the consultation will be confirmed.
                </p>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 flex gap-3 justify-end rounded-b-2xl">
            <button 
                type="button"
                wire:click="$set('showAcceptModal', false)"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                Cancel
            </button>
            <button 
                wire:click="acceptRequest"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
            >
                <span wire:loading.remove wire:target="acceptRequest">Accept Request</span>
                <span wire:loading wire:target="acceptRequest" class="flex items-center justify-center gap-2">
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

<!-- Decline Request Modal -->
@if($showDeclineModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
        <div class="px-6 pt-6 pb-4">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Decline Consultation Request</h3>
            <p class="text-sm text-gray-600 mb-4">Please provide a professional reason</p>
            
            <!-- Professional Reminder -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <p class="text-sm font-medium text-yellow-900 mb-2">⚠️ Professional Reminder:</p>
                <ul class="text-sm text-yellow-800 space-y-1 list-disc list-inside">
                    <li>Declining affects your acceptance rate</li>
                    <li>Be respectful and professional in your reason</li>
                    <li>Consider if you can refer to another lawyer</li>
                    <li>Your reason will be shared with the client</li>
                </ul>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for declining (optional)</label>
                <textarea 
                    wire:model="declineReason" 
                    rows="4" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="e.g., Schedule conflict, outside my area of expertise, conflict of interest..."
                ></textarea>
                @error('declineReason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <p class="text-xs text-gray-500 mt-1">Be honest and professional. This helps the client understand.</p>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 flex gap-3 justify-end rounded-b-2xl">
            <button 
                type="button"
                wire:click="$set('showDeclineModal', false)"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                Cancel
            </button>
            <button 
                wire:click="declineRequest"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
            >
                <span wire:loading.remove wire:target="declineRequest">Decline Request</span>
                <span wire:loading wire:target="declineRequest" class="flex items-center justify-center gap-2">
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

<!-- Mark as Completed Modal -->
@if($showCompleteModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-2xl font-bold text-gray-900">Mark as Completed</h3>
            <p class="text-sm text-gray-600 mt-1">Upload reviewed document or provide completion notes</p>
        </div>

        <div class="p-6 space-y-6">
            <!-- Important Note -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Completion Requirements:</p>
                        <ul class="list-disc list-inside space-y-1 text-blue-700">
                            <li>Upload a reviewed document (PDF, DOC, DOCX - Max 10MB), OR</li>
                            <li>Provide detailed completion notes explaining your review</li>
                            <li>You can provide both for better client understanding</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Upload Reviewed Document -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Upload Reviewed Document (Optional)
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-500 transition">
                    <input 
                        type="file" 
                        wire:model="reviewedDocument"
                        accept=".pdf,.doc,.docx"
                        class="hidden"
                        id="reviewed-document-upload"
                    >
                    <label for="reviewed-document-upload" class="cursor-pointer">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">
                            <span class="font-medium text-primary-600">Click to upload</span> or drag and drop
                        </p>
                        <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX - Max 10MB</p>
                    </label>
                </div>
                @if($reviewedDocument)
                    <div class="mt-3 flex items-center gap-2 text-sm text-green-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>File selected: {{ $reviewedDocument->getClientOriginalName() }}</span>
                    </div>
                @endif
                @error('reviewedDocument')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div wire:loading wire:target="reviewedDocument" class="mt-2 text-sm text-blue-600">
                    Uploading...
                </div>
            </div>

            <!-- Completion Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Completion Notes <span class="text-red-600">*</span>
                </label>
                <textarea 
                    wire:model="completionNotes"
                    rows="6"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="Explain what you reviewed, key findings, recommendations, or any important notes for the client..."
                ></textarea>
                <p class="mt-1 text-xs text-gray-500">Provide detailed notes about your review and any recommendations</p>
                @error('completionNotes')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 flex gap-3 justify-end">
            <button 
                wire:click="$set('showCompleteModal', false)"
                type="button"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                Cancel
            </button>
            <button 
                wire:click="completeConsultation"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
            >
                <span wire:loading.remove wire:target="completeConsultation">Mark as Completed</span>
                <span wire:loading wire:target="completeConsultation" class="flex items-center justify-center gap-2">
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

<!-- Update Completion Modal -->
@if($showUpdateCompletionModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-2xl font-bold text-gray-900">Update Review</h3>
            <p class="text-sm text-gray-600 mt-1">Edit notes or replace document</p>
        </div>

        <div class="p-6 space-y-6">
            <!-- Info Note -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Update Options:</p>
                        <ul class="list-disc list-inside space-y-1 text-blue-700">
                            <li>Edit your completion notes</li>
                            <li>Upload a new document (replaces the old one)</li>
                            <li>Or use the Delete button to remove the document first</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Update Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Update Completion Notes
                </label>
                <textarea 
                    wire:model="updateNotes"
                    rows="6"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="Update your review notes..."
                ></textarea>
                @error('updateNotes')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Replace Document -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Replace Document (Optional)
                </label>
                @if($consultation->reviewed_document_path)
                    <p class="text-sm text-gray-600 mb-2">
                        Current: Reviewed Document
                    </p>
                @endif
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-500 transition">
                    <input 
                        type="file" 
                        wire:model="updateDocument"
                        accept=".pdf,.doc,.docx"
                        class="hidden"
                        id="update-document-upload"
                    >
                    <label for="update-document-upload" class="cursor-pointer">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">
                            <span class="font-medium text-primary-600">Click to upload new document</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX - Max 10MB</p>
                    </label>
                </div>
                @if($updateDocument)
                    <div class="mt-3 flex items-center gap-2 text-sm text-green-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>New file selected: {{ $updateDocument->getClientOriginalName() }}</span>
                    </div>
                @endif
                @error('updateDocument')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div wire:loading wire:target="updateDocument" class="mt-2 text-sm text-blue-600">
                    Uploading...
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 flex gap-3 justify-end">
            <button 
                wire:click="$set('showUpdateCompletionModal', false)"
                type="button"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                Cancel
            </button>
            <button 
                wire:click="updateCompletion"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-4 py-2 text-sm font-medium text-white bg-[#1E3A8A] rounded-lg hover:bg-[#1E40AF]"
            >
                <span wire:loading.remove wire:target="updateCompletion">Update Review</span>
                <span wire:loading wire:target="updateCompletion" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Updating...
                </span>
            </button>
        </div>
    </div>
</div>
@endif

<!-- Delete Document Confirmation Modal -->
@if($showDeleteDocumentModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Delete Document?</h3>
                    <p class="text-sm text-gray-600 mt-1">This action can be tracked but cannot be undone</p>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                <p class="text-sm text-yellow-800">
                    The document will be marked as deleted and you can upload a new one. The deletion will be visible in the history.
                </p>
            </div>

            <p class="text-sm text-gray-700 mb-4">
                Document: <span class="font-medium">Reviewed Document</span>
            </p>
        </div>

        <div class="p-6 border-t border-gray-200 flex gap-3 justify-end">
            <button 
                wire:click="$set('showDeleteDocumentModal', false)"
                type="button"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                Cancel
            </button>
            <button 
                wire:click="deleteReviewedDocument"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
            >
                <span wire:loading.remove wire:target="deleteReviewedDocument">Delete Document</span>
                <span wire:loading wire:target="deleteReviewedDocument" class="flex items-center justify-center gap-2">
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


<!-- Cancel Offer Confirmation Modal -->
@if($showCancelOfferModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Cancel Service Offer?</h3>
                    <p class="text-sm text-gray-600 mt-1">This action cannot be undone</p>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                <p class="text-sm text-yellow-800">
                    <strong>Warning:</strong> Cancelling this offer will notify the client and they will no longer be able to accept it.
                </p>
            </div>

            <div class="space-y-2 mb-4">
                <p class="text-sm text-gray-700">
                    <span class="font-medium">Service:</span> {{ ucfirst(str_replace('_', ' ', $consultation->consultation_type)) }}
                </p>
                <p class="text-sm text-gray-700">
                    <span class="font-medium">Title:</span> {{ $consultation->title }}
                </p>
                @if($consultation->quoted_price)
                    <p class="text-sm text-gray-700">
                        <span class="font-medium">Price:</span> ₱{{ number_format($consultation->quoted_price, 2) }}
                    </p>
                @else
                    <p class="text-sm text-gray-700">
                        <span class="font-medium">Price:</span> Free
                    </p>
                @endif
            </div>

            <p class="text-sm text-gray-600">
                Are you sure you want to cancel this service offer?
            </p>
        </div>

        <div class="p-6 border-t border-gray-200 flex gap-3 justify-end">
            <button 
                wire:click="$set('showCancelOfferModal', false)"
                type="button"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                No, Keep Offer
            </button>
            <button 
                wire:click="cancelOffer"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                type="button"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
            >
                <span wire:loading.remove wire:target="cancelOffer">Yes, Cancel Offer</span>
                <span wire:loading wire:target="cancelOffer" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Cancelling...
                </span>
            </button>
        </div>
    </div>
</div>
@endif

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('open-url', (event) => {
            window.open(event.url, '_blank');
        });
    });
</script>

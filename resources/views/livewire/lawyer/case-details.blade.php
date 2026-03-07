<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>

<div class="p-8">
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('lawyer.cases') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Consultation Threads
        </a>
    </div>

    <!-- Case Header -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 break-words">{{ $case->title }}</h1>
                    @php
                        $displayStatus = $case->getDisplayStatus();
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap
                        {{ $displayStatus === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $displayStatus === 'scheduled' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $displayStatus === 'in_progress' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $displayStatus === 'ended' ? 'bg-gray-100 text-gray-700' : '' }}
                        {{ $displayStatus === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $displayStatus === 'awaiting_quote_approval' ? 'bg-purple-100 text-purple-700' : '' }}
                        {{ $displayStatus === 'payment_pending' ? 'bg-orange-100 text-orange-700' : '' }}
                        {{ $displayStatus === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $displayStatus === 'declined' ? 'bg-gray-100 text-gray-700' : '' }}
                    ">
                        @if($displayStatus === 'ended')
                            Ended - Waiting to be Completed
                        @else
                            {{ ucfirst(str_replace('_', ' ', $displayStatus)) }}
                        @endif
                    </span>
                </div>
                <p class="text-sm sm:text-base text-gray-600 mb-4 break-all">Thread #{{ $case->getThreadNumber() }}</p>
                
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-primary-600 font-semibold text-base sm:text-lg">{{ substr($case->client->name, 0, 1) }}</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $case->client->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $case->client->email }}</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 sm:gap-4 w-full lg:w-auto">
                <div class="flex-1 lg:flex-none text-center p-3 sm:p-4 bg-gray-50 rounded-xl">
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalSessions }}</p>
                    <p class="text-xs text-gray-600 whitespace-nowrap">Total Sessions</p>
                </div>
                <div class="flex-1 lg:flex-none text-center p-3 sm:p-4 bg-green-50 rounded-xl">
                    <p class="text-xl sm:text-2xl font-bold text-green-700">{{ $completedSessions }}</p>
                    <p class="text-xs text-gray-600">Completed</p>
                </div>
            </div>
        </div>

        @if($case->description || $case->client_notes)
            <div class="border-t border-gray-200 pt-6">
                @if($case->description)
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Case Description</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $case->description }}</p>
                    </div>
                @endif

                @if($case->client_notes)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Client's Legal Concern</h3>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $case->client_notes }}</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
    <!-- 2-Column Layout: Sessions (Left) & Timeline (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sessions List (2/3 width on desktop) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">All Sessions</h2>
                </div>
                
                <!-- Offer Additional Service (Lawyer-Initiated) -->
                <div class="bg-gradient-to-r from-primary-50 to-accent-50 rounded-xl border border-primary-200 p-4 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1">
                            <h3 class="text-base font-bold text-gray-900 mb-1">Offer Additional Service</h3>
                            <p class="text-sm text-gray-600">Book another consultation session (chat/video) or document review for this case. Your client will be notified and can accept or decline.</p>
                        </div>
                        <div class="flex-shrink-0 w-full sm:w-auto">
                            <a href="{{ route('lawyer.book-service', $case->id) }}" 
                               class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-[#1E40AF] transition font-medium shadow-md hover:shadow-lg text-sm w-full sm:w-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Book Service
                            </a>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($sessions as $index => $session)
                        <a href="{{ route('lawyer.consultation.details', $session->id) }}" class="block border border-gray-200 rounded-xl p-4 hover:border-primary-300 hover:shadow-xl transition cursor-pointer">
                            <div class="flex flex-col gap-4">
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded">
                                            Session {{ $index + 1 }}
                                        </span>
                                        @php
                                            $displayStatus = $session->getDisplayStatus();
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                            {{ $displayStatus === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $displayStatus === 'scheduled' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $displayStatus === 'in_progress' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $displayStatus === 'ended' ? 'bg-gray-100 text-gray-700' : '' }}
                                            {{ $displayStatus === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $displayStatus === 'pending_client_acceptance' ? 'bg-purple-100 text-purple-700' : '' }}
                                            {{ $displayStatus === 'awaiting_quote_approval' ? 'bg-purple-100 text-purple-700' : '' }}
                                            {{ $displayStatus === 'payment_pending' ? 'bg-orange-100 text-orange-700' : '' }}
                                            {{ $displayStatus === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                            {{ $displayStatus === 'declined' ? 'bg-gray-100 text-gray-700' : '' }}
                                        ">
                                            @if($displayStatus === 'ended')
                                                Ended - Waiting to be Completed
                                            @elseif($displayStatus === 'pending_client_acceptance')
                                                Pending Client Approval
                                            @else
                                                {{ ucfirst(str_replace('_', ' ', $displayStatus)) }}
                                            @endif
                                        </span>
                                        <span class="px-2 py-1 bg-primary-100 text-primary-700 text-xs font-medium rounded">
                                            {{ ucfirst($session->consultation_type) }}
                                        </span>
                                    </div>

                                    <h3 class="text-base lg:text-lg font-semibold text-gray-900 mb-1">{{ $session->title }}</h3>
                                    
                                    @if($session->description)
                                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($session->description, 150) }}</p>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-3 lg:gap-4 text-sm text-gray-600">
                                        @if($session->scheduled_at)
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span>{{ $session->scheduled_at->format('M d, Y g:i A') }}</span>
                                            </div>
                                        @endif

                                        @if($session->rate)
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>₱{{ number_format($session->rate, 2) }}</span>
                                            </div>
                                        @endif

                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>Created {{ $session->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- RIGHT: Documents & Timeline (1/3 width) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- All Documents Card -->
            @if($allDocuments->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">All Documents ({{ $allDocuments->filter(fn($doc) => !$doc->deleted_at)->count() }})</h2>
                    
                    <div class="space-y-2 max-h-[300px] overflow-y-auto pr-2">
                        @foreach($allDocuments as $doc)
                            @if(!$doc->deleted_at)
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-primary-300 transition cursor-pointer"
                                     wire:click="redirectToConsultation({{ $doc->consultation_id }})">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 {{ isset($doc->is_reviewed_document) && $doc->is_reviewed_document ? 'text-green-600' : 'text-blue-600' }} flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $doc->original_filename }}</p>
                                            @if(isset($doc->is_reviewed_document) && $doc->is_reviewed_document)
                                                <p class="text-xs text-green-600 font-medium">Reviewed Document (Lawyer)</p>
                                                <p class="text-xs text-gray-500">
                                                    @if($doc->file_size > 0)
                                                        {{ number_format($doc->file_size / 1048576, 2) }} MB
                                                    @endif
                                                </p>
                                            @else
                                                <p class="text-xs text-gray-500">{{ $doc->getFileSizeFormatted() }}</p>
                                            @endif
                                            <p class="text-xs text-primary-600 font-medium mt-1">Session {{ $sessions->search(fn($s) => $s->id === $doc->consultation_id) + 1 }}</p>
                                            <p class="text-xs text-gray-600 truncate">{{ $doc->consultation->title }}</p>
                                            <p class="text-xs text-gray-500">{{ $doc->uploaded_at->format('M d, Y') }}</p>
                                        </div>
                                        <button 
                                            wire:click.stop="getDocumentDownloadUrl('{{ $doc->id }}')"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition flex-shrink-0"
                                            title="Download">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Timeline Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Timeline</h2>

                <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                    @foreach($timeline as $event)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                                    @if($event['type'] === 'session_created') bg-blue-100
                                    @elseif($event['type'] === 'session_scheduled') bg-yellow-100
                                    @elseif($event['type'] === 'session_completed') bg-green-100
                                    @elseif($event['type'] === 'follow_up_requested') bg-purple-100
                                    @elseif($event['type'] === 'follow_up_accepted') bg-green-100
                                    @elseif($event['type'] === 'follow_up_declined') bg-red-100
                                    @elseif($event['type'] === 'follow_up_cancelled') bg-red-100
                                    @endif">
                                    <svg class="w-4 h-4 
                                        @if($event['type'] === 'session_created') text-blue-600
                                        @elseif($event['type'] === 'session_scheduled') text-yellow-600
                                        @elseif($event['type'] === 'session_completed') text-green-600
                                        @elseif($event['type'] === 'follow_up_requested') text-purple-600
                                        @elseif($event['type'] === 'follow_up_accepted') text-green-600
                                        @elseif($event['type'] === 'follow_up_declined') text-red-600
                                        @elseif($event['type'] === 'follow_up_cancelled') text-red-600
                                        @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                @if(!$loop->last)
                                    <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                                @endif
                            </div>

                            <div class="flex-1 pb-4">
                                @if($event['type'] === 'session_created')
                                    <p class="font-semibold text-sm text-gray-900">Session Created</p>
                                    <p class="text-xs text-gray-600">{{ $event['data']->title }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $event['data']->consultation_type)) }}</p>
                                    <p class="text-xs text-gray-500">by {{ $event['data']->client->name }} (Client)</p>
                                @elseif($event['type'] === 'session_scheduled')
                                    <p class="font-semibold text-sm text-gray-900">Session Scheduled</p>
                                    <p class="text-xs text-gray-600">{{ $event['data']->scheduled_at->format('M d, Y g:i A') }}</p>
                                    <p class="text-xs text-gray-500">by {{ $event['data']->lawyer->name }} (Lawyer)</p>
                                @elseif($event['type'] === 'session_completed')
                                    <p class="font-semibold text-sm text-gray-900">Session Completed</p>
                                    <p class="text-xs text-gray-600">{{ $event['data']->title }}</p>
                                    <p class="text-xs text-gray-500">with {{ $event['data']->client->name }} (Client)</p>
                                @elseif($event['type'] === 'follow_up_requested')
                                    <p class="font-semibold text-sm text-gray-900">Additional Service Requested</p>
                                    <p class="text-xs text-gray-600">{{ ucfirst(str_replace('_', ' ', $event['data']->service_type)) }}</p>
                                    <p class="text-xs text-gray-500">by {{ $event['data']->requester->name }} ({{ $event['data']->requester->isLawyer() ? 'Lawyer' : 'Client' }})</p>
                                @elseif($event['type'] === 'follow_up_accepted')
                                    <p class="font-semibold text-sm text-gray-900">Additional Service Accepted</p>
                                    <p class="text-xs text-gray-600">{{ ucfirst(str_replace('_', ' ', $event['data']->service_type)) }}</p>
                                    @if($event['data']->responder)
                                        <p class="text-xs text-gray-500">by {{ $event['data']->responder->name }} ({{ $event['data']->responder->isLawyer() ? 'Lawyer' : 'Client' }})</p>
                                    @endif
                                @elseif($event['type'] === 'follow_up_declined')
                                    <p class="font-semibold text-sm text-gray-900">Additional Service Declined</p>
                                    <p class="text-xs text-gray-600">{{ ucfirst(str_replace('_', ' ', $event['data']->service_type)) }}</p>
                                    @if($event['data']->responder)
                                        <p class="text-xs text-gray-500">by {{ $event['data']->responder->name }} ({{ $event['data']->responder->isLawyer() ? 'Lawyer' : 'Client' }})</p>
                                    @endif
                                @elseif($event['type'] === 'follow_up_cancelled')
                                    <p class="font-semibold text-sm text-gray-900">Additional Service Cancelled</p>
                                    <p class="text-xs text-gray-600">{{ ucfirst(str_replace('_', ' ', $event['data']->service_type)) }}</p>
                                    <p class="text-xs text-gray-500">by {{ $event['data']->requester->name }} ({{ $event['data']->requester->isLawyer() ? 'Lawyer' : 'Client' }})</p>
                                @endif
                                
                                <p class="text-xs text-gray-400 mt-1">{{ $event['date']->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('open-url', (event) => {
            window.open(event.url, '_blank');
        });
    });
</script>

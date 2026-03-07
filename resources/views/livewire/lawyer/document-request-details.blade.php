<x-slot name="sidebar">
    <x-lawyer-sidebar />
</x-slot>
<div class="w-full px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('lawyer.document-requests') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Document Requests
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
                        Paid - Ready to Start
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
                @endif
            </div>
        </div>

        <!-- Client Info -->
        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            @if($request->client->profile_photo_url)
                <img src="{{ $request->client->profile_photo_url }}" 
                    alt="{{ $request->client->name }}"
                    class="w-12 h-12 rounded-lg object-cover">
            @else
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                    <span class="text-primary-600 font-semibold">
                        {{ substr($request->client->name, 0, 1) }}
                    </span>
                </div>
            @endif
            <div class="flex-1">
                <p class="font-medium text-gray-900">{{ $request->client->name }}</p>
                <p class="text-sm text-gray-500">{{ $request->client->email }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Amount</p>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($request->price, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Action Cards -->
    @if($request->status === 'paid')
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-blue-900 mb-2">Ready to Start</h3>
                    <p class="text-blue-800 mb-4">
                        Client has paid for this document. Click below to start working on it.
                        Estimated completion: {{ $request->service->estimated_completion_days }} {{ Str::plural('day', $request->service->estimated_completion_days) }}
                    </p>
                    <button wire:click="startWork" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed">
                        <span wire:loading.remove wire:target="startWork">Start Working</span>
                        <span wire:loading wire:target="startWork">Starting...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($request->status === 'in_progress')
        <div class="bg-purple-50 border border-purple-200 rounded-2xl p-6 mb-6">
            <h3 class="text-lg font-bold text-purple-900 mb-4">Upload Completed Document</h3>
            
            <form wire:submit.prevent="completeDocument" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Completed Document (PDF, DOC, DOCX)</label>
                    <input type="file" wire:model="completedDocument" accept=".pdf,.doc,.docx"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @error('completedDocument') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    
                    @if($completedDocument)
                        <p class="mt-2 text-sm text-gray-600">
                            Selected: {{ $completedDocument->getClientOriginalName() }} 
                            ({{ number_format($completedDocument->getSize() / 1024, 2) }} KB)
                        </p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes for Client (Optional)</label>
                    <textarea wire:model="lawyerNotes" rows="3"
                        placeholder="Any additional notes or instructions for the client..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea>
                    @error('lawyerNotes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                @if($request->completion_deadline)
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-600">
                            Completion Deadline: 
                            <span class="font-medium {{ $request->completion_deadline->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $request->completion_deadline->format('M d, Y') }}
                            </span>
                        </p>
                    </div>
                @endif

                <button type="submit" 
                    class="w-full px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed">
                    <span wire:loading.remove wire:target="completeDocument">Complete & Upload Document</span>
                    <span wire:loading wire:target="completeDocument" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Uploading...
                    </span>
                </button>
            </form>
        </div>
    @endif

    @if($request->status === 'revision_requested')
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-yellow-900 mb-2">Revision Requested</h3>
                    <p class="text-yellow-800 mb-4">
                        The client has requested revisions to the document. 
                        Revisions used: {{ $request->revisions_used }} of {{ $request->revisions_allowed }}
                    </p>
                    
                    @if($request->revision_notes)
                        <div class="bg-white rounded-lg p-4 mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Client's Revision Notes:</p>
                            <p class="text-gray-900">{{ $request->revision_notes }}</p>
                        </div>
                    @endif

                    <button wire:click="startRevision" 
                        class="px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed">
                        <span wire:loading.remove wire:target="startRevision">Start Working on Revision</span>
                        <span wire:loading wire:target="startRevision">Starting...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($request->status === 'completed' && $request->draft_document_path)
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-green-900 mb-2">Document Completed</h3>
                    <p class="text-green-800 mb-4">
                        Document has been completed and delivered to the client.
                        Completed on {{ $request->completed_at->format('M d, Y h:i A') }}
                    </p>
                    <button wire:click="downloadExistingDocument" 
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download Document
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Client's Submitted Information -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Client's Submitted Information</h2>
        
        <div class="space-y-4">
            @foreach($request->form_data as $fieldId => $value)
                @php
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
                <p class="text-sm font-medium text-gray-700 mb-2">Client's Additional Notes</p>
                <p class="text-gray-900">{{ $request->client_notes }}</p>
            </div>
        @endif
    </div>
</div>

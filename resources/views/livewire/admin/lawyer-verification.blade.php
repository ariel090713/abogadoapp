<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Lawyer Verification</h1>
        <p class="text-gray-600 mt-2">Review and verify lawyer applications</p>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Lawyers</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Verification</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ number_format($stats['pending']) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Verified Lawyers</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($stats['verified']) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ number_format($stats['rejected']) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by name, email, or IBP number..."
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
                    <option value="pending">Pending Verification</option>
                    <option value="verified">Verified</option>
                    <option value="rejected">Rejected</option>
                    <option value="all">All Lawyers</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Lawyers Table -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lawyer</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IBP Number</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Experience</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specializations</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($lawyers as $lawyer)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                @if($lawyer->user)
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                                            {{ $lawyer->user->initials() }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $lawyer->user->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $lawyer->user->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400">User deleted</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm text-gray-900">{{ $lawyer->ibp_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-900">{{ $lawyer->years_experience }} years</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($lawyer->specializations->take(2) as $spec)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                                            {{ $spec->name }}
                                        </span>
                                    @endforeach
                                    @if($lawyer->specializations->count() > 2)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                                            +{{ $lawyer->specializations->count() - 2 }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($lawyer->is_verified)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full inline-flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button 
                                        wire:click="viewDetails({{ $lawyer->id }})"
                                        class="px-3 py-1.5 bg-primary-700 text-white text-sm rounded-lg hover:bg-primary-800 transition"
                                    >
                                        View Details
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium">No lawyers found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your search or filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($lawyers->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $lawyers->links() }}
            </div>
        @endif
    </div>

    <!-- View Details Modal -->
    @if($showDetailsModal && $selectedLawyer)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">Lawyer Details</h2>
                    <button 
                        wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-600 transition"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    @if($selectedLawyer->user)
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Full Name</p>
                                    <p class="font-medium text-gray-900">{{ $selectedLawyer->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Email</p>
                                    <p class="font-medium text-gray-900">{{ $selectedLawyer->user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Phone</p>
                                    <p class="font-medium text-gray-900">{{ $selectedLawyer->user->phone ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Joined</p>
                                    <p class="font-medium text-gray-900">{{ $selectedLawyer->user->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Professional Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">IBP Number</p>
                                    <p class="font-medium text-gray-900 font-mono">{{ $selectedLawyer->ibp_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Years of Experience</p>
                                    <p class="font-medium text-gray-900">{{ $selectedLawyer->years_experience }} years</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Law School</p>
                                    <p class="font-medium text-gray-900">{{ $selectedLawyer->law_school }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Graduation Year</p>
                                    <p class="font-medium text-gray-900">{{ $selectedLawyer->graduation_year }}</p>
                                </div>
                                @if($selectedLawyer->law_firm)
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-600">Law Firm</p>
                                        <p class="font-medium text-gray-900">{{ $selectedLawyer->law_firm }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Verification Documents -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Verification Documents</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 border border-gray-200 rounded-lg">
                                    <p class="text-sm text-gray-600 mb-2">IBP Card / ID</p>
                                    @if($selectedLawyer->ibp_card_path)
                                        <a 
                                            href="{{ route('admin.lawyer.document', ['lawyer' => $selectedLawyer->id, 'type' => 'ibp']) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition font-medium"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View Document
                                        </a>
                                    @else
                                        <p class="text-sm text-gray-500 italic">No document uploaded</p>
                                    @endif
                                </div>
                                
                                <div class="p-4 border border-gray-200 rounded-lg">
                                    <p class="text-sm text-gray-600 mb-2">Supporting Document</p>
                                    @if($selectedLawyer->supporting_document_path)
                                        <a 
                                            href="{{ route('admin.lawyer.document', ['lawyer' => $selectedLawyer->id, 'type' => 'supporting']) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition font-medium"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View Document
                                        </a>
                                    @else
                                        <p class="text-sm text-gray-500 italic">No document uploaded</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Specializations -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Specializations</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($selectedLawyer->specializations as $spec)
                                    <span class="px-4 py-2 bg-primary-100 text-primary-700 rounded-lg font-medium">
                                        {{ $spec->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Bio -->
                        @if($selectedLawyer->bio)
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Bio</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $selectedLawyer->bio }}</p>
                            </div>
                        @endif

                        <!-- Languages -->
                        @if($selectedLawyer->languages)
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Languages</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($selectedLawyer->languages as $language)
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                            {{ $language }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Verification Status -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Verification Status</h3>
                            <div class="p-4 rounded-xl {{ $selectedLawyer->is_verified ? 'bg-green-50 border border-green-200' : ($selectedLawyer->is_rejected ? 'bg-red-50 border border-red-200' : 'bg-yellow-50 border border-yellow-200') }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        @if($selectedLawyer->is_verified)
                                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <div>
                                                <p class="font-semibold text-green-900">Verified</p>
                                                <p class="text-sm text-green-700">Verified on {{ $selectedLawyer->verified_at?->format('M d, Y h:i A') ?? 'N/A' }}</p>
                                            </div>
                                        @elseif($selectedLawyer->is_rejected)
                                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            <div>
                                                <p class="font-semibold text-red-900">Rejected</p>
                                                <p class="text-sm text-red-700">Rejected on {{ $selectedLawyer->rejected_at?->format('M d, Y h:i A') ?? 'N/A' }}</p>
                                                @if($selectedLawyer->rejection_reason)
                                                    <p class="text-sm text-red-700 mt-1"><span class="font-medium">Reason:</span> {{ $selectedLawyer->rejection_reason }}</p>
                                                @endif
                                            </div>
                                        @else
                                            <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            <div>
                                                <p class="font-semibold text-yellow-900">Pending Verification</p>
                                                <p class="text-sm text-yellow-700">Awaiting admin review</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Toggle Switch -->
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-600">Verified</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                wire:click="toggleVerification({{ $selectedLawyer->id }})"
                                                {{ $selectedLawyer->is_verified ? 'checked' : '' }}
                                                class="sr-only peer"
                                            >
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500">User information not available</p>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-end gap-3">
                    <button 
                        wire:click="closeModal"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium"
                    >
                        Close
                    </button>
                    @if(!$selectedLawyer->is_verified)
                        <button 
                            wire:click="openRejectModal({{ $selectedLawyer->id }})"
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium"
                        >
                            Reject Application
                        </button>
                        <button 
                            wire:click="verifyLawyer({{ $selectedLawyer->id }})"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium"
                        >
                            <span wire:loading.remove wire:target="verifyLawyer({{ $selectedLawyer->id }})">✓ Verify Lawyer</span>
                            <span wire:loading wire:target="verifyLawyer({{ $selectedLawyer->id }})">Processing...</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Reject Modal -->
    @if($showRejectModal && $selectedLawyer)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full">
                <!-- Modal Header -->
                <div class="bg-red-50 border-b border-red-200 px-6 py-4">
                    <h2 class="text-xl font-bold text-red-900">Reject Lawyer Application</h2>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4">
                    @if($selectedLawyer->user)
                        <p class="text-gray-700">
                            You are about to reject the application of <span class="font-semibold">{{ $selectedLawyer->user->name }}</span>. 
                            Please provide a reason for rejection.
                        </p>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection</label>
                            <textarea 
                                wire:model="rejectReason"
                                rows="4"
                                placeholder="Explain why this application is being rejected..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            ></textarea>
                            @error('rejectReason')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-end gap-3">
                    <button 
                        wire:click="closeModal"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="rejectLawyer"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium"
                    >
                        <span wire:loading.remove wire:target="rejectLawyer">Reject Application</span>
                        <span wire:loading wire:target="rejectLawyer">Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

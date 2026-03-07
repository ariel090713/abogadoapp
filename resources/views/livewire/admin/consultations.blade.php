<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="p-4 sm:p-6 lg:p-8">
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Consultations Management</h1>
            <p class="mt-1 text-gray-600">Monitor and manage all consultations</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-yellow-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-green-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['completed'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Cancelled</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['cancelled'] }}</p>
                </div>
                <div class="bg-red-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <flux:input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search by ID, title, client, or lawyer..."
                    icon="magnifying-glass"
                />
            </div>
            <div>
                <flux:select wire:model.live="statusFilter">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="pending_client_acceptance">Pending Client Acceptance</option>
                    <option value="awaiting_quote_approval">Awaiting Quote Approval</option>
                    <option value="payment_pending">Payment Pending</option>
                    <option value="accepted">Accepted</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="declined">Declined</option>
                    <option value="expired">Expired</option>
                </flux:select>
            </div>
            <div>
                <flux:select wire:model.live="typeFilter">
                    <option value="all">All Types</option>
                    <option value="chat">Chat</option>
                    <option value="video">Video</option>
                    <option value="document_review">Document Review</option>
                </flux:select>
            </div>
        </div>
    </div>


    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alert</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lawyer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($consultations as $consultation)
                        @php
                            $isUrgent = false;
                            $urgencyClass = '';
                            $urgencyLabel = '';
                            $urgencyIcon = '';
                            $urgencyColor = '';
                            
                            // Check for payment deadline urgency
                            if ($consultation->payment_deadline && $consultation->status === 'payment_pending') {
                                $hoursUntilDeadline = now()->diffInHours($consultation->payment_deadline, false);
                                if ($hoursUntilDeadline < 0) {
                                    $isUrgent = true;
                                    $urgencyClass = 'bg-red-50';
                                    $urgencyLabel = 'EXPIRED';
                                    $urgencyIcon = '🔴';
                                    $urgencyColor = 'text-red-700 font-bold';
                                } elseif ($hoursUntilDeadline <= 2) {
                                    $isUrgent = true;
                                    $urgencyClass = 'bg-red-50';
                                    $urgencyLabel = 'CRITICAL: ' . round($hoursUntilDeadline) . 'h';
                                    $urgencyIcon = '🔴';
                                    $urgencyColor = 'text-red-600 font-semibold';
                                } elseif ($hoursUntilDeadline <= 6) {
                                    $isUrgent = true;
                                    $urgencyClass = 'bg-orange-50';
                                    $urgencyLabel = round($hoursUntilDeadline) . 'h to pay';
                                    $urgencyIcon = '🟠';
                                    $urgencyColor = 'text-orange-600 font-semibold';
                                }
                            }
                            
                            // Check for scheduled consultation urgency
                            if ($consultation->scheduled_at && in_array($consultation->status, ['scheduled', 'accepted'])) {
                                $hoursUntilConsultation = now()->diffInHours($consultation->scheduled_at, false);
                                if ($hoursUntilConsultation < 0) {
                                    $isUrgent = true;
                                    $urgencyClass = 'bg-red-50';
                                    $urgencyLabel = 'OVERDUE';
                                    $urgencyIcon = '🔴';
                                    $urgencyColor = 'text-red-700 font-bold';
                                } elseif ($hoursUntilConsultation <= 1) {
                                    $isUrgent = true;
                                    $urgencyClass = 'bg-red-50';
                                    $urgencyLabel = 'NOW: ' . round($hoursUntilConsultation * 60) . 'min';
                                    $urgencyIcon = '🔴';
                                    $urgencyColor = 'text-red-600 font-bold';
                                } elseif ($hoursUntilConsultation <= 24) {
                                    $isUrgent = true;
                                    $urgencyClass = 'bg-yellow-50';
                                    $urgencyLabel = 'In ' . round($hoursUntilConsultation) . 'h';
                                    $urgencyIcon = '🟡';
                                    $urgencyColor = 'text-yellow-700 font-semibold';
                                }
                            }
                            
                            // Check for lawyer response deadline
                            if ($consultation->lawyer_response_deadline && $consultation->status === 'pending') {
                                $hoursUntilDeadline = now()->diffInHours($consultation->lawyer_response_deadline, false);
                                if ($hoursUntilDeadline < 0) {
                                    $isUrgent = true;
                                    $urgencyClass = 'bg-red-50';
                                    $urgencyLabel = 'NO RESPONSE';
                                    $urgencyIcon = '🔴';
                                    $urgencyColor = 'text-red-700 font-bold';
                                } elseif ($hoursUntilDeadline <= 2) {
                                    $isUrgent = true;
                                    $urgencyClass = 'bg-orange-50';
                                    $urgencyLabel = round($hoursUntilDeadline) . 'h to respond';
                                    $urgencyIcon = '🟠';
                                    $urgencyColor = 'text-orange-600 font-semibold';
                                } elseif ($hoursUntilDeadline <= 12) {
                                    $isUrgent = true;
                                    $urgencyClass = 'bg-yellow-50';
                                    $urgencyLabel = round($hoursUntilDeadline) . 'h to respond';
                                    $urgencyIcon = '🟡';
                                    $urgencyColor = 'text-yellow-700';
                                }
                            }
                            
                            // Check for pending too long (more than 24h without lawyer response)
                            if ($consultation->status === 'pending' && !$consultation->lawyer_response_deadline) {
                                $hoursSinceCreated = now()->diffInHours($consultation->created_at);
                                if ($hoursSinceCreated > 24) {
                                    $isUrgent = true;
                                    $urgencyClass = 'bg-orange-50';
                                    $urgencyLabel = 'Pending ' . round($hoursSinceCreated) . 'h';
                                    $urgencyIcon = '⚠️';
                                    $urgencyColor = 'text-orange-600';
                                }
                            }
                        @endphp
                        <tr class="hover:bg-gray-100 {{ $urgencyClass }}">
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($isUrgent)
                                    <div class="flex flex-col items-center">
                                        <span class="text-2xl">{{ $urgencyIcon }}</span>
                                        <span class="text-xs {{ $urgencyColor }} mt-1">{{ $urgencyLabel }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#{{ $consultation->id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($consultation->client)
                                    <div class="text-sm font-medium text-gray-900">{{ $consultation->client->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $consultation->client->email }}</div>
                                @else
                                    <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($consultation->lawyer)
                                    <div class="text-sm font-medium text-gray-900">{{ $consultation->lawyer->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $consultation->lawyer->email }}</div>
                                @else
                                    <span class="text-sm text-gray-400">Not assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $consultation->consultation_type === 'chat' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $consultation->consultation_type === 'video' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $consultation->consultation_type === 'document_review' ? 'bg-green-100 text-green-800' : '' }}
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $consultation->consultation_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $consultation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $consultation->status === 'accepted' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $consultation->status === 'scheduled' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                    {{ $consultation->status === 'in_progress' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $consultation->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $consultation->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $consultation->status === 'declined' ? 'bg-gray-100 text-gray-800' : '' }}
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $consultation->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ₱{{ number_format($consultation->total_amount ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $consultation->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a 
                                    href="{{ route('admin.consultation.details', $consultation->id) }}" 
                                    class="px-3 py-1 text-sm text-white bg-primary-700 hover:bg-primary-800 rounded inline-block transition"
                                >
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-4 text-lg font-medium">No consultations found</p>
                                <p class="mt-2 text-sm">Try adjusting your search or filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($consultations->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $consultations->links() }}
            </div>
        @endif
    </div>
</div>
</div>

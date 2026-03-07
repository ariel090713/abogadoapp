<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">User Management</h1>
        <p class="text-gray-600 mt-1">Manage all platform users</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow p-4 border border-gray-100">
            <p class="text-xs text-gray-600 mb-1">Total Users</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border border-gray-100">
            <p class="text-xs text-gray-600 mb-1">Clients</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['clients']) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border border-gray-100">
            <p class="text-xs text-gray-600 mb-1">Lawyers</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['lawyers']) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border border-gray-100">
            <p class="text-xs text-gray-600 mb-1">Admins</p>
            <p class="text-2xl font-bold text-accent-600">{{ number_format($stats['admins']) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border border-gray-100">
            <p class="text-xs text-gray-600 mb-1">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['active']) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border border-gray-100">
            <p class="text-xs text-gray-600 mb-1">Suspended</p>
            <p class="text-2xl font-bold text-red-600">{{ number_format($stats['suspended']) }}</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by name or email..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select wire:model.live="roleFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option value="">All Roles</option>
                    <option value="client">Client</option>
                    <option value="lawyer">Lawyer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select wire:model.live="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Suspended</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Suspend</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($user->profile_photo)
                                        <img src="{{ $user->profile_photo }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold">
                                            {{ $user->initials() }}
                                        </div>
                                    @endif
                                    <div>
                                        <a href="#" class="font-medium text-gray-900 hover:text-primary-600">{{ $user->name }}</a>
                                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs rounded-full font-medium
                                    {{ $user->role === 'admin' ? 'bg-accent-100 text-accent-800' : '' }}
                                    {{ $user->role === 'lawyer' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $user->role === 'client' ? 'bg-blue-100 text-blue-800' : '' }}
                                ">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs rounded-full font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Active' : 'Suspended' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    @if($user->role !== 'admin')
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                wire:click="toggleUserStatus({{ $user->id }})"
                                                {{ $user->is_active ? 'checked' : '' }}
                                                class="sr-only peer"
                                            >
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                        </label>
                                    @else
                                        <span class="text-xs text-gray-400">N/A</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <button 
                                        wire:click="viewUser({{ $user->id }})"
                                        class="px-3 py-1.5 bg-primary-700 text-white text-sm rounded-lg hover:bg-primary-800 transition"
                                    >
                                        View Details
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>

    <!-- User Details Modal -->
    @if($showUserModal && $selectedUser)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">User Details</h2>
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
                    <!-- Personal Information -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Full Name</p>
                                <p class="font-medium text-gray-900">{{ $selectedUser->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium text-gray-900">{{ $selectedUser->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-medium text-gray-900">{{ $selectedUser->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Joined</p>
                                <p class="font-medium text-gray-900">{{ $selectedUser->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Account Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Role</p>
                                <p class="font-medium text-gray-900">{{ ucfirst($selectedUser->role) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <p class="font-medium {{ $selectedUser->is_active ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $selectedUser->is_active ? 'Active' : 'Suspended' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email Verified</p>
                                <p class="font-medium text-gray-900">{{ $selectedUser->email_verified_at ? $selectedUser->email_verified_at->format('M d, Y h:i A') : 'Not Verified' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Last Login</p>
                                <p class="font-medium text-gray-900">{{ $selectedUser->last_login_at ? $selectedUser->last_login_at->format('M d, Y h:i A') : 'Never' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Last Seen</p>
                                <p class="font-medium text-gray-900">{{ $selectedUser->lastSeenHuman() }}</p>
                            </div>
                            @if($selectedUser->city || $selectedUser->province)
                                <div>
                                    <p class="text-sm text-gray-600">Location</p>
                                    <p class="font-medium text-gray-900">{{ $selectedUser->city && $selectedUser->province ? $selectedUser->city . ', ' . $selectedUser->province : ($selectedUser->city ?? $selectedUser->province) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Professional Information (Lawyer Only) -->
                    @if($selectedUser->role === 'lawyer' && $selectedUser->lawyerProfile)
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Professional Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">IBP Number</p>
                                    <p class="font-medium text-gray-900 font-mono">{{ $selectedUser->lawyerProfile->ibp_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Years of Experience</p>
                                    <p class="font-medium text-gray-900">{{ $selectedUser->lawyerProfile->years_experience }} years</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Law School</p>
                                    <p class="font-medium text-gray-900">{{ $selectedUser->lawyerProfile->law_school ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Graduation Year</p>
                                    <p class="font-medium text-gray-900">{{ $selectedUser->lawyerProfile->graduation_year ?? 'N/A' }}</p>
                                </div>
                                @if($selectedUser->lawyerProfile->law_firm)
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-600">Law Firm</p>
                                        <p class="font-medium text-gray-900">{{ $selectedUser->lawyerProfile->law_firm }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Specializations -->
                        @if($selectedUser->lawyerProfile->specializations && $selectedUser->lawyerProfile->specializations->count() > 0)
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Specializations</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($selectedUser->lawyerProfile->specializations as $spec)
                                        <span class="px-4 py-2 bg-primary-100 text-primary-700 rounded-lg font-medium">
                                            {{ $spec->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Bio -->
                        @if($selectedUser->lawyerProfile->bio)
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Bio</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $selectedUser->lawyerProfile->bio }}</p>
                            </div>
                        @endif

                        <!-- Languages -->
                        @if($selectedUser->lawyerProfile->languages)
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Languages</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($selectedUser->lawyerProfile->languages as $language)
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
                            <div class="p-4 rounded-xl {{ $selectedUser->lawyerProfile->is_verified ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                                <div class="flex items-center gap-3">
                                    @if($selectedUser->lawyerProfile->is_verified)
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p class="font-semibold text-green-900">Verified</p>
                                            <p class="text-sm text-green-700">Verified on {{ $selectedUser->lawyerProfile->verified_at?->format('M d, Y h:i A') ?? 'N/A' }}</p>
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
                            </div>
                        </div>
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
                </div>
            </div>
        </div>
    @endif
</div>

<x-slot name="sidebar">
    <x-client-sidebar />
</x-slot>

<div class="min-h-screen bg-white">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Info Panel with Header -->
        <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-100 rounded-2xl p-4 sm:p-6 mb-6">
            <div class="flex items-start gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Security Settings</h1>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Manage your password and security preferences.
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <x-profile-nav type="client" current="security" />

        <!-- Security Options -->
        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 space-y-6">
            <h3 class="text-lg font-semibold text-gray-900">Security Settings</h3>
            <p class="text-gray-600">Manage your password and security preferences</p>
            
            <div class="space-y-4">
                <a href="{{ route('user-password.edit') }}" class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Change Password</p>
                            <p class="text-sm text-gray-500">Update your password regularly for security</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <a href="{{ route('two-factor.show') }}" class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Two-Factor Authentication</p>
                            <p class="text-sm text-gray-500">Add an extra layer of security to your account</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

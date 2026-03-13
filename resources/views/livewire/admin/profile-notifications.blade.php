<x-slot name="sidebar">
    <x-admin-sidebar />
</x-slot>

<div class="min-h-screen bg-white">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Info Panel with Header -->
        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 rounded-2xl p-4 sm:p-6 mb-6">
            <div class="flex items-start gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Notification Preferences</h1>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Manage how you receive notifications.
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <x-profile-nav type="admin" current="notifications" />

        <!-- Notification Settings -->
        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 space-y-6">
            <h3 class="text-lg font-semibold text-gray-900">Email Notifications</h3>
            <p class="text-gray-600">Choose what notifications you want to receive via email</p>
            
            <div class="space-y-4">
                <!-- System Alerts -->
                <div class="flex items-start justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">System Alerts</p>
                        <p class="text-sm text-gray-500">Critical system notifications and alerts</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked disabled class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>

                <!-- User Activity -->
                <div class="flex items-start justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">User Activity</p>
                        <p class="text-sm text-gray-500">New user registrations and verifications</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>

                <!-- Transaction Alerts -->
                <div class="flex items-start justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">Transaction Alerts</p>
                        <p class="text-sm text-gray-500">Payment and refund notifications</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>

                <!-- Reports -->
                <div class="flex items-start justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">Weekly Reports</p>
                        <p class="text-sm text-gray-500">Platform statistics and analytics</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    Note: System alerts cannot be disabled as they contain critical information about platform operations.
                </p>
            </div>
        </div>
    </div>
</div>

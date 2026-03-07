<x-slot name="sidebar">
    <x-client-sidebar />
</x-slot>

<div class="min-h-screen bg-white">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Info Panel with Header -->
        <div class="bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-100 rounded-2xl p-4 sm:p-6 mb-6">
            <div class="flex items-start gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Notification Preferences</h1>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Choose how you want to be notified.
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <x-profile-nav type="client" current="notifications" />

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg" x-data x-init="$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))">
                <p class="text-sm text-green-600 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Notification Settings -->
        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 space-y-6">
            <h3 class="text-lg font-semibold text-gray-900">Notification Preferences</h3>
            <p class="text-gray-600">Choose how you want to be notified. Changes are saved automatically.</p>
            
            <div class="space-y-4">
                <div class="flex items-start justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">Consultation Updates</p>
                        <p class="text-sm text-gray-500 mt-1">Get notified about consultation status changes</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="notif_consultation_updates_mail" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>

                <div class="flex items-start justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">Payment Notifications</p>
                        <p class="text-sm text-gray-500 mt-1">Receive updates about payments</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="notif_payment_updates_mail" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>

                <div class="flex items-start justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">Marketing Emails</p>
                        <p class="text-sm text-gray-500 mt-1">Receive updates about new features and promotions</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="notif_marketing_mail" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unsubscribe - AbogadoMo App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-gray-50">
    <x-guest-navbar />

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                @if($status === 'confirm')
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Unsubscribe from Newsletter</h2>
                        <p class="text-gray-600">Are you sure you want to unsubscribe from our newsletter?</p>
                        <p class="text-sm text-gray-500 mt-2">{{ $subscriber->email }}</p>
                    </div>

                    <form action="{{ route('newsletter.confirm-unsubscribe', $subscriber->token) }}" method="POST" class="space-y-4">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 bg-accent-600 text-white rounded-xl font-semibold hover:bg-accent-700 transition">
                            Yes, Unsubscribe Me
                        </button>
                        <a href="{{ route('home') }}" class="block w-full px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition text-center">
                            Cancel
                        </a>
                    </form>

                @elseif($status === 'success')
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Successfully Unsubscribed</h2>
                        <p class="text-gray-600 mb-6">{{ $message }}</p>
                        <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-primary-700 text-white rounded-xl font-semibold hover:bg-primary-800 transition">
                            Return to Home
                        </a>
                    </div>

                @elseif($status === 'info')
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Already Unsubscribed</h2>
                        <p class="text-gray-600 mb-6">{{ $message }}</p>
                        <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-primary-700 text-white rounded-xl font-semibold hover:bg-primary-800 transition">
                            Return to Home
                        </a>
                    </div>

                @else
                    <div class="text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Error</h2>
                        <p class="text-gray-600 mb-6">{{ $message }}</p>
                        <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-primary-700 text-white rounded-xl font-semibold hover:bg-primary-800 transition">
                            Return to Home
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    <!-- Navbar -->
    <x-guest-navbar />

    <!-- Coming Soon Content -->
    <div class="min-h-screen flex items-center justify-center px-4 py-20">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Icon -->
            <div class="mb-8 flex justify-center">
                <div class="bg-primary-100 rounded-full p-6">
                    <svg class="w-20 h-20 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Heading -->
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                Coming Soon
            </h1>

            <!-- Description -->
            <p class="text-xl text-gray-600 mb-8">
                We're working hard to bring you something amazing. Stay tuned!
            </p>

            <!-- Features Preview -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                <div class="bg-gray-50 rounded-2xl p-6 text-left">
                    <div class="bg-primary-100 rounded-lg p-3 w-12 h-12 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Webinars</h3>
                    <p class="text-gray-600">Join live legal education sessions with expert lawyers</p>
                </div>

                <div class="bg-gray-50 rounded-2xl p-6 text-left">
                    <div class="bg-accent-100 rounded-lg p-3 w-12 h-12 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-accent-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Community</h3>
                    <p class="text-gray-600">Connect with others and share legal insights</p>
                </div>
            </div>

            <!-- Coming Soon Message -->
            <div class="bg-gray-50 rounded-2xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Stay Tuned</h3>
                <p class="text-gray-600">We're working hard to bring these features to you soon!</p>
            </div>

            <!-- Back to Home -->
            <div class="mt-12">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-primary-700 hover:text-primary-800 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>

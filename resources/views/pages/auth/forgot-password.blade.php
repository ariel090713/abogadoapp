<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <!-- Icon -->
        <div class="flex justify-center">
            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Forgot Password?</h1>
            <p class="text-gray-600">No worries! Enter your email and we'll send you reset instructions</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-green-700 text-center font-medium">{{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5" x-data="{ loading: false }" @submit="loading = true">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                label="Email address"
                type="email"
                required
                autofocus
                placeholder="email@example.com"
            />

            <button 
                type="submit" 
                class="w-full py-3 text-base font-semibold bg-[#1E3A8A] text-white rounded-xl hover:bg-[#1E40AF] transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                :disabled="loading"
            >
                <span x-show="!loading">Send Reset Link</span>
                <span x-show="loading" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending...
                </span>
            </button>
        </form>

        <div class="text-center text-sm text-gray-600">
            <span>Remember your password?</span>
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-semibold ml-1">
                Back to login
            </a>
        </div>
    </div>
</x-layouts::auth>

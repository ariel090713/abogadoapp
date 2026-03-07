<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <!-- Icon -->
        <div class="flex justify-center">
            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Reset Password</h1>
            <p class="text-gray-600">Enter your new password below</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-green-700 text-center">{{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-5" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <flux:input
                name="email"
                value="{{ request('email') }}"
                label="Email address"
                type="email"
                required
                autocomplete="email"
                readonly
            />

            <!-- Password -->
            <flux:input
                name="password"
                label="New Password"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Create a strong password"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                label="Confirm password"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Re-enter your password"
                viewable
            />

            <button 
                type="submit" 
                class="w-full py-3 text-base font-semibold bg-[#1E3A8A] text-white rounded-xl hover:bg-[#1E40AF] transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                :disabled="loading"
            >
                <span x-show="!loading">Reset Password</span>
                <span x-show="loading" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Resetting...
                </span>
            </button>
        </form>
    </div>
</x-layouts::auth>

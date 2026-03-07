<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h1>
            <p class="text-gray-600">Log in to access your account</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-green-700 text-center">{{ session('status') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm text-red-700 text-center">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Social Login Buttons -->
        <div class="space-y-3">
            <a href="{{ route('auth.google') }}" 
               class="flex items-center justify-center gap-3 w-full px-4 py-3 border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition font-medium text-gray-700 shadow-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span>Continue with Google</span>
            </a>
            
            <a href="{{ route('auth.facebook') }}" 
               class="flex items-center justify-center gap-3 w-full px-4 py-3 bg-[#1877F2] text-white rounded-xl hover:bg-[#166FE5] transition font-medium shadow-sm">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                <span>Continue with Facebook</span>
            </a>
        </div>
        
        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-gray-500 font-medium">Or continue with email</span>
            </div>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5" x-data="{ loading: false }" @submit="loading = true">
            @csrf

            <!-- Email Address -->
            <div>
                <flux:input
                    name="email"
                    label="Email address"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="email@example.com"
                />
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <flux:label>Password</flux:label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <flux:input
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                    viewable
                />
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" label="Remember me for 30 days" :checked="old('remember')" />

            <button 
                type="submit" 
                class="w-full py-3 text-base font-semibold bg-[#1E3A8A] text-white rounded-xl hover:bg-[#1E40AF] transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                :disabled="loading"
            >
                <span x-show="!loading">Log in</span>
                <span x-show="loading" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Logging in...
                </span>
            </button>
        </form>

        @if (Route::has('register'))
            <div class="text-center text-sm text-gray-600">
                <span>Don't have an account?</span>
                <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-semibold ml-1">
                    Sign up
                </a>
            </div>
        @endif
    </div>
</x-layouts::auth>

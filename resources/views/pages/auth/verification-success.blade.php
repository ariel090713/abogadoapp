<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <!-- Success Icon with Animation -->
        <div class="flex justify-center">
            <div class="relative">
                <!-- Animated Circle -->
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center animate-pulse">
                    <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <!-- Confetti Effect -->
                <div class="absolute inset-0 -z-10">
                    <div class="absolute top-0 left-0 w-2 h-2 bg-primary-500 rounded-full animate-ping"></div>
                    <div class="absolute top-0 right-0 w-2 h-2 bg-accent-500 rounded-full animate-ping" style="animation-delay: 0.2s"></div>
                    <div class="absolute bottom-0 left-0 w-2 h-2 bg-gold-500 rounded-full animate-ping" style="animation-delay: 0.4s"></div>
                    <div class="absolute bottom-0 right-0 w-2 h-2 bg-green-500 rounded-full animate-ping" style="animation-delay: 0.6s"></div>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Email Verified!</h1>
            <p class="text-gray-600">Your email has been successfully verified</p>
        </div>

        <!-- Success Message -->
        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <h3 class="font-semibold text-green-900 mb-1">Welcome to AbogadoMo!</h3>
                    <p class="text-sm text-green-800">You can now access all features of your account. Let's complete your profile setup.</p>
                </div>
            </div>
        </div>

        <!-- Countdown -->
        <div class="text-center" x-data="{ countdown: 5 }" x-init="
            let interval = setInterval(() => {
                countdown--;
                if (countdown === 0) {
                    clearInterval(interval);
                    window.location.href = '{{ route('onboarding.start') }}';
                }
            }, 1000);
        ">
            <div class="inline-flex items-center gap-3 bg-gray-50 rounded-xl px-6 py-4 border border-gray-200">
                <svg class="w-5 h-5 text-gray-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <div>
                    <p class="text-sm text-gray-600">Redirecting in</p>
                    <p class="text-2xl font-bold text-gray-900">
                        <span x-text="countdown"></span> <span x-text="countdown === 1 ? 'second' : 'seconds'"></span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Manual Continue Button -->
        <div class="flex flex-col gap-3">
            <a href="{{ route('onboarding.start') }}" class="w-full">
                <flux:button variant="primary" class="w-full py-3 text-base font-semibold">
                    Continue to Setup
                </flux:button>
            </a>
            <p class="text-xs text-center text-gray-500">Or wait for automatic redirect</p>
        </div>
    </div>
</x-layouts::auth>

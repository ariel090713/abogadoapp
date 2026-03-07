<x-layouts::auth>
    <div class="mt-4 flex flex-col gap-6">
        <!-- Icon -->
        <div class="flex justify-center">
            <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Verify Your Email</h2>
            <flux:text class="text-gray-600">
                {{ __('We sent a verification link to your email address. Please check your inbox and click the link to verify your account.') }}
            </flux:text>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <flux:text class="text-center font-medium text-green-700">
                    {{ __('A new verification link has been sent to your email address.') }}
                </flux:text>
            </div>
        @endif

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="font-semibold text-blue-900 mb-2 text-sm">Didn't receive the email?</h4>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• Check your spam or junk folder</li>
                <li>• Make sure you entered the correct email address</li>
                <li>• Click the button below to resend the verification email</li>
            </ul>
        </div>

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <flux:button type="submit" variant="primary" class="w-full">
                    {{ __('Resend Verification Email') }}
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button variant="ghost" type="submit" class="text-sm cursor-pointer" data-test="logout-button">
                    {{ __('Log out') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>

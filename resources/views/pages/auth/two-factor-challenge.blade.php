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

        <div
            class="relative w-full h-auto"
            x-cloak
            x-data="{
                showRecoveryInput: @js($errors->has('recovery_code')),
                code: '',
                recovery_code: '',
                toggleInput() {
                    this.showRecoveryInput = !this.showRecoveryInput;
                    this.code = '';
                    this.recovery_code = '';
                    $dispatch('clear-2fa-auth-code');
                    $nextTick(() => {
                        this.showRecoveryInput
                            ? this.$refs.recovery_code?.focus()
                            : $dispatch('focus-2fa-auth-code');
                    });
                },
            }"
        >
            <!-- Header -->
            <div x-show="!showRecoveryInput" class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Two-Factor Authentication</h1>
                <p class="text-gray-600">Enter the 6-digit code from your authenticator app</p>
            </div>

            <div x-show="showRecoveryInput" class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Recovery Code</h1>
                <p class="text-gray-600">Enter one of your emergency recovery codes</p>
            </div>

            <form method="POST" action="{{ route('two-factor.login.store') }}">
                @csrf

                <div class="space-y-6">
                    <!-- OTP Input -->
                    <div x-show="!showRecoveryInput">
                        <div class="flex items-center justify-center my-6">
                            <flux:otp
                                x-model="code"
                                length="6"
                                name="code"
                                label="OTP Code"
                                label:sr-only
                                class="mx-auto"
                             />
                        </div>
                    </div>

                    <!-- Recovery Code Input -->
                    <div x-show="showRecoveryInput">
                        <div class="my-6">
                            <flux:input
                                type="text"
                                name="recovery_code"
                                x-ref="recovery_code"
                                x-bind:required="showRecoveryInput"
                                autocomplete="one-time-code"
                                x-model="recovery_code"
                                placeholder="Enter recovery code"
                            />
                        </div>

                        @error('recovery_code')
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                                <p class="text-sm text-red-700">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <flux:button
                        variant="primary"
                        type="submit"
                        class="w-full py-3 text-base font-semibold"
                    >
                        Verify & Continue
                    </flux:button>
                </div>

                <div class="mt-6 text-center text-sm text-gray-600">
                    <span>Having trouble?</span>
                    <button type="button" @click="toggleInput()" class="text-primary-600 hover:text-primary-700 font-semibold ml-1">
                        <span x-show="!showRecoveryInput">Use recovery code</span>
                        <span x-show="showRecoveryInput">Use authenticator code</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::auth>

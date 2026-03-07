<x-layouts::guest>
    <div class="min-h-screen bg-white flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full">
            <!-- Card Container -->
            <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12 text-center border border-gray-200">
                <!-- Success Icon -->
                <div class="flex justify-center mb-8">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>

                <!-- Success Message -->
                <h1 class="text-4xl font-bold text-gray-900 mb-4">You're All Set!</h1>
                
                @if(auth()->user()->role === 'lawyer')
                    <p class="text-xl text-gray-600 mb-8">
                        Your lawyer profile has been submitted for verification. We'll review your credentials and notify you once approved.
                    </p>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8 text-left max-w-lg mx-auto">
                        <h3 class="font-semibold text-blue-900 mb-3">What's Next?</h3>
                        <ul class="text-sm text-blue-800 space-y-2">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Our team will verify your IBP credentials</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>You'll receive an email notification (usually within 24-48 hours)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Once approved, you can start accepting consultations</span>
                            </li>
                        </ul>
                    </div>
                @else
                    <p class="text-xl text-gray-600 mb-8">
                        Welcome to AbogadoMo! Your account is ready. You can now browse lawyers and book consultations.
                    </p>
                @endif

                <!-- Manual Button -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                    <x-button href="{{ route('dashboard') }}" variant="primary" class="text-lg shadow-lg">
                        Go to Dashboard
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</x-layouts::guest>

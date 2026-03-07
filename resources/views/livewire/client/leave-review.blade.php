<x-slot name="sidebar">
    <x-client-sidebar />
</x-slot>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                {{ $isEditing ? 'Edit Your Review' : 'Leave a Review' }}
            </h1>
            <p class="text-gray-600">Share your experience to help others make informed decisions</p>
            
            @if($isEditing && $existingReview && $existingReview->canEdit())
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <svg class="w-5 h-5 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        You can edit this review until {{ $existingReview->created_at->addDays(7)->format('M d, Y') }}. After that, it will be permanent.
                    </p>
                </div>
            @endif
        </div>

        <!-- Review Form -->
        <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
            <form wire:submit.prevent="submit" class="space-y-6">
                <!-- Rating -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                        Rating <span class="text-accent-600">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <button 
                                type="button"
                                wire:click="setRating({{ $i }})"
                                class="focus:outline-none transition-transform hover:scale-110"
                            >
                                <svg class="w-10 h-10 {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                        @if($rating > 0)
                            <span class="ml-3 text-lg font-semibold text-gray-700">{{ $rating }} out of 5</span>
                        @endif
                    </div>
                    @error('rating')
                        <p class="mt-2 text-sm text-accent-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comment -->
                <div>
                    <label for="comment" class="block text-sm font-semibold text-gray-900 mb-2">
                        Your Review <span class="text-accent-600">*</span>
                    </label>
                    <div x-data="{ count: 0 }">
                        <textarea 
                            id="comment"
                            wire:model="comment"
                            x-on:input="count = $el.value.length"
                            rows="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-none"
                            placeholder="Share details of your experience with this lawyer..."
                        ></textarea>
                        <div class="mt-2 flex justify-between items-center">
                            <p class="text-sm text-gray-500">Minimum 10 characters</p>
                            <p class="text-sm text-gray-500"><span x-text="count"></span>/1000</p>
                        </div>
                    </div>
                    @error('comment')
                        <p class="mt-2 text-sm text-accent-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Guidelines -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Review Guidelines</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Be honest and specific about your experience</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Focus on the service quality and professionalism</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Avoid personal attacks or offensive language</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $isEditing ? 'You can edit once within 7 days' : 'You can edit your review within 7 days' }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button 
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="flex-1 sm:flex-none px-8 py-3 bg-primary-700 text-white font-semibold rounded-lg hover:bg-primary-800 transition-colors shadow-lg hover:shadow-xl"
                    >
                        <span wire:loading.remove wire:target="submit">
                            {{ $isEditing ? 'Update Review' : 'Submit Review' }}
                        </span>
                        <span wire:loading wire:target="submit" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                    <a 
                        href="{{ $consultationId ? route('client.consultation.details', $consultationId) : route('client.document.details', $documentRequestId) }}"
                        class="flex-1 sm:flex-none px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors text-center"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

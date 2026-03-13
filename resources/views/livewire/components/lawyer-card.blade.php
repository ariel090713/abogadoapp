@if($lawyer->user)
@if($viewMode === 'grid')
    <!-- Grid View -->
    <a href="{{ route('lawyers.show', $lawyer->username) }}" class="block bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
        <div class="p-4 sm:p-5 flex flex-col h-full">
            <!-- Profile Photo & Verified Badge -->
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    @if($lawyer->user->profile_photo)
                        <img src="{{ $lawyer->user->profile_photo }}" alt="{{ $lawyer->user->name }}" class="w-14 h-14 rounded-xl object-cover">
                    @else
                        <div class="w-14 h-14 rounded-xl bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-lg">
                            {{ $lawyer->user->initials() }}
                        </div>
                    @endif
                </div>
                @if($lawyer->is_verified)
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Verified
                    </span>
                @endif
            </div>

            <!-- Name & Rating -->
            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $lawyer->user->name }}</h3>
            <div class="flex items-center gap-2 mb-2">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $lawyer->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <span class="text-sm text-gray-600">{{ number_format($lawyer->rating, 1) }} ({{ $lawyer->total_reviews }})</span>
            </div>

            <!-- Location -->
            @if($lawyer->user->city || $lawyer->user->province)
                <div class="flex items-center gap-1 text-xs text-gray-500 mb-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>{{ $lawyer->user->city ? $lawyer->user->city . ', ' : '' }}{{ $lawyer->user->province }}</span>
                </div>
            @endif

            <!-- Specializations -->
            <div class="flex flex-wrap gap-1.5 mb-2">
                @foreach($lawyer->specializations->take(2) as $spec)
                    <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-xs rounded-full">{{ $spec->name }}</span>
                @endforeach
                @if($lawyer->specializations->count() > 2)
                    <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-xs rounded-full">+{{ $lawyer->specializations->count() - 2 }}</span>
                @endif
            </div>

            <!-- Bio Preview -->
            <p class="text-xs text-gray-600 line-clamp-2 mb-3">{{ $lawyer->bio }}</p>

            <!-- Spacer to push footer to bottom -->
            <div class="flex-grow"></div>

            <!-- Footer - Always at bottom -->
            <div class="pt-4 mt-auto border-t border-gray-200">
                <div class="space-y-1">
                    <span class="text-xs text-gray-500 block mb-2">Available Services</span>
                    
                    @if($lawyer->offers_chat_consultation)
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Chat</span>
                            <span class="text-xs font-semibold text-primary-700">₱{{ number_format($lawyer->chat_rate_15min, 0) }}/15min</span>
                        </div>
                    @endif
                    
                    @if($lawyer->offers_video_consultation)
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Video</span>
                            <span class="text-xs font-semibold text-primary-700">₱{{ number_format($lawyer->video_rate_15min, 0) }}/15min</span>
                        </div>
                    @endif
                    
                    @if($lawyer->offers_document_review)
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Document</span>
                            <span class="text-xs font-semibold text-primary-700">₱{{ number_format($lawyer->document_review_min_price, 0) }}+</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </a>
@else
    <!-- List View -->
    <a href="{{ route('lawyers.show', $lawyer->username) }}" class="block bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
        <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
            <!-- Profile Photo -->
            @if($lawyer->user->profile_photo)
                <img src="{{ $lawyer->user->profile_photo }}" alt="{{ $lawyer->user->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover flex-shrink-0">
            @else
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-xl sm:text-2xl flex-shrink-0">
                    {{ $lawyer->user->initials() }}
                </div>
            @endif

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $lawyer->user->name }}</h3>
                            @if($lawyer->is_verified)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Verified
                                </span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mt-1">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $lawyer->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600">{{ number_format($lawyer->rating, 1) }} ({{ $lawyer->total_reviews }} reviews)</span>
                            <span class="text-gray-400">•</span>
                            <span class="text-sm text-gray-600">{{ $lawyer->years_experience }} years experience</span>
                            @if($lawyer->user->city || $lawyer->user->province)
                                <span class="text-gray-400">•</span>
                                <div class="flex items-center gap-1 text-sm text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $lawyer->user->city ? $lawyer->user->city . ', ' : '' }}{{ $lawyer->user->province }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-0 sm:text-right w-full sm:w-auto border-t sm:border-t-0 border-gray-100 pt-3 sm:pt-0">
                        <div class="space-y-1">
                            <span class="text-xs text-gray-500 block mb-2">Available Services</span>
                            
                            @if($lawyer->offers_chat_consultation)
                                <div class="flex justify-between items-center gap-4">
                                    <span class="text-xs text-gray-600">Chat</span>
                                    <span class="text-sm font-semibold text-primary-700">₱{{ number_format($lawyer->chat_rate_15min, 0) }}/15min</span>
                                </div>
                            @endif
                            
                            @if($lawyer->offers_video_consultation)
                                <div class="flex justify-between items-center gap-4">
                                    <span class="text-xs text-gray-600">Video</span>
                                    <span class="text-sm font-semibold text-primary-700">₱{{ number_format($lawyer->video_rate_15min, 0) }}/15min</span>
                                </div>
                            @endif
                            
                            @if($lawyer->offers_document_review)
                                <div class="flex justify-between items-center gap-4">
                                    <span class="text-xs text-gray-600">Document</span>
                                    <span class="text-sm font-semibold text-primary-700">₱{{ number_format($lawyer->document_review_min_price, 0) }}+</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Specializations -->
                <div class="flex flex-wrap gap-1.5 mb-2">
                    @foreach($lawyer->specializations->take(3) as $spec)
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-xs rounded-full">{{ $spec->name }}</span>
                    @endforeach
                    @if($lawyer->specializations->count() > 3)
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-xs rounded-full">+{{ $lawyer->specializations->count() - 3 }}</span>
                    @endif
                </div>

                <!-- Bio -->
                <p class="text-xs sm:text-sm text-gray-600 line-clamp-2">{{ $lawyer->bio }}</p>
            </div>

        </div>
    </a>
@endif
@endif

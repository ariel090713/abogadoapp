<div class="min-h-screen bg-white">
    <!-- Hero Header with Gradient -->
    <div class="relative bg-gradient-to-br from-primary-900 via-primary-800 to-accent-900 text-white overflow-hidden">
        <!-- Abstract Background Elements -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 mix-blend-overlay"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-accent-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-pulse" style="animation-delay: 2s;"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="flex flex-col lg:flex-row items-start lg:items-center gap-8">
                <!-- Profile Photo -->
                <div class="flex-shrink-0">
                    @if($lawyer->user->profile_photo)
                        <img src="{{ $lawyer->user->profile_photo }}" 
                             alt="{{ $lawyer->user->name }}" 
                             class="w-32 h-32 lg:w-40 lg:h-40 rounded-2xl object-cover border-4 border-white/20 shadow-2xl">
                    @else
                        <div class="w-32 h-32 lg:w-40 lg:h-40 rounded-2xl bg-white/10 backdrop-blur-sm border-4 border-white/20 flex items-center justify-center text-white font-bold text-5xl shadow-2xl">
                            {{ $lawyer->user->initials() }}
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="flex-1 w-full">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="flex-1">
                            <!-- Name & Verification -->
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                                <h1 class="text-3xl lg:text-4xl font-bold">{{ $lawyer->user->name }}</h1>
                                @if($lawyer->is_verified)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-500/20 backdrop-blur-sm text-green-100 text-sm font-semibold rounded-full border border-green-400/30">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified
                                    </span>
                                @endif
                            </div>

                            <!-- Rating & Stats -->
                            <div class="flex flex-wrap items-center gap-4 lg:gap-6 mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $lawyer->rating ? 'text-yellow-400' : 'text-white/30' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-lg font-bold">{{ number_format($lawyer->rating, 1) }}</span>
                                    <span class="text-white/80">({{ $lawyer->total_reviews }} reviews)</span>
                                </div>
                                <span class="hidden sm:inline text-white/40">•</span>
                                <span class="text-white/90">{{ $lawyer->total_consultations }} consultations</span>
                                <span class="hidden sm:inline text-white/40">•</span>
                                <span class="text-white/90">{{ $lawyer->years_experience }} years experience</span>
                            </div>

                            <!-- Location & IBP -->
                            <div class="flex flex-wrap items-center gap-4 text-sm text-white/90">
                                @if($lawyer->user->city || $lawyer->user->province)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $lawyer->user->city ? $lawyer->user->city . ', ' : '' }}{{ $lawyer->user->province }}
                                    </div>
                                @endif
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    IBP No. {{ $lawyer->ibp_number }}
                                </div>
                            </div>
                        </div>

                        <!-- Book Consultation Button (Only for clients and guests) -->
                        @if(!auth()->check() || auth()->user()->role !== 'lawyer')
                            <div class="w-full lg:w-auto">
                                <a href="{{ route('consultation.book', $lawyer) }}" 
                                   class="inline-flex items-center justify-center gap-2 w-full lg:w-auto px-8 py-4 bg-white text-primary-700 font-bold text-lg rounded-xl hover:bg-gray-50 transition-all shadow-xl hover:shadow-2xl hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Book Consultation
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- About -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-1 h-8 bg-primary-600 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-900">About</h2>
                    </div>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $lawyer->bio }}</p>
                </div>

                <!-- Practice Areas -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-1 h-8 bg-primary-600 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-900">Practice Areas</h2>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @foreach($lawyer->specializations as $spec)
                            <div class="px-5 py-2.5 bg-gradient-to-r from-primary-50 to-primary-100 text-primary-700 rounded-xl font-semibold border border-primary-200 hover:shadow-md transition-shadow">
                                {{ $spec->name }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Languages -->
                @if($lawyer->languages && count($lawyer->languages) > 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-1 h-8 bg-primary-600 rounded-full"></div>
                            <h2 class="text-2xl font-bold text-gray-900">Languages</h2>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @foreach($lawyer->languages as $language)
                                <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-50 text-gray-700 rounded-xl font-medium border border-gray-200">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                    </svg>
                                    {{ $language }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Education & Experience -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-1 h-8 bg-primary-600 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-900">Education & Experience</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($lawyer->law_school)
                            <div class="p-6 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                                    </svg>
                                    <div class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Law School</div>
                                </div>
                                <div class="font-bold text-gray-900 text-lg mb-1">{{ $lawyer->law_school }}</div>
                                @if($lawyer->graduation_year)
                                    <div class="text-sm text-gray-600">Graduated {{ $lawyer->graduation_year }}</div>
                                @endif
                            </div>
                        @endif
                        
                        @if($lawyer->law_firm)
                            <div class="p-6 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <div class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Law Firm</div>
                                </div>
                                <div class="font-bold text-gray-900 text-lg">{{ $lawyer->law_firm }}</div>
                                <div class="text-sm text-gray-600">Current Practice</div>
                            </div>
                        @endif
                        
                        <div class="p-6 bg-gray-50 rounded-xl border border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <div class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Experience</div>
                            </div>
                            <div class="font-bold text-gray-900 text-lg">{{ $lawyer->years_experience }} {{ $lawyer->years_experience == 1 ? 'Year' : 'Years' }}</div>
                            <div class="text-sm text-gray-600">Professional Practice</div>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-8 bg-primary-600 rounded-full"></div>
                            <h2 class="text-2xl font-bold text-gray-900">Client Reviews</h2>
                        </div>
                        <span class="text-sm text-gray-500">{{ $lawyer->total_reviews }} reviews</span>
                    </div>

                    <!-- Review Filter Tabs -->
                    <div class="flex gap-2 mb-6 border-b border-gray-200">
                        <button wire:click="setReviewFilter('all')" 
                                class="px-4 py-2 font-medium text-sm transition-colors {{ $reviewFilter === 'all' ? 'text-primary-700 border-b-2 border-primary-700' : 'text-gray-600 hover:text-gray-900' }}">
                            All Reviews
                        </button>
                        <button wire:click="setReviewFilter('consultations')" 
                                class="px-4 py-2 font-medium text-sm transition-colors {{ $reviewFilter === 'consultations' ? 'text-primary-700 border-b-2 border-primary-700' : 'text-gray-600 hover:text-gray-900' }}">
                            Consultations
                        </button>
                        <button wire:click="setReviewFilter('documents')" 
                                class="px-4 py-2 font-medium text-sm transition-colors {{ $reviewFilter === 'documents' ? 'text-primary-700 border-b-2 border-primary-700' : 'text-gray-600 hover:text-gray-900' }}">
                            Documents
                        </button>
                    </div>
                    
                    @if($this->reviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($this->reviews as $review)
                                <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center text-primary-700 font-bold text-lg flex-shrink-0">
                                            {{ $review->client->initials() }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between mb-2">
                                                <div class="flex-1">
                                                    <div class="font-semibold text-gray-900">{{ $review->client->name }}</div>
                                                    <div class="flex items-center gap-3 mt-1">
                                                        <div class="flex items-center">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                        <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                                        @if($review->is_edited)
                                                            <span class="text-xs px-2 py-0.5 bg-gray-200 text-gray-600 rounded-full font-medium">Edited</span>
                                                        @endif
                                                    </div>
                                                    <!-- Service Type Badge -->
                                                    <div class="mt-2">
                                                        @if($review->consultation_id)
                                                            <span class="inline-flex items-center gap-1 text-xs px-2 py-1 bg-blue-50 text-blue-700 rounded-full font-medium">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                                </svg>
                                                                {{ $review->service_type }} Consultation
                                                            </span>
                                                        @elseif($review->document_request_id)
                                                            <span class="inline-flex items-center gap-1 text-xs px-2 py-1 bg-green-50 text-green-700 rounded-full font-medium">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                </svg>
                                                                Document: {{ $review->service_name }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @if($review->comment)
                                                <p class="text-gray-700 leading-relaxed mt-3">{{ $review->comment }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $this->reviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            <p class="text-gray-500">
                                @if($reviewFilter === 'consultations')
                                    No consultation reviews yet
                                @elseif($reviewFilter === 'documents')
                                    No document reviews yet
                                @else
                                    No reviews yet
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-4 space-y-6">
                    <!-- Services & Pricing -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Services & Pricing
                        </h3>
                        <div class="space-y-6">
                            @if($lawyer->offers_chat_consultation)
                                <div class="pb-6 border-b border-gray-100 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-gray-900">Chat Consultation</span>
                                    </div>
                                    <div class="space-y-2.5">
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm text-gray-600">15 minutes</span>
                                            <span class="font-bold text-primary-700">₱{{ number_format($lawyer->chat_rate_15min, 0) }}</span>
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm text-gray-600">30 minutes</span>
                                            <span class="font-bold text-primary-700">₱{{ number_format($lawyer->chat_rate_30min, 0) }}</span>
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm text-gray-600">60 minutes</span>
                                            <span class="font-bold text-primary-700">₱{{ number_format($lawyer->chat_rate_60min, 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($lawyer->offers_video_consultation)
                                <div class="pb-6 border-b border-gray-100 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-10 h-10 rounded-lg bg-accent-50 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-gray-900">Video Consultation</span>
                                    </div>
                                    <div class="space-y-2.5">
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm text-gray-600">15 minutes</span>
                                            <span class="font-bold text-accent-700">₱{{ number_format($lawyer->video_rate_15min, 0) }}</span>
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm text-gray-600">30 minutes</span>
                                            <span class="font-bold text-accent-700">₱{{ number_format($lawyer->video_rate_30min, 0) }}</span>
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm text-gray-600">60 minutes</span>
                                            <span class="font-bold text-accent-700">₱{{ number_format($lawyer->video_rate_60min, 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($lawyer->offers_document_review)
                                <div class="pb-6 border-b border-gray-100 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-gray-900">Document Review</span>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Starting at</span>
                                            <span class="font-bold text-green-700">₱{{ number_format($lawyer->document_review_min_price, 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($this->documentServices->count() > 0)
                                <div class="pb-6 border-b border-gray-100 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-gray-900">Document Drafting</span>
                                    </div>
                                    <div class="space-y-2">
                                        @foreach($this->documentServices->take(5) as $service)
                                            <a href="{{ route('documents.request', $service->id) }}" 
                                               class="block p-3 bg-gray-50 rounded-lg hover:bg-purple-50 hover:border-purple-200 border border-transparent transition cursor-pointer">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-medium text-gray-900 text-sm truncate">{{ $service->template->name }}</div>
                                                        @if($service->description)
                                                            <div class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $service->description }}</div>
                                                        @endif
                                                        @if($service->estimated_completion_days)
                                                            <div class="text-xs text-gray-600 mt-1">
                                                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                {{ $service->estimated_completion_days }} {{ $service->estimated_completion_days == 1 ? 'day' : 'days' }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="text-right flex-shrink-0">
                                                        <div class="font-bold text-purple-700 text-sm whitespace-nowrap">₱{{ number_format($service->price, 0) }}</div>
                                                        <div class="text-xs text-purple-600 mt-1">Request →</div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                        @if($this->documentServices->count() > 5)
                                            <div class="text-center pt-2">
                                                <a href="{{ route('documents.browse') }}?lawyer={{ $lawyer->username }}" 
                                                   class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                                                    View all {{ $this->documentServices->count() }} documents →
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Availability -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Availability
                        </h3>
                        @if($lawyer->availabilitySchedules->count() > 0)
                            <div class="space-y-3">
                                @foreach($lawyer->availabilitySchedules->sortBy('day_of_week') as $schedule)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <span class="font-medium text-gray-700 text-sm">{{ $schedule->day_name }}</span>
                                        <span class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - 
                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm text-center py-4">Schedule not set</p>
                        @endif
                    </div>

                    @if(!auth()->check() || auth()->user()->role !== 'lawyer')
                    <!-- CTA -->
                    <div class="bg-gradient-to-br from-accent-600 to-accent-700 rounded-2xl shadow-lg p-6 text-white">
                        <h3 class="text-lg font-bold mb-3">Ready to get started?</h3>
                        <p class="text-white/90 text-sm mb-6">Book a consultation with {{ explode(' ', $lawyer->user->name)[0] }} today and get expert legal advice.</p>
                        <a href="{{ route('consultation.book', $lawyer) }}" 
                           class="block w-full text-center px-6 py-3 bg-white text-accent-700 font-bold rounded-xl hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl">
                            Book Now
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

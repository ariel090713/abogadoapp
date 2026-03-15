<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Hero Banner -->
    <section class="relative bg-gradient-to-br from-primary-900 via-primary-800 to-accent-900 text-white overflow-hidden py-24 lg:py-32">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 mix-blend-overlay"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-accent-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full text-sm font-semibold mb-6 border border-white/20 shadow-sm text-accent-100 tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                LIVE WEBINARS
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-white via-primary-50 to-accent-100 drop-shadow-sm">
                Master Legal Concepts
            </h1>
            <p class="text-lg md:text-xl md:leading-relaxed text-primary-100/90 max-w-3xl mx-auto mb-10">
                Join our premium live webinars led by top legal experts. Get real-time answers to complex legal questions and stay ahead of critical compliance updates.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="#schedule" class="px-8 py-4 bg-accent-600 hover:bg-accent-500 text-white rounded-xl font-bold transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">
                    View Schedule
                </a>
            </div>
        </div>
    </section>

    <!-- Schedule Grid -->
    <section id="schedule" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-20">
        
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-900 border-l-4 border-accent-500 pl-4 py-1">Upcoming & Ongoing</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-12">
            @forelse($webinars as $webinar)
                <a href="{{ route('resources.webinars.view', $webinar['slug']) }}" class="group block h-full">
                    <article class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 h-full flex flex-col relative focus-within:ring-4 focus-within:ring-primary-500/20">
                        
                        <!-- Status Badge Overlay -->
                        <div class="absolute top-4 right-4 z-10">
                            @if($webinar['status'] === 'Ongoing')
                                <span class="flex items-center gap-1.5 px-3 py-1 bg-red-500/90 backdrop-blur text-white text-xs font-bold rounded-full shadow-sm animate-pulse">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                                    LIVE NOW
                                </span>
                            @else
                                <span class="px-3 py-1 bg-white/90 backdrop-blur text-primary-800 text-xs font-bold rounded-full shadow-sm border border-white/40">
                                    UPCOMING
                                </span>
                            @endif
                        </div>

                        <!-- Image Section -->
                        <div class="relative h-56 overflow-hidden">
                            <div class="absolute inset-0 bg-primary-900/20 group-hover:bg-transparent transition-colors duration-500 z-10"></div>
                            <img src="{{ $webinar['image'] }}" alt="{{ $webinar['title'] }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-in-out">
                            
                            <!-- Date overlay inside image -->
                            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent z-10 text-white">
                                <div class="flex items-center gap-2 text-sm font-medium">
                                    <svg class="w-4 h-4 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($webinar['date'])->format('F j, Y - g:i A') }}
                                </div>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="p-6 flex flex-col flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-700 transition-colors leading-tight line-clamp-2">
                                {{ $webinar['title'] }}
                            </h3>
                            
                            <p class="text-sm text-gray-500 mb-6 line-clamp-3 leading-relaxed flex-1">
                                {{ $webinar['description'] }}
                            </p>
                            
                            <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div class="text-xs">
                                        <p class="text-gray-400">Speaker/s</p>
                                        <p class="font-semibold text-gray-800 line-clamp-1 max-w-[150px]">{{ $webinar['speakers'] }}</p>
                                    </div>
                                </div>

                                <div class="text-primary-600 group-hover:text-accent-600 transition-colors">
                                    <svg class="w-6 h-6 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </article>
                </a>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-24 bg-white rounded-3xl border border-gray-100 border-dashed">
                    <div class="w-20 h-20 bg-primary-50 rounded-full flex items-center justify-center text-primary-400 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Webinars Scheduled</h3>
                    <p class="text-gray-500 text-center max-w-sm">We are preparing new exciting topics. Check back soon or subscribe to our newsletter for updates.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>

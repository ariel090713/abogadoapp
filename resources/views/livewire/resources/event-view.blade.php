<div>
    <section class="relative bg-gradient-to-br from-primary-900 via-primary-800 to-accent-900 text-white overflow-hidden py-24 lg:py-32">
        <!-- Abstract Background Elements -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 mix-blend-overlay"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-accent-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-pulse" style="animation-delay: 2s;"></div>
        </div>
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('resources.events') }}" class="inline-flex items-center gap-2 text-white/70 hover:text-white transition-colors mb-6 font-medium text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Events
            </a>
            <div>
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full text-sm font-semibold mb-6 border border-white/20 shadow-sm text-accent-100 tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    EVENT SCHEDULE
                </span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-white via-primary-50 to-accent-100 drop-shadow-sm">{{ $event->title }}</h1>
            <div class="flex flex-wrap items-center gap-4 text-primary-100 font-medium">
                <span class="flex items-center gap-2 bg-white/10 backdrop-blur px-4 py-2 rounded-lg border border-white/20">
                    <svg class="w-4 h-4 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $event->event_date->format('F d, Y - h:i A') }}
                </span>
                <span class="px-4 py-2 bg-accent-600/90 backdrop-blur text-white rounded-lg border border-white/20 shadow-sm">
                    {{ ucfirst($event->event_type) }}
                </span>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none">
                {!! $event->content !!}
            </div>
        </div>
    </section>
</div>

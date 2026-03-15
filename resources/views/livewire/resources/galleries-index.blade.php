<div>
    <section class="relative bg-gradient-to-br from-primary-900 via-primary-800 to-accent-900 text-white overflow-hidden py-24 lg:py-32">
        <!-- Abstract Background Elements -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 mix-blend-overlay"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-accent-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-pulse" style="animation-delay: 2s;"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full text-sm font-semibold mb-6 border border-white/20 shadow-sm text-accent-100 tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                OUR MOMENTS
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-white via-primary-50 to-accent-100 drop-shadow-sm">Galleries</h1>
            <p class="text-lg md:text-xl md:leading-relaxed text-primary-100/90 max-w-3xl mx-auto">Photos and videos from our events and activities</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($galleries->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($galleries as $gallery)
                        <a href="{{ route('resources.galleries.view', $gallery->slug) }}" class="block bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition group">
                            <div class="aspect-video bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center">
                                <svg class="w-20 h-20 text-white opacity-50 group-hover:scale-110 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="p-6 h-full flex flex-col">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="px-3 py-1 bg-accent-100 text-accent-700 text-xs font-semibold rounded-full self-start">
                                        {{ ucfirst($gallery->type) }}
                                    </span>
                                    <span class="text-sm text-gray-500">{{ $gallery->items->count() }} items</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3 flex-grow">{{ $gallery->title }}</h3>
                                <span class="inline-flex items-center gap-2 text-primary-700 font-semibold group-hover:text-primary-800 transition mt-auto">
                                    View Gallery
                                    <svg class="w-4 h-4 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-12">{{ $galleries->links() }}</div>
            @else
                <div class="text-center py-16">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No galleries found</h3>
                </div>
            @endif
        </div>
    </section>
</div>

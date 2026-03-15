<div>
    <section class="relative bg-gradient-to-br from-primary-900 via-primary-800 to-accent-900 text-white overflow-hidden py-24 lg:py-32">
        <!-- Abstract Background Elements -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 mix-blend-overlay"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-accent-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-pulse" style="animation-delay: 2s;"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full text-sm font-semibold mb-6 border border-white/20 shadow-sm text-accent-100 tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    LEGAL UPDATES
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-7xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-white via-primary-50 to-accent-100 drop-shadow-sm">Latest News</h1>
                <p class="text-lg md:text-xl md:leading-relaxed text-primary-100/90 max-w-3xl mx-auto">
                    Stay updated with the latest legal news and updates in the Philippines
                </p>
            </div>
        </div>
    </section>

    <section class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <input type="text" wire:model.live.debounce.300ms="search" 
                    placeholder="Search news..." 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($news->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($news as $item)
                        <a href="{{ route('resources.news.view', $item->slug) }}" class="block bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition group">
                            @if($item->featured_image)
                                <div class="aspect-video overflow-hidden">
                                    <img src="{{ $item->featured_image }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </div>
                            @else
                                <div class="aspect-video bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="p-6 h-full flex flex-col">
                                <div class="text-sm text-gray-500 mb-2">{{ $item->created_at->format('M d, Y') }}</div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $item->title }}</h3>
                                <p class="text-gray-600 mb-4 line-clamp-3 flex-grow">{{ $item->excerpt }}</p>
                                <span class="inline-flex items-center gap-2 text-primary-700 font-semibold group-hover:text-primary-800 transition mt-auto">
                                    Read More
                                    <svg class="w-4 h-4 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-12">{{ $news->links() }}</div>
            @else
                <div class="text-center py-16">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No news found</h3>
                    <p class="text-gray-600">Check back later for updates</p>
                </div>
            @endif
        </div>
    </section>
</div>

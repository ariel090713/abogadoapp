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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                ARTICLES
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-white via-primary-50 to-accent-100 drop-shadow-sm">Blogs</h1>
            <p class="text-lg md:text-xl md:leading-relaxed text-primary-100/90 max-w-3xl mx-auto">Insights, opinions, and expert perspectives on Philippine law</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($blogs->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($blogs as $blog)
                        <a href="{{ route('resources.blogs.view', $blog->slug) }}" class="block bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition group">
                            @if($blog->featured_image)
                                <div class="aspect-video overflow-hidden">
                                    <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </div>
                            @else
                                <div class="aspect-video bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="p-6 h-full flex flex-col">
                                <span class="px-3 py-1 bg-accent-100 text-accent-700 text-xs font-semibold rounded-full self-start">
                                    {{ ucfirst(str_replace('_', ' ', $blog->category)) }}
                                </span>
                                <h3 class="text-xl font-bold text-gray-900 mt-3 mb-3">{{ $blog->title }}</h3>
                                <p class="text-gray-600 mb-4 line-clamp-3 flex-grow">{{ $blog->excerpt }}</p>
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
                <div class="mt-12">{{ $blogs->links() }}</div>
            @else
                <div class="text-center py-16">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No blogs found</h3>
                </div>
            @endif
        </div>
    </section>
</div>

<div>
    <section class="bg-gradient-to-br from-primary-700 via-primary-800 to-accent-700 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6">Blogs</h1>
            <p class="text-xl text-primary-100 max-w-3xl mx-auto">Insights, opinions, and expert perspectives on Philippine law</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($blogs->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($blogs as $blog)
                        <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition group">
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
                            <div class="p-6">
                                <span class="px-3 py-1 bg-accent-100 text-accent-700 text-xs font-semibold rounded-full">
                                    {{ ucfirst(str_replace('_', ' ', $blog->category)) }}
                                </span>
                                <h3 class="text-xl font-bold text-gray-900 mt-3 mb-3">{{ $blog->title }}</h3>
                                <p class="text-gray-600 mb-4 line-clamp-3">{{ $blog->excerpt }}</p>
                                <a href="{{ route('resources.blogs.view', $blog->slug) }}" 
                                    class="inline-flex items-center gap-2 text-primary-700 font-semibold hover:text-primary-800 transition">
                                    Read More
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </article>
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

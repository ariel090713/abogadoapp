<div>
    <section class="bg-gradient-to-br from-primary-700 via-primary-800 to-accent-700 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6">Galleries</h1>
            <p class="text-xl text-primary-100 max-w-3xl mx-auto">Photos and videos from our events and activities</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($galleries->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($galleries as $gallery)
                        <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition group">
                            <div class="aspect-video bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center">
                                <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="px-3 py-1 bg-accent-100 text-accent-700 text-xs font-semibold rounded-full">
                                        {{ ucfirst($gallery->type) }}
                                    </span>
                                    <span class="text-sm text-gray-500">{{ $gallery->items->count() }} items</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $gallery->title }}</h3>
                                <a href="{{ route('resources.galleries.view', $gallery->slug) }}" 
                                    class="inline-flex items-center gap-2 text-primary-700 font-semibold hover:text-primary-800 transition">
                                    View Gallery
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </article>
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

<div>
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-primary-700 via-primary-800 to-accent-700 text-white py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('resources.legal-guides') }}" 
                    class="inline-flex items-center gap-2 text-primary-100 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Legal Guides
                </a>
            </div>

            <div class="flex items-center gap-3 mb-4">
                <span class="px-4 py-2 bg-accent-600 text-white text-sm font-semibold rounded-full">
                    {{ ucfirst(str_replace('_', ' ', $guide->category)) }}
                </span>
                <span class="text-primary-100">
                    {{ $guide->created_at->format('F d, Y') }}
                </span>
                <span class="text-primary-100">•</span>
                <span class="text-primary-100">
                    {{ number_format($guide->views) }} views
                </span>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $guide->title }}</h1>
            <p class="text-xl text-primary-100">{{ $guide->excerpt }}</p>

            @if($guide->author)
                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-primary-600">
                    <div class="w-12 h-12 bg-primary-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ substr($guide->author->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-semibold">{{ $guide->author->name }}</div>
                        <div class="text-sm text-primary-100">Author</div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Featured Image -->
    @if($guide->featured_image)
        <section class="py-8 bg-gray-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <img src="{{ $guide->featured_image }}" 
                    alt="{{ $guide->title }}" 
                    class="w-full rounded-2xl shadow-xl">
            </div>
        </section>
    @endif

    <!-- Content -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none">
                {!! $guide->content !!}
            </div>
        </div>
    </section>

    <!-- Related Guides -->
    @if($relatedGuides->count() > 0)
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Related Guides</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedGuides as $related)
                        <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition">
                            @if($related->featured_image)
                                <div class="aspect-video overflow-hidden">
                                    <img src="{{ $related->featured_image }}" 
                                        alt="{{ $related->title }}" 
                                        class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                </div>
                            @endif

                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                    {{ $related->title }}
                                </h3>
                                <p class="text-gray-600 mb-4 line-clamp-2 text-sm">
                                    {{ $related->excerpt }}
                                </p>
                                <a href="{{ route('resources.legal-guides.view', $related->slug) }}" 
                                    class="inline-flex items-center gap-2 text-primary-700 font-semibold hover:text-primary-800 transition text-sm">
                                    Read More
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>

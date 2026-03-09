<div>
    <section class="bg-gradient-to-br from-primary-700 via-primary-800 to-accent-700 text-white py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('resources.news') }}" class="inline-flex items-center gap-2 text-primary-100 hover:text-white transition mb-6">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to News
            </a>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $news->title }}</h1>
            <p class="text-xl text-primary-100">{{ $news->excerpt }}</p>
            <div class="flex items-center gap-3 mt-6 pt-6 border-t border-primary-600">
                <span class="text-primary-100">{{ $news->created_at->format('F d, Y') }}</span>
                <span class="text-primary-100">•</span>
                <span class="text-primary-100">{{ number_format($news->views) }} views</span>
            </div>
        </div>
    </section>

    @if($news->featured_image)
        <section class="py-8 bg-gray-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <img src="{{ $news->featured_image }}" alt="{{ $news->title }}" class="w-full rounded-2xl shadow-xl">
            </div>
        </section>
    @endif

    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none">
                {!! $news->content !!}
            </div>
        </div>
    </section>
</div>

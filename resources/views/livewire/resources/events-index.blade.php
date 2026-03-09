<div>
    <section class="bg-gradient-to-br from-primary-700 via-primary-800 to-accent-700 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6">Events</h1>
            <p class="text-xl text-primary-100 max-w-3xl mx-auto">Join our webinars, seminars, and workshops</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($events as $event)
                        <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition group">
                            @if($event->featured_image)
                                <div class="aspect-video overflow-hidden">
                                    <img src="{{ $event->featured_image }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </div>
                            @else
                                <div class="aspect-video bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="px-3 py-1 bg-primary-100 text-primary-700 text-xs font-semibold rounded-full">
                                        {{ ucfirst($event->event_type) }}
                                    </span>
                                    <span class="text-sm {{ $event->isUpcoming() ? 'text-green-600' : 'text-gray-500' }}">
                                        {{ $event->event_date->format('M d, Y') }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $event->title }}</h3>
                                <p class="text-gray-600 mb-4 line-clamp-2">{{ $event->description }}</p>
                                <a href="{{ route('resources.events.view', $event->slug) }}" 
                                    class="inline-flex items-center gap-2 text-primary-700 font-semibold hover:text-primary-800 transition">
                                    View Details
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="mt-12">{{ $events->links() }}</div>
            @else
                <div class="text-center py-16">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No events found</h3>
                </div>
            @endif
        </div>
    </section>
</div>

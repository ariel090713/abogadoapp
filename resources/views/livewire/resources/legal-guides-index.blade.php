<div>
    <!-- Hero Section -->
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    EXPERT ADVICE
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-7xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-white via-primary-50 to-accent-100 drop-shadow-sm">Legal Guides</h1>
                <p class="text-lg md:text-xl md:leading-relaxed text-primary-100/90 max-w-3xl mx-auto">
                    Educational articles and comprehensive guides to help you understand Philippine law
                </p>
            </div>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <section class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Search -->
                    <div>
                        <input type="text" wire:model.live.debounce.300ms="search" 
                            placeholder="Search legal guides..." 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <select wire:model.live="category" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="all">All Categories</option>
                            <option value="family_law">Family Law</option>
                            <option value="criminal_law">Criminal Law</option>
                            <option value="labor_law">Labor Law</option>
                            <option value="business_law">Business Law</option>
                            <option value="property_law">Property Law</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Guides Grid -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($guides->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($guides as $guide)
                        <a href="{{ route('resources.legal-guides.view', $guide->slug) }}" class="block bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition group">
                            @if($guide->featured_image)
                                <div class="aspect-video overflow-hidden">
                                    <img src="{{ $guide->featured_image }}" 
                                         alt="{{ $guide->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </div>
                            @else
                                <div class="aspect-video bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="p-6 h-full flex flex-col">
                                <span class="px-3 py-1 bg-accent-100 text-accent-700 text-xs font-semibold rounded-full self-start">
                                    {{ ucfirst(str_replace('_', ' ', $guide->category)) }}
                                </span>
                                <h3 class="text-xl font-bold text-gray-900 mt-3 mb-3">{{ $guide->title }}</h3>
                                <p class="text-gray-600 mb-4 line-clamp-3 flex-grow">{{ $guide->excerpt }}</p>
                                <span class="inline-flex items-center gap-2 text-primary-700 font-semibold group-hover:text-primary-800 transition mt-auto">
                                    Read Guide
                                    <svg class="w-4 h-4 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $guides->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No guides found</h3>
                    <p class="text-gray-600">Try adjusting your search or filter criteria</p>
                </div>
            @endif
        </div>
    </section>
</div>

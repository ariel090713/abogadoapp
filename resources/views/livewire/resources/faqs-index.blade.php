<div class="bg-white min-h-screen">
    <!-- Hero Section -->
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                HELP CENTER
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-white via-primary-50 to-accent-100 drop-shadow-sm">Frequently Asked Questions</h1>
            <p class="text-lg md:text-xl md:leading-relaxed text-primary-100/90 max-w-3xl mx-auto">
                Find answers to common questions about our legal services
            </p>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <section class="py-8 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="mb-6">
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="searchQuery"
                        placeholder="Search FAQs..." 
                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="flex flex-wrap gap-2">
                <button 
                    wire:click="$set('selectedCategory', 'all')"
                    class="px-4 py-2 rounded-lg font-medium transition {{ $selectedCategory === 'all' ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                >
                    All
                </button>
                @foreach($categories as $category)
                    <button 
                        wire:click="$set('selectedCategory', '{{ $category }}')"
                        class="px-4 py-2 rounded-lg font-medium transition capitalize {{ $selectedCategory === $category ? 'bg-primary-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        {{ ucfirst($category) }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQs Section -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($faqs->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-600 text-lg">No FAQs found matching your search.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($faqs as $faq)
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition">
                            <button 
                                wire:click="toggleFaq({{ $faq->id }})"
                                class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-gray-50 transition"
                            >
                                <div class="flex-1 pr-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $faq->question }}</h3>
                                    @if($faq->category !== 'general')
                                        <span class="inline-block mt-2 px-3 py-1 bg-primary-100 text-primary-700 text-xs font-medium rounded-full capitalize">
                                            {{ $faq->category }}
                                        </span>
                                    @endif
                                </div>
                                <svg 
                                    class="w-6 h-6 text-gray-400 transition-transform {{ $openFaqId === $faq->id ? 'rotate-180' : '' }}" 
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            
                            @if($openFaqId === $faq->id)
                                <div class="px-6 pb-4 border-t border-gray-100">
                                    <div class="pt-4 text-gray-700 leading-relaxed prose max-w-none">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Still have questions?</h2>
            <p class="text-xl text-gray-600 mb-8">Our team is here to help you</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-primary-700 text-white rounded-xl hover:bg-primary-800 transition font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Contact Us
            </a>
        </div>
    </section>
</div>

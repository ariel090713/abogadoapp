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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                FREE RESOURCES
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-white via-primary-50 to-accent-100 drop-shadow-sm">Free Legal Templates</h1>
            <p class="text-lg md:text-xl md:leading-relaxed text-primary-100/90 max-w-3xl mx-auto">Download free legal document templates and guides</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($downloadables->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($downloadables as $item)
                        <article class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition">
                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 bg-accent-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-8 h-8 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="px-3 py-1 bg-primary-100 text-primary-700 text-xs font-semibold rounded-full">
                                        {{ strtoupper($item->file_type) }}
                                    </span>
                                    <h3 class="text-lg font-bold text-gray-900 mt-2 mb-2">{{ $item->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-3">{{ $item->description }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">{{ $item->getFileSizeFormatted() }}</span>
                                        <button wire:click="download({{ $item->id }})" 
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition text-sm font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </button>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-2">{{ number_format($item->downloads) }} downloads</div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="mt-12">{{ $downloadables->links() }}</div>
            @else
                <div class="text-center py-16">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No templates available</h3>
                </div>
            @endif
        </div>
    </section>
</div>

<div x-data="{ 
    showLightbox: false, 
    currentIndex: 0,
    images: @js($gallery->items->sortBy('order')->map(fn($item) => ['url' => $item->file_path, 'title' => $item->title])->values()),
    openLightbox(index) {
        this.currentIndex = index;
        this.showLightbox = true;
        document.body.style.overflow = 'hidden';
    },
    closeLightbox() {
        this.showLightbox = false;
        document.body.style.overflow = 'auto';
    },
    nextImage() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
    },
    prevImage() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
    }
}" @keydown.escape.window="closeLightbox()" @keydown.arrow-right.window="showLightbox && nextImage()" @keydown.arrow-left.window="showLightbox && prevImage()">
    <section class="bg-gradient-to-br from-primary-700 via-primary-800 to-accent-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('resources.galleries') }}" class="inline-flex items-center gap-2 text-primary-100 hover:text-white transition mb-6">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Galleries
            </a>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $gallery->title }}</h1>
            <p class="text-xl text-primary-100">{{ $gallery->description }}</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($gallery->items->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($gallery->items->sortBy('order') as $index => $item)
                        <div class="aspect-square overflow-hidden rounded-2xl shadow-lg cursor-pointer group" @click="openLightbox({{ $index }})">
                            <img src="{{ $item->file_path }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xl text-gray-600">This gallery is empty</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div x-show="showLightbox" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 p-4"
         style="display: none;">
        
        <!-- Close Button -->
        <button @click="closeLightbox()" class="absolute top-4 right-4 text-white hover:text-gray-300 transition z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Previous Button -->
        <button @click="prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 transition bg-black/50 rounded-full p-3 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <!-- Image Container -->
        <div class="max-w-7xl max-h-[90vh] w-full h-full flex items-center justify-center">
            <template x-for="(image, index) in images" :key="index">
                <div x-show="currentIndex === index" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="flex flex-col items-center gap-4">
                    <img :src="image.url" :alt="image.title" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl">
                    <p class="text-white text-lg font-medium" x-text="image.title"></p>
                    <p class="text-gray-300 text-sm" x-text="`${currentIndex + 1} / ${images.length}`"></p>
                </div>
            </template>
        </div>

        <!-- Next Button -->
        <button @click="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 transition bg-black/50 rounded-full p-3 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</div>

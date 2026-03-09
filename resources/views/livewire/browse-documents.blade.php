<div class="min-h-screen bg-white">
    <!-- Hero Header with Gradient -->
    <div class="relative bg-gradient-to-br from-primary-700 via-primary-800 to-accent-700 text-white overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDEzNGg3djFoLTd6bTAtNWg3djFoLTd6Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-10"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">Legal Document Drafting</h1>
                <p class="text-xl md:text-2xl text-white/90 mb-8">Get professional legal documents drafted by verified lawyers</p>
                
                <!-- Search Bar -->
                <div class="max-w-3xl mx-auto">
                    <div class="relative">
                        <input type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Search for contracts, affidavits, agreements..."
                            class="w-full px-6 py-5 pr-14 text-lg text-gray-900 bg-white rounded-2xl shadow-2xl focus:ring-4 focus:ring-white/30 focus:outline-none border-0 placeholder-gray-400">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        @if(!$hasSearch)
            <!-- Popular Categories - Quick Actions -->
            <div class="mb-16">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900 mb-3">Browse by Category</h2>
                    <p class="text-lg text-gray-600">Select a category to find the document you need</p>
                </div>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach($categories as $category)
                        @php
                            $colors = $category->getColorClasses();
                        @endphp
                        <button wire:click="searchByCategory('{{ $category->slug }}')"
                            class="group relative bg-white rounded-xl p-5 shadow-md hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-primary-200 text-left overflow-hidden">
                            <!-- Gradient Background on Hover -->
                            <div class="absolute inset-0 bg-gradient-to-br from-primary-50 to-accent-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <div class="relative">
                                <!-- Icon -->
                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg mb-3 transition-all duration-300 {{ $colors['bg'] }} {{ $colors['text'] }} {{ $colors['hover_bg'] }} {{ $colors['hover_text'] }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                
                                <!-- Title -->
                                <h3 class="text-sm font-bold text-gray-900 mb-1 group-hover:text-primary-700 transition-colors leading-tight">
                                    {{ $category->name }}
                                </h3>
                                
                                <!-- Arrow -->
                                <div class="flex items-center text-xs font-medium text-gray-500 group-hover:text-primary-600 transition-colors">
                                    <span>Browse</span>
                                    <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- How It Works -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl p-8 lg:p-12">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-3">How It Works</h2>
                    <p class="text-lg text-gray-600">Get your legal documents in 3 simple steps</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-600 text-white rounded-2xl mb-4 text-2xl font-bold shadow-lg">
                            1
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Search & Select</h3>
                        <p class="text-gray-600">Browse or search for the document you need from verified lawyers</p>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-accent-600 text-white rounded-2xl mb-4 text-2xl font-bold shadow-lg">
                            2
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Fill & Pay</h3>
                        <p class="text-gray-600">Complete the required information and make a secure payment</p>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 text-white rounded-2xl mb-4 text-2xl font-bold shadow-lg">
                            3
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Receive Document</h3>
                        <p class="text-gray-600">Get your professionally drafted document within the estimated time</p>
                    </div>
                </div>
            </div>
        @else
            <!-- Search Results -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            @if($search)
                                Search Results for "{{ $search }}"
                            @elseif($category)
                                {{ ucfirst($category) }} Documents
                            @endif
                        </h2>
                        <p class="text-gray-600 mt-1">{{ $documents->total() }} {{ Str::plural('document', $documents->total()) }} found</p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <!-- Clear Filters -->
                        @if($search || $category)
                            <button wire:click="$set('search', ''); $set('category', '')" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                Clear Filters
                            </button>
                        @endif
                        
                        <!-- Sort -->
                        <select wire:model.live="sortBy" 
                            class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                            <option value="random">Random</option>
                            <option value="newest">Newest</option>
                            <option value="popular">Popular</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                        </select>
                    </div>
                </div>
            </div>

            @if($documents->count() > 0)
                <!-- Documents Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($documents as $document)
                        <div class="group bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-xl hover:border-primary-300 transition-all duration-300 cursor-pointer">
                            <!-- Category Badge -->
                            @if($document->template)
                                <div class="mb-4">
                                    <span class="inline-block px-3 py-1.5 bg-gradient-to-r from-primary-50 to-accent-50 text-primary-700 text-xs font-bold rounded-full border border-primary-200">
                                        {{ ucfirst($document->template->category) }}
                                    </span>
                                </div>
                            @endif

                            <!-- Document Name -->
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-primary-700 transition-colors">
                                {{ $document->name }}
                            </h3>

                            <!-- Description -->
                            @if($document->description)
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $document->description }}</p>
                            @endif

                            <!-- Lawyer Info -->
                            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                                @if($document->lawyer->profile_photo_url)
                                    <img src="{{ $document->lawyer->profile_photo_url }}" 
                                        alt="{{ $document->lawyer->name }}"
                                        class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                        <span class="text-primary-600 font-semibold text-sm">
                                            {{ substr($document->lawyer->name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $document->lawyer->name }}</p>
                                    @if($document->lawyer->lawyerProfile)
                                        <p class="text-xs text-gray-500">{{ $document->lawyer->lawyerProfile->years_of_experience }} years exp</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div class="text-center p-2 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Price</p>
                                    <p class="text-sm font-bold text-primary-700">₱{{ number_format($document->price, 0) }}</p>
                                </div>
                                <div class="text-center p-2 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Fill Time</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $document->estimated_client_time }}m</p>
                                </div>
                                <div class="text-center p-2 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Delivery</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $document->estimated_completion_days }}d</p>
                                </div>
                                <div class="text-center p-2 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Revisions</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $document->revisions_allowed }}</p>
                                </div>
                            </div>

                            <!-- Orders Count -->
                            @if($document->total_orders > 0)
                                <div class="mb-4 text-xs text-gray-500 text-center">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                        </svg>
                                        {{ $document->total_orders }} {{ Str::plural('order', $document->total_orders) }}
                                    </span>
                                </div>
                            @endif

                            <!-- Request Button -->
                            @auth
                                @if(auth()->user()->role === 'client')
                                    <a href="{{ route('documents.request', $document->id) }}" 
                                        class="block w-full text-center px-4 py-2.5 bg-primary-700 text-white font-semibold rounded-lg hover:bg-primary-800 transition-colors">
                                        Request Document
                                    </a>
                                @else
                                    <div class="text-center px-4 py-2.5 bg-gray-100 text-gray-500 font-medium rounded-lg cursor-not-allowed">
                                        Lawyers cannot request documents
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}" 
                                    class="block w-full text-center px-4 py-2.5 bg-primary-700 text-white font-semibold rounded-lg hover:bg-primary-800 transition-colors">
                                    Login to Request
                                </a>
                            @endauth
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $documents->links() }}
                </div>
            @else
                <!-- No Results -->
                <div class="text-center py-16 bg-gray-50 rounded-2xl">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No documents found</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your search or browse by category</p>
                    <button wire:click="$set('search', ''); $set('category', '')" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-primary-700 text-white font-semibold rounded-xl hover:bg-[#1E40AF] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        Browse All Categories
                    </button>
                </div>
            @endif
        @endif
    </div>
</div>

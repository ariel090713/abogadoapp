<nav class="bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm sticky top-0 z-50 transition-all duration-300" x-data="{ mobileMenuOpen: false, documentsOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 md:h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0">
                <img src="https://lawyerstorage-public.s3.ap-southeast-2.amazonaws.com/abogadomo-logo.png" alt="AbogadoMo Logo" class="w-10 h-10 rounded-lg shadow-sm">
                <span class="text-xl md:text-2xl font-bold text-primary-700">AbogadoMo</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('lawyers.search') }}" class="text-gray-700 hover:text-primary-700 font-medium transition">
                    Find a Lawyer
                </a>
                
                <!-- Documents Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button 
                        @click="open = !open"
                        class="flex items-center gap-1 text-gray-700 hover:text-primary-700 font-medium transition"
                    >
                        <span>Documents</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div 
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute top-full left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2"
                        style="display: none;"
                    >
                        <a href="{{ route('documents.browse') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div>
                                <div class="text-sm font-medium">Document Drafting</div>
                                <div class="text-xs text-gray-500">Request custom documents</div>
                            </div>
                        </a>
                        <a href="{{ route('resources.downloadables') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div>
                                <div class="text-sm font-medium">Downloadables</div>
                                <div class="text-xs text-gray-500">Free legal templates</div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <!-- Resources Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button 
                        @click="open = !open"
                        class="flex items-center gap-1 text-gray-700 hover:text-primary-700 font-medium transition"
                    >
                        <span>Resources</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div 
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute top-full left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2"
                        style="display: none;"
                    >
                        <a href="{{ route('resources.legal-guides') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <div>
                                <div class="text-sm font-medium">Legal Guides</div>
                                <div class="text-xs text-gray-500">Educational articles</div>
                            </div>
                        </a>
                        <a href="{{ route('resources.news') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            <div>
                                <div class="text-sm font-medium">News</div>
                                <div class="text-xs text-gray-500">Latest legal updates</div>
                            </div>
                        </a>
                        <a href="{{ route('resources.blogs') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <div>
                                <div class="text-sm font-medium">Blogs</div>
                                <div class="text-xs text-gray-500">Insights & opinions</div>
                            </div>
                        </a>
                        <a href="{{ route('resources.events') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <div class="text-sm font-medium">Events</div>
                                <div class="text-xs text-gray-500">Webinars & seminars</div>
                            </div>
                        </a>
                        <a href="{{ route('resources.galleries') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <div class="text-sm font-medium">Galleries</div>
                                <div class="text-xs text-gray-500">Photos & videos</div>
                            </div>
                        </a>
                        <a href="{{ route('resources.faqs') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <div class="text-sm font-medium">FAQs</div>
                                <div class="text-xs text-gray-500">Common questions</div>
                            </div>
                        </a>
                    </div>
                </div>
                

                <!-- Community Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button 
                        @click="open = !open"
                        class="flex items-center gap-1 text-gray-700 hover:text-primary-700 font-medium transition"
                    >
                        <span>Community</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div 
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute top-full left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2"
                        style="display: none;"
                    >
                        <a href="{{ route('resources.webinars') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <div class="text-sm font-medium">Webinars</div>
                                <div class="text-xs text-gray-500">Live online sessions</div>
                            </div>
                        </a>
                        <a href="{{ route('community') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                            <div>
                                <div class="text-sm font-medium">Discussions</div>
                                <div class="text-xs text-gray-500">Join the conversation</div>
                            </div>
                        </a>
                    </div>
                </div>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-primary-700 text-white rounded-lg hover:bg-primary-800 font-medium transition shadow-sm">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2.5 bg-primary-700 text-white rounded-lg hover:bg-primary-800 font-medium transition shadow-sm">
                        Login
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button 
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="md:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100 transition"
                aria-label="Toggle menu"
            >
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden border-t border-gray-100 bg-white"
        @click.away="mobileMenuOpen = false"
    >
        <div class="px-4 py-4 space-y-3">
            <a 
                href="{{ route('lawyers.search') }}" 
                class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition"
                @click="mobileMenuOpen = false"
            >
                Find a Lawyer
            </a>
            
            <!-- Documents Dropdown Mobile -->
            <div x-data="{ open: false }">
                <button 
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition"
                >
                    <span>Documents</span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div 
                    x-show="open"
                    x-transition
                    class="mt-2 ml-4 space-y-2"
                    style="display: none;"
                >
                    <a 
                        href="{{ route('documents.browse') }}" 
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 transition"
                        @click="mobileMenuOpen = false"
                    >
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <div class="text-sm font-medium">Document Drafting</div>
                            <div class="text-xs text-gray-500">Request custom documents</div>
                        </div>
                    </a>
                    <a 
                        href="{{ route('resources.downloadables') }}" 
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 transition"
                        @click="mobileMenuOpen = false"
                    >
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <div class="text-sm font-medium">Downloadables</div>
                            <div class="text-xs text-gray-500">Free legal templates</div>
                        </div>
                    </a>
                </div>
            </div>
            
            <!-- Resources Dropdown Mobile -->
            <div x-data="{ open: false }">
                <button 
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition"
                >
                    <span>Resources</span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div 
                    x-show="open"
                    x-transition
                    class="mt-2 ml-4 space-y-2"
                    style="display: none;"
                >
                    <a 
                        href="{{ route('resources.legal-guides') }}" 
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 transition"
                        @click="mobileMenuOpen = false"
                    >
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <div>
                            <div class="text-sm font-medium">Legal Guides</div>
                            <div class="text-xs text-gray-500">Educational articles</div>
                        </div>
                    </a>
                    <a 
                        href="{{ route('resources.news') }}" 
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 transition"
                        @click="mobileMenuOpen = false"
                    >
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        <div>
                            <div class="text-sm font-medium">News</div>
                            <div class="text-xs text-gray-500">Latest legal updates</div>
                        </div>
                    </a>
                    <a 
                        href="{{ route('resources.blogs') }}" 
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 transition"
                        @click="mobileMenuOpen = false"
                    >
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <div>
                            <div class="text-sm font-medium">Blogs</div>
                            <div class="text-xs text-gray-500">Insights & opinions</div>
                        </div>
                    </a>
                    <a 
                        href="{{ route('resources.events') }}" 
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 transition"
                        @click="mobileMenuOpen = false"
                    >
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <div class="text-sm font-medium">Events</div>
                            <div class="text-xs text-gray-500">Webinars & seminars</div>
                        </div>
                    </a>
                    <a 
                        href="{{ route('resources.galleries') }}" 
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 transition"
                        @click="mobileMenuOpen = false"
                    >
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <div class="text-sm font-medium">Galleries</div>
                            <div class="text-xs text-gray-500">Photos & videos</div>
                        </div>
                    </a>
                    <a 
                        href="{{ route('resources.faqs') }}" 
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 transition"
                        @click="mobileMenuOpen = false"
                    >
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <div class="text-sm font-medium">FAQs</div>
                            <div class="text-xs text-gray-500">Common questions</div>
                        </div>
                    </a>
                </div>
            </div>
            

            <!-- Community Dropdown Mobile -->
            <div x-data="{ open: false }">
                <button 
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition"
                >
                    <span>Community</span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div 
                    x-show="open"
                    x-transition
                    class="mt-2 ml-4 space-y-2"
                    style="display: none;"
                >
                    <a 
                        href="{{ route('resources.webinars') }}" 
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 transition"
                        @click="mobileMenuOpen = false"
                    >
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <div class="text-sm font-medium">Webinars</div>
                            <div class="text-xs text-gray-500">Live online sessions</div>
                        </div>
                    </a>
                    <a 
                        href="{{ route('community') }}" 
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 transition"
                        @click="mobileMenuOpen = false"
                    >
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                        <div>
                            <div class="text-sm font-medium">Discussions</div>
                            <div class="text-xs text-gray-500">Join the conversation</div>
                        </div>
                    </a>
                </div>
            </div>
            
            @auth
                <a 
                    href="{{ route('dashboard') }}" 
                    class="block px-4 py-3 bg-primary-700 text-white rounded-lg hover:bg-primary-800 font-medium transition text-center shadow-sm"
                    @click="mobileMenuOpen = false"
                >
                    Dashboard
                </a>
            @else
                <a 
                    href="{{ route('login') }}" 
                    class="block px-4 py-3 bg-primary-700 text-white rounded-lg hover:bg-primary-800 font-medium transition text-center shadow-sm"
                    @click="mobileMenuOpen = false"
                >
                    Login
                </a>
            @endauth
        </div>
    </div>
</nav>

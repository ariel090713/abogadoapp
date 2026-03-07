<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AbogadoMo App - Find Your Lawyer Online</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased font-sans">
    <!-- Navigation -->
    <x-guest-navbar />

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-700 via-primary-800 to-primary-900 text-white py-16 md:py-24 lg:py-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4 text-accent-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span>Trusted by 10,000+ Filipinos</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                    Expert Legal Advice,<br/>
                    <span class="text-accent-400">Anytime, Anywhere</span>
                </h1>
                
                <p class="text-lg md:text-xl text-primary-100 leading-relaxed mb-10 max-w-3xl mx-auto">
                    Connect with verified Philippine lawyers for online consultations. Get professional legal assistance from the comfort of your home.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('lawyers.search') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary-700 rounded-xl font-semibold hover:bg-gray-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <span>Find a Lawyer</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-accent-600 text-white rounded-xl font-semibold hover:bg-accent-700 transition border-2 border-accent-500">
                        Register as Lawyer
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-16 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">SIMPLE PROCESS</span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">Get legal help in three simple steps</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Connection Lines -->
                <div class="hidden md:block absolute top-20 left-1/4 right-1/4 h-0.5 bg-gradient-to-r from-primary-200 via-primary-300 to-primary-200"></div>
                
                <div class="relative bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition text-center group border border-gray-100">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-600 to-primary-700 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg group-hover:scale-110 transition">1</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Find Your Lawyer</h3>
                    <p class="text-base text-gray-600 leading-relaxed">Search and filter lawyers by practice area, location, rating, and price</p>
                </div>
                
                <div class="relative bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition text-center group border border-gray-100">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-600 to-primary-700 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg group-hover:scale-110 transition">2</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Book Consultation</h3>
                    <p class="text-base text-gray-600 leading-relaxed">Choose a time slot and pay securely online through multiple payment options</p>
                </div>
                
                <div class="relative bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition text-center group border border-gray-100">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-600 to-primary-700 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg group-hover:scale-110 transition">3</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Get Legal Advice</h3>
                    <p class="text-base text-gray-600 leading-relaxed">Meet your lawyer via video call, chat, or document review</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Practice Areas -->
    <section class="py-16 md:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 bg-accent-100 text-accent-700 rounded-full text-sm font-semibold mb-4">EXPERTISE</span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">Practice Areas</h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">Find lawyers specialized in various fields of law</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @php
                    $areas = [
                        ['name' => 'Family Law', 'icon' => '👨‍👩‍👧‍👦'],
                        ['name' => 'Criminal Law', 'icon' => '⚖️'],
                        ['name' => 'Corporate Law', 'icon' => '💼'],
                        ['name' => 'Labor Law', 'icon' => '👷'],
                        ['name' => 'Real Estate', 'icon' => '🏠'],
                        ['name' => 'Civil Law', 'icon' => '📋'],
                        ['name' => 'Tax Law', 'icon' => '💰'],
                        ['name' => 'Immigration', 'icon' => '🌍'],
                        ['name' => 'IP Law', 'icon' => '💡'],
                        ['name' => 'Banking', 'icon' => '🏦'],
                    ];
                @endphp
                @foreach($areas as $area)
                    <a href="{{ route('lawyers.search') }}" class="group relative bg-white rounded-2xl p-6 shadow-md hover:shadow-xl transition-all transform hover:-translate-y-1 text-center border border-gray-100">
                        <div class="text-5xl mb-4 transform group-hover:scale-110 transition">{{ $area['icon'] }}</div>
                        <div class="font-semibold text-base text-gray-900 group-hover:text-primary-700 transition">{{ $area['name'] }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Trusted By Section -->
    <section class="py-16 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">TRUSTED PLATFORM</span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">Trusted by Clients and Lawyers</h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">Join thousands of satisfied users who trust AbogadoMo App</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-5xl font-bold text-primary-700 mb-2">500+</div>
                    <div class="text-gray-600 font-medium">Verified Lawyers</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-primary-700 mb-2">10k+</div>
                    <div class="text-gray-600 font-medium">Consultations</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-primary-700 mb-2">4.8/5</div>
                    <div class="text-gray-600 font-medium">Average Rating</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-primary-700 mb-2">24/7</div>
                    <div class="text-gray-600 font-medium">Available</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="py-16 md:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 bg-accent-100 text-accent-700 rounded-full text-sm font-semibold mb-4">TESTIMONIALS</span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">What Our Clients Say</h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">Real experiences from real people</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-6 leading-relaxed">"AbogadoMo App made it so easy to find a lawyer for my family case. The video consultation was professional and convenient. Highly recommended!"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-bold">MR</div>
                        <div>
                            <div class="font-semibold text-gray-900">Maria Rodriguez</div>
                            <div class="text-sm text-gray-600">Client</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-6 leading-relaxed">"As a lawyer, this platform has helped me reach more clients and manage my practice efficiently. The payment system is secure and reliable."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-accent-100 rounded-full flex items-center justify-center text-accent-700 font-bold">JS</div>
                        <div>
                            <div class="font-semibold text-gray-900">Atty. Juan Santos</div>
                            <div class="text-sm text-gray-600">Lawyer</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-6 leading-relaxed">"Got quick legal advice for my business contract. The lawyer was knowledgeable and the whole process was smooth. Will use again!"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-bold">CT</div>
                        <div>
                            <div class="font-semibold text-gray-900">Carlos Tan</div>
                            <div class="text-sm text-gray-600">Business Owner</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Payment Options Section -->
    <section class="py-16 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">SECURE PAYMENTS</span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">Multiple Payment Options</h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">Pay securely with your preferred method</p>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12">
                <!-- PayMongo -->
                <div class="flex items-center justify-center p-6 bg-gray-50 rounded-xl border border-gray-200 hover:border-primary-300 transition">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary-700 mb-1">PayMongo</div>
                        <div class="text-sm text-gray-600">Secure Payment Gateway</div>
                    </div>
                </div>
                <!-- GCash -->
                <div class="flex items-center justify-center p-6 bg-gray-50 rounded-xl border border-gray-200 hover:border-primary-300 transition">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-1">GCash</div>
                        <div class="text-sm text-gray-600">E-Wallet</div>
                    </div>
                </div>
                <!-- Maya -->
                <div class="flex items-center justify-center p-6 bg-gray-50 rounded-xl border border-gray-200 hover:border-primary-300 transition">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 mb-1">Maya</div>
                        <div class="text-sm text-gray-600">Digital Payment</div>
                    </div>
                </div>
                <!-- Credit/Debit Card -->
                <div class="flex items-center justify-center p-6 bg-gray-50 rounded-xl border border-gray-200 hover:border-primary-300 transition">
                    <div class="text-center">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                            </svg>
                            <span class="text-xl font-bold text-gray-900">Cards</span>
                        </div>
                        <div class="text-sm text-gray-600">Visa, Mastercard</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 md:py-24 bg-gradient-to-br from-primary-700 via-primary-800 to-primary-900 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">Stay Updated</h2>
            <p class="text-lg md:text-xl text-primary-100 mb-8">Subscribe to our newsletter for legal tips, updates, and exclusive offers</p>
            @livewire('newsletter-subscribe')
            <p class="text-sm text-primary-200 mt-4">We respect your privacy. Unsubscribe anytime.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <img src="https://lawyerstorage-public.s3.ap-southeast-2.amazonaws.com/abogadomo-logo.png" alt="AbogadoMo Logo" class="w-10 h-10 rounded-lg shadow-sm">
                        <span class="text-2xl font-bold">AbogadoMo</span>
                    </div>
                    <p class="text-gray-400 leading-relaxed">Your trusted platform for online legal consultations in the Philippines</p>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">For Clients</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="{{ route('lawyers.search') }}" class="hover:text-white transition">Find a Lawyer</a></li>
                        <li><a href="{{ route('documents.browse') }}" class="hover:text-white transition">Browse Documents</a></li>
                        <li><a href="#guides" class="hover:text-white transition">Legal Guides</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">For Lawyers</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Join as Lawyer</a></li>
                        <li><a href="#" class="hover:text-white transition">Benefits</a></li>
                        <li><a href="#" class="hover:text-white transition">Resources</a></li>
                    </ul>
                </div>
                <div id="contact">
                    <h3 class="font-bold text-lg mb-4">Company</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="#about" class="hover:text-white transition">About Us</a></li>
                        <li><a href="#contact" class="hover:text-white transition">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-400 text-center md:text-left">&copy; {{ date('Y') }} AbogadoMo App. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    @livewireScripts
</body>
</html>

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
    <section class="relative bg-gradient-to-br from-primary-900 via-primary-800 to-accent-900 text-white py-16 md:py-24 lg:py-32 overflow-hidden">
        <!-- Abstract Background Elements -->
        <div class="absolute inset-0 z-0">
            <img src="https://lawyerstorage-public.s3.ap-southeast-2.amazonaws.com/abogadomoattybg.png" alt="Background" class="absolute inset-0 w-full h-full object-cover scale-110 opacity-[0.10] mix-blend-overlay">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 mix-blend-overlay"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-accent-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-pulse" style="animation-delay: 2s;"></div>
        </div>
        
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
                        <span>Start Your Legal Concern</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-16 md:py-24 bg-white relative overflow-hidden">
        <!-- Abstract background elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] bg-primary-50/60 rounded-full blur-3xl"></div>
            <div class="absolute top-[60%] -right-[10%] w-[40%] h-[40%] bg-accent-50/60 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16 md:mb-20">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary-700 rounded-full text-sm font-semibold mb-6 border border-primary-100 tracking-wider shadow-sm transform hover:scale-105 transition duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    SIMPLE PROCESS
                </span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 tracking-tight">How It Works</h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">Get legal help in three simple steps</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative mt-12">
                <!-- Connection Line (Desktop Only) -->
                <div class="hidden md:block absolute top-[5.5rem] left-[12%] right-[12%] border-t-2 border-dashed border-primary-200 z-0 opacity-70"></div>
                
                <!-- Step 1 -->
                <div class="relative bg-white rounded-3xl p-6 hover:shadow-[0_20px_40px_-15px_rgba(var(--color-primary-400),0.2)] transition-all duration-500 text-center group border border-gray-100 transform hover:-translate-y-2 z-10 md:mt-6">
                    <div class="absolute inset-0 bg-gradient-to-b from-primary-50/50 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative w-20 h-20 mx-auto mb-6">
                        <!-- Tilted shadow layer -->
                        <div class="absolute inset-0 bg-primary-100 rounded-2xl rotate-6 group-hover:rotate-12 transition-transform duration-500 opacity-60"></div>
                        <!-- Main block -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-600 to-primary-700 text-white rounded-2xl flex items-center justify-center text-3xl font-bold shadow-lg transform group-hover:-translate-y-1 transition duration-500">
                            1
                        </div>
                        <!-- Icon Badge -->
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center group-hover:scale-110 transition-transform duration-500 border border-gray-50 z-20">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41" class="animate-pulse" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-700 transition-colors duration-300">Ask AI</h3>
                    <p class="text-sm text-gray-600 leading-relaxed relative z-10">Describe your case to our Smart AI and let it instantly match you with the right lawyer.</p>
                </div>
                
                <!-- Step 2 -->
                <div class="relative bg-white rounded-3xl p-6 hover:shadow-[0_20px_40px_-15px_rgba(var(--color-primary-400),0.2)] transition-all duration-500 text-center group border border-gray-100 transform hover:-translate-y-2 z-10">
                    <div class="absolute inset-0 bg-gradient-to-b from-primary-50/50 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative w-20 h-20 mx-auto mb-6">
                        <!-- Tilted shadow layer -->
                        <div class="absolute inset-0 bg-primary-100 rounded-2xl -rotate-6 group-hover:-rotate-12 transition-transform duration-500 opacity-60"></div>
                        <!-- Main block -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-600 to-primary-700 text-white rounded-2xl flex items-center justify-center text-3xl font-bold shadow-lg transform group-hover:-translate-y-1 transition duration-500">
                            Or
                        </div>
                        <!-- Icon Badge -->
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center group-hover:scale-110 transition-transform duration-500 border border-gray-50 z-20">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-700 transition-colors duration-300">Browse Directly</h3>
                    <p class="text-sm text-gray-600 leading-relaxed relative z-10">Prefer to explore? Search and filter lawyers by practice area, location, or price manually.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="relative bg-white rounded-3xl p-6 hover:shadow-[0_20px_40px_-15px_rgba(var(--color-primary-400),0.2)] transition-all duration-500 text-center group border border-gray-100 transform hover:-translate-y-2 z-10 md:mt-6">
                     <div class="absolute inset-0 bg-gradient-to-b from-primary-50/50 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                     <div class="relative w-20 h-20 mx-auto mb-6">
                        <!-- Tilted shadow layer -->
                        <div class="absolute inset-0 bg-primary-100 rounded-2xl rotate-6 group-hover:rotate-12 transition-transform duration-500 opacity-60"></div>
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-600 to-primary-700 text-white rounded-2xl flex items-center justify-center text-3xl font-bold shadow-lg transform group-hover:-translate-y-1 transition duration-500">
                            2
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center group-hover:scale-110 transition-transform duration-500 border border-gray-50 z-20">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-700 transition-colors duration-300">Book Consultation</h3>
                    <p class="text-sm text-gray-600 leading-relaxed relative z-10">Choose an available time slot and pay securely online through multiple payment options.</p>
                </div>
                
                <!-- Step 4 -->
                <div class="relative bg-white rounded-3xl p-6 hover:shadow-[0_20px_40px_-15px_rgba(var(--color-primary-400),0.2)] transition-all duration-500 text-center group border border-gray-100 transform hover:-translate-y-2 z-10">
                     <div class="absolute inset-0 bg-gradient-to-b from-primary-50/50 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                     <div class="relative w-20 h-20 mx-auto mb-6">
                        <!-- Tilted shadow layer -->
                        <div class="absolute inset-0 bg-primary-100 rounded-2xl -rotate-6 group-hover:-rotate-12 transition-transform duration-500 opacity-60"></div>
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-600 to-primary-700 text-white rounded-2xl flex items-center justify-center text-3xl font-bold shadow-lg transform group-hover:-translate-y-1 transition duration-500">
                            3
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center group-hover:scale-110 transition-transform duration-500 border border-gray-50 z-20">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-700 transition-colors duration-300">Get Legal Advice</h3>
                    <p class="text-sm text-gray-600 leading-relaxed relative z-10">Meet your chosen lawyer via secure video call, chat, or thorough document review.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Practice Areas -->
    <section class="py-16 md:py-24 bg-gray-50 relative overflow-hidden" :class="{ 'z-[60]': expandedSpec }" x-data="{ 
        expandedSpec: null, 
        selectedChild: null,
        init() {
            this.$watch('expandedSpec', val => {
                if (val) document.body.style.overflow = 'hidden';
                else document.body.style.overflow = '';
            });
        },
        toggleSpec(id, index) {
            if (this.expandedSpec === id) {
                this.expandedSpec = null;
            } else {
                this.expandedSpec = id;
                this.selectedChild = null;
            }
        },
        closeModal() {
            this.expandedSpec = null;
        },
        selectChild(child) {
            if (this.selectedChild && this.selectedChild.id === child.id) {
                this.selectedChild = null;
            } else {
                this.selectedChild = child;
            }
        }
    }">
        <!-- Decorative Background -->
        <div class="absolute inset-0 z-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#1E3A8A 2px, transparent 2px); background-size: 32px 32px;"></div>

        <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-accent-50 text-accent-700 rounded-full text-sm font-semibold mb-6 border border-accent-100 shadow-sm transform hover:scale-105 transition duration-300 tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    EXPERTISE
                </span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 tracking-tight">Practice Areas</h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">Find specialized lawyers tailored to your specific legal needs</p>
            </div>
            
            @php
                $specializations = \App\Models\Specialization::with('children')
                    ->where(function($query) {
                        $query->where('is_parent', true)->orWhereNull('parent_id');
                    })
                    ->orderBy('name')
                    ->get();
            @endphp
            
            <!-- Mobile View (Accordion Style) - Hidden on Desktop -->
            <div class="xl:hidden space-y-4">
                @foreach($specializations as $spec)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:border-primary-200 transition-colors duration-300">
                        <!-- Parent Card -->
                        <button 
                            @click="toggleSpec({{ $spec->id }}, 0)"
                            class="w-full text-left p-5 md:p-6 flex items-center justify-between hover:bg-gray-50/50 transition duration-300"
                        >
                            <div class="flex items-center gap-4 flex-1">
                                @if($spec->image_url)
                                    <div class="relative w-16 h-16 rounded-xl overflow-hidden shrink-0 shadow-sm group-hover:shadow-md transition-shadow">
                                        <img src="{{ $spec->image_url }}" alt="{{ $spec->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-primary-900/10"></div>
                                    </div>
                                @else
                                    <div class="w-16 h-16 bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl flex items-center justify-center shrink-0 border border-primary-200/50 shadow-sm group-hover:shadow-md transition-shadow group-hover:scale-105 duration-300">
                                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-primary-700 transition-colors">{{ $spec->name }}</h3>
                                    <p class="text-sm font-medium text-gray-500">{{ $spec->children->count() }} {{ Str::plural('Category', $spec->children->count()) }}</p>
                                </div>
                            </div>
                            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-colors duration-300" :class="{ 'bg-primary-50 text-primary-600': expandedSpec === {{ $spec->id }}, 'bg-gray-50 text-gray-400 group-hover:bg-gray-100': expandedSpec !== {{ $spec->id }} }">
                                <svg class="w-5 h-5 transition-transform duration-300" :class="{ 'rotate-180': expandedSpec === {{ $spec->id }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        
                        <!-- Expanded Content -->
                        <div 
                            x-show="expandedSpec === {{ $spec->id }}"
                            x-collapse
                            class="border-t border-gray-100 bg-gradient-to-b from-gray-50/50 to-white"
                            style="display: none;"
                        >
                            <div class="p-5 md:p-6">
                                @if($spec->description)
                                    <p class="text-gray-600 mb-6 leading-relaxed">{{ $spec->description }}</p>
                                @endif
                                
                                <a href="{{ route('lawyers.search', ['specializations' => [$spec->slug]]) }}" class="inline-flex w-full sm:w-auto justify-center items-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-xl font-semibold hover:bg-primary-700 transition-colors duration-300 mb-8 shadow-md hover:shadow-lg">
                                    <span>Browse All {{ $spec->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                                
                                <!-- Sub-specializations -->
                                @if($spec->children->count() > 0)
                                    <div>
                                        <h4 class="text-xs font-bold tracking-widest text-gray-400 uppercase mb-4">Specific Categories</h4>
                                        <div class="space-y-3">
                                            @foreach($spec->children as $child)
                                                <div class="group/sub">
                                                    <button 
                                                        @click="selectChild({{ json_encode(['id' => $child->id]) }})"
                                                        class="w-full text-left p-4 rounded-xl border border-gray-200 transition-all duration-300"
                                                        :class="{ 
                                                            'border-primary-300 bg-primary-50/30 shadow-sm': selectedChild && selectedChild.id === {{ $child->id }},
                                                            'bg-white hover:border-primary-200 hover:shadow-sm': !selectedChild || selectedChild.id !== {{ $child->id }}
                                                        }"
                                                    >
                                                        <div class="flex items-center justify-between">
                                                            <span class="font-semibold text-gray-800" :class="{ 'text-primary-700': selectedChild && selectedChild.id === {{ $child->id }} }">{{ $child->name }}</span>
                                                            <svg class="w-5 h-5 transition-transform duration-300" :class="{ 'text-primary-600 rotate-90': selectedChild && selectedChild.id === {{ $child->id }}, 'text-gray-400 group-hover/sub:text-primary-400': !selectedChild || selectedChild.id !== {{ $child->id }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                            </svg>
                                                        </div>
                                                    </button>
                                                    
                                                    <div 
                                                        x-show="selectedChild && selectedChild.id === {{ $child->id }}"
                                                        x-collapse
                                                        class="px-4 pb-4 pt-2"
                                                        style="display: none;"
                                                    >
                                                        <div class="bg-white rounded-xl p-4 border border-primary-100 shadow-sm relative overflow-hidden">
                                                            <div class="absolute top-0 left-0 w-1 h-full bg-primary-500"></div>
                                                            @if($child->description)
                                                                <p class="text-sm text-gray-600 mb-4">{{ $child->description }}</p>
                                                            @endif
                                                            <a href="{{ route('lawyers.search', ['specializations' => [$child->slug]]) }}" class="inline-flex items-center gap-2 text-primary-700 font-semibold text-sm hover:text-primary-800 transition-colors group/link w-fit">
                                                                Find Specialized Lawyers
                                                                <svg class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Desktop View (Overlay Style, 5 Columns) - Hidden on Mobile/Tablet -->
            <div class="hidden xl:grid grid-cols-5 gap-6 relative z-50">
                @foreach($specializations as $index => $spec)
                    <div class="relative" :class="{ 'z-[100]': expandedSpec === {{ $spec->id }}, 'z-10 hover:z-20': expandedSpec !== {{ $spec->id }} }">
                        <!-- Parent Card -->
                        <button 
                            @click="toggleSpec({{ $spec->id }}, {{ $index }})"
                            class="w-full group relative bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 h-[22rem] text-left block"
                            :class="{ 'ring-2 ring-primary-500 ring-offset-4 ring-offset-gray-50 scale-[1.02] shadow-xl': expandedSpec === {{ $spec->id }}, 'transform hover:-translate-y-2': expandedSpec !== {{ $spec->id }} }"
                        >
                            <!-- Background Image or Default -->
                            <div class="absolute inset-0 bg-gray-900 overflow-hidden">
                                @if($spec->image_url)
                                    <img src="{{ $spec->image_url }}" alt="{{ $spec->name }}" class="w-full h-full object-cover opacity-60 group-hover:opacity-80 group-hover:scale-110 transition duration-700 ease-in-out">
                                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent opacity-90 group-hover:opacity-100 transition duration-500"></div>
                                @else
                                    <div class="absolute inset-0 bg-gradient-to-br from-primary-800 to-gray-900 opacity-90"></div>
                                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSIvPjwvc3ZnPg==')] pointer-events-none"></div>
                                    <div class="w-full h-[60%] flex items-center justify-center transform group-hover:scale-110 group-hover:-translate-y-2 transition duration-500">
                                        <svg class="w-24 h-24 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Content -->
                            <div class="absolute bottom-0 left-0 right-0 p-6 z-10 transform translate-y-2 group-hover:translate-y-0 transition duration-500">
                                <div class="w-12 h-1 bg-accent-500 rounded-full mb-4 transform scale-x-0 group-hover:scale-x-100 origin-left transition duration-500"></div>
                                <h3 class="font-bold text-2xl text-white leading-tight mb-2 max-w-xs">{{ $spec->name }}</h3>
                                <div class="flex items-center justify-between">
                                    <p class="text-primary-100 font-medium text-sm">{{ $spec->children->count() }} Categories</p>
                                    <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center transform group-hover:bg-accent-500 group-hover:text-white transition-all duration-300">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </button>
                        
                        <div 
                            x-show="expandedSpec === {{ $spec->id }}"
                            class="fixed inset-0 z-[110] flex items-center justify-center p-4 xl:p-8"
                            style="display: none;"
                        >
                            <!-- Dark Blur Backdrop -->
                            <div 
                                class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                                    x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    @click.stop="closeModal()"
                                ></div>

                                <div 
                                    class="relative z-10 w-full max-w-[60rem] max-h-[85vh] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col transform transition-all"
                                    x-transition:enter="transition-all ease-out duration-500"
                                    x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave="transition-all ease-in duration-300"
                                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                                >
                                    <!-- Modal Header (Compact matched with AI Chat) -->
                                    <div class="bg-primary-700 text-white p-4 md:p-6 flex items-center justify-between flex-shrink-0">
                                        <div class="flex items-center gap-3 md:gap-4">
                                            <div class="bg-white/20 rounded-full p-2 md:p-3">
                                                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg md:text-xl font-bold">{{ $spec->name }}</h3>
                                                <p class="text-xs md:text-sm text-primary-100">Browse specialized categories</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-3 items-center">
                                            <a href="{{ route('lawyers.search', ['specializations' => [$spec->slug]]) }}" class="hidden sm:inline-flex px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded-lg transition-colors items-center gap-2 border border-white/20">
                                                Browse All
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                                </svg>
                                            </a>
                                            <!-- Close Button -->
                                            <button 
                                                @click.stop="closeModal()"
                                                class="text-white hover:bg-white/20 rounded-lg p-2 transition"
                                                title="Close Panel"
                                            >
                                                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Sub-specializations Body -->
                                    <div class="flex-1 p-6 md:p-8 bg-gray-50 overflow-y-auto">
                                        @if($spec->description)
                                            <div class="mb-6 bg-white p-4 rounded-2xl border border-primary-100 shadow-sm">
                                                <p class="text-gray-600 text-sm leading-relaxed">{{ $spec->description }}</p>
                                            </div>
                                        @endif

                                        <div class="mb-5 flex items-center justify-between">
                                            <h4 class="text-md font-bold text-gray-900">
                                                Specific Categories
                                                <span class="bg-primary-100 text-primary-700 px-2 py-0.5 rounded-full text-xs ml-2">{{ $spec->children->count() }}</span>
                                            </h4>
                                        </div>

                                        @if($spec->children->count() > 0)
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                                                @foreach($spec->children as $child)
                                                    <div class="group/card relative bg-white border border-gray-100 rounded-xl overflow-hidden hover:border-primary-200 transition-colors shadow-sm" :class="{ 'ring-2 ring-primary-500 border-transparent': selectedChild && selectedChild.id === {{ $child->id }} }">
                                                        <!-- Main Card Button -->
                                                        <button 
                                                            @click="selectChild({{ json_encode(['id' => $child->id]) }})"
                                                            class="w-full text-left p-4 bg-white transition-all duration-300 relative z-10"
                                                        >
                                                            <div class="flex items-start justify-between gap-3">
                                                                <div class="flex-1">
                                                                    <h5 class="font-bold text-gray-900 mb-1 group-hover/card:text-primary-700 transition-colors line-clamp-2" :class="{ 'text-primary-700': selectedChild && selectedChild.id === {{ $child->id }} }">{{ $child->name }}</h5>
                                                                </div>
                                                                <div class="shrink-0 w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center group-hover/card:bg-primary-50 transition-colors" :class="{ 'bg-primary-50 text-primary-600': selectedChild && selectedChild.id === {{ $child->id }} }">
                                                                     <svg class="w-4 h-4 text-gray-400 group-hover/card:text-primary-600 transition-transform duration-300" :class="{ 'rotate-180': selectedChild && selectedChild.id === {{ $child->id }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                        </button>
                                                        
                                                        <!-- Detail Accordion -->
                                                        <div 
                                                            x-show="selectedChild && selectedChild.id === {{ $child->id }}"
                                                            x-collapse
                                                            class="bg-gray-50 border-t border-gray-100"
                                                            style="display: none;"
                                                        >
                                                            <div class="p-4 md:p-5">
                                                                @if($child->description)
                                                                    <p class="text-sm text-gray-600 mb-4 leading-relaxed">{{ $child->description }}</p>
                                                                @else
                                                                    <p class="text-sm text-gray-400 mb-4 italic">No additional description provided.</p>
                                                                @endif
                                                                
                                                                <a href="{{ route('lawyers.search', ['specializations' => [$child->slug]]) }}" class="flex items-center justify-center gap-2 px-4 py-2 text-white bg-primary-700 hover:bg-primary-800 text-sm font-semibold rounded-lg transition-colors group/btn">
                                                                    Find Lawyers
                                                                    <svg class="w-4 h-4 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-8">
                                                <p class="text-gray-500">Loading specific categories...</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- End Desktop View -->
        </div>
    </section>

    <section class="py-16 md:py-24 bg-gradient-to-br from-primary-900 via-primary-800 to-accent-900 relative overflow-hidden">
        <!-- Abstract Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary-600/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-full h-full bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDEzNGg3djFoLTd6bTAtNWg3djFoLTd6Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-10"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-accent-600/20 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 bg-white/10 backdrop-blur-sm text-primary-100 rounded-full text-sm font-semibold mb-4 border border-white/10 tracking-wider">TRUSTED PLATFORM</span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">Trusted by Clients and Lawyers</h2>
                <p class="text-lg md:text-xl text-primary-100 max-w-2xl mx-auto">Join thousands of satisfied users who trust <span class="text-white font-semibold">AbogadoMo App</span></p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                <!-- Stat Card 1 -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/20 to-accent-500/20 rounded-2xl transform group-hover:scale-105 transition-transform duration-500 ease-out"></div>
                    <div class="relative bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/10 text-center hover:bg-white/20 transition duration-500 h-full flex flex-col items-center justify-center transform group-hover:-translate-y-2 group-hover:shadow-[0_0_30px_rgba(var(--color-primary-500),0.3)]">
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition duration-500">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="flex items-baseline justify-center gap-1 group-hover:scale-110 transition duration-500">
                            <div class="text-5xl font-bold text-white tracking-tight">500</div>
                            <div class="text-4xl font-bold text-accent-400">+</div>
                        </div>
                        <div class="text-primary-100 font-medium text-lg mt-2">Verified Lawyers</div>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/20 to-accent-500/20 rounded-2xl transform group-hover:scale-105 transition-transform duration-500 ease-out"></div>
                    <div class="relative bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/10 text-center hover:bg-white/20 transition duration-500 h-full flex flex-col items-center justify-center transform group-hover:-translate-y-2 group-hover:shadow-[0_0_30px_rgba(var(--color-primary-500),0.3)]">
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:-rotate-3 transition duration-500">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                        </div>
                        <div class="flex items-baseline justify-center gap-1 group-hover:scale-110 transition duration-500">
                            <div class="text-5xl font-bold text-white tracking-tight">10k</div>
                            <div class="text-4xl font-bold text-accent-400">+</div>
                        </div>
                        <div class="text-primary-100 font-medium text-lg mt-2">Consultations</div>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/20 to-accent-500/20 rounded-2xl transform group-hover:scale-105 transition-transform duration-500 ease-out"></div>
                    <div class="relative bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/10 text-center hover:bg-white/20 transition duration-500 h-full flex flex-col items-center justify-center transform group-hover:-translate-y-2 group-hover:shadow-[0_0_30px_rgba(var(--color-primary-500),0.3)]">
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition duration-500">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <div class="flex items-baseline justify-center gap-1 group-hover:scale-110 transition duration-500">
                            <div class="text-5xl font-bold text-white tracking-tight">4.8</div>
                            <div class="text-4xl font-bold text-accent-400">/5</div>
                        </div>
                        <div class="text-primary-100 font-medium text-lg mt-2">Average Rating</div>
                    </div>
                </div>

                <!-- Stat Card 4 -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/20 to-accent-500/20 rounded-2xl transform group-hover:scale-105 transition-transform duration-500 ease-out"></div>
                    <div class="relative bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/10 text-center hover:bg-white/20 transition duration-500 h-full flex flex-col items-center justify-center transform group-hover:-translate-y-2 group-hover:shadow-[0_0_30px_rgba(var(--color-primary-500),0.3)]">
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:-rotate-3 transition duration-500">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex items-baseline justify-center gap-1 group-hover:scale-110 transition duration-500">
                            <div class="text-5xl font-bold text-white tracking-tight">24</div>
                            <div class="text-4xl font-bold text-accent-400">/7</div>
                        </div>
                        <div class="text-primary-100 font-medium text-lg mt-2">Available</div>
                    </div>
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
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 max-w-5xl mx-auto">
                <!-- GCash -->
                <div class="flex items-center justify-center p-8 bg-white rounded-2xl border-2 border-gray-200 hover:border-blue-400 hover:shadow-lg transition group">
                    <img src="https://lawyerstorage-public.s3.ap-southeast-2.amazonaws.com/gcash.png" 
                         alt="GCash" 
                         class="h-10 w-auto object-contain">
                </div>

                <!-- Maya -->
                <div class="flex items-center justify-center p-8 bg-white rounded-2xl border-2 border-gray-200 hover:border-green-400 hover:shadow-lg transition group">
                    <img src="https://lawyerstorage-public.s3.ap-southeast-2.amazonaws.com/maya.jpg" 
                         alt="Maya" 
                         class="h-10 w-auto object-contain">
                </div>

                <!-- Visa -->
                <div class="flex items-center justify-center p-8 bg-white rounded-2xl border-2 border-gray-200 hover:border-blue-600 hover:shadow-lg transition group">
                    <img src="https://lawyerstorage-public.s3.ap-southeast-2.amazonaws.com/visa.png" 
                         alt="Visa" 
                         class="h-8 w-auto object-contain">
                </div>

                <!-- Mastercard -->
                <div class="flex items-center justify-center p-8 bg-white rounded-2xl border-2 border-gray-200 hover:border-red-500 hover:shadow-lg transition group">
                    <img src="https://lawyerstorage-public.s3.ap-southeast-2.amazonaws.com/mastercard.webp" 
                         alt="Mastercard" 
                         class="h-8 w-auto object-contain">
                </div>
            </div>

            <!-- Powered by PayMongo -->
            <div class="text-center mt-12">
                <p class="text-sm text-gray-500 mb-3">Powered by</p>
                <div class="inline-flex items-center gap-3 px-8 py-4 bg-gray-50 rounded-xl border border-gray-200">
                    <svg class="w-8 h-8" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="100" height="100" rx="20" fill="#1E3A8A"/>
                        <path d="M50 20C33.43 20 20 33.43 20 50C20 66.57 33.43 80 50 80C66.57 80 80 66.57 80 50C80 33.43 66.57 20 50 20ZM50 72C37.85 72 28 62.15 28 50C28 37.85 37.85 28 50 28C62.15 28 72 37.85 72 50C72 62.15 62.15 72 50 72Z" fill="white"/>
                        <path d="M50 38C43.37 38 38 43.37 38 50C38 56.63 43.37 62 50 62C56.63 62 62 56.63 62 50C62 43.37 56.63 38 50 38ZM54 51H51V54C51 54.55 50.55 55 50 55C49.45 55 49 54.55 49 54V51H46C45.45 51 45 50.55 45 50C45 49.45 45.45 49 46 49H49V46C49 45.45 49.45 45 50 45C50.55 45 51 45.45 51 46V49H54C54.55 49 55 49.45 55 50C55 50.55 54.55 51 54 51Z" fill="white"/>
                    </svg>
                    <span class="text-xl font-bold text-gray-900">PayMongo</span>
                    <span class="text-xs text-gray-500 ml-2 hidden sm:inline">Secure Payment Gateway</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Join as Lawyer Section -->
    <section class="py-16 md:py-24 bg-gradient-to-br from-primary-700 via-primary-800 to-accent-700 text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDEzNGg3djFoLTd6bTAtNWg3djFoLTd6Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-10"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div>
                    <span class="inline-block px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-sm font-semibold mb-6">FOR LAWYERS</span>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">Join Our Network of Legal Professionals</h2>
                    <p class="text-lg md:text-xl text-white/90 mb-8 leading-relaxed">
                        Expand your practice, reach more clients, and grow your legal career with AbogadoMo. Join hundreds of verified lawyers serving clients across the Philippines.
                    </p>

                    <!-- CTA Button -->
                    <a href="{{ route('register') }}" 
                        class="inline-flex items-center gap-2 px-8 py-4 bg-white text-primary-700 font-bold rounded-xl hover:bg-gray-50 transition shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                        <span>Join as a Lawyer</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>

                <!-- Right Content - Benefits -->
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-1">Reach More Clients</h3>
                            <p class="text-white/80">Connect with clients nationwide looking for legal services</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-1">Flexible Schedule</h3>
                            <p class="text-white/80">Set your own availability and consultation rates</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-1">Secure Platform</h3>
                            <p class="text-white/80">Safe payments, data protection, and professional support</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-1">Grow Your Practice</h3>
                            <p class="text-white/80">Build your reputation with client reviews and ratings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mobile App Section -->
    <section class="py-16 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div>
                    <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-6">COMING SOON</span>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">Get Legal Help On The Go</h2>
                    <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed">
                        Download the AbogadoMo mobile app and access legal services anytime, anywhere. Connect with lawyers, manage consultations, and get legal advice right from your smartphone.
                    </p>

                    <!-- Features List -->
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Video consultations on mobile</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Instant notifications and updates</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Secure document sharing</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Easy payment options</span>
                        </div>
                    </div>

                    <!-- App Store Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#" class="inline-flex items-center gap-3 px-6 py-4 bg-black text-white rounded-xl hover:bg-gray-800 transition shadow-lg group">
                            <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                            </svg>
                            <div class="text-left">
                                <div class="text-xs">Download on the</div>
                                <div class="text-lg font-semibold -mt-1">App Store</div>
                            </div>
                        </a>

                        <a href="#" class="inline-flex items-center gap-3 px-6 py-4 bg-black text-white rounded-xl hover:bg-gray-800 transition shadow-lg group">
                            <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.53,12.9 20.18,13.18L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/>
                            </svg>
                            <div class="text-left">
                                <div class="text-xs">GET IT ON</div>
                                <div class="text-lg font-semibold -mt-1">Google Play</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Right Content - Phone Mockup -->
                <div class="relative">
                    <div class="relative mx-auto w-64 md:w-80">
                        <!-- Phone Frame -->
                        <div class="relative bg-gray-900 rounded-[3rem] p-3 shadow-2xl">
                            <!-- Screen -->
                            <div class="bg-white rounded-[2.5rem] overflow-hidden aspect-[9/19]">
                                <!-- Placeholder for app screenshot -->
                                <div class="w-full h-full bg-gradient-to-br from-primary-600 to-accent-600 flex items-center justify-center p-8">
                                    <div class="text-center text-white">
                                        <div class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-bold mb-2">AbogadoMo</h3>
                                        <p class="text-white/90 text-sm">Legal Services On The Go</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Notch -->
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-6 bg-gray-900 rounded-b-2xl"></div>
                        </div>
                        
                        <!-- Floating Elements -->
                        <div class="absolute -top-4 -right-4 w-20 h-20 bg-primary-100 rounded-2xl flex items-center justify-center shadow-lg animate-bounce">
                            <svg class="w-10 h-10 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-accent-100 rounded-2xl flex items-center justify-center shadow-lg" style="animation: bounce 2s infinite 0.5s;">
                            <svg class="w-8 h-8 text-accent-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 md:py-24 bg-gradient-to-br from-primary-700 via-primary-800 to-accent-700 text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDEzNGg3djFoLTd6bTAtNWg3djFoLTd6Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-10"></div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">Stay Updated</h2>
            <p class="text-lg md:text-xl text-white/90 mb-8">Subscribe to our newsletter for legal tips, updates, and exclusive offers</p>
            @livewire('newsletter-subscribe')
            <p class="text-sm text-white/70 mt-4">We respect your privacy. Unsubscribe anytime.</p>
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

    <!-- Back to Top Button -->
    <div x-data="{ show: false }" 
         x-on:scroll.window="show = window.pageYOffset >= 300" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300 transform" 
         x-transition:enter-start="opacity-0 translate-y-8" 
         x-transition:enter-end="opacity-100 translate-y-0" 
         x-transition:leave="transition ease-in duration-300 transform" 
         x-transition:leave-start="opacity-100 translate-y-0" 
         x-transition:leave-end="opacity-0 translate-y-8" 
         class="fixed bottom-6 right-6 z-50">
        <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" 
                class="bg-primary-700 hover:bg-primary-800 text-white p-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 group"
                aria-label="Back to top">
            <svg class="w-6 h-6 transform group-hover:-translate-y-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>
    </div>

    @livewireScripts
</body>
</html>

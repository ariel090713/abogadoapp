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
    <section class="relative bg-gradient-to-br from-primary-700 via-primary-800 to-accent-700 text-white py-16 md:py-24 lg:py-32 overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="https://lawyerstorage-public.s3.ap-southeast-2.amazonaws.com/abogadomoattybg.png" alt="Background" class="w-full h-full object-cover scale-110 opacity-30">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-700/70 via-primary-800/70 to-accent-700/70"></div>
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
                        <span>Describe Your Legal Concern</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
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
    <section class="py-16 md:py-24 bg-gray-50" x-data="{ 
        expandedSpec: null, 
        selectedChild: null,
        toggleSpec(id, index) {
            if (this.expandedSpec === id) {
                this.expandedSpec = null;
                this.selectedChild = null;
            } else {
                this.expandedSpec = id;
                this.selectedChild = null;
            }
        },
        selectChild(child) {
            if (this.selectedChild && this.selectedChild.id === child.id) {
                this.selectedChild = null;
            } else {
                this.selectedChild = child;
            }
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 bg-accent-100 text-accent-700 rounded-full text-sm font-semibold mb-4">EXPERTISE</span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">Practice Areas</h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">Find lawyers specialized in various fields of law</p>
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
            <div class="lg:hidden space-y-4">
                @foreach($specializations as $spec)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <!-- Parent Card -->
                        <button 
                            @click="toggleSpec({{ $spec->id }}, 0)"
                            class="w-full text-left p-6 flex items-center justify-between hover:bg-gray-50 transition"
                        >
                            <div class="flex items-center gap-4 flex-1">
                                @if($spec->image_url)
                                    <img src="{{ $spec->image_url }}" alt="{{ $spec->name }}" class="w-16 h-16 object-cover rounded-xl">
                                @else
                                    <div class="w-16 h-16 bg-gradient-to-br from-primary-600 to-primary-800 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-bold text-lg text-gray-900">{{ $spec->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $spec->children->count() }} {{ Str::plural('specialization', $spec->children->count()) }}</p>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-400 transition-transform flex-shrink-0" :class="{ 'rotate-180': expandedSpec === {{ $spec->id }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Expanded Content -->
                        <div 
                            x-show="expandedSpec === {{ $spec->id }}"
                            x-transition
                            class="border-t border-gray-200 p-6 bg-gray-50"
                            style="display: none;"
                        >
                            @if($spec->description)
                                <p class="text-gray-600 mb-4">{{ $spec->description }}</p>
                            @endif
                            
                            <a href="{{ route('lawyers.search', ['specializations' => [$spec->slug]]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-700 text-white rounded-lg font-semibold hover:bg-primary-800 transition mb-6">
                                <span>Find {{ $spec->name }} Lawyers</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                            
                            <!-- Sub-specializations -->
                            @if($spec->children->count() > 0)
                                <div class="mt-4">
                                    <h4 class="font-bold text-gray-900 mb-3">Sub-specializations</h4>
                                    <div class="space-y-2">
                                        @foreach($spec->children as $child)
                                            <div>
                                                <button 
                                                    @click="selectChild({{ json_encode(['id' => $child->id, 'name' => $child->name, 'description' => $child->description]) }})"
                                                    class="w-full text-left p-3 rounded-lg border-2 transition-all"
                                                    :class="{ 
                                                        'border-primary-500 bg-primary-50': selectedChild && selectedChild.id === {{ $child->id }},
                                                        'border-gray-200 bg-white hover:border-primary-300': !selectedChild || selectedChild.id !== {{ $child->id }}
                                                    }"
                                                >
                                                    <div class="flex items-center justify-between">
                                                        <span class="font-medium text-gray-900">{{ $child->name }}</span>
                                                        <svg class="w-5 h-5 text-primary-600 transition-transform" :class="{ 'rotate-90': selectedChild && selectedChild.id === {{ $child->id }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                    </div>
                                                </button>
                                                
                                                <div 
                                                    x-show="selectedChild && selectedChild.id === {{ $child->id }}"
                                                    x-transition
                                                    class="mt-2 p-3 bg-primary-50 rounded-lg border border-primary-200"
                                                    style="display: none;"
                                                >
                                                    @if($child->description)
                                                        <p class="text-sm text-gray-700 mb-3">{{ $child->description }}</p>
                                                    @endif
                                                    <a href="{{ route('lawyers.search', ['specializations' => [$child->slug]]) }}" class="inline-flex items-center gap-2 px-3 py-2 bg-primary-700 text-white text-sm rounded-lg font-semibold hover:bg-primary-800 transition">
                                                        <span>Find Lawyers</span>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Desktop View (Overlay Style) - Hidden on Mobile -->
            <div class="hidden lg:grid grid-cols-3 gap-6 lg:gap-8 relative">
                @foreach($specializations as $index => $spec)
                    @php
                        $positionInRow = $index % 3;
                        // Calculate offset including gaps: -0%, -100% - gap, -200% - 2*gap
                        // gap-8 = 2rem = 32px, so we need to account for it
                        if ($positionInRow == 0) {
                            $leftOffset = 0;
                        } elseif ($positionInRow == 1) {
                            $leftOffset = 'calc(-100% - 2rem)'; // -100% minus one gap
                        } else {
                            $leftOffset = 'calc(-200% - 4rem)'; // -200% minus two gaps
                        }
                    @endphp
                    <div class="relative" :class="{ 'z-50': expandedSpec === {{ $spec->id }}, 'z-10': expandedSpec !== {{ $spec->id }} }">
                        <!-- Parent Card -->
                        <button 
                            @click="toggleSpec({{ $spec->id }}, {{ $index }})"
                            class="w-full group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2 h-80 duration-300"
                            :class="{ 'ring-4 ring-primary-500': expandedSpec === {{ $spec->id }} }"
                        >
                            <!-- Background Image or Default -->
                            <div class="absolute inset-0 bg-gradient-to-br from-primary-600 to-primary-800">
                                @if($spec->image_url)
                                    <img src="{{ $spec->image_url }}" alt="{{ $spec->name }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition duration-500">
                                @else
                                    <!-- Default gradient background -->
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-32 h-32 text-white opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                            
                            <!-- Title (Always Visible) -->
                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                <h3 class="font-bold text-2xl text-white leading-tight">{{ $spec->name }}</h3>
                                <p class="text-primary-100 text-sm mt-2">{{ $spec->children->count() }} {{ Str::plural('specialization', $spec->children->count()) }}</p>
                            </div>
                            
                            <!-- Click indicator -->
                            <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm rounded-full p-2">
                                <svg class="w-6 h-6 text-white transition-transform" :class="{ 'rotate-180': expandedSpec === {{ $spec->id }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        
                        <!-- Expanded Panel (Absolute positioned, aligned to row start) -->
                        <div 
                            x-show="expandedSpec === {{ $spec->id }}"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            @click.away="expandedSpec = null; selectedChild = null"
                            class="absolute top-0 bg-white rounded-2xl shadow-2xl overflow-hidden z-40"
                            style="display: none; left: {{ is_numeric($leftOffset) ? $leftOffset . '%' : $leftOffset }}; width: calc(300% + 4rem); min-height: calc(200% + 1.5rem);"
                        >
                            <!-- Close Button -->
                            <button 
                                @click="expandedSpec = null; selectedChild = null"
                                class="absolute top-4 right-4 z-50 bg-red-600 hover:bg-red-700 text-white rounded-full p-2 shadow-lg transition"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            
                            <div class="p-8">
                                <!-- Parent Info -->
                                <div class="flex items-start gap-4 mb-8">
                                    @if($spec->image_url)
                                        <img src="{{ $spec->image_url }}" alt="{{ $spec->name }}" class="w-24 h-24 object-cover rounded-xl shadow-lg">
                                    @else
                                        <div class="w-24 h-24 bg-gradient-to-br from-primary-600 to-primary-800 rounded-xl flex items-center justify-center shadow-lg">
                                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $spec->name }}</h3>
                                        @if($spec->description)
                                            <p class="text-gray-600 leading-relaxed mb-4">{{ $spec->description }}</p>
                                        @endif
                                        <a href="{{ route('lawyers.search', ['specializations' => [$spec->slug]]) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-700 text-white rounded-xl font-semibold hover:bg-primary-800 transition shadow-lg">
                                            <span>Find {{ $spec->name }} Lawyers</span>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Sub-specializations in 3-column grid -->
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 mb-4">Sub-specializations ({{ $spec->children->count() }})</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($spec->children as $child)
                                            <div>
                                                <button 
                                                    @click="selectChild({{ json_encode([
                                                        'id' => $child->id,
                                                        'name' => $child->name,
                                                        'description' => $child->description,
                                                        'image_url' => $child->image_url,
                                                        'icon' => $child->icon
                                                    ]) }})"
                                                    class="w-full text-left p-4 rounded-xl border-2 transition-all hover:border-primary-500 hover:bg-primary-50 hover:shadow-md"
                                                    :class="{ 
                                                        'border-primary-500 bg-primary-50 shadow-md': selectedChild && selectedChild.id === {{ $child->id }},
                                                        'border-gray-200 bg-white': !selectedChild || selectedChild.id !== {{ $child->id }}
                                                    }"
                                                >
                                                    <div class="flex items-center justify-between gap-2">
                                                        <p class="font-semibold text-gray-900 flex-1">{{ $child->name }}</p>
                                                        <svg class="w-5 h-5 text-primary-600 flex-shrink-0 transition-transform" :class="{ 'rotate-90': selectedChild && selectedChild.id === {{ $child->id }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                        </svg>
                                                    </div>
                                                </button>
                                                
                                                <!-- Child Detail (Collapsible below card) -->
                                                <div 
                                                    x-show="selectedChild && selectedChild.id === {{ $child->id }}"
                                                    x-transition
                                                    class="mt-2 p-4 bg-gradient-to-br from-primary-50 to-accent-50 rounded-xl border-2 border-primary-200"
                                                    style="display: none;"
                                                >
                                                    @if($child->image_url)
                                                        <img src="{{ $child->image_url }}" alt="{{ $child->name }}" class="w-full h-32 object-cover rounded-lg mb-3">
                                                    @else
                                                        <div class="w-full h-32 bg-gradient-to-br from-primary-600 to-primary-800 rounded-lg flex items-center justify-center mb-3">
                                                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <h5 class="font-bold text-gray-900 mb-2">{{ $child->name }}</h5>
                                                    @if($child->description)
                                                        <p class="text-sm text-gray-700 leading-relaxed mb-3">{{ $child->description }}</p>
                                                    @endif
                                                    <a href="{{ route('lawyers.search', ['specializations' => [$child->slug]]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-700 text-white text-sm rounded-lg font-semibold hover:bg-primary-800 transition">
                                                        <span>Find Lawyers</span>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- End Desktop View -->
        </div>
    </section>

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
    @livewireScripts
</body>
</html>

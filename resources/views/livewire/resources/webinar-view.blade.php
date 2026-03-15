<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Hero Banner with Cover Image -->
    <section class="relative h-[60vh] min-h-[400px] flex items-end">
        <div class="absolute inset-0 z-0">
            <img src="{{ $webinar['image'] }}" alt="{{ $webinar['title'] }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/80 to-gray-900/30"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10 pb-12 lg:pb-16">
            <a href="{{ route('resources.webinars') }}" class="inline-flex items-center gap-2 text-white/70 hover:text-white transition-colors mb-6 font-medium text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Webinars
            </a>
            
            <div class="flex flex-wrap items-center gap-3 mb-4">
                @if($webinar['status'] === 'Ongoing')
                    <span class="flex items-center gap-1.5 px-3 py-1 bg-red-500/90 backdrop-blur text-white text-xs font-bold rounded-full shadow-sm animate-pulse">
                        <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                        LIVE NOW
                    </span>
                @else
                    <span class="px-3 py-1 bg-white/20 backdrop-blur text-white border border-white/30 text-xs font-bold rounded-full shadow-sm">
                        UPCOMING
                    </span>
                @endif
                <span class="px-3 py-1 bg-accent-500/90 backdrop-blur text-white text-xs font-bold rounded-full shadow-sm">
                    FREE WEBINAR
                </span>
            </div>

            <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight max-w-4xl drop-shadow-md">
                {{ $webinar['title'] }}
            </h1>

            <div class="flex flex-wrap items-center gap-6 text-white/90 font-medium bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20 max-w-fit">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ \Carbon\Carbon::parse($webinar['date'])->format('l, F j, Y') }}
                </div>
                <div class="hidden sm:block w-1 h-1 bg-white/50 rounded-full"></div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ \Carbon\Carbon::parse($webinar['date'])->format('g:i A') }} ({{ $webinar['duration'] }})
                </div>
                <div class="hidden sm:block w-1 h-1 bg-white/50 rounded-full"></div>
                <div class="flex items-center gap-2 border border-accent-400/30 bg-accent-400/10 px-3 py-1 rounded-full">
                    <svg class="w-4 h-4 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    {{ $webinar['platform'] }}
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content & Registration Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
            
            <!-- Left Column: Details -->
            <div class="lg:col-span-7 xl:col-span-8 space-y-12">
                
                <!-- Description -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        About This Webinar
                    </h2>
                    <div class="prose prose-lg text-gray-600 prose-primary">
                        <p class="leading-relaxed">{{ $webinar['description'] }}</p>
                    </div>
                </div>

                <!-- Speakers -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-accent-100 flex items-center justify-center text-accent-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        Expert Speakers
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($webinar['speakers'] as $speaker)
                            <div class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                <img src="{{ $speaker['image'] }}" alt="{{ $speaker['name'] }}" class="w-16 h-16 rounded-full object-cover border-2 border-primary-100">
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $speaker['name'] }}</h4>
                                    <p class="text-xs text-primary-600 font-medium uppercase tracking-wider">{{ $speaker['role'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Agenda -->
                @if(isset($webinar['agenda']) && count($webinar['agenda']) > 0)
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        Schedule Overview
                    </h2>
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                        <ul class="space-y-6">
                            @foreach($webinar['agenda'] as $index => $agendaItem)
                                <li class="relative flex gap-4">
                                    <div class="absolute left-3.5 top-8 bottom-[-24px] w-px bg-gray-200 {{ $loop->last ? 'hidden' : '' }}"></div>
                                    <div class="relative z-10 w-8 h-8 rounded-full bg-primary-50 border-2 border-primary-200 flex items-center justify-center font-bold text-primary-600 text-sm shrink-0">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="pt-1 text-gray-700 font-medium">
                                        {{ $agendaItem }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Registration Sticky Sidebar -->
            <div class="lg:col-span-5 xl:col-span-4">
                <div class="sticky top-28">
                    
                    @if($registered)
                        <div class="bg-gradient-to-b from-primary-800 to-primary-900 rounded-3xl p-8 shadow-2xl text-center border border-primary-700 text-white transform transition-all animate-fade-in-up">
                            <div class="w-20 h-20 mx-auto bg-accent-500 rounded-full flex items-center justify-center mb-6 shadow-lg shadow-accent-500/20">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-3">You're In!</h3>
                            <p class="text-primary-100 mb-6">Your registration for <strong>{{ $webinar['title'] }}</strong> was successful. We've sent a confirmation email with the joining link.</p>
                            
                            <div class="bg-white/10 rounded-xl p-4 border border-white/20 flex flex-col items-center gap-2">
                                <span class="text-xs text-primary-200 uppercase tracking-wider font-bold">Add to Calendar</span>
                                <div class="flex gap-2">
                                    <button class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm font-medium transition">Google</button>
                                    <button class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm font-medium transition">Apple</button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-primary-700 to-primary-800 px-8 py-6 text-white text-center">
                                <h3 class="text-xl font-bold mb-1">Reserve Your Seat</h3>
                                <p class="text-primary-100 text-sm">Spots are limited, register for free.</p>
                            </div>
                            
                            <div class="p-8">
                                <form wire:submit.prevent="register" class="space-y-5">
                                    
                                    <div>
                                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                                        <input wire:model="name" type="text" id="name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all bg-gray-50 focus:bg-white @error('name') border-red-300 ring-4 ring-red-500/10 @enderror" placeholder="John Doe">
                                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                                        <input wire:model="email" type="email" id="email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all bg-gray-50 focus:bg-white @error('email') border-red-300 ring-4 ring-red-500/10 @enderror" placeholder="john@company.com">
                                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="company" class="block text-sm font-semibold text-gray-700 mb-1.5">Company / Organization <span class="font-normal text-gray-400 font-normal italic">(Optional)</span></label>
                                        <input wire:model="company" type="text" id="company" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all bg-gray-50 focus:bg-white" placeholder="Company Name">
                                    </div>

                                    <div>
                                        <label for="questions" class="block text-sm font-semibold text-gray-700 mb-1.5">Questions for Speakers <span class="font-normal text-gray-400 font-normal italic">(Optional)</span></label>
                                        <textarea wire:model="questions" id="questions" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all bg-gray-50 focus:bg-white resize-none" placeholder="What would you like to ask during the Q&A?"></textarea>
                                    </div>

                                    <button type="submit" class="w-full py-4 bg-accent-600 hover:bg-accent-500 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-center justify-center gap-2 group mt-4">
                                        Register Now
                                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </button>

                                    <p class="text-xs text-center text-gray-400 mt-4 leading-relaxed">
                                        By registering, you agree to our <a href="#" class="text-primary-600 hover:underline">Terms of Service</a> and acknowledge our Privacy Policy.
                                    </p>

                                </form>
                            </div>
                        </div>
                    @endif
                    
                </div>
            </div>

        </div>
    </section>

</div>

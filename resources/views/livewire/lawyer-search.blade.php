<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- AI Assistant Modal -->
    @if($showAIModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" wire:click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden" @click.stop>
                @if(!$aiMode)
                    <!-- Initial Choice -->
                    <div class="p-8 md:p-12">
                        <div class="text-center mb-8">
                            <div class="bg-gradient-to-br from-primary-700 to-accent-700 rounded-full p-4 w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">How would you like to find a lawyer?</h2>
                            <p class="text-lg text-gray-600">Choose the option that works best for you</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- AI Assistant Option -->
                            <button 
                                wire:click="startAIChat"
                                class="group p-8 border-2 border-gray-200 rounded-2xl hover:border-primary-500 hover:shadow-xl transition-all duration-300 text-left"
                            >
                                <div class="bg-gradient-to-br from-primary-100 to-accent-100 rounded-xl p-4 w-16 h-16 mb-6 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">Ask AI to Help Me</h3>
                                <p class="text-gray-600 mb-4">Describe your legal concern and let our AI assistant recommend the best lawyers for your needs.</p>
                                <div class="flex items-center text-primary-700 font-medium group-hover:translate-x-2 transition-transform">
                                    <span>Get Started</span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </div>
                            </button>

                            <!-- Manual Browse Option -->
                            <button 
                                wire:click="closeModal"
                                class="group p-8 border-2 border-gray-200 rounded-2xl hover:border-primary-500 hover:shadow-xl transition-all duration-300 text-left"
                            >
                                <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl p-4 w-16 h-16 mb-6 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">Let Me Browse Lawyers</h3>
                                <p class="text-gray-600 mb-4">Explore our directory of verified lawyers using filters and search to find the right match.</p>
                                <div class="flex items-center text-primary-700 font-medium group-hover:translate-x-2 transition-transform">
                                    <span>Browse Now</span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                @else
                    <!-- AI Chat Interface -->
                    <div class="flex flex-col h-[80vh]">
                        <!-- Chat Header -->
                        <div class="bg-gradient-to-br from-primary-700 to-accent-700 text-white p-6 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-white/20 rounded-full p-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">AI Legal Assistant</h3>
                                    <p class="text-sm text-primary-100">Helping you find the right lawyer</p>
                                </div>
                            </div>
                            <button wire:click="closeModal" class="text-white hover:bg-white/20 rounded-lg p-2 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Chat Messages -->
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
                            @foreach($conversation as $message)
                                @if($message['role'] === 'user')
                                    <!-- User Message -->
                                    <div class="flex justify-end">
                                        <div class="bg-primary-700 text-white rounded-2xl rounded-tr-sm px-6 py-3 max-w-[80%]">
                                            <p class="text-sm md:text-base">{{ $message['content'] }}</p>
                                        </div>
                                    </div>
                                @else
                                    <!-- AI Message -->
                                    <div class="flex justify-start">
                                        <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-sm px-6 py-3 max-w-[80%] shadow-sm">
                                            <p class="text-sm md:text-base text-gray-800">{{ $message['content'] }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @if($isAIThinking)
                                <div class="flex justify-start">
                                    <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-sm px-6 py-3 shadow-sm">
                                        <div class="flex items-center gap-2">
                                            <div class="flex gap-1">
                                                <div class="w-2 h-2 bg-primary-700 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                                                <div class="w-2 h-2 bg-primary-700 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                                                <div class="w-2 h-2 bg-primary-700 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                                            </div>
                                            <span class="text-sm text-gray-600">AI is thinking...</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Recommendation Button (if AI has recommendations) -->
                        @if(!empty($aiRecommendedSpecializations))
                            <div class="p-4 bg-primary-50 border-t border-primary-200">
                                <button 
                                    wire:click="applyAIFilters"
                                    class="w-full bg-gradient-to-r from-primary-700 to-accent-700 text-white px-6 py-4 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>View Filtered Lawyers ({{ count($aiRecommendedSpecializations) }} specializations)</span>
                                </button>
                            </div>
                        @endif

                        <!-- Chat Input -->
                        <div class="p-6 bg-white border-t border-gray-200">
                            <form wire:submit.prevent="sendMessage" class="flex gap-3">
                                <input 
                                    type="text"
                                    wire:model="userMessage"
                                    placeholder="Type your message..."
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    @if($isAIThinking) disabled @endif
                                >
                                <button 
                                    type="submit"
                                    class="bg-primary-700 text-white px-6 py-3 rounded-xl hover:bg-primary-800 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                                    @if($isAIThinking) disabled @endif
                                >
                                    <span class="hidden md:inline">Send</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="bg-gradient-to-br from-primary-700 via-primary-800 to-accent-700 text-white py-8 md:py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-3 md:mb-4">Find Your Lawyer</h1>
            <p class="text-base md:text-lg lg:text-xl text-primary-100">Connect with verified legal professionals in the Philippines</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        <!-- Filters Bar -->
        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 mb-6 border border-gray-100">
            <div class="flex flex-col lg:flex-row items-start lg:items-center gap-4">
                <!-- Search -->
                <div class="w-full lg:w-80">
                    <div class="relative">
                        <input 
                            type="text"
                            wire:model.live.debounce.500ms="search" 
                            placeholder="Search lawyer by name..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        >
                        <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Practice Area Dropdown -->
                <div class="w-full lg:w-64" x-data="{ open: false }">
                    <div class="relative">
                        <button 
                            @click="open = !open"
                            @click.away="open = false"
                            class="w-full px-4 py-2.5 text-left border border-gray-300 rounded-lg hover:border-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white flex items-center justify-between"
                        >
                            <span class="text-gray-700">
                                @if(count($specializations) > 0)
                                    <span class="font-medium text-primary-700">{{ count($specializations) }} Practice Area(s)</span>
                                @else
                                    Practice Area
                                @endif
                            </span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div 
                            x-show="open"
                            x-transition
                            class="absolute z-50 mt-2 w-full md:w-96 bg-white rounded-lg shadow-xl border border-gray-200 max-h-96 overflow-y-auto"
                        >
                            <!-- Search Box -->
                            <div class="p-3 border-b border-gray-200 sticky top-0 bg-white">
                                <input 
                                    type="text" 
                                    wire:model.live.debounce.300ms="specializationSearch"
                                    placeholder="Search practice areas..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                                >
                            </div>

                            <!-- Specializations List -->
                            <div class="p-2">
                                @if($allSpecializations->count() > 0)
                                    @foreach($allSpecializations as $spec)
                                        @if($spec->is_parent)
                                            <div class="mb-2">
                                                <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer {{ in_array($spec->slug, $specializations) ? 'bg-primary-50' : '' }}">
                                                    <input 
                                                        type="checkbox" 
                                                        wire:click="toggleSpecialization('{{ $spec->slug }}')"
                                                        {{ in_array($spec->slug, $specializations) ? 'checked' : '' }}
                                                        class="w-4 h-4 text-primary-600 focus:ring-primary-500 rounded">
                                                    <span class="ml-3 text-sm font-semibold text-gray-900">{{ $spec->name }}</span>
                                                </label>
                                                
                                                @if($spec->children->count() > 0)
                                                    @foreach($spec->children as $child)
                                                        <label class="flex items-center p-2 pl-8 rounded-lg hover:bg-gray-50 cursor-pointer {{ in_array($child->slug, $specializations) ? 'bg-primary-50' : '' }}">
                                                            <input 
                                                                type="checkbox" 
                                                                wire:click="toggleSpecialization('{{ $child->slug }}')"
                                                                {{ in_array($child->slug, $specializations) ? 'checked' : '' }}
                                                                class="w-4 h-4 text-primary-600 focus:ring-primary-500 rounded">
                                                            <span class="ml-3 text-sm text-gray-700">{{ $child->name }}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="text-center py-8 text-gray-500">
                                        <p class="text-sm">No practice areas found</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location Dropdown -->
                <div class="w-full lg:w-56" x-data="{ open: false }">
                    <div class="relative">
                        <button 
                            @click="open = !open"
                            @click.away="open = false"
                            class="w-full px-4 py-2.5 text-left border border-gray-300 rounded-lg hover:border-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white flex items-center justify-between"
                        >
                            <span class="text-gray-700 truncate">
                                @if($location)
                                    <span class="font-medium text-primary-700">{{ $location }}</span>
                                @else
                                    Province
                                @endif
                            </span>
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div 
                            x-show="open"
                            x-transition
                            class="absolute z-50 mt-2 w-full md:w-80 bg-white rounded-lg shadow-xl border border-gray-200 max-h-96 overflow-y-auto"
                        >
                            <div class="p-2">
                                @foreach($provinces as $province)
                                    <button 
                                        type="button"
                                        wire:click="$set('location', '{{ $province }}')"
                                        @click="open = false"
                                        class="w-full text-left p-2 rounded-lg hover:bg-gray-50 text-sm text-gray-700 {{ $location === $province ? 'bg-primary-50 font-medium text-primary-700' : '' }}"
                                    >
                                        {{ $province }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Languages Dropdown -->
                <div class="w-full lg:w-64" x-data="{ open: false }">
                    <div class="relative">
                        <button 
                            @click="open = !open"
                            @click.away="open = false"
                            class="w-full px-4 py-2.5 text-left border border-gray-300 rounded-lg hover:border-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white flex items-center justify-between"
                        >
                            <span class="text-gray-700">
                                @if(count($languages) > 0)
                                    <span class="font-medium text-primary-700">{{ count($languages) }} Language(s)</span>
                                @else
                                    Languages
                                @endif
                            </span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div 
                            x-show="open"
                            x-transition
                            class="absolute z-50 mt-2 w-full bg-white rounded-lg shadow-xl border border-gray-200 max-h-80 overflow-y-auto"
                        >
                            <div class="p-2">
                                @foreach($availableLanguages as $lang)
                                    <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer {{ in_array($lang, $languages) ? 'bg-primary-50' : '' }}">
                                        <input 
                                            type="checkbox" 
                                            wire:click="toggleLanguage('{{ $lang }}')"
                                            {{ in_array($lang, $languages) ? 'checked' : '' }}
                                            class="w-4 h-4 text-primary-600 focus:ring-primary-500 rounded">
                                        <span class="ml-3 text-sm text-gray-700">{{ $lang }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clear Filters -->
                @if($search || !empty($specializations) || $location || !empty($languages))
                    <button 
                        wire:click="clearFilters" 
                        class="px-4 py-2.5 text-sm text-accent-600 hover:text-accent-700 font-medium whitespace-nowrap"
                    >
                        Clear all
                    </button>
                @endif
            </div>

            <!-- Active Filters Tags -->
            @if($search || !empty($specializations) || $location || !empty($languages))
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex flex-wrap gap-2">
                        @if($search)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm">
                                Search: "{{ $search }}"
                                <button wire:click="$set('search', '')" class="hover:text-primary-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </span>
                        @endif
                        @if(!empty($specializations))
                            @foreach($specializations as $specSlug)
                                @php
                                    $selectedSpec = \App\Models\Specialization::where('slug', $specSlug)->first();
                                @endphp
                                @if($selectedSpec)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm">
                                        {{ $selectedSpec->name }}
                                        <button wire:click="toggleSpecialization('{{ $specSlug }}')" class="hover:text-primary-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            @endforeach
                        @endif
                        @if($location)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm">
                                Province: {{ $location }}
                                <button wire:click="$set('location', '')" class="hover:text-primary-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </span>
                        @endif
                        @if(!empty($languages))
                            @foreach($languages as $lang)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm">
                                    {{ $lang }}
                                    <button wire:click="toggleLanguage('{{ $lang }}')" class="hover:text-primary-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </span>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Toolbar -->
        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 mb-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="text-sm text-gray-600">
                    <span class="font-bold text-gray-900 text-base md:text-lg">{{ $lawyers->total() }}</span> lawyers found
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 md:gap-4 w-full sm:w-auto">
                    <!-- Sort -->
                    <select wire:model.live="sortBy" class="w-full sm:w-48 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="rating">Highest Rated</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="experience">Most Experienced</option>
                        <option value="reviews">Most Reviews</option>
                    </select>

                    <!-- View Mode -->
                    <div class="flex gap-2 border border-gray-200 rounded-lg p-1 bg-gray-50 self-center">
                        <button 
                            wire:click="$set('viewMode', 'grid')"
                            class="p-2 rounded-md transition {{ $viewMode === 'grid' ? 'bg-primary-700 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}"
                            aria-label="Grid view"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                        <button 
                            wire:click="$set('viewMode', 'list')"
                            class="p-2 rounded-md transition {{ $viewMode === 'list' ? 'bg-primary-700 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}"
                            aria-label="List view"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lawyers Grid/List -->
        @if($lawyers->count() > 0)
            <div class="{{ $viewMode === 'grid' ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6' : 'space-y-4' }}">
                @foreach($lawyers as $lawyer)
                    <livewire:components.lawyer-card :lawyer="$lawyer" :viewMode="$viewMode" :key="$lawyer->id" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $lawyers->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No lawyers found</h3>
                <p class="text-gray-600 mb-4">Try adjusting your filters or search criteria</p>
                <button wire:click="clearFilters" class="px-6 py-2.5 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition">
                    Clear Filters
                </button>
            </div>
        @endif
    </div>
</div>

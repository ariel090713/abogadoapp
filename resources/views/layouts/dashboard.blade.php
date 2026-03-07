<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name', 'AbogadoMo') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Pusher -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Initialize Pusher
        window.Pusher = Pusher;
        window.pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
            encrypted: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });

        // Global notification handler
        @auth
        const userChannel = window.pusher.subscribe('private-user.{{ auth()->id() }}');
        
        userChannel.bind('notification', function(data) {
            console.log('Notification received:', data);
            
            // Show toast notification
            if (window.showToast) {
                window.showToast(data.type, data.data.message || 'New notification');
            }
            
            // Dispatch Livewire event if needed
            if (data.type === 'consultation.update') {
                Livewire.dispatch('consultation-updated', data.data);
            }
        });
        @endauth
    </script>
    
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: false }">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-cloak
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
             style="display: none;">
        </div>

        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-primary-900 to-primary-800 border-r border-primary-700 fixed h-full overflow-y-auto z-50 transform transition-transform duration-300 lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <!-- Logo -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-primary-700">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="https://lawyerstorage-public.s3.ap-southeast-2.amazonaws.com/abogadomo-logo.png" alt="AbogadoMo Logo" class="w-8 h-8 rounded-lg shadow-lg">
                    <span class="text-xl font-bold text-white">AbogadoMo</span>
                </a>
                
                <!-- Close button (mobile only) -->
                <button @click="sidebarOpen = false" class="lg:hidden p-2 text-gray-300 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-1 pb-4">
                {{ $sidebar ?? '' }}
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64 w-full">
            <!-- Top Bar -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                </div>
                
                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- View My Profile (Lawyers only) -->
                    @if(auth()->user()->role === 'lawyer' && auth()->user()->lawyerProfile && auth()->user()->lawyerProfile->username)
                        <a href="{{ route('lawyers.show', auth()->user()->lawyerProfile->username) }}" 
                           target="_blank"
                           class="p-2 text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition"
                           title="View My Public Profile">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                    @endif

                    <!-- Notification Center -->
                    @livewire('notification-center')

                    <!-- User Role Badge (hidden on mobile) -->
                    <span class="hidden md:inline-block px-3 py-1 bg-primary-100 text-primary-700 text-sm font-medium rounded-full">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>

                    <!-- User Info & Logout -->
                    <div class="flex items-center gap-2 pl-2 sm:pl-3 border-l border-gray-200">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ auth()->user()->profile_photo }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl object-cover">
                        @else
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-sm">
                                {{ auth()->user()->initials() }}
                            </div>
                        @endif
                        <div class="hidden lg:flex flex-col">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="min-h-[calc(100vh-4rem)]">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>

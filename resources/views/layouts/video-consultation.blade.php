<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Video Consultation' }} - {{ config('app.name', 'AbogadoMo') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Pusher -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-900">
    <!-- Full screen video consultation -->
    <div class="w-full h-screen overflow-hidden">
        {{ $slot }}
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>

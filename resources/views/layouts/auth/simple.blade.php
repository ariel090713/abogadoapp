<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gradient-to-b from-gray-50 to-white antialiased">
        <!-- Background Pattern -->
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <x-placeholder-pattern />
        </div>

        <div class="flex min-h-screen flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-3 mb-8" wire:navigate>
                    <img src="https://lawyerstorage-public.s3.ap-southeast-2.amazonaws.com/abogadomo-logo.png" alt="AbogadoMo Logo" class="w-16 h-16 rounded-lg shadow-lg">
                    <span class="text-2xl font-bold text-gray-900">{{ config('app.name', 'AbogadoMo') }}</span>
                </a>

                <!-- Content Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>

@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 px-6 py-3 font-semibold rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2';
    
    $variantClasses = match($variant) {
        'primary' => 'bg-[#1E3A8A] text-white hover:bg-[#1E40AF] focus:ring-[#1E3A8A]',
        'secondary' => 'bg-white border-2 border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-gray-500',
        'danger' => 'bg-[#B91C1C] text-white hover:bg-[#991B1B] focus:ring-[#B91C1C]',
        'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100 focus:ring-gray-500',
        default => 'bg-[#1E3A8A] text-white hover:bg-[#1E40AF] focus:ring-[#1E3A8A]',
    };
    
    $classes = $baseClasses . ' ' . $variantClasses;
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

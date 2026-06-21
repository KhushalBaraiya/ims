@props([
    'size' => 'md', // sm, md
    'color' => 'blue' // blue, white
])

@php
    $sizeClass = match($size) {
        'sm' => 'h-4 w-4 border-2',
        default => 'h-6 w-6 border-2',
    };
    
    $colorClass = match($color) {
        'white' => 'border-white/90 border-t-transparent',
        default => 'border-blue-600 border-t-transparent dark:border-blue-400',
    };
@endphp

<div {{ $attributes->merge(['class' => 'animate-spin rounded-full ' . $sizeClass . ' ' . $colorClass]) }} role="status">
    <span class="sr-only">Loading...</span>
</div>

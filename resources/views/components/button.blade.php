@props([
    'variant' => 'primary', // primary, success, warning, danger, secondary, outline
    'size' => 'md', // sm, md
    'href' => null,
    'type' => 'button'
])

@php
    $baseStyles = 'inline-flex items-center justify-center font-semibold rounded-lg shadow-sm transition-all focus:outline-none active:scale-[0.98] cursor-pointer whitespace-nowrap';
    
    $sizeStyles = match($size) {
        'sm' => 'px-3 py-1.5 text-[12px] gap-1.5',
        default => 'px-4 py-2 text-[13px] gap-2',
    };

    $variantStyles = match($variant) {
        'success' => 'bg-emerald-600 hover:bg-emerald-500 text-white dark:bg-emerald-700 dark:hover:bg-emerald-600',
        'warning' => 'bg-amber-500 hover:bg-amber-400 text-white dark:bg-amber-600 dark:hover:bg-amber-500',
        'danger' => 'bg-rose-600 hover:bg-rose-500 text-white dark:bg-rose-700 dark:hover:bg-rose-600',
        'secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:border-slate-700',
        'outline' => 'bg-transparent border border-slate-300 text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800/50',
        default => 'bg-gradient-to-r from-blue-600 to-violet-600 hover:from-blue-500 hover:to-violet-500 text-white',
    };

    $classes = "{$baseStyles} {$sizeStyles} {$variantStyles}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

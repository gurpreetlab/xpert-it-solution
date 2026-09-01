@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $baseClass = "inline-flex items-center justify-center font-bold rounded-xl transition cursor-pointer shadow-2xs focus:outline-hidden disabled:opacity-50 disabled:cursor-not-allowed";

    $variants = [
        'primary' => 'bg-primary hover:bg-primary-hover text-white',
        'secondary' => 'bg-surface-muted hover:bg-zinc-200 text-zinc-900 border border-border',
        'dark' => 'bg-zinc-900 hover:bg-zinc-800 text-white',
        'danger' => 'bg-rose-600 hover:bg-rose-700 text-white',
        'ghost' => 'bg-transparent hover:bg-surface-muted text-zinc-700',
    ];

    $sizes = [
        'xs' => 'px-2.5 py-1 text-[11px]',
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-xs sm:text-sm',
        'lg' => 'px-5 py-2.5 text-sm sm:text-base',
    ];

    $classes = $baseClass . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($attributes->has('href'))
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

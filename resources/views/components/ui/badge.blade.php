@props([
    'variant' => 'neutral',
])

@php
    $variants = [
        'neutral' => 'bg-surface-muted text-zinc-700 border-border',
        'primary' => 'bg-primary/10 text-primary border-primary/20',
        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'warning' => 'bg-amber-50 text-amber-700 border-amber-200',
        'danger' => 'bg-rose-50 text-rose-700 border-rose-200',
    ];

    $classes = "inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[10px] font-bold border shadow-2xs " . ($variants[$variant] ?? $variants['neutral']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>

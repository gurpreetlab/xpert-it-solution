@props([
    'label' => null,
    'error' => null,
])

<div class="space-y-1">
    @if($label)
        <label class="block text-xs font-bold text-zinc-700">
            {{ $label }}
        </label>
    @endif

    <select
        {{ $attributes->merge([
            'class' => 'w-full bg-surface border border-border rounded-xl px-3 py-2 text-xs sm:text-sm text-zinc-900 focus:ring-2 focus:ring-primary focus:border-primary focus:outline-hidden transition shadow-2xs cursor-pointer'
        ]) }}>
        {{ $slot }}
    </select>

    @if($error)
        <p class="text-[11px] font-semibold text-rose-600">{{ $error }}</p>
    @endif
</div>

@props([
    'label' => null,
    'error' => null,
    'icon' => null,
])

<div class="space-y-1">
    @if($label)
        <label class="block text-xs font-bold text-zinc-700">
            {{ $label }}
        </label>
    @endif

    <div class="relative flex items-center">
        @if($icon)
            <flux:icon icon="{{ $icon }}" class="absolute left-3.5 size-4 text-zinc-400 pointer-events-none" />
        @endif

        <input
            {{ $attributes->merge([
                'class' => 'w-full bg-surface border border-border rounded-xl text-xs sm:text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-primary focus:border-primary focus:outline-hidden transition shadow-2xs ' . ($icon ? 'pl-10 pr-4 py-2.5' : 'px-4 py-2.5')
            ]) }}
        />
    </div>

    @if($error)
        <p class="text-[11px] font-semibold text-rose-600">{{ $error }}</p>
    @endif
</div>

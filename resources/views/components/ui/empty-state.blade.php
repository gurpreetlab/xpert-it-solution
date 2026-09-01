@props([
    'title' => 'No items found',
    'description' => 'Try adjusting your search or filters.',
    'icon' => 'inbox',
])

<div class="flex flex-col items-center justify-center p-8 sm:p-12 text-center rounded-2xl border border-dashed border-border bg-surface shadow-2xs space-y-3">
    <div class="size-12 rounded-2xl bg-surface-muted flex items-center justify-center text-zinc-400">
        <flux:icon icon="{{ $icon }}" class="size-6 text-primary/60" />
    </div>
    <div class="space-y-1">
        <h3 class="text-sm font-bold text-zinc-900">{{ $title }}</h3>
        <p class="text-xs text-zinc-500 max-w-sm leading-relaxed">{{ $description }}</p>
    </div>
    @if($slot->isNotEmpty())
        <div class="pt-2">
            {{ $slot }}
        </div>
    @endif
</div>

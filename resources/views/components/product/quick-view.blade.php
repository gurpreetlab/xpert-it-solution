@props(['product' => null])

@if($product)
<div
    x-data="{ open: true }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false"
            class="fixed inset-0 transition-opacity bg-zinc-900/60 backdrop-blur-xs"
            aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Content -->
        <div
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-surface rounded-2xl shadow-xl border border-border">

            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-primary">{{ $product->brand?->name ?? 'IT Product' }}</span>
                <button @click="open = false" type="button" class="text-zinc-400 hover:text-zinc-600 p-1 rounded-lg">
                    <flux:icon icon="x-mark" class="size-5" />
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Image -->
                <div class="aspect-square bg-surface-muted rounded-xl border border-border p-4 flex items-center justify-center">
                    @php
                        $img = $product->primaryImage?->path ?? ($product->images->first()?->path ?? null);
                        $imgUrl = $img ? (str_starts_with($img, 'http') ? $img : asset('storage/' . $img)) : null;
                    @endphp
                    @if($imgUrl)
                        <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="size-full object-contain">
                    @else
                        <flux:icon icon="cpu-chip" class="size-16 text-zinc-300" />
                    @endif
                </div>

                <!-- Info -->
                <div class="space-y-4 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-zinc-900 leading-snug">{{ $product->name }}</h3>
                        <p class="text-xs text-zinc-500 mt-2 leading-relaxed line-clamp-3">
                            {{ $product->short_description ?? 'High performance IT hardware solution designed for reliability and speed.' }}
                        </p>

                        <div class="mt-4 flex items-baseline gap-2">
                            <span class="text-2xl font-extrabold text-zinc-950">₹{{ number_format($product->sale_price) }}</span>
                            @if(($product->mrp ?? 0) > $product->sale_price)
                                <span class="text-sm text-zinc-400 line-through">₹{{ number_format($product->mrp) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-2 pt-4 border-t border-border">
                        <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary-hover transition">
                            <span>View Full Specifications</span>
                            <flux:icon icon="arrow-right" class="size-4" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

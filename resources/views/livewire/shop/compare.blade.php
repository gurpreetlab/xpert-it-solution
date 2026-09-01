<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 mb-2">
        <a href="{{ route('home') }}" class="hover:text-primary transition" wire:navigate>Home</a>
        <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
        <span class="text-zinc-900 font-semibold">Compare Products</span>
    </nav>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900">Side-by-Side Product Comparison</h1>
            <p class="text-xs sm:text-sm text-zinc-500 mt-1">Compare technical specifications, prices, and features across IT hardware.</p>
        </div>

        @if($this->comparedProducts->isNotEmpty())
            <button
                type="button"
                wire:click="clearAll"
                class="px-3.5 py-2 rounded-xl border border-border text-xs font-semibold text-rose-600 hover:bg-rose-50 transition cursor-pointer self-start sm:self-auto flex items-center gap-1.5">
                <flux:icon icon="trash" class="size-4" />
                <span>Clear All</span>
            </button>
        @endif
    </div>

    @if($this->comparedProducts->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 px-4 text-center bg-surface rounded-2xl border border-border shadow-2xs">
            <div class="size-14 rounded-full bg-surface-muted flex items-center justify-center text-zinc-400 mb-3">
                <flux:icon icon="scale" class="size-7 text-primary" />
            </div>
            <h2 class="text-base font-bold text-zinc-900">No products selected for comparison</h2>
            <p class="text-xs text-zinc-500 mt-1 max-w-sm">Click the comparison icon <flux:icon icon="scale" class="size-3.5 inline text-primary" /> on any product card to compare side-by-side.</p>
            <div class="mt-5">
                <a href="{{ route('shop.products') }}" wire:navigate class="px-4 py-2.5 rounded-xl bg-primary text-white font-semibold text-xs hover:bg-primary-hover transition inline-block">
                    Explore IT Products
                </a>
            </div>
        </div>
    @else
        <div class="bg-surface rounded-2xl border border-border shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="border-b border-border bg-surface-muted/50 text-zinc-500 font-bold">
                            <th class="p-4 w-1/4 min-w-[150px] uppercase text-[10px] tracking-wider">Specifications</th>
                            @foreach($this->comparedProducts as $product)
                                <th wire:key="compare-header-{{ $product->id }}" class="p-4 w-1/4 min-w-[220px] align-top">
                                    <div class="flex flex-col space-y-2 relative">
                                        <button
                                            type="button"
                                            wire:click="removeProduct({{ $product->id }})"
                                            class="absolute -top-1 -right-1 p-1 text-zinc-400 hover:text-rose-600 transition cursor-pointer"
                                            title="Remove">
                                            <flux:icon icon="x-mark" class="size-4" />
                                        </button>

                                        <div class="aspect-video w-full rounded-xl bg-surface flex items-center justify-center overflow-hidden border border-border p-2">
                                            @php
                                                $img = $product->primaryImage?->path ?? ($product->images->first()?->path ?? null);
                                                $imgUrl = $img ? (str_starts_with($img, 'http') ? $img : asset('storage/' . $img)) : null;
                                            @endphp
                                            @if($imgUrl)
                                                <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="h-16 object-contain" />
                                            @else
                                                <flux:icon icon="cpu-chip" class="size-8 text-zinc-300" />
                                            @endif
                                        </div>

                                        <div>
                                            <span class="text-[10px] font-bold text-primary uppercase tracking-wider block">{{ $product->brand?->name }}</span>
                                            <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate class="font-bold text-zinc-900 hover:text-primary line-clamp-2 leading-tight">
                                                {{ $product->name }}
                                            </a>
                                            <div class="text-base font-extrabold text-zinc-950 mt-1">₹{{ number_format($product->sale_price) }}</div>
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                            @for($i = count($this->comparedProducts); $i < 3; $i++)
                                <th wire:key="compare-empty-{{ $i }}" class="p-4 w-1/4 bg-surface-muted/20">
                                    <div class="flex flex-col items-center justify-center py-10 border-2 border-dashed border-border rounded-xl text-center">
                                        <flux:icon icon="plus" class="size-5 text-zinc-300" />
                                        <a href="{{ route('shop.products') }}" wire:navigate class="text-xs text-primary font-semibold hover:underline mt-1">Add Product</a>
                                    </div>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <tr>
                            <td class="p-4 font-bold text-zinc-400 text-[10px] uppercase tracking-wider bg-surface-muted/20">Category</td>
                            @foreach($this->comparedProducts as $product)
                                <td class="p-4 font-semibold text-zinc-900">{{ $product->category?->name ?? '-' }}</td>
                            @endforeach
                            @for($i = count($this->comparedProducts); $i < 3; $i++) <td class="p-4 bg-surface-muted/20"></td> @endfor
                        </tr>

                        <tr>
                            <td class="p-4 font-bold text-zinc-400 text-[10px] uppercase tracking-wider bg-surface-muted/20">Availability</td>
                            @foreach($this->comparedProducts as $product)
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold {{ ($product->stock ?? 1) > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ ($product->stock ?? 1) > 0 ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </td>
                            @endforeach
                            @for($i = count($this->comparedProducts); $i < 3; $i++) <td class="p-4 bg-surface-muted/20"></td> @endfor
                        </tr>

                        <tr>
                            <td class="p-4 font-bold text-zinc-400 text-[10px] uppercase tracking-wider bg-surface-muted/20">Warranty</td>
                            @foreach($this->comparedProducts as $product)
                                <td class="p-4 text-zinc-700 font-medium">{{ $product->warranty ?? '3 Years Brand Warranty' }}</td>
                            @endforeach
                            @for($i = count($this->comparedProducts); $i < 3; $i++) <td class="p-4 bg-surface-muted/20"></td> @endfor
                        </tr>

                        <tr>
                            <td class="p-4 font-bold text-zinc-400 text-[10px] uppercase tracking-wider bg-surface-muted/20">Technical Summary</td>
                            @foreach($this->comparedProducts as $product)
                                <td class="p-4 text-xs text-zinc-600 leading-relaxed">{{ $product->short_description ?? 'High performance IT product.' }}</td>
                            @endforeach
                            @for($i = count($this->comparedProducts); $i < 3; $i++) <td class="p-4 bg-surface-muted/20"></td> @endfor
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</main>

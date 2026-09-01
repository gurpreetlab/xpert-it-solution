<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 min-h-[60vh]">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white sm:text-4xl">Product Comparison</h1>
            <p class="mt-2 text-sm text-zinc-500">Compare specifications, prices, and performance side-by-side to make informed decisions.</p>
        </div>
        @if($this->comparedProducts->isNotEmpty())
            <flux:button variant="outline" wire:click="clearAll" class="cursor-pointer text-rose-500 hover:text-rose-600 self-start sm:self-auto" icon="trash">
                Clear All
            </flux:button>
        @endif
    </div>

    @if($this->comparedProducts->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 px-4 text-center bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <div class="size-16 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-400 dark:text-zinc-600 mb-4">
                <flux:icon icon="scale" class="size-8" />
            </div>
            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">No products to compare</h2>
            <p class="text-sm text-zinc-500 mt-1 max-w-xs">Add products from our catalog to compare their specifications side-by-side.</p>
            <div class="mt-6">
                <flux:button href="{{ route('shop.products') }}" variant="primary" class="cursor-pointer" wire:navigate>
                    Browse Products
                </flux:button>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 text-zinc-500 font-semibold">
                            <th class="p-4 w-1/4">Product Specifications</th>
                            @foreach($this->comparedProducts as $product)
                                <th wire:key="header-{{ $product->id }}" class="p-4 w-1/4 min-w-[200px]">
                                    <div class="flex flex-col space-y-3 relative">
                                        <!-- Remove Button -->
                                        <button type="button" wire:click="removeProduct({{ $product->id }})" class="absolute -top-1 -right-1 text-zinc-400 hover:text-rose-500 transition cursor-pointer" title="Remove">
                                            <flux:icon icon="x-mark" class="size-4" />
                                        </button>

                                        <!-- Image -->
                                        <div class="relative aspect-video w-full rounded-xl bg-zinc-50 dark:bg-zinc-950 flex items-center justify-center overflow-hidden border border-zinc-100 dark:border-zinc-800">
                                            @php
                                                $img = $product->primaryImage?->path ?? ($product->images->first()?->path ?? null);
                                                $imgUrl = $img ? (str_starts_with($img, 'http') ? $img : asset('storage/' . $img)) : null;
                                            @endphp
                                            @if($imgUrl)
                                                <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="h-20 object-contain p-2" />
                                            @else
                                                <flux:icon icon="cube" class="size-6 text-zinc-300 dark:text-zinc-700" />
                                            @endif
                                        </div>

                                        <!-- Details -->
                                        <div>
                                            <flux:badge size="sm" color="blue" class="mb-1">{{ $product->brand?->name }}</flux:badge>
                                            <h3 class="text-sm font-bold text-zinc-900 dark:text-white line-clamp-2 leading-snug">{{ $product->name }}</h3>
                                            <div class="text-base font-extrabold text-zinc-950 dark:text-white mt-1">₹{{ number_format($product->sale_price, 2) }}</div>
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                            @for($i = count($this->comparedProducts); $i < 3; $i++)
                                <th wire:key="placeholder-{{ $i }}" class="p-4 w-1/4 bg-zinc-50/20 dark:bg-zinc-950/10">
                                    <div class="flex flex-col items-center justify-center py-10 border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-2xl text-center">
                                        <flux:icon icon="plus" class="size-5 text-zinc-300" />
                                        <a href="{{ route('shop.products') }}" wire:navigate class="text-xs text-blue-500 hover:underline mt-1">Add product</a>
                                    </div>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        <!-- Category Row -->
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10 transition">
                            <td class="p-4 font-semibold text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-wider">Category</td>
                            @foreach($this->comparedProducts as $product)
                                <td class="p-4 text-zinc-900 dark:text-white font-medium">{{ $product->category?->name ?? '-' }}</td>
                            @endforeach
                            @for($i = count($this->comparedProducts); $i < 3; $i++)
                                <td class="p-4 bg-zinc-50/20 dark:bg-zinc-950/10"></td>
                            @endfor
                        </tr>

                        <!-- Brand Row -->
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10 transition">
                            <td class="p-4 font-semibold text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-wider">Brand</td>
                            @foreach($this->comparedProducts as $product)
                                <td class="p-4 text-zinc-900 dark:text-white font-medium">{{ $product->brand?->name ?? '-' }}</td>
                            @endforeach
                            @for($i = count($this->comparedProducts); $i < 3; $i++)
                                <td class="p-4 bg-zinc-50/20 dark:bg-zinc-950/10"></td>
                            @endfor
                        </tr>

                        <!-- Availability Row -->
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10 transition">
                            <td class="p-4 font-semibold text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-wider">Availability</td>
                            @foreach($this->comparedProducts as $product)
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold {{ $product->stock > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        <span class="size-1.5 rounded-full {{ $product->stock > 0 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ' units)' : 'Out of Stock' }}
                                    </span>
                                </td>
                            @endforeach
                            @for($i = count($this->comparedProducts); $i < 3; $i++)
                                <td class="p-4 bg-zinc-50/20 dark:bg-zinc-950/10"></td>
                            @endfor
                        </tr>

                        <!-- Warranty Row -->
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10 transition">
                            <td class="p-4 font-semibold text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-wider">Warranty</td>
                            @foreach($this->comparedProducts as $product)
                                <td class="p-4 text-zinc-700 dark:text-zinc-300">{{ $product->warranty ?? 'Standard Warranty' }}</td>
                            @endforeach
                            @for($i = count($this->comparedProducts); $i < 3; $i++)
                                <td class="p-4 bg-zinc-50/20 dark:bg-zinc-950/10"></td>
                            @endfor
                        </tr>

                        <!-- Weight Row -->
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10 transition">
                            <td class="p-4 font-semibold text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-wider">Weight</td>
                            @foreach($this->comparedProducts as $product)
                                <td class="p-4 text-zinc-700 dark:text-zinc-300">{{ $product->weight ? $product->weight . ' kg' : '-' }}</td>
                            @endforeach
                            @for($i = count($this->comparedProducts); $i < 3; $i++)
                                <td class="p-4 bg-zinc-50/20 dark:bg-zinc-950/10"></td>
                            @endfor
                        </tr>

                        <!-- Description Row -->
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10 transition">
                            <td class="p-4 font-semibold text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-wider">Summary</td>
                            @foreach($this->comparedProducts as $product)
                                <td class="p-4 text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed min-w-[200px]">{{ $product->short_description ?? 'High performance device.' }}</td>
                            @endforeach
                            @for($i = count($this->comparedProducts); $i < 3; $i++)
                                <td class="p-4 bg-zinc-50/20 dark:bg-zinc-950/10"></td>
                            @endfor
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 min-h-[60vh]">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white sm:text-4xl">My Wishlist</h1>
        <p class="mt-2 text-sm text-zinc-500">Keep track of the products you are interested in.</p>
    </div>

    @if($this->wishlistItems->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 px-4 text-center bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <div class="size-16 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-400 dark:text-zinc-600 mb-4">
                <flux:icon icon="heart" class="size-8" />
            </div>
            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Your wishlist is empty</h2>
            <p class="text-sm text-zinc-500 mt-1 max-w-xs">Explore our catalog and add items you like to your wishlist.</p>
            <div class="mt-6">
                <flux:button href="{{ route('shop.products') }}" variant="primary" class="cursor-pointer" wire:navigate>
                    Browse Products
                </flux:button>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            @foreach($this->wishlistItems as $product)
                @php
                    $img = $product->primaryImage?->path ?? ($product->images->first()?->path ?? null);
                    $imgUrl = null;
                    if ($img) {
                        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                            $imgUrl = $img;
                        } elseif (str_starts_with($img, 'storage/')) {
                            $imgUrl = asset($img);
                        } else {
                            $imgUrl = asset('storage/' . $img);
                        }
                    }
                    $categoryIcon = \App\Support\CategoryVisuals::icon($product->category?->name);
                    [$gradientFrom, $gradientTo] = \App\Support\CategoryVisuals::gradient($product->category?->name, muted: true);
                @endphp

                <div wire:key="wishlist-item-{{ $product->id }}" class="flex flex-col bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
                    <!-- Image Section -->
                    <div class="relative aspect-video bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} flex items-center justify-center text-white overflow-hidden">
                        @if($imgUrl)
                            <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="size-full object-cover">
                        @else
                            <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_14px]"></div>
                            <flux:icon icon="{{ $categoryIcon }}" class="size-7 text-white" />
                        @endif

                        <button type="button" wire:click="removeFromWishlist({{ $product->id }})" class="absolute top-3 right-3 bg-white/80 hover:bg-white text-rose-500 hover:text-rose-600 p-1.5 rounded-full backdrop-blur-sm shadow-sm transition cursor-pointer" title="Remove from wishlist">
                            <flux:icon icon="trash" class="size-4" />
                        </button>
                    </div>

                    <!-- Content Section -->
                    <div class="flex-1 p-5 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-medium text-zinc-500">{{ $product->brand?->name }}</span>
                                <span class="font-semibold {{ $product->stock > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </div>
                            <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate>
                                <h3 class="text-sm font-bold text-zinc-900 dark:text-white line-clamp-1 hover:text-blue-600 transition-colors">
                                    {{ $product->name }}
                                </h3>
                            </a>
                            <p class="text-xs text-zinc-500 line-clamp-2 leading-relaxed">
                                {{ $product->short_description ?? 'High performance device.' }}
                            </p>
                        </div>

                        <!-- Action Section -->
                        <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-base font-extrabold text-zinc-950 dark:text-white">₹{{ number_format($product->sale_price, 2) }}</span>
                            </div>

                            <div class="flex gap-2">
                                <flux:button size="sm" variant="ghost" href="{{ route('shop.product.details', $product->slug) }}" wire:navigate class="cursor-pointer text-zinc-500">
                                    View
                                </flux:button>
                                <flux:button size="sm" variant="filled" wire:click="addToCart({{ $product->id }})" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white border-0" :disabled="$product->stock <= 0">
                                    Add to Cart
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

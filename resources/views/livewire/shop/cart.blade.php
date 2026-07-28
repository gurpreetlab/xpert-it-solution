<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition" wire:navigate>Home</a>
        <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
        <span class="text-zinc-900 dark:text-white font-semibold">Shopping Cart</span>
    </nav>

    <!-- Page Header -->
    <div class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Your Shopping Cart</h2>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
            {{ $this->cartItems->count() }} {{ Str::plural('item', $this->cartItems->count()) }} ready for checkout.
        </p>
    </div>

    @if($this->cartItems->isEmpty())

        <!-- Empty Cart State -->
        <div class="py-20 text-center rounded-3xl border border-dashed border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
            <div class="inline-flex items-center justify-center size-16 rounded-2xl bg-zinc-100 dark:bg-zinc-800 mb-4">
                <flux:icon icon="shopping-cart" class="size-8 text-zinc-400" />
            </div>
            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Your cart is empty</h3>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 max-w-sm mx-auto">
                Looks like you haven't added any hardware yet. Browse the catalog to find networking, CCTV, storage and more.
            </p>
            <flux:button href="{{ route('shop.products') }}" wire:navigate variant="filled" size="sm" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium">
                Start Shopping
            </flux:button>
        </div>

    @else

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Left: Cart Items -->
            <div class="lg:col-span-8 space-y-4">

                <div class="rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm divide-y divide-zinc-100 dark:divide-zinc-800 overflow-hidden">
                    @foreach($this->cartItems as $item)
                        @php
                            $product = $item->product;

                            $gradientFrom = 'from-zinc-800';
                            $gradientTo = 'to-zinc-950';
                            $categoryIcon = 'shopping-bag';

                            if ($product->category?->name === 'Networking') {
                                $gradientFrom = 'from-blue-900'; $gradientTo = 'to-zinc-950'; $categoryIcon = 'wifi';
                            } elseif ($product->category?->name === 'CCTV & Security') {
                                $gradientFrom = 'from-emerald-900'; $gradientTo = 'to-zinc-950'; $categoryIcon = 'video-camera';
                            } elseif ($product->category?->name === 'Storage') {
                                $gradientFrom = 'from-purple-900'; $gradientTo = 'to-zinc-950'; $categoryIcon = 'circle-stack';
                            } elseif ($product->category?->name === 'Computer Peripherals') {
                                $gradientFrom = 'from-amber-900'; $gradientTo = 'to-zinc-950'; $categoryIcon = 'computer-desktop';
                            } elseif ($product->category?->name === 'Power & Accessories') {
                                $gradientFrom = 'from-orange-900'; $gradientTo = 'to-zinc-950'; $categoryIcon = 'bolt';
                            } elseif ($product->category?->name === 'Printing') {
                                $gradientFrom = 'from-indigo-900'; $gradientTo = 'to-zinc-950'; $categoryIcon = 'printer';
                            }

                            $imgPath = $product->primaryImage?->path ?? $product->images->first()?->path ?? null;
                            $lineTotal = $product->sale_price * $item->quantity;
                        @endphp

                        <div wire:key="cart-item-{{ $item->id }}" class="flex flex-col sm:flex-row gap-4 p-5">

                            <!-- Thumbnail -->
                            <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate class="relative shrink-0 size-24 sm:size-28 rounded-2xl overflow-hidden border border-zinc-200 dark:border-zinc-800 flex items-center justify-center bg-white dark:bg-zinc-950">
                                @if($imgPath)
                                    <img src="{{ str_starts_with($imgPath, 'http') ? $imgPath : asset('storage/' . $imgPath) }}" alt="{{ $product->name }}" class="size-full object-contain p-2" />
                                @else
                                    <div class="absolute inset-0 bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} flex items-center justify-center">
                                        <flux:icon icon="{{ $categoryIcon }}" class="size-8 text-white/90" />
                                    </div>
                                @endif
                            </a>

                            <!-- Details -->
                            <div class="flex-1 flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="flex-1 space-y-1.5">
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="font-medium text-zinc-500 dark:text-zinc-400">{{ $product->brand?->name ?? 'Enterprise Hardware' }}</span>
                                        @if($product->stock <= 0)
                                            <span class="font-semibold text-rose-600 dark:text-rose-400">Out of Stock</span>
                                        @elseif($product->stock < $item->quantity)
                                            <span class="font-semibold text-amber-600 dark:text-amber-400">Only {{ $product->stock }} left</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate>
                                        <h3 class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition line-clamp-1">
                                            {{ $product->name }}
                                        </h3>
                                    </a>
                                    <span class="text-xs text-zinc-400 font-mono">SKU: {{ $product->sku ?? 'N/A' }}</span>

                                    <button type="button" wire:click="removeItem({{ $item->id }})" class="flex items-center gap-1 text-xs font-semibold text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition pt-1 cursor-pointer">
                                        <flux:icon icon="trash" class="size-3.5" />
                                        Remove
                                    </button>
                                </div>

                                <!-- Quantity Control -->
                                <div class="flex items-center border border-zinc-200 dark:border-zinc-800 rounded-xl bg-zinc-50 dark:bg-zinc-950 p-1 shadow-sm w-fit">
                                    <button type="button" wire:click="decrementQuantity({{ $item->id }})" class="size-7 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-bold transition cursor-pointer">
                                        -
                                    </button>
                                    <span class="w-10 text-center text-sm font-bold text-zinc-900 dark:text-white">{{ $item->quantity }}</span>
                                    <button type="button" wire:click="incrementQuantity({{ $item->id }})" class="size-7 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-bold transition cursor-pointer">
                                        +
                                    </button>
                                </div>

                                <!-- Line Pricing -->
                                <div class="flex flex-col sm:items-end w-full sm:w-28">
                                    @if($product->mrp > $product->sale_price)
                                        <span class="text-[11px] text-zinc-400 dark:text-zinc-500 line-through">₹{{ number_format($product->mrp * $item->quantity, 2) }}</span>
                                    @endif
                                    <span class="text-base font-extrabold text-zinc-950 dark:text-white">₹{{ number_format($lineTotal, 2) }}</span>
                                    <span class="text-[11px] text-zinc-400">₹{{ number_format($product->sale_price, 2) }} / unit</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between px-1">
                    <flux:button href="{{ route('shop.products') }}" wire:navigate variant="ghost" size="sm" icon="arrow-left" class="text-zinc-600 dark:text-zinc-400">
                        Continue Shopping
                    </flux:button>

                    <button type="button" wire:click="clearCart" wire:confirm="Remove all items from your cart?" class="flex items-center gap-1.5 text-xs font-semibold text-zinc-500 hover:text-rose-600 dark:hover:text-rose-400 transition cursor-pointer">
                        <flux:icon icon="trash" class="size-3.5" />
                        Clear Cart
                    </button>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-6">

                    <div class="rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-sm space-y-6">
                        <h3 class="text-lg font-bold tracking-tight text-zinc-900 dark:text-white flex items-center gap-2">
                            <flux:icon icon="receipt-percent" class="size-5 text-blue-600" />
                            Order Summary
                        </h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between text-zinc-600 dark:text-zinc-300">
                                <span>MRP</span>
                                <span class="font-semibold text-zinc-900 dark:text-white">₹{{ number_format($this->mrp, 2) }}</span>
                            </div>

                            @if($this->savings > 0)
                                <div class="flex items-center justify-between text-emerald-600 dark:text-emerald-400">
                                    <span>Savings</span>
                                    <span class="font-semibold">- ₹{{ number_format($this->savings, 2) }}</span>
                                </div>
                            @endif

                            <div class="flex items-center justify-between text-zinc-600 dark:text-zinc-300">
                                <span>Subtotal ({{ $this->cartItems->sum('quantity') }} items)</span>
                                <span class="font-semibold text-zinc-900 dark:text-white">₹{{ number_format($this->subtotal, 2) }}</span>
                            </div>

                            <div class="flex items-center justify-between text-zinc-600 dark:text-zinc-300">
                                <span>Shipping</span>
                                <span class="font-semibold text-emerald-600 dark:text-emerald-400">Free</span>
                            </div>
                            <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400 text-xs">
                                <span>Inclusive of applicable GST</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                            <span class="text-sm font-bold text-zinc-900 dark:text-white">Total</span>
                            <span class="text-2xl font-black tracking-tight text-zinc-950 dark:text-white">₹{{ number_format($this->subtotal, 2) }}</span>
                        </div>

                        <flux:button wire:click="checkout" icon="lock-closed" variant="filled" class="w-full bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium cursor-pointer">
                            Proceed to Checkout
                        </flux:button>

                        <p class="text-[11px] text-center text-zinc-400 leading-relaxed">
                            Secure checkout &middot; Encrypted payment processing
                        </p>
                    </div>

                    <!-- Trust Icons -->
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div class="p-3 rounded-2xl bg-zinc-100/70 dark:bg-zinc-900/70 border border-zinc-200/50 dark:border-zinc-800/50">
                            <flux:icon icon="shield-check" class="size-5 text-blue-600 dark:text-blue-400 mx-auto mb-1" />
                            <span class="block text-[11px] font-bold text-zinc-900 dark:text-white">Genuine</span>
                            <span class="text-[10px] text-zinc-500">Brand Warranty</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-zinc-100/70 dark:bg-zinc-900/70 border border-zinc-200/50 dark:border-zinc-800/50">
                            <flux:icon icon="bolt" class="size-5 text-emerald-600 dark:text-emerald-400 mx-auto mb-1" />
                            <span class="block text-[11px] font-bold text-zinc-900 dark:text-white">Fast Dispatch</span>
                            <span class="text-[10px] text-zinc-500">Express Freight</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-zinc-100/70 dark:bg-zinc-900/70 border border-zinc-200/50 dark:border-zinc-800/50">
                            <flux:icon icon="circle-stack" class="size-5 text-purple-600 dark:text-purple-400 mx-auto mb-1" />
                            <span class="block text-[11px] font-bold text-zinc-900 dark:text-white">Bulk Pricing</span>
                            <span class="text-[10px] text-zinc-500">Corporate Quotes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif

</main>

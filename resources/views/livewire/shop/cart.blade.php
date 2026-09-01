<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 mb-2">
        <a href="{{ route('home') }}" class="hover:text-primary transition" wire:navigate>Home</a>
        <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
        <span class="text-zinc-900 font-semibold">Shopping Cart</span>
    </nav>

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900">Your Shopping Cart</h1>
            <p class="text-xs sm:text-sm text-zinc-500 mt-1">
                {{ $this->cartItems?->count() ?? 0 }} {{ Str::plural('item', $this->cartItems?->count() ?? 0) }} in cart
            </p>
        </div>
    </div>

    @if($this->cartItems?->isEmpty())

        <!-- Empty Cart State -->
        <div class="py-16 text-center rounded-2xl border border-dashed border-border bg-surface shadow-2xs">
            <div class="inline-flex items-center justify-center size-16 rounded-2xl bg-surface-muted mb-3">
                <flux:icon icon="shopping-cart" class="size-8 text-primary" />
            </div>
            <h2 class="text-lg font-bold text-zinc-900">Your cart is empty</h2>
            <p class="text-xs text-zinc-500 mt-1 max-w-sm mx-auto">
                Explore our IT catalog to find routers, SSDs, IP cameras, peripherals and power accessories.
            </p>
            <a href="{{ route('shop.products') }}" wire:navigate class="mt-6 inline-block px-5 py-2.5 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary-hover transition shadow-2xs">
                Explore Hardware
            </a>
        </div>

    @else

        <!-- Free Delivery Progress Bar -->
        @php
            $threshold = 2000;
            $currentSubtotal = $this->totals->subtotal;
            $progressPercent = min(100, round(($currentSubtotal / $threshold) * 100));
            $remaining = max(0, $threshold - $currentSubtotal);
        @endphp
        <div class="p-4 rounded-xl border border-border bg-surface space-y-2">
            <div class="flex items-center justify-between text-xs font-semibold">
                <span class="text-zinc-700 flex items-center gap-1.5">
                    <flux:icon icon="truck" class="size-4 text-primary" />
                    @if($remaining > 0)
                        <span>Add ₹{{ number_format($remaining) }} more to qualify for <strong>Free Express Delivery</strong></span>
                    @else
                        <span class="text-emerald-600 font-bold">✓ Congratulations! You qualify for Free Express Delivery</span>
                    @endif
                </span>
                <span class="text-zinc-400 font-mono text-[11px]">{{ $progressPercent }}%</span>
            </div>
            <div class="w-full bg-surface-muted rounded-full h-2 overflow-hidden border border-border">
                <div class="bg-primary h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercent }}%;"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Left: Cart Items -->
            <div class="lg:col-span-8 space-y-4">

                <div class="rounded-2xl border border-border bg-surface shadow-2xs divide-y divide-border overflow-hidden">
                    @foreach($this->cartItems as $item)
                        @php
                            $product = $item->product;
                            $imgPath = $product->primaryImage?->path ?? $product->images->first()?->path ?? null;
                            $lineTotal = $product->sale_price * $item->quantity;
                        @endphp

                        <div wire:key="cart-item-{{ $item->id }}" class="flex flex-col sm:flex-row gap-4 p-4">

                            <!-- Thumbnail -->
                            <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate class="shrink-0 size-20 sm:size-24 rounded-xl overflow-hidden border border-border flex items-center justify-center bg-surface-muted p-2">
                                @if($imgPath)
                                    <img src="{{ str_starts_with($imgPath, 'http') ? $imgPath : asset('storage/' . $imgPath) }}" alt="{{ $product->name }}" class="size-full object-contain" />
                                @else
                                    <flux:icon icon="cpu-chip" class="size-8 text-primary" />
                                @endif
                            </a>

                            <!-- Details -->
                            <div class="flex-1 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-zinc-400">
                                        <span>{{ $product->brand?->name ?? 'IT Hardware' }}</span>
                                    </div>
                                    <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate>
                                        <h3 class="text-xs sm:text-sm font-bold text-zinc-900 hover:text-primary transition line-clamp-1">
                                            {{ $product->name }}
                                        </h3>
                                    </a>
                                    <span class="text-[10px] text-zinc-400 font-mono block">SKU: {{ $product->sku ?? 'N/A' }}</span>

                                    <button
                                        type="button"
                                        wire:click="removeItem({{ $item->id }})"
                                        class="flex items-center gap-1 text-[11px] font-semibold text-rose-600 hover:underline transition pt-1 cursor-pointer">
                                        <flux:icon icon="trash" class="size-3" />
                                        Remove
                                    </button>
                                </div>

                                <!-- Quantity Selector -->
                                <div class="flex items-center border border-border rounded-lg bg-surface p-1 shadow-2xs w-fit">
                                    <button
                                        type="button"
                                        wire:click="decrementQuantity({{ $item->id }})"
                                        class="size-7 flex items-center justify-center rounded-md hover:bg-surface-muted text-zinc-700 font-bold transition cursor-pointer">
                                        -
                                    </button>
                                    <span class="w-8 text-center text-xs font-bold text-zinc-900">{{ $item->quantity }}</span>
                                    <button
                                        type="button"
                                        wire:click="incrementQuantity({{ $item->id }})"
                                        class="size-7 flex items-center justify-center rounded-md hover:bg-surface-muted text-zinc-700 font-bold transition cursor-pointer">
                                        +
                                    </button>
                                </div>

                                <!-- Line Price -->
                                <div class="text-right">
                                    <span class="text-sm font-extrabold text-zinc-950 block">₹{{ number_format($lineTotal) }}</span>
                                    <span class="text-[10px] text-zinc-400">₹{{ number_format($product->sale_price) }} / unit</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between px-1">
                    <a href="{{ route('shop.products') }}" wire:navigate class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                        ← Continue Shopping
                    </a>

                    <button
                        type="button"
                        wire:click="clearCart"
                        wire:confirm="Clear all items from your cart?"
                        class="text-xs font-semibold text-zinc-500 hover:text-rose-600 transition cursor-pointer flex items-center gap-1">
                        <flux:icon icon="trash" class="size-3.5" />
                        Clear Cart
                    </button>
                </div>
            </div>

            <!-- Right: Summary -->
            <div class="lg:col-span-4">
                <div class="sticky top-20 space-y-4">

                    <div class="rounded-2xl border border-border bg-surface p-6 shadow-2xs space-y-4">
                        <h2 class="text-base font-bold text-zinc-900">Order Summary</h2>

                        <div class="space-y-2.5 text-xs text-zinc-600">
                            <div class="flex items-center justify-between">
                                <span>Subtotal ({{ $this->cartItems->sum('quantity') }} items)</span>
                                <span class="font-bold text-zinc-900">₹{{ number_format($this->totals->subtotal) }}</span>
                            </div>

                            @if($this->totals->savings > 0)
                                <div class="flex items-center justify-between text-emerald-600">
                                    <span>Total Discount Savings</span>
                                    <span class="font-bold">- ₹{{ number_format($this->totals->savings) }}</span>
                                </div>
                            @endif

                            <div class="flex items-center justify-between">
                                <span>Shipping Estimate</span>
                                <span class="font-bold text-emerald-600">{{ $remaining === 0 ? 'FREE' : '₹99' }}</span>
                            </div>

                            <div class="flex items-center justify-between text-zinc-400 text-[10px]">
                                <span>Taxes</span>
                                <span>Inclusive of GST</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-border flex items-center justify-between">
                            <span class="text-sm font-bold text-zinc-900">Total Payable</span>
                            <span class="text-xl font-black text-zinc-950">₹{{ number_format($this->totals->subtotal + ($remaining === 0 ? 0 : 99)) }}</span>
                        </div>

                        <button
                            type="button"
                            wire:click="checkout"
                            class="w-full py-3 rounded-xl bg-primary hover:bg-primary-hover text-white font-bold text-xs transition cursor-pointer shadow-2xs flex items-center justify-center gap-2">
                            <flux:icon icon="lock-closed" class="size-4" />
                            <span>Proceed to Checkout</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    @endif

</main>

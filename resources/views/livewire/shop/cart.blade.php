<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-extrabold tracking-tight text-zinc-900">My cart</h2>
        <span class="text-xs text-zinc-500 font-medium">{{ $this->cartItems->count() }} items</span>
    </div>

    @if($this->cartItems->isEmpty())
        <!-- Empty Cart State -->
        <div class="py-20 text-center rounded-[2.5rem] border border-dashed border-zinc-200 bg-white">
            <div class="inline-flex items-center justify-center size-16 rounded-full bg-zinc-100 mb-4">
                <flux:icon icon="shopping-bag" class="size-8 text-zinc-400" />
            </div>
            <h3 class="text-lg font-bold text-zinc-900">Your cart is empty</h3>
            <p class="text-sm text-zinc-500 mt-1 max-w-sm mx-auto">Browse our collection to add products to your bag.</p>
            <flux:button href="{{ route('shop.products') }}" wire:navigate variant="filled" size="sm" class="mt-6 bg-zinc-900 hover:bg-zinc-800 text-white rounded-2xl">
                Explore Products
            </flux:button>
        </div>
    @else

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Cart Items Mobile App Card List -->
            <div class="lg:col-span-8 space-y-4">
                @foreach($this->cartItems as $item)
                    @php
                        $product = $item->product;
                        $imgPath = $product->primaryImage?->path ?? $product->images->first()?->path ?? null;
                        $lineTotal = $product->sale_price * $item->quantity;
                    @endphp

                    <div wire:key="cart-item-{{ $item->id }}" class="bg-white border border-zinc-200/80 rounded-[2rem] p-4 shadow-sm space-y-3">
                        <div class="flex items-center gap-4">
                            <!-- Rounded Image Container -->
                            <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate class="shrink-0 size-20 rounded-2xl bg-zinc-100 border border-zinc-200/60 overflow-hidden flex items-center justify-center p-1">
                                @if($imgPath)
                                    <img src="{{ str_starts_with($imgPath, 'http') ? $imgPath : asset('storage/' . $imgPath) }}" alt="{{ $product->name }}" class="size-full object-contain" />
                                @else
                                    <flux:icon icon="shopping-bag" class="size-8 text-zinc-400" />
                                @endif
                            </a>

                            <!-- Title & Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-bold text-zinc-900 truncate">{{ $product->name }}</h3>
                                <span class="text-xs text-zinc-400 block mt-0.5">{{ $product->brand?->name ?? 'Collection' }}</span>
                                <span class="text-xs font-semibold bg-zinc-100 px-2 py-0.5 rounded-full text-zinc-600 inline-block mt-1">
                                    SKU: {{ $product->sku ?? 'N/A' }}
                                </span>
                            </div>

                            <!-- Price -->
                            <div class="text-right">
                                <span class="text-base font-extrabold text-zinc-900">₹{{ number_format($lineTotal, 0) }}</span>
                            </div>
                        </div>

                        <!-- Actions & Quantity Bar (Matching reference app screenshot) -->
                        <div class="pt-2 border-t border-zinc-100 flex items-center justify-between text-xs">
                            <button type="button" wire:click="removeItem({{ $item->id }})" class="text-rose-600 hover:text-rose-700 font-semibold flex items-center gap-1 cursor-pointer">
                                <flux:icon icon="trash" class="size-3.5" />
                                Remove
                            </button>

                            <div class="flex items-center gap-2 border border-zinc-200 rounded-full bg-zinc-50 px-2 py-1">
                                <button type="button" wire:click="decrementQuantity({{ $item->id }})" class="size-6 rounded-full bg-white text-zinc-800 font-bold shadow-xs flex items-center justify-center">-</button>
                                <span class="w-6 text-center font-bold text-zinc-900">{{ $item->quantity }}</span>
                                <button type="button" wire:click="incrementQuantity({{ $item->id }})" class="size-6 rounded-full bg-white text-zinc-800 font-bold shadow-xs flex items-center justify-center">+</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary Card -->
            <div class="lg:col-span-4">
                <div class="bg-white border border-zinc-200/80 rounded-[2rem] p-6 shadow-sm space-y-4">
                    <h3 class="text-base font-extrabold text-zinc-900">Order Summary</h3>

                    <div class="space-y-2.5 text-xs text-zinc-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-bold text-zinc-900">₹{{ number_format($this->subtotal, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span class="font-bold text-emerald-600">Free</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-zinc-100 flex justify-between items-center text-sm font-extrabold text-zinc-900">
                        <span>Total</span>
                        <span class="text-xl">₹{{ number_format($this->subtotal, 0) }}</span>
                    </div>

                    <button type="button" wire:click="checkout" class="w-full py-3.5 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-2xl shadow-md cursor-pointer transition">
                        Checkout
                    </button>
                </div>
            </div>
        </div>

    @endif

</main>

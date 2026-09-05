<x-layouts::blank :title="__('Cart')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data>

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-8">
            <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition" wire:navigate>Home</a>
            <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
            <span class="text-zinc-900 dark:text-white font-semibold">Cart</span>
        </nav>

        {{-- Page Header --}}
        <div class="flex items-center justify-between border-b border-zinc-200 pb-5 mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 tracking-tight">
                Cart
            </h1>

            <flux:button
                href="{{ route('home') }}">
                Continue Shopping
            </flux:button>
        </div>

        {{-- Empty Cart State --}}
        <template x-if="$store.cart.items.length === 0">
            <div class="bg-white border border-zinc-200 rounded-2xl p-12 text-center shadow-sm my-8">
                <div class="inline-flex p-4 rounded-full bg-zinc-100 mb-4 text-zinc-400">
                    <flux:icon icon="shopping-bag" class="size-10" />
                </div>
                <h3 class="text-lg font-bold text-zinc-900 mb-1">Your cart is currently empty</h3>
                <p class="text-xs sm:text-sm text-zinc-500 max-w-sm mx-auto mb-6">
                    Looks like you haven't added anything to your cart yet. Explore our top products and start shopping!
                </p>
                <a href="{{ route('home') }}">
                    <flux:button variant="primary" class="bg-rose-600 hover:bg-rose-700 cursor-pointer">
                        Explore Products
                    </flux:button>
                </a>
            </div>
        </template>

        {{-- Main Cart Layout --}}
        <template x-if="$store.cart.items.length > 0">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- Left: Cart Items List --}}
                <div class="lg:col-span-8 bg-white border border-zinc-200 rounded-2xl shadow-sm divide-y divide-zinc-100 overflow-hidden">
                    <template x-for="item in $store.cart.items" :key="item.product_id">
                        <div class="p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 transition-colors hover:bg-zinc-50/50">

                            {{-- Product Details --}}
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <img
                                    :src="item.image ? '/storage/' + item.image : '/placeholder.jpg'"
                                    :alt="item.name"
                                    class="size-20 rounded-xl object-cover bg-zinc-100 border border-zinc-200 shrink-0" />
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm sm:text-base font-bold text-zinc-900 line-clamp-2" x-text="item.name"></h3>
                                    <p class="text-xs text-zinc-500 mt-0.5">Unit Price: ₹<span x-text="Number(item.price).toFixed(2)"></span></p>

                                    {{-- Mobile Remove Link --}}
                                    <button
                                        type="button"
                                        @click="$store.cart.removeItem(item.product_id)"
                                        class="sm:hidden text-xs text-rose-600 hover:underline mt-2 flex items-center gap-1 font-semibold">
                                        <flux:icon icon="trash" class="size-3.5" /> Remove
                                    </button>
                                </div>
                            </div>

                            {{-- Quantity Controls & Total --}}
                            <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-3 sm:pt-0 border-t sm:border-0 border-zinc-100">

                                {{-- Quantity Selector --}}
                                <div class="flex items-center border border-zinc-200 rounded-lg bg-zinc-50 overflow-hidden">
                                    <button
                                        type="button"
                                        @click="$store.cart.updateQuantity(item.product_id, item.quantity - 1)"
                                        class="px-2.5 py-1.5 text-zinc-600 hover:bg-zinc-200 transition-colors cursor-pointer"
                                        aria-label="Decrease quantity">
                                        <flux:icon icon="minus" class="size-3.5" />
                                    </button>

                                    <span class="px-3 py-1.5 text-xs sm:text-sm font-bold text-zinc-900 min-w-[32px] text-center" x-text="item.quantity"></span>

                                    <button
                                        type="button"
                                        @click="$store.cart.updateQuantity(item.product_id, item.quantity + 1)"
                                        class="px-2.5 py-1.5 text-zinc-600 hover:bg-zinc-200 transition-colors cursor-pointer"
                                        aria-label="Increase quantity">
                                        <flux:icon icon="plus" class="size-3.5" />
                                    </button>
                                </div>

                                {{-- Total Price per Item --}}
                                <div class="text-right">
                                    <div class="text-sm sm:text-base font-extrabold text-zinc-950">
                                        ₹<span x-text="(item.price * item.quantity).toFixed(2)"></span>
                                    </div>
                                </div>

                                {{-- Desktop Remove Button --}}
                                <button
                                    type="button"
                                    @click="$store.cart.removeItem(item.product_id)"
                                    class="hidden sm:flex p-2 text-zinc-400 hover:text-rose-600 transition-colors rounded-lg hover:bg-rose-50"
                                    title="Remove item">
                                    <flux:icon icon="trash" class="size-4" />
                                </button>
                            </div>

                        </div>
                    </template>
                </div>

                {{-- Right: Order Summary Sidebar --}}
                <div class="lg:col-span-4 bg-white border border-zinc-200 rounded-2xl p-6 shadow-sm space-y-6 sticky top-6">
                    <h2 class="text-base font-bold text-zinc-900 border-b border-zinc-100 pb-3">
                        Order Summary
                    </h2>

                    <div class="space-y-3 text-xs sm:text-sm">
                        <div class="flex justify-between text-zinc-600">
                            <span>Subtotal (<span x-text="$store.cart.totalCount"></span> items)</span>
                            <span class="font-semibold text-zinc-900">₹<span x-text="$store.cart.totalAmount.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between text-zinc-600">
                            <span>Shipping</span>
                            <span class="text-emerald-600 font-semibold">FREE</span>
                        </div>
                        <div class="flex justify-between text-zinc-600">
                            <span>Taxes</span>
                            <span class="text-zinc-500">Calculated at checkout</span>
                        </div>

                        <div class="pt-4 border-t border-zinc-100 flex justify-between items-center text-sm sm:text-base font-bold">
                            <span class="text-zinc-900">Total</span>
                            <span class="text-lg sm:text-xl font-black text-rose-600">₹<span x-text="$store.cart.totalAmount.toFixed(2)"></span></span>
                        </div>
                    </div>

                    {{-- Checkout CTA --}}
                    <a href="{{ route('shop.checkout') }}" class="block">
                        <flux:button variant="primary" class="w-full justify-center bg-rose-600 hover:bg-rose-700 py-3 text-sm font-bold cursor-pointer">
                            Proceed to Checkout &rarr;
                        </flux:button>
                    </a>

                    <div class="flex items-center justify-center gap-2 text-[11px] text-zinc-400 pt-2">
                        <flux:icon icon="shield-check" class="size-4 text-emerald-500" />
                        <span>Safe and Secure Checkout</span>
                    </div>
                </div>

            </div>
        </template>

    </div>
</x-layouts::blank>
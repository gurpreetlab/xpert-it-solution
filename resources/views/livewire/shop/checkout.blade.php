<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition" wire:navigate>Home</a>
        <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
        <a href="{{ route('cart.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition" wire:navigate>Cart</a>
        <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
        <span class="text-zinc-900 dark:text-white font-semibold">Checkout</span>
    </nav>

    <div class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Checkout</h2>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Confirm your shipping address and review your order before payment.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left: Address + Order Review -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Shipping Address -->
            <div class="rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm p-6 sm:p-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold tracking-tight text-zinc-900 dark:text-white flex items-center gap-2">
                        <flux:icon icon="map-pin" class="size-5 text-blue-600" />
                        Shipping Address
                    </h3>
                    @if($this->addresses->isNotEmpty())
                    <flux:button wire:click="$set('showAddressForm', true)" variant="ghost" size="sm" icon="plus" class="text-blue-600 dark:text-blue-400">
                        Add New
                    </flux:button>
                    @endif
                </div>

                @if($this->addresses->isNotEmpty() && ! $showAddressForm)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($this->addresses as $address)
                    <button
                        type="button"
                        wire:click="selectAddress({{ $address->id }})"
                        class="text-left p-4 rounded-2xl border-2 transition cursor-pointer {{ $selectedAddressId === $address->id ? 'border-blue-600 bg-blue-50 dark:bg-blue-950/30' : 'border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700' }}">
                        <div class="flex items-start justify-between gap-2 mb-1.5">
                            <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $address->full_name }}</span>
                            @if($address->is_default)
                            <flux:badge size="sm" color="blue">Default</flux:badge>
                            @endif
                        </div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                            {{ $address->address_line1 }}{{ $address->address_line2 ? ', ' . $address->address_line2 : '' }},
                            {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}
                        </p>
                        <p class="text-xs text-zinc-400 mt-1">{{ $address->phone }}</p>
                    </button>
                    @endforeach
                </div>
                @endif

                @if($showAddressForm)
                <form wire:submit="saveAddress" class="grid grid-cols-1 sm:grid-cols-2 gap-4 {{ $this->addresses->isNotEmpty() ? 'mt-6 pt-6 border-t border-zinc-100 dark:border-zinc-800' : '' }}">
                    <flux:input wire:model="full_name" label="Full Name" placeholder="Recipient's name" />
                    <flux:input wire:model="phone" label="Phone Number" placeholder="10-digit mobile number" />
                    <flux:input wire:model="address_line1" label="Address Line 1" placeholder="House no, building, street" class="sm:col-span-2" />
                    <flux:input wire:model="address_line2" label="Address Line 2 (optional)" placeholder="Landmark, area" class="sm:col-span-2" />
                    <flux:input wire:model="city" label="City" />
                    <flux:input wire:model="state" label="State" />
                    <flux:input wire:model="pincode" label="Pincode" />

                    <div class="sm:col-span-2 flex items-center gap-3 pt-2">
                        <flux:button type="submit" variant="filled" size="sm" class="bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium">
                            Save Address
                        </flux:button>
                        @if($this->addresses->isNotEmpty())
                        <flux:button type="button" wire:click="$set('showAddressForm', false)" variant="ghost" size="sm" class="text-zinc-500">
                            Cancel
                        </flux:button>
                        @endif
                    </div>
                </form>
                @endif
            </div>

            <!-- Order Review -->
            <div class="rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm p-6 sm:p-8">
                <h3 class="text-lg font-bold tracking-tight text-zinc-900 dark:text-white flex items-center gap-2 mb-5">
                    <flux:icon icon="clipboard-document-list" class="size-5 text-blue-600" />
                    Order Review
                </h3>

                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
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

                    <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
                        <div class="relative size-14 shrink-0 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center overflow-hidden">
                            @if($item->product->primaryImage?->path)
                            <img src="{{ asset('storage/' . $item->product->primaryImage->path) }}" alt="{{ $item->product->name }}" class="size-full object-contain p-1" />
                            @else
                            <div class="absolute inset-0 bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} flex items-center justify-center">
                                <flux:icon icon="{{ $categoryIcon }}" class="size-8 text-white/90" />
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-zinc-900 dark:text-white truncate">{{ $item->product->name }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Qty: {{ $item->quantity }} &times; ₹{{ number_format($item->sale_price, 2) }}</p>
                        </div>
                        <span class="text-sm font-bold text-zinc-900 dark:text-white">₹{{ number_format($item->sale_price * $item->quantity, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right: Payment Panel -->
        <div class="lg:col-span-4">
            <div class="sticky top-24 space-y-6">

                <div class="rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold tracking-tight text-zinc-900 dark:text-white flex items-center gap-2">
                        <flux:icon icon="credit-card" class="size-5 text-blue-600" />
                        Payment Summary
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between text-zinc-600 dark:text-zinc-300">
                            <span>Price</span>
                            <span class="font-semibold text-zinc-900 dark:text-white">₹{{ number_format($this->mrp, 2) }}</span>
                        </div>

                        @if($this->savings > 0)
                        <div class="flex items-center justify-between text-emerald-600 dark:text-emerald-400">
                            <span>Savings</span>
                            <span class="font-semibold">- ₹{{ number_format($this->savings, 2) }}</span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between text-zinc-600 dark:text-zinc-300">
                            <span>Subtotal</span>
                            <span class="font-semibold text-zinc-900 dark:text-white">₹{{ number_format($this->subtotal, 2) }}</span>
                        </div>

                        @if($couponDiscountPercent > 0)
                        <div class="flex items-center justify-between text-emerald-600 dark:text-emerald-400">
                            <span>Promo Discount ({{ $couponDiscountPercent }}%)</span>
                            <span class="font-semibold">- ₹{{ number_format($this->couponDiscountAmount, 2) }}</span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between text-zinc-600 dark:text-zinc-300">
                            <span>Shipping</span>
                            <span class="font-semibold text-emerald-600 dark:text-emerald-400">
                                {{ $this->shippingFee > 0 ? '₹' . number_format($this->shippingFee, 2) : 'Free' }}
                            </span>
                        </div>
                    </div>

                    <!-- Coupon Input Form -->
                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 space-y-2">
                        <label class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider">Promo Code</label>
                        @if($appliedCouponId)
                        <div class="flex items-center justify-between bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-2.5">
                            <div class="flex items-center gap-1.5 text-xs text-emerald-800 dark:text-emerald-400 font-semibold">
                                <flux:icon icon="tag" class="size-4" />
                                <span>{{ strtoupper($couponCode) }} applied</span>
                            </div>
                            <button type="button" wire:click="removeCoupon" class="text-xs text-rose-500 hover:text-rose-600 font-medium">Remove</button>
                        </div>
                        @else
                        <div class="flex gap-2">
                            <input type="text" wire:model.defer="couponCode" placeholder="WELCOME10, XPERT20" class="flex-1 h-9 px-3 rounded-lg text-xs bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-blue-500" />
                            <flux:button size="sm" variant="outline" wire:click="applyCoupon" class="cursor-pointer">Apply</flux:button>
                        </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                        <span class="text-sm font-bold text-zinc-900 dark:text-white">Total Payable</span>
                        <span class="text-2xl font-black tracking-tight text-zinc-950 dark:text-white">₹{{ number_format($this->total, 2) }}</span>
                    </div>

                    <flux:button
                        wire:click="placeOrder"
                        wire:loading.attr="disabled"
                        wire:target="placeOrder"
                        icon="lock-closed"
                        variant="filled"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium cursor-pointer">
                        <span wire:loading.remove wire:target="placeOrder">Pay ₹{{ number_format($this->total, 2) }}</span>
                        <span wire:loading wire:target="placeOrder">Preparing payment...</span>
                    </flux:button>

                    <p class="text-[11px] text-center text-zinc-400 leading-relaxed">
                        Payments secured by Razorpay &middot; Cards, UPI &amp; Netbanking accepted
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-zinc-100/70 dark:bg-zinc-900/70 border border-zinc-200/50 dark:border-zinc-800/50 flex items-start gap-3">
                    <flux:icon icon="shield-check" class="size-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" />
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                        Your payment details are encrypted and processed directly by Razorpay. We never store your card information.
                    </p>
                </div>
            </div>
        </div>
    </div>

</main>

@script
<script>
    $wire.on('razorpay-checkout', (payload) => {
        const data = payload[0] ?? payload;

        const options = {
            key: data.key,
            amount: data.amount,
            currency: data.currency,
            name: data.name,
            description: 'Order ' + data.orderNumber,
            order_id: data.razorpayOrderId,
            prefill: data.prefill,
            theme: {
                color: '#2563eb'
            },
            handler: function(response) {
                $wire.verifyPayment(
                    response.razorpay_payment_id,
                    response.razorpay_order_id,
                    response.razorpay_signature
                );
            },
            modal: {
                ondismiss: function() {
                    $wire.paymentFailed(data.razorpayOrderId);
                }
            }
        };

        const razorpayCheckout = new Razorpay(options);

        razorpayCheckout.on('payment.failed', function() {
            $wire.paymentFailed(data.razorpayOrderId);
        });

        razorpayCheckout.open();
    });
</script>
@endscript
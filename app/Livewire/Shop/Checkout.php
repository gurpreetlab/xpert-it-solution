<?php

namespace App\Livewire\Shop;

use App\Models\Address;
use App\Models\CartItem;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderConfirmed;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Order as RazorpayOrder;
use Razorpay\Api\Utility as RazorpayUtility;

/**
 * @property-read Collection<int, Address> $addresses
 * @property-read Collection<int, CartItem> $cartItems
 * @property-read float $mrp
 * @property-read float $subtotal
 * @property-read float $savings
 * @property-read int $shippingFee
 * @property-read float $taxAmount
 * @property-read float $total
 */
class Checkout extends Component
{
    public ?int $selectedAddressId = null;

    public bool $showAddressForm = false;

    public string $couponCode = '';

    public ?int $appliedCouponId = null;

    public int $couponDiscountPercent = 0;

    #[Validate('required|string|max:255')]
    public string $full_name = '';

    #[Validate('required|string|max:20')]
    public string $phone = '';

    #[Validate('required|string|max:255')]
    public string $address_line1 = '';

    #[Validate('nullable|string|max:255')]
    public string $address_line2 = '';

    #[Validate('required|string|max:100')]
    public string $city = '';

    #[Validate('required|string|max:100')]
    public string $state = '';

    #[Validate('required|string|max:10')]
    public string $pincode = '';

    public function mount(): void
    {
        if ($this->cartItems->isEmpty()) {
            $this->redirect(route('shop.cart'), navigate: true);

            return;
        }

        $default =
            $this->addresses->firstWhere('is_default', true) ??
            $this->addresses->first();
        $this->selectedAddressId = $default?->id;

        if ($this->addresses->isEmpty()) {
            $this->showAddressForm = true;
        }
    }

    /** @return EloquentCollection<int, Address> */
    #[Computed]
    public function addresses(): EloquentCollection
    {
        return Auth::user()->addresses()->latest()->get();
    }

    /** @return EloquentCollection<int, CartItem> */
    #[Computed]
    public function cartItems(): EloquentCollection
    {
        $cart = Auth::user()->cart;

        if (! $cart) {
            return new EloquentCollection;
        }

        return $cart
            ->items()
            ->with(['product.category', 'product.brand'])
            ->get();
    }

    #[Computed]
    public function mrp(): float
    {
        return $this->cartItems->sum(
            fn ($item) => ($item->product->mrp ?? 0) * $item->quantity,
        );
    }

    #[Computed]
    public function subtotal(): float
    {
        return $this->cartItems->sum(
            fn ($item) => $item->sale_price * $item->quantity,
        );
    }

    #[Computed]
    public function savings(): float
    {
        return $this->cartItems->sum(function ($item) {
            $mrp = $item->product->mrp ?? 0;

            return $mrp > $item->sale_price
                ? ($mrp - $item->sale_price) * $item->quantity
                : 0;
        });
    }

    #[Computed]
    public function couponDiscountAmount(): float
    {
        return $this->couponDiscountPercent > 0
            ? round($this->subtotal * ($this->couponDiscountPercent / 100), 2)
            : 0.0;
    }

    #[Computed]
    public function shippingFee(): int
    {
        return 0; // free shipping for now — adjust if you introduce thresholds/zones
    }

    #[Computed]
    public function taxAmount(): float
    {
        return $this->subtotal * (shop()->gst_rate / 100);
    }

    #[Computed]
    public function total(): float
    {
        return $this->subtotal + $this->shippingFee - $this->couponDiscountAmount();
    }

    /**
     * Apply active promo/coupon code to checkout.
     */
    public function applyCoupon(): void
    {
        if ($this->couponCode === '') {
            $this->dispatch('cart-toast', message: 'Please enter a coupon code.', variant: 'warning');
            return;
        }

        $coupon = \App\Models\Coupon::findActive($this->couponCode);

        if (! $coupon) {
            $this->dispatch('cart-toast', message: 'Invalid or inactive coupon code.', variant: 'danger');
            return;
        }

        $this->appliedCouponId = $coupon->id;
        $this->couponDiscountPercent = $coupon->discount_percent;

        $this->dispatch('cart-toast', message: "Coupon applied! You saved {$coupon->discount_percent}%.", variant: 'success');
    }

    /**
     * Remove applied coupon.
     */
    public function removeCoupon(): void
    {
        $this->couponCode = '';
        $this->appliedCouponId = null;
        $this->couponDiscountPercent = 0;

        $this->dispatch('cart-toast', message: 'Coupon removed.', variant: 'info');
    }

    #[Computed]
    private function cgstAmount(): float
    {
        return $this->subtotal * (shop()->cgst_rate / 100);
    }

    #[Computed]
    private function sgstAmount(): float
    {
        return $this->subtotal * (shop()->sgst_rate / 100);
    }

    /*
    * The GST amount is the sum of CGST and SGST.
    */
    #[Computed]
    private function gstAmount(): float
    {
        return $this->subtotal * (shop()->gst_rate / 100);
    }

    public function selectAddress(int|string $addressId): void
    {
        if (is_string($addressId)) {
            $addressId = (int) $addressId;
        }

        $this->selectedAddressId = $addressId;
        $this->showAddressForm = false;
    }

    public function saveAddress(): void
    {
        $this->validate();

        $address = Auth::user()
            ->addresses()
            ->create([
                'full_name' => $this->full_name,
                'phone' => $this->phone,
                'address_line1' => $this->address_line1,
                'address_line2' => $this->address_line2,
                'city' => $this->city,
                'state' => $this->state,
                'pincode' => $this->pincode,
                'is_default' => $this->addresses->isEmpty(),
            ]);

        $this->selectedAddressId = $address->id;
        $this->showAddressForm = false;
        $this->reset([
            'full_name',
            'phone',
            'address_line1',
            'address_line2',
            'city',
            'state',
            'pincode',
        ]);

        unset($this->addresses);
    }

    /**
     * Creates a pending local Order + Razorpay order, then hands off to
     * the browser to open the Razorpay Checkout modal via a dispatched event.
     */
    public function placeOrder(): void
    {
        if (! $this->selectedAddressId) {
            $this->dispatch(
                'cart-toast',
                message: 'Please select a shipping address',
                variant: 'warning',
            );

            return;
        }

        if ($this->cartItems->isEmpty()) {
            $this->redirect(route('shop.cart'), navigate: true);

            return;
        }

        // Re-validate stock right before charging — availability may have
        // changed since items were added to the cart.
        foreach ($this->cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                $this->dispatch(
                    'cart-toast',
                    message: "\"{$item->product->name}\" no longer has enough stock",
                    variant: 'danger',
                );
                unset($this->cartItems);

                return;
            }
        }

        $address = Address::where('user_id', Auth::id())->findOrFail(
            $this->selectedAddressId,
        );

        DB::transaction(function () use ($address) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => $address->id,
                'shipping_name' => $address->full_name,
                'shipping_phone' => $address->phone,
                'shipping_address_line1' => $address->address_line1,
                'shipping_address_line2' => $address->address_line2,
                'shipping_city' => $address->city,
                'shipping_state' => $address->state,
                'shipping_pincode' => $address->pincode,
                'shipping_country' => $address->country,
                'subtotal' => $this->subtotal,
                'discount' => $this->savings + $this->couponDiscountAmount(),
                'shipping_fee' => $this->shippingFee,
                'tax_amount' => $this->taxAmount,
                'total' => $this->total,
                'payment_method' => 'razorpay',
                'payment_status' => 'pending',
                'status' => 'pending',
            ]);

            foreach ($this->cartItems as $item) {
                $lineTaxable = $item->sale_price * $item->quantity;
                $lineTax = round(
                    $lineTaxable * (shop()->gst_rate / 100),
                    2,
                );

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'sku' => $item->product->sku,
                    'hsn_code' => $item->product->hsn_code,
                    'unit_price' => $item->sale_price,
                    'mrp' => $item->product->mrp,
                    'cgst_rate' => shop()->cgst_rate,
                    'cgst_amount' => $this->cgstAmount(),
                    'sgst_rate' => shop()->sgst_rate,
                    'sgst_amount' => $this->sgstAmount(),
                    'gst_rate' => shop()->gst_rate,
                    'gst_amount' => $this->gstAmount(),
                    'quantity' => $item->quantity,
                ]);
            }

            // Razorpay works in the smallest currency unit (paise for INR).
            $amountInPaise = (int) round($order->total * 100);

            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret'),
            );

            $razorpayOrderEntity = new RazorpayOrder;

            $razorpayOrder = $razorpayOrderEntity->create([
                'receipt' => $order->order_number,
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'notes' => [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                ],
            ]);

            $order->update(['razorpay_order_id' => $razorpayOrder->id]);

            $this->dispatch('razorpay-checkout', [
                'key' => config('services.razorpay.key'),
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'razorpayOrderId' => $razorpayOrder->id,
                'orderNumber' => $order->order_number,
                'name' => 'Xpert IT Solution',
                'prefill' => [
                    'name' => $address->full_name,
                    'contact' => $address->phone,
                    'email' => Auth::user()->email,
                ],
            ]);
        });
    }

    /**
     * Called from the browser after Razorpay Checkout returns a successful
     * payment handler response. Verifies the signature server-side before
     * trusting anything the client sent.
     */
    public function verifyPayment(
        string $razorpayPaymentId,
        string $razorpayOrderId,
        string $razorpaySignature,
    ): void {
        $order = Order::where('razorpay_order_id', $razorpayOrderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret'),
        );

        try {
            $utility = app(RazorpayUtility::class);

            $utility->verifyPaymentSignature([
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature,
            ]);
        } catch (SignatureVerificationError) {
            $order->update(['payment_status' => 'failed']);
            $this->dispatch(
                'cart-toast',
                message: 'Payment verification failed. Please try again.',
                variant: 'danger',
            );

            return;
        }

        DB::transaction(function () use (
            $order,
            $razorpayPaymentId,
            $razorpaySignature,
        ) {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature,
            ]);

            /** @var OrderItem $item */
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity);
                }
            }

            Auth::user()->cart?->items()->delete();
        });

        // Invoice numbers must be issued sequentially at the moment
        // of sale — generated here, not lazily on first PDF download.
        Invoice::generateForOrder($order);

        $this->dispatch('cart-updated');

        Auth::user()->notify(new OrderConfirmed($order));

        $admin = \App\Models\User::role('super-admin')->first();
        if ($admin) {
            $admin->notify(new \App\Notifications\AdminNewOrderNotification($order));
        }

        $this->redirect(
            route('shop.order.confirmation', $order->order_number),
            navigate: true,
        );
    }

    /**
     * Called from the browser if the Razorpay modal is dismissed or the
     * payment attempt fails. Order stays pending/failed; cart is untouched
     * so the customer can retry.
     */
    public function paymentFailed(string $razorpayOrderId): void
    {
        Order::where('razorpay_order_id', $razorpayOrderId)
            ->where('user_id', Auth::id())
            ->update(['payment_status' => 'failed']);

        $this->dispatch(
            'cart-toast',
            message: 'Payment was not completed',
            variant: 'warning',
        );
    }

    #[Layout('layouts.blank')]
    public function render(): View
    {
        return view('livewire.shop.checkout');
    }
}

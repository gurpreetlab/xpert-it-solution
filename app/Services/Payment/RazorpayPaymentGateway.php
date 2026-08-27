<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Order as RazorpayOrder;
use Razorpay\Api\Utility as RazorpayUtility;

class RazorpayPaymentGateway implements PaymentGatewayInterface
{
    protected string $key;
    protected string $secret;

    public function __construct()
    {
        $this->key = (string) config('services.razorpay.key');
        $this->secret = (string) config('services.razorpay.secret');
    }

    /**
     * Create a Razorpay order for the given local Order and return client checkout payload.
     *
     * @return array<string, mixed>
     */
    public function createOrder(Order $order): array
    {
        $amountInPaise = (int) round($order->total * 100);

        $api = new Api($this->key, $this->secret);
        $razorpayOrderEntity = new RazorpayOrder();

        $razorpayOrder = $razorpayOrderEntity->create([
            'receipt' => $order->order_number,
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'notes' => [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
            ],
        ]);

        $order->update(['razorpay_order_id' => $razorpayOrder->id]);

        return [
            'key' => $this->key,
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'razorpayOrderId' => $razorpayOrder->id,
            'orderNumber' => $order->order_number,
            'name' => 'Xpert IT Solution',
            'prefill' => [
                'name' => $order->shipping_name,
                'contact' => $order->shipping_phone,
                'email' => $order->user?->email ?? '',
            ],
        ];
    }

    /**
     * Verify payment signature sent from Razorpay client handler.
     *
     * @param array<string, string> $paymentData
     */
    public function verifyPayment(array $paymentData): bool
    {
        try {
            new Api($this->key, $this->secret);

            $utility = app(RazorpayUtility::class);

            $utility->verifyPaymentSignature([
                'razorpay_order_id' => $paymentData['razorpay_order_id'] ?? '',
                'razorpay_payment_id' => $paymentData['razorpay_payment_id'] ?? '',
                'razorpay_signature' => $paymentData['razorpay_signature'] ?? '',
            ]);

            return true;
        } catch (SignatureVerificationError|\Throwable $e) {
            return false;
        }
    }

    /**
     * Handle failed or abandoned Razorpay payment attempt.
     */
    public function handlePaymentFailure(string $gatewayOrderId): void
    {
        Order::where('razorpay_order_id', $gatewayOrderId)
            ->update(['payment_status' => 'failed']);
    }
}

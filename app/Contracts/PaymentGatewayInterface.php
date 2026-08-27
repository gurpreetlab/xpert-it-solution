<?php

namespace App\Contracts;

use App\Models\Order;

interface PaymentGatewayInterface
{
    /**
     * Create a payment gateway order for the given local Order and return client checkout payload.
     *
     * @return array<string, mixed>
     */
    public function createOrder(Order $order): array;

    /**
     * Verify payment signature/status sent from client handler.
     *
     * @param array<string, string> $paymentData
     */
    public function verifyPayment(array $paymentData): bool;

    /**
     * Handle failed or abandoned payment attempt.
     */
    public function handlePaymentFailure(string $gatewayOrderId): void;
}

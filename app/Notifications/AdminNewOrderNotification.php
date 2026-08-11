<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->order;

        $message = (new MailMessage)
            ->subject("New Corporate Order Placed — {$order->order_number}")
            ->line("A new corporate order has been successfully placed and paid on Xpert IT Solution.")
            ->line("Order Number: {$order->order_number}")
            ->line("Customer: {$order->user?->name} ({$order->user?->email})")
            ->line("Total Paid: ₹" . number_format($order->total, 2))
            ->line("Shipping to: {$order->shipping_name}, {$order->shipping_address_line1}, {$order->shipping_city} - {$order->shipping_pincode}")
            ->action('View Order in Admin Panel', route('dashboard.orders.show', $order))
            ->line('Thank you for using our platform!');

        if ($order->invoice) {
            $message->attachData(
                $order->invoice->renderPdf()->output(),
                $order->invoice->pdfFilename(),
                ['mime' => 'application/pdf'],
            );
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::blank')] class extends Component
{
    //
};
?>

<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition" wire:navigate>Home</a>
        <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
        <span class="text-zinc-900 dark:text-white font-semibold">Shipping Policy</span>
    </nav>

    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-wrap items-center gap-3 mb-2">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Shipping Policy</h1>
            <span class="inline-flex items-center gap-1.5 pl-3 pr-3 py-1 rounded-full bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 text-xs font-medium">
                <flux:icon icon="calendar" class="size-3" />
                Last Updated: {{ now()->format('F j, Y') }}
            </span>
        </div>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 max-w-2xl">
            Everything you need to know about how we pack, ship, and deliver your order.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Sticky Table of Contents Sidebar -->
        <div class="lg:col-span-1">
            <div class="lg:sticky lg:top-24 space-y-6">
                <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-4">On This Page</h3>
                    <nav class="space-y-1 text-sm">
                        <a href="#coverage" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">1. Shipping Coverage</a>
                        <a href="#processing" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">2. Processing Time</a>
                        <a href="#charges" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">3. Shipping Charges</a>
                        <a href="#timelines" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">4. Delivery Timelines</a>
                        <a href="#tracking" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">5. Order Tracking</a>
                        <a href="#delays" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">6. Delays &amp; Lost Shipments</a>
                        <a href="#bulk" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">7. Bulk &amp; Enterprise Orders</a>
                        <a href="#contact" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">8. Contact Us</a>
                    </nav>
                </div>

                <div class="p-5 rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                    <flux:icon icon="truck" class="size-6 text-blue-600 dark:text-blue-400 mb-2" />
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed mb-3">
                        Already placed an order? Track its shipping status from your account.
                    </p>
                    <flux:button size="sm" href="{{ route('shop.orders') }}" wire:navigate class="w-full">My Orders</flux:button>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="lg:col-span-3 space-y-6">

            <section id="coverage" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="globe-asia-australia" class="size-5 text-blue-600 dark:text-blue-400" />
                    1. Shipping Coverage
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    We currently ship to addresses across India, including most cities and towns serviceable by our logistics partners. Shipping to certain remote or restricted pin codes may not be available; you will be notified at checkout if your location is unserviceable.
                </p>
            </section>

            <section id="processing" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="archive-box" class="size-5 text-blue-600 dark:text-blue-400" />
                    2. Processing Time
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    Orders are typically processed and handed over to our courier partner within <strong class="text-zinc-900 dark:text-white">1-2 business days</strong> of payment confirmation. Orders placed on weekends or public holidays are processed on the next business day. Custom-configured or enterprise bulk orders may require additional processing time, which will be communicated at the time of order confirmation.
                </p>
            </section>

            <section id="charges" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="currency-rupee" class="size-5 text-blue-600 dark:text-blue-400" />
                    3. Shipping Charges
                </h2>
                <div class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    <p>Shipping charges, where applicable, are calculated based on the delivery address, order weight, and package dimensions, and are displayed at checkout before payment.</p>
                    <p>We may periodically offer free shipping promotions on qualifying orders; any such offer will be clearly indicated on the product or cart page.</p>
                </div>
            </section>

            <section id="timelines" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="calendar-days" class="size-5 text-blue-600 dark:text-blue-400" />
                    4. Delivery Timelines
                </h2>
                <div class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    <p>Standard delivery timelines, once dispatched, are typically:</p>
                    <ul class="list-disc list-inside space-y-1.5 marker:text-blue-500">
                        <li><strong class="text-zinc-900 dark:text-white">Metro cities:</strong> 2-4 business days</li>
                        <li><strong class="text-zinc-900 dark:text-white">Other cities &amp; towns:</strong> 4-7 business days</li>
                        <li><strong class="text-zinc-900 dark:text-white">Remote or rural areas:</strong> 7-10 business days</li>
                    </ul>
                    <p>These timelines are estimates and are not guaranteed; actual delivery may vary based on courier partner schedules, weather, or local logistics conditions.</p>
                </div>
            </section>

            <section id="tracking" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="map-pin" class="size-5 text-blue-600 dark:text-blue-400" />
                    5. Order Tracking
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    Once your order is dispatched, you will receive a shipping confirmation with tracking details via email and/or SMS. You can also view live order status at any time from the <strong class="text-zinc-900 dark:text-white">My Orders</strong> section of your account.
                </p>
            </section>

            <section id="delays" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="exclamation-triangle" class="size-5 text-blue-600 dark:text-blue-400" />
                    6. Delays &amp; Lost Shipments
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    While we work with reliable courier partners, delays can occasionally occur due to factors outside our control. If your order has not arrived within the expected delivery window, please contact our support team with your order number and we will investigate with the courier partner and keep you updated. In the rare event a shipment is confirmed lost in transit, we will arrange a replacement or full refund.
                </p>
            </section>

            <section id="bulk" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="building-office-2" class="size-5 text-blue-600 dark:text-blue-400" />
                    7. Bulk &amp; Enterprise Orders
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    For large-volume or enterprise infrastructure orders (networking, CCTV, or storage rollouts), shipping is coordinated directly with our sales team and may involve staged delivery, freight shipping, or on-site installation scheduling. Contact us for a dedicated logistics plan.
                </p>
            </section>

            <!-- Contact Card -->
            <section id="contact" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-900 text-white shadow-sm">
                <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
                    <flux:icon icon="chat-bubble-left-right" class="size-5 text-blue-400" />
                    8. Contact Us
                </h2>
                <p class="text-sm text-zinc-300 leading-relaxed mb-5">
                    For shipping questions or delivery support, our team is happy to help.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="flex items-center gap-2">
                        <flux:icon icon="envelope" class="size-4 shrink-0 text-zinc-500" />
                        <span>{{ config('shop.company.email') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:icon icon="phone" class="size-4 shrink-0 text-zinc-500" />
                        <span>+91 {{ config('shop.company.phone') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:icon icon="map-pin" class="size-4 shrink-0 text-zinc-500" />
                        <span>{{ config('shop.company.address_line1') . ' ' . config('shop.company.address_line2') . ', ' . config('shop.company.state') }}</span>
                    </div>
                </div>
            </section>

        </div>
    </div>
</main>
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
        <span class="text-zinc-900 dark:text-white font-semibold">Refund &amp; Return Policy</span>
    </nav>

    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-wrap items-center gap-3 mb-2">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Refund &amp; Return Policy</h1>
            <span class="inline-flex items-center gap-1.5 pl-3 pr-3 py-1 rounded-full bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 text-xs font-medium">
                <flux:icon icon="calendar" class="size-3" />
                Last Updated: {{ now()->format('F j, Y') }}
            </span>
        </div>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 max-w-2xl">
            How to return, exchange, or request a refund for products purchased from Xpert IT Solution.
        </p>
    </div>

    <!-- Quick Facts Strip -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-center shadow-sm">
            <flux:icon icon="calendar-days" class="size-5 text-blue-600 dark:text-blue-400 mx-auto mb-1.5" />
            <div class="text-sm font-bold text-zinc-900 dark:text-white">7 Days</div>
            <div class="text-xs text-zinc-500 dark:text-zinc-400">Return Window</div>
        </div>
        <div class="p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-center shadow-sm">
            <flux:icon icon="cube" class="size-5 text-blue-600 dark:text-blue-400 mx-auto mb-1.5" />
            <div class="text-sm font-bold text-zinc-900 dark:text-white">Original Packaging</div>
            <div class="text-xs text-zinc-500 dark:text-zinc-400">Required</div>
        </div>
        <div class="p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-center shadow-sm">
            <flux:icon icon="banknotes" class="size-5 text-blue-600 dark:text-blue-400 mx-auto mb-1.5" />
            <div class="text-sm font-bold text-zinc-900 dark:text-white">5-7 Days</div>
            <div class="text-xs text-zinc-500 dark:text-zinc-400">Refund Processing</div>
        </div>
        <div class="p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-center shadow-sm">
            <flux:icon icon="arrow-path-rounded-square" class="size-5 text-blue-600 dark:text-blue-400 mx-auto mb-1.5" />
            <div class="text-sm font-bold text-zinc-900 dark:text-white">Original Method</div>
            <div class="text-xs text-zinc-500 dark:text-zinc-400">Refund Route</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Sticky Table of Contents Sidebar -->
        <div class="lg:col-span-1">
            <div class="lg:sticky lg:top-24 space-y-6">
                <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-4">On This Page</h3>
                    <nav class="space-y-1 text-sm">
                        <a href="#eligibility" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">1. Return Eligibility</a>
                        <a href="#window" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">2. Return Window</a>
                        <a href="#non-returnable" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">3. Non-Returnable Items</a>
                        <a href="#how-to-return" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">4. How to Initiate a Return</a>
                        <a href="#refund-process" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">5. Refund Process</a>
                        <a href="#damaged" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">6. Damaged or Defective Items</a>
                        <a href="#cancellations" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">7. Order Cancellations</a>
                        <a href="#contact" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">8. Contact Us</a>
                    </nav>
                </div>

                <div class="p-5 rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                    <flux:icon icon="shopping-bag" class="size-6 text-blue-600 dark:text-blue-400 mb-2" />
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed mb-3">
                        Track a return you've already started from your account.
                    </p>
                    <flux:button size="sm" href="{{ route('shop.orders') }}" wire:navigate class="w-full">My Orders</flux:button>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="lg:col-span-3 space-y-6">

            <section id="eligibility" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="check-circle" class="size-5 text-blue-600 dark:text-blue-400" />
                    1. Return Eligibility
                </h2>
                <div class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    <p>To be eligible for a return, an item must be:</p>
                    <ul class="list-disc list-inside space-y-1.5 marker:text-blue-500">
                        <li>Unused, uninstalled, and in the same condition you received it</li>
                        <li>In its original packaging, with all accessories, manuals, and accompanying items</li>
                        <li>Accompanied by the original invoice or order confirmation</li>
                        <li>Free from physical damage not caused by a manufacturing defect</li>
                    </ul>
                </div>
            </section>

            <section id="window" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="clock" class="size-5 text-blue-600 dark:text-blue-400" />
                    2. Return Window
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    You may request a return within <strong class="text-zinc-900 dark:text-white">7 days</strong> of the delivery date shown in your order tracking. Return requests submitted after this window will not be accepted, except where the product is found to be defective under manufacturer warranty.
                </p>
            </section>

            <section id="non-returnable" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="no-symbol" class="size-5 text-blue-600 dark:text-blue-400" />
                    3. Non-Returnable Items
                </h2>
                <div class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    <p>The following items are not eligible for return unless defective on arrival:</p>
                    <ul class="list-disc list-inside space-y-1.5 marker:text-blue-500">
                        <li>Products with broken seals, such as software licences or consumables</li>
                        <li>Custom-configured or made-to-order enterprise hardware</li>
                        <li>Items marked as final sale or clearance at the time of purchase</li>
                        <li>Products installed, activated, or configured by the customer</li>
                    </ul>
                </div>
            </section>

            <section id="how-to-return" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="clipboard-document-list" class="size-5 text-blue-600 dark:text-blue-400" />
                    4. How to Initiate a Return
                </h2>
                <ol class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed list-decimal list-inside">
                    <li>Go to <strong class="text-zinc-900 dark:text-white">My Orders</strong> and select the item you wish to return.</li>
                    <li>Choose a reason for the return and submit your request.</li>
                    <li>Our team will review the request and, if approved, share pickup or drop-off instructions.</li>
                    <li>Pack the item securely in its original packaging along with all accessories and the invoice.</li>
                </ol>
            </section>

            <section id="refund-process" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="banknotes" class="size-5 text-blue-600 dark:text-blue-400" />
                    5. Refund Process
                </h2>
                <div class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    <p>Once the returned item is received and inspected, we will notify you of the approval or rejection of your refund. Approved refunds are processed within <strong class="text-zinc-900 dark:text-white">5-7 business days</strong> to the original payment method used at checkout via Razorpay.</p>
                    <p>Shipping charges, if any, are non-refundable unless the return is due to our error or a defective product.</p>
                </div>
            </section>

            <section id="damaged" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="exclamation-circle" class="size-5 text-blue-600 dark:text-blue-400" />
                    6. Damaged or Defective Items
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    If you receive a damaged or defective product, please contact us within <strong class="text-zinc-900 dark:text-white">48 hours</strong> of delivery with photos or a video of the item and packaging. We will arrange a free replacement, repair, or full refund at no additional cost to you, subject to verification.
                </p>
            </section>

            <section id="cancellations" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="x-circle" class="size-5 text-blue-600 dark:text-blue-400" />
                    7. Order Cancellations
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    Orders can be cancelled free of charge before they are shipped. Once an order has been dispatched, it cannot be cancelled and must instead follow the standard return process outlined above.
                </p>
            </section>

            <!-- Contact Card -->
            <section id="contact" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-900 text-white shadow-sm">
                <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
                    <flux:icon icon="chat-bubble-left-right" class="size-5 text-blue-400" />
                    8. Contact Us
                </h2>
                <p class="text-sm text-zinc-300 leading-relaxed mb-5">
                    For return requests or refund status enquiries, reach out to our support team.
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
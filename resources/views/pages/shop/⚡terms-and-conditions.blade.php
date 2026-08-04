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
        <span class="text-zinc-900 dark:text-white font-semibold">Terms &amp; Conditions</span>
    </nav>

    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-wrap items-center gap-3 mb-2">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Terms &amp; Conditions</h1>
            <span class="inline-flex items-center gap-1.5 pl-3 pr-3 py-1 rounded-full bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 text-xs font-medium">
                <flux:icon icon="calendar" class="size-3" />
                Last Updated: {{ now()->format('F j, Y') }}
            </span>
        </div>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 max-w-2xl">
            Please read these terms carefully before using our website or purchasing products from Xpert IT Solution.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Sticky Table of Contents Sidebar -->
        <div class="lg:col-span-1">
            <div class="lg:sticky lg:top-24 space-y-6">
                <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-4">On This Page</h3>
                    <nav class="space-y-1 text-sm">
                        <a href="#acceptance" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">1. Acceptance of Terms</a>
                        <a href="#eligibility" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">2. Eligibility &amp; Accounts</a>
                        <a href="#products-pricing" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">3. Products &amp; Pricing</a>
                        <a href="#orders-payments" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">4. Orders &amp; Payments</a>
                        <a href="#shipping" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">5. Shipping &amp; Delivery</a>
                        <a href="#returns" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">6. Cancellations &amp; Returns</a>
                        <a href="#warranty" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">7. Warranty</a>
                        <a href="#ip" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">8. Intellectual Property</a>
                        <a href="#liability" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">9. Limitation of Liability</a>
                        <a href="#governing-law" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">10. Governing Law</a>
                        <a href="#contact" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">11. Contact Us</a>
                    </nav>
                </div>

                <div class="p-5 rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                    <flux:icon icon="scale" class="size-6 text-blue-600 dark:text-blue-400 mb-2" />
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                        By placing an order with us, you agree to be bound by the terms set out on this page.
                    </p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="lg:col-span-3 space-y-6">

            <section id="acceptance" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="check-badge" class="size-5 text-blue-600 dark:text-blue-400" />
                    1. Acceptance of Terms
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    By accessing or using the Xpert IT Solution website, browsing our product catalog, creating an account, or placing an order, you agree to be bound by these Terms &amp; Conditions. If you do not agree with any part of these terms, please discontinue use of our website.
                </p>
            </section>

            <section id="eligibility" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="user-circle" class="size-5 text-blue-600 dark:text-blue-400" />
                    2. Eligibility &amp; Accounts
                </h2>
                <div class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    <p>You must be at least 18 years old, or be a duly authorised representative of a business entity, to place an order on our website.</p>
                    <p>You are responsible for maintaining the confidentiality of your account credentials and for all activity that occurs under your account. Notify us immediately of any unauthorised use of your account.</p>
                </div>
            </section>

            <section id="products-pricing" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="tag" class="size-5 text-blue-600 dark:text-blue-400" />
                    3. Products &amp; Pricing
                </h2>
                <div class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    <p>We make every effort to display accurate product descriptions, specifications, images, and pricing. However, we do not warrant that product descriptions or other content on the site are entirely error-free.</p>
                    <p>Prices are listed in Indian Rupees (INR) and are subject to change without prior notice. Discounts, MRP, and sale prices shown on product pages are indicative and may be revised at our discretion. In the event of a pricing error, we reserve the right to cancel or refuse any affected order.</p>
                    <p>Product availability is not guaranteed until an order is confirmed; items may go out of stock between browsing and checkout.</p>
                </div>
            </section>

            <section id="orders-payments" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="shopping-cart" class="size-5 text-blue-600 dark:text-blue-400" />
                    4. Orders &amp; Payments
                </h2>
                <div class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    <p>Placing an order constitutes an offer to purchase, which we may accept or decline at our discretion — for example, in cases of stock unavailability, pricing errors, or suspected fraudulent activity.</p>
                    <p>Payments are processed securely through Razorpay, supporting major cards, UPI, net banking, and wallets. Your order is confirmed only once payment has been successfully authorised.</p>
                </div>
            </section>

            <section id="shipping" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="truck" class="size-5 text-blue-600 dark:text-blue-400" />
                    5. Shipping &amp; Delivery
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    Estimated delivery timelines are provided at checkout and on our
                    <a href="{{ route('shop.shipping-policy') }}" class="text-blue-600 dark:text-blue-400 hover:underline" wire:navigate>Shipping Policy</a>
                    page. Delivery dates are estimates only and may be affected by courier delays, remote locations, or circumstances beyond our control.
                </p>
            </section>

            <section id="returns" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="arrow-uturn-left" class="size-5 text-blue-600 dark:text-blue-400" />
                    6. Cancellations &amp; Returns
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    Order cancellations, returns, and refunds are governed by our
                    <a href="{{ route('shop.refund-policy') }}" class="text-blue-600 dark:text-blue-400 hover:underline" wire:navigate>Refund &amp; Return Policy</a>,
                    which forms part of these Terms &amp; Conditions.
                </p>
            </section>

            <section id="warranty" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="shield-check" class="size-5 text-blue-600 dark:text-blue-400" />
                    7. Warranty
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    Where applicable, products are covered by the respective manufacturer's warranty as stated on the product page. Warranty claims are subject to the manufacturer's terms and must be accompanied by a valid proof of purchase. We assist in facilitating warranty claims but are not liable for defects arising from misuse, unauthorised repair, or normal wear and tear.
                </p>
            </section>

            <section id="ip" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="sparkles" class="size-5 text-blue-600 dark:text-blue-400" />
                    8. Intellectual Property
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    All content on this website — including the Xpert IT Solution name, logo, product descriptions, graphics, and layout — is our property or that of our licensors and is protected by applicable intellectual property laws. You may not reproduce, distribute, or use this content for commercial purposes without our prior written consent. Third-party product names, brands, and logos remain the property of their respective owners.
                </p>
            </section>

            <section id="liability" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="exclamation-triangle" class="size-5 text-blue-600 dark:text-blue-400" />
                    9. Limitation of Liability
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    To the maximum extent permitted by law, Xpert IT Solution shall not be liable for any indirect, incidental, or consequential damages arising from the use of our website or products, including but not limited to business interruption, loss of data, or loss of profits. Our total liability for any claim relating to an order shall not exceed the amount paid for that order.
                </p>
            </section>

            <section id="governing-law" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="building-library" class="size-5 text-blue-600 dark:text-blue-400" />
                    10. Governing Law
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    These Terms &amp; Conditions are governed by the laws of India. Any disputes arising out of or in connection with these terms shall be subject to the exclusive jurisdiction of the courts located in {{ shop()->state }}.
                </p>
            </section>

            <!-- Contact Card -->
            <section id="contact" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-900 text-white shadow-sm">
                <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
                    <flux:icon icon="chat-bubble-left-right" class="size-5 text-blue-400" />
                    11. Contact Us
                </h2>
                <p class="text-sm text-zinc-300 leading-relaxed mb-5">
                    For any questions regarding these Terms &amp; Conditions, please get in touch with us.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="flex items-center gap-2">
                        <flux:icon icon="envelope" class="size-4 shrink-0 text-zinc-500" />
                        <span>{{ shop()->email }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:icon icon="phone" class="size-4 shrink-0 text-zinc-500" />
                        <span>+91 {{ shop()->phone }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:icon icon="map-pin" class="size-4 shrink-0 text-zinc-500" />
                        <span>{{ shop()->address_line1 }}, {{ shop()->address_line2 }}, {{ shop()->state }}</span>
                    </div>
                </div>
            </section>

        </div>
    </div>
</main>
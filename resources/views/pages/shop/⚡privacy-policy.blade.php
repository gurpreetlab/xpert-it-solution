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
        <span class="text-zinc-900 dark:text-white font-semibold">Privacy Policy</span>
    </nav>

    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-wrap items-center gap-3 mb-2">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Privacy Policy</h1>
            <span class="inline-flex items-center gap-1.5 pl-3 pr-3 py-1 rounded-full bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 text-xs font-medium">
                <flux:icon icon="calendar" class="size-3" />
                Last Updated: {{ now()->format('F j, Y') }}
            </span>
        </div>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 max-w-2xl">
            This policy explains how Xpert IT Solution collects, uses, and protects your information when you browse our catalog or place an order.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Sticky Table of Contents Sidebar -->
        <div class="lg:col-span-1">
            <div class="lg:sticky lg:top-24 space-y-6">
                <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-4">On This Page</h3>
                    <nav class="space-y-1 text-sm">
                        <a href="#information-we-collect" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">1. Information We Collect</a>
                        <a href="#how-we-use" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">2. How We Use Your Data</a>
                        <a href="#cookies" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">3. Cookies &amp; Tracking</a>
                        <a href="#payments" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">4. Payment &amp; Order Data</a>
                        <a href="#sharing" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">5. Data Sharing</a>
                        <a href="#security" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">6. Data Security</a>
                        <a href="#your-rights" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">7. Your Rights</a>
                        <a href="#children" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">8. Children's Privacy</a>
                        <a href="#changes" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">9. Changes to This Policy</a>
                        <a href="#contact" class="block px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition">10. Contact Us</a>
                    </nav>
                </div>

                <div class="p-5 rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                    <flux:icon icon="shield-check" class="size-6 text-blue-600 dark:text-blue-400 mb-2" />
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                        Questions about your data? Reach our support team any time — see the contact section below.
                    </p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="lg:col-span-3 space-y-6">

            <section id="information-we-collect" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="identification" class="size-5 text-blue-600 dark:text-blue-400" />
                    1. Information We Collect
                </h2>
                <div class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    <p>We collect information you provide directly to us, such as your name, email address, phone number, shipping and billing address, and company details when you register an account, place an order, or contact our support team.</p>
                    <p>We also automatically collect certain technical information when you use our website, including your IP address, browser type, device information, and pages visited, to help us operate and improve the catalog experience.</p>
                    <p>If you submit a product review, the review content and star rating are stored against your account and displayed publicly on the relevant product page.</p>
                </div>
            </section>

            <section id="how-we-use" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="cog-6-tooth" class="size-5 text-blue-600 dark:text-blue-400" />
                    2. How We Use Your Data
                </h2>
                <div class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    <p>We use the information we collect to:</p>
                    <ul class="list-disc list-inside space-y-1.5 marker:text-blue-500">
                        <li>Process and fulfil your orders, including billing, shipping, and after-sales support</li>
                        <li>Create and manage your customer account and order history</li>
                        <li>Respond to enquiries, warranty claims, and support requests</li>
                        <li>Send order confirmations, shipping updates, and service notifications</li>
                        <li>Improve our product catalog, website performance, and customer experience</li>
                        <li>Detect, investigate, and prevent fraudulent or unauthorised transactions</li>
                    </ul>
                </div>
            </section>

            <section id="cookies" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="cake" class="size-5 text-blue-600 dark:text-blue-400" />
                    3. Cookies &amp; Tracking
                </h2>
                <div class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    <p>We use essential cookies to keep you logged in, remember items in your cart, and maintain your filter and sort preferences while browsing the product catalog. These are required for the site to function correctly.</p>
                    <p>We may also use analytics cookies to understand how visitors use our site so we can improve navigation and product discovery. You can control or disable cookies through your browser settings, though some features may not work as intended if you do so.</p>
                </div>
            </section>

            <section id="payments" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="credit-card" class="size-5 text-blue-600 dark:text-blue-400" />
                    4. Payment &amp; Order Data
                </h2>
                <div class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    <p>Payments made on our website are processed securely through Razorpay. We do not store your full card, UPI, or net-banking credentials on our servers — this data is handled directly by our payment gateway partner under their own security and privacy standards.</p>
                    <p>We retain order records, invoices, and transaction references for accounting, warranty, and legal compliance purposes.</p>
                </div>
            </section>

            <section id="sharing" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="share" class="size-5 text-blue-600 dark:text-blue-400" />
                    5. Data Sharing
                </h2>
                <div class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    <p>We do not sell your personal information. We share data only with trusted third parties where necessary to operate our business, including:</p>
                    <ul class="list-disc list-inside space-y-1.5 marker:text-blue-500">
                        <li>Payment processors (e.g. Razorpay) to complete transactions</li>
                        <li>Courier and logistics partners to deliver your orders</li>
                        <li>IT hosting and infrastructure providers who support our website</li>
                        <li>Regulatory or law enforcement authorities, where legally required</li>
                    </ul>
                </div>
            </section>

            <section id="security" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="lock-closed" class="size-5 text-blue-600 dark:text-blue-400" />
                    6. Data Security
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    We apply industry-standard technical and organisational safeguards — including encrypted connections, access controls, and secure hosting — to protect your information against unauthorised access, alteration, or disclosure. No method of transmission over the internet is completely secure, so we cannot guarantee absolute security.
                </p>
            </section>

            <section id="your-rights" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="finger-print" class="size-5 text-blue-600 dark:text-blue-400" />
                    7. Your Rights
                </h2>
                <div class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    <p>You may access, update, or correct your account details at any time from your profile settings. You may also request a copy of the personal data we hold about you, or ask us to delete your account and associated data, subject to our legal and accounting retention obligations.</p>
                    <p>To exercise any of these rights, contact us using the details in Section 10.</p>
                </div>
            </section>

            <section id="children" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="user-group" class="size-5 text-blue-600 dark:text-blue-400" />
                    8. Children's Privacy
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    Our website and products are intended for business and adult consumer use. We do not knowingly collect personal information from individuals under the age of 18. If we become aware that we have inadvertently collected such data, we will take steps to delete it.
                </p>
            </section>

            <section id="changes" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                    <flux:icon icon="arrow-path" class="size-5 text-blue-600 dark:text-blue-400" />
                    9. Changes to This Policy
                </h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                    We may update this Privacy Policy from time to time to reflect changes in our practices or legal requirements. The "Last Updated" date at the top of this page indicates when the policy was last revised. Continued use of our website after changes are posted constitutes acceptance of the updated policy.
                </p>
            </section>

            <!-- Contact Card -->
            <section id="contact" class="scroll-mt-24 p-6 sm:p-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-900 text-white shadow-sm">
                <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
                    <flux:icon icon="chat-bubble-left-right" class="size-5 text-blue-400" />
                    10. Contact Us
                </h2>
                <p class="text-sm text-zinc-300 leading-relaxed mb-5">
                    If you have any questions about this Privacy Policy or how we handle your data, please reach out to us.
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
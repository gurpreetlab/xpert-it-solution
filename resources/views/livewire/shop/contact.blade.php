<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition" wire:navigate>Home</a>
        <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
        <span class="text-zinc-900 dark:text-white font-semibold">Contact Us</span>
    </nav>

    <!-- Hero -->
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-zinc-900 to-zinc-950 mb-12">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:20px_20px]"></div>
        <div class="absolute -top-10 -right-10 size-64 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 size-64 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 px-6 sm:px-12 py-14 sm:py-16 text-center max-w-2xl mx-auto">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/20 backdrop-blur-xs text-xs font-semibold uppercase tracking-wider text-blue-300 mb-5">
                <flux:icon icon="chat-bubble-left-right" class="size-3.5" />
                We'd Love to Hear From You
            </span>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-white mb-4">Get in Touch</h1>
            <p class="text-sm sm:text-base text-zinc-400 leading-relaxed">
                Have a question about a product, need a bulk quote, or want help planning your infrastructure? Our team typically responds within one business day.
            </p>
        </div>
    </section>

    <!-- Quick Contact Info Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-12">
        <a href="mailto:{{ config('shop.company.email') }}" class="group p-5 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm flex items-center gap-4 hover:border-blue-300 dark:hover:border-blue-800 hover:shadow-md transition">
            <div class="size-11 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <flux:icon icon="envelope" class="size-5 text-blue-600 dark:text-blue-400" />
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Email Us</p>
                <p class="text-sm font-bold text-zinc-900 dark:text-white truncate">{{ config('shop.company.email') }}</p>
            </div>
        </a>

        <a href="tel:+91{{ config('shop.company.phone') }}" class="group p-5 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm flex items-center gap-4 hover:border-blue-300 dark:hover:border-blue-800 hover:shadow-md transition">
            <div class="size-11 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <flux:icon icon="phone" class="size-5 text-blue-600 dark:text-blue-400" />
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Call Us</p>
                <p class="text-sm font-bold text-zinc-900 dark:text-white truncate">+91 {{ config('shop.company.phone') }}</p>
            </div>
        </a>

        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm flex items-center gap-4">
            <div class="size-11 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center shrink-0">
                <flux:icon icon="map-pin" class="size-5 text-blue-600 dark:text-blue-400" />
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Visit Us</p>
                <p class="text-sm font-bold text-zinc-900 dark:text-white truncate">{{ config('shop.company.address_line1') . ', ' . config('shop.company.state') }}</p>
            </div>
        </div>
    </section>

    <!-- Form + Sidebar -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">

        <!-- Contact Form -->
        <div class="lg:col-span-7">
            <div class="p-6 sm:p-8 rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-1">Send Us a Message</h2>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-6">Fill out the form below and our team will get back to you shortly.</p>

                @if (session('success'))
                    <div class="flex items-start gap-3 p-4 mb-6 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900 text-emerald-700 dark:text-emerald-400">
                        <flux:icon icon="check-circle" class="size-5 shrink-0 mt-0.5" />
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <form wire:submit="submit" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1.5">Full Name</label>
                            <input
                                type="text"
                                id="name"
                                wire:model="name"
                                placeholder="John Doe"
                                class="w-full text-sm border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 dark:text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                            @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1.5">Email Address</label>
                            <input
                                type="email"
                                id="email"
                                wire:model="email"
                                placeholder="john@company.com"
                                class="w-full text-sm border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 dark:text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                            @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1.5">Phone Number</label>
                            <input
                                type="tel"
                                id="phone"
                                wire:model="phone"
                                placeholder="+91 98765 43210"
                                class="w-full text-sm border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 dark:text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                            @error('phone') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="subject" class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1.5">Subject</label>
                            <select
                                id="subject"
                                wire:model="subject"
                                class="w-full text-sm border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 dark:text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                <option value="">Select a topic</option>
                                <option value="sales">Sales Enquiry</option>
                                <option value="bulk_order">Bulk / Corporate Order</option>
                                <option value="support">Product Support</option>
                                <option value="other">Something Else</option>
                            </select>
                            @error('subject') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="message" class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1.5">Message</label>
                        <textarea
                            id="message"
                            wire:model="message"
                            rows="5"
                            placeholder="Tell us a bit about what you need..."
                            class="w-full text-sm border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 dark:text-white rounded-xl p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"></textarea>
                        @error('message') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <flux:button type="submit" variant="filled" class="bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium w-full sm:w-auto cursor-pointer" wire:loading.attr="disabled" wire:target="submit">
                        <span wire:loading.remove wire:target="submit">Send Message</span>
                        <span wire:loading wire:target="submit">Sending...</span>
                    </flux:button>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-5 space-y-6">

            <!-- Business Hours -->
            <div class="p-6 rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="size-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center shrink-0">
                        <flux:icon icon="clock" class="size-5 text-blue-600 dark:text-blue-400" />
                    </div>
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Business Hours</h3>
                </div>
                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">Monday – Saturday</span>
                        <span class="font-semibold text-zinc-900 dark:text-white">9:00 AM – 7:00 PM</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">Sunday</span>
                        <span class="font-semibold text-zinc-900 dark:text-white">Closed</span>
                    </div>
                    <div class="flex items-center justify-between pt-2.5 border-t border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400">Support</span>
                        <span class="inline-flex items-center gap-1.5 font-semibold text-emerald-600 dark:text-emerald-400">
                            <span class="size-1.5 rounded-full bg-emerald-500"></span>
                            24/7 Available
                        </span>
                    </div>
                </div>
            </div>

            <!-- Full Address -->
            <div class="p-6 rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="size-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center shrink-0">
                        <flux:icon icon="building-office-2" class="size-5 text-blue-600 dark:text-blue-400" />
                    </div>
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Head Office</h3>
                </div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                    {{ config('shop.company.address_line1') }}<br>
                    {{ config('shop.company.address_line2') }}<br>
                    {{ config('shop.company.state') }}
                </p>
            </div>

            <!-- Map Placeholder -->
            <div class="relative aspect-video rounded-3xl overflow-hidden bg-gradient-to-br from-zinc-800 to-zinc-950 flex items-center justify-center">
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:20px_20px]"></div>
                <div class="relative z-10 p-6 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md text-center">
                    <flux:icon icon="map" class="size-8 text-white mx-auto mb-2" />
                    <p class="text-xs font-semibold text-white/80">Find Us on the Map</p>
                </div>
            </div>
        </div>
    </section>

</main>
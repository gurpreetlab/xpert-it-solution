<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 mb-8">
        <a href="{{ route('home') }}" class="hover:text-[#003d29] transition" wire:navigate>Home</a>
        <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
        <span class="text-zinc-900 font-semibold">Contact Us</span>
    </nav>

    <!-- Header Section -->
    <div class="mb-10 text-center max-w-2xl mx-auto">
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-zinc-900 mb-3">Get in Touch</h1>
        <p class="text-sm text-zinc-500 font-normal leading-relaxed">
            Have a question about a product, need help with your order, or want to speak with our support team? Fill out the form below.
        </p>
    </div>

    <!-- Quick Contact Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-12">
        <a href="mailto:{{ shop()->email }}" class="group p-5 rounded-2xl bg-[#f5f6f6] border border-transparent hover:border-zinc-300 flex items-center gap-4 transition">
            <div class="size-11 rounded-xl bg-white flex items-center justify-center shrink-0 shadow-xs text-[#003d29]">
                <flux:icon icon="envelope" class="size-5" />
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-wider text-zinc-400">Email Us</p>
                <p class="text-sm font-bold text-zinc-900 truncate">{{ shop()->email }}</p>
            </div>
        </a>

        <a href="tel:+001234567890" class="group p-5 rounded-2xl bg-[#f5f6f6] border border-transparent hover:border-zinc-300 flex items-center gap-4 transition">
            <div class="size-11 rounded-xl bg-white flex items-center justify-center shrink-0 shadow-xs text-[#003d29]">
                <flux:icon icon="phone" class="size-5" />
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-wider text-zinc-400">Call Us</p>
                <p class="text-sm font-bold text-zinc-900 truncate">+001234567890</p>
            </div>
        </a>

        <div class="p-5 rounded-2xl bg-[#f5f6f6] flex items-center gap-4">
            <div class="size-11 rounded-xl bg-white flex items-center justify-center shrink-0 shadow-xs text-[#003d29]">
                <flux:icon icon="map-pin" class="size-5" />
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-wider text-zinc-400">Visit Us</p>
                <p class="text-sm font-bold text-zinc-900 truncate">{{ shop()->address_line1 }}, {{ shop()->state }}</p>
            </div>
        </div>
    </section>

    <!-- Form + Info -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">

        <!-- Contact Form -->
        <div class="lg:col-span-7">
            <div class="p-6 sm:p-8 rounded-2xl border border-zinc-200 bg-white shadow-xs">
                <h2 class="text-lg font-bold text-zinc-900 mb-1">Send Us a Message</h2>
                <p class="text-xs text-zinc-500 mb-6">Fill out the form below and our team will get back to you shortly.</p>

                @if (session('success'))
                    <div class="flex items-start gap-3 p-4 mb-6 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800">
                        <flux:icon icon="check-circle" class="size-5 shrink-0 mt-0.5" />
                        <p class="text-xs font-semibold">{{ session('success') }}</p>
                    </div>
                @endif

                <form wire:submit="submit" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-xs font-bold text-zinc-700 mb-1">Full Name</label>
                            <input
                                type="text"
                                id="name"
                                wire:model="name"
                                placeholder="John Doe"
                                class="w-full text-xs bg-[#f5f6f6] border-0 rounded-full px-4 py-2.5 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-[#003d29] transition" />
                            @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-zinc-700 mb-1">Email Address</label>
                            <input
                                type="email"
                                id="email"
                                wire:model="email"
                                placeholder="john@company.com"
                                class="w-full text-xs bg-[#f5f6f6] border-0 rounded-full px-4 py-2.5 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-[#003d29] transition" />
                            @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-xs font-bold text-zinc-700 mb-1">Phone Number</label>
                            <input
                                type="tel"
                                id="phone"
                                wire:model="phone"
                                placeholder="+001234567890"
                                class="w-full text-xs bg-[#f5f6f6] border-0 rounded-full px-4 py-2.5 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-[#003d29] transition" />
                            @error('phone') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="subject" class="block text-xs font-bold text-zinc-700 mb-1">Subject</label>
                            <select
                                id="subject"
                                wire:model="subject"
                                class="w-full text-xs bg-[#f5f6f6] border-0 rounded-full px-4 py-2.5 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-[#003d29] transition">
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
                        <label for="message" class="block text-xs font-bold text-zinc-700 mb-1">Message</label>
                        <textarea
                            id="message"
                            wire:model="message"
                            rows="4"
                            placeholder="How can we help you?"
                            class="w-full text-xs bg-[#f5f6f6] border-0 rounded-2xl p-4 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-[#003d29] transition"></textarea>
                        @error('message') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="bg-[#003d29] hover:bg-[#062d1f] text-white font-semibold text-xs py-3 px-8 rounded-full transition shadow-xs cursor-pointer" wire:loading.attr="disabled" wire:target="submit">
                        <span wire:loading.remove wire:target="submit">Send Message</span>
                        <span wire:loading wire:target="submit">Sending...</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-5 space-y-6">

            <div class="p-6 rounded-2xl border border-zinc-200 bg-white shadow-xs">
                <h3 class="text-sm font-bold text-zinc-900 mb-3">Business Hours</h3>
                <div class="space-y-2 text-xs font-medium">
                    <div class="flex items-center justify-between text-zinc-600">
                        <span>Monday – Saturday</span>
                        <span class="font-bold text-zinc-900">9:00 AM – 7:00 PM</span>
                    </div>
                    <div class="flex items-center justify-between text-zinc-600">
                        <span>Sunday</span>
                        <span class="font-bold text-zinc-900">Closed</span>
                    </div>
                </div>
            </div>

            <div class="p-6 rounded-2xl border border-zinc-200 bg-white shadow-xs">
                <h3 class="text-sm font-bold text-zinc-900 mb-2">Shopcart Head Office</h3>
                <p class="text-xs text-zinc-500 leading-relaxed font-normal">
                    {{ shop()->address_line1 }}<br>
                    {{ shop()->state }}
                </p>
            </div>

        </div>
    </section>

</main>
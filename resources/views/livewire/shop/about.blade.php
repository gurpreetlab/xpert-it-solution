<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 mb-8">
        <a href="{{ route('home') }}" class="hover:text-primary transition" wire:navigate>Home</a>
        <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
        <span class="text-zinc-900 font-semibold">About Us</span>
    </nav>

    <!-- Hero -->
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-50 via-slate-50 to-indigo-50 border border-blue-100 mb-16">
        <div class="relative z-10 px-6 sm:px-12 py-16 sm:py-20 text-center max-w-3xl mx-auto">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 border border-primary/20 text-xs font-bold uppercase tracking-wider text-primary mb-5">
                <flux:icon icon="building-office-2" class="size-3.5" />
                About Xpert IT Solution
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-zinc-900 mb-4 leading-tight">
                Building Reliable IT Infrastructure for Growing Businesses
            </h1>
            <p class="text-sm sm:text-base text-zinc-600 leading-relaxed font-normal">
                {{ shop()->name }} is a premium supplier of IT infrastructure, CCTV surveillance, networking systems, enterprise back-ups, and storage solutions — trusted by businesses who need authentic equipment backed by manufacturer warranty.
            </p>
        </div>
    </section>

    <!-- Stats -->
    <section class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-16">
        <div class="p-5 rounded-2xl border border-border bg-surface shadow-2xs text-center">
            <p class="text-2xl sm:text-3xl font-black text-zinc-950">10+</p>
            <p class="text-xs text-zinc-500 font-medium mt-1">Years of Experience</p>
        </div>
        <div class="p-5 rounded-2xl border border-border bg-surface shadow-2xs text-center">
            <p class="text-2xl sm:text-3xl font-black text-zinc-950">500+</p>
            <p class="text-xs text-zinc-500 font-medium mt-1">Products Delivered</p>
        </div>
        <div class="p-5 rounded-2xl border border-border bg-surface shadow-2xs text-center">
            <p class="text-2xl sm:text-3xl font-black text-zinc-950">1,200+</p>
            <p class="text-xs text-zinc-500 font-medium mt-1">Corporate Clients</p>
        </div>
        <div class="p-5 rounded-2xl border border-border bg-surface shadow-2xs text-center">
            <p class="text-2xl sm:text-3xl font-black text-zinc-950">24/7</p>
            <p class="text-xs text-zinc-500 font-medium mt-1">Support Availability</p>
        </div>
    </section>

    <!-- Our Story -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-16 items-center">
        <div class="lg:col-span-6">
            <div class="relative aspect-4/3 rounded-3xl overflow-hidden bg-gradient-to-br from-blue-900 to-zinc-950 flex items-center justify-center p-8">
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:20px_20px]"></div>
                <div class="absolute size-40 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>
                <div class="relative z-10 p-8 rounded-3xl bg-white/10 border border-white/20 backdrop-blur-md shadow-2xl">
                    <flux:icon icon="cube-transparent" class="size-16 text-white" />
                </div>
            </div>
        </div>

        <div class="lg:col-span-6 space-y-4">
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Our Story</h2>
            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                What started as a small IT hardware supplier has grown into a trusted name for enterprise-grade networking, surveillance, and storage equipment. We work directly with leading brands to bring genuine, warrantied products to businesses of every size.
            </p>
            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                Our team of engineers and consultants doesn't just sell hardware — we help you choose the right setup, install it correctly, and support it for the long run.
            </p>

            <div class="grid grid-cols-2 gap-4 pt-4">
                <div class="flex items-start gap-3">
                    <flux:icon icon="check-badge" class="size-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" />
                    <span class="text-xs text-zinc-600 dark:text-zinc-400">Genuine, warrantied hardware</span>
                </div>
                <div class="flex items-start gap-3">
                    <flux:icon icon="check-badge" class="size-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" />
                    <span class="text-xs text-zinc-600 dark:text-zinc-400">Expert pre-sales consultation</span>
                </div>
                <div class="flex items-start gap-3">
                    <flux:icon icon="check-badge" class="size-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" />
                    <span class="text-xs text-zinc-600 dark:text-zinc-400">On-site installation support</span>
                </div>
                <div class="flex items-start gap-3">
                    <flux:icon icon="check-badge" class="size-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" />
                    <span class="text-xs text-zinc-600 dark:text-zinc-400">Responsive after-sales service</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Domains -->
    <section class="mb-16">
        <div class="mb-6 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900">What We Specialize In</h2>
            <p class="text-sm text-zinc-500 mt-1">Enterprise hardware across every corner of your infrastructure.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="p-5 rounded-2xl border border-border bg-surface shadow-2xs text-center hover:shadow-md hover:-translate-y-0.5 transition">
                <div class="size-11 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-3">
                    <flux:icon icon="wifi" class="size-5 text-primary" />
                </div>
                <p class="text-xs font-bold text-zinc-800">Networking</p>
            </div>
            <div class="p-5 rounded-2xl border border-border bg-surface shadow-2xs text-center hover:shadow-md hover:-translate-y-0.5 transition">
                <div class="size-11 rounded-xl bg-emerald-50 flex items-center justify-center mx-auto mb-3">
                    <flux:icon icon="video-camera" class="size-5 text-emerald-600" />
                </div>
                <p class="text-xs font-bold text-zinc-800">CCTV & Security</p>
            </div>
            <div class="p-5 rounded-2xl border border-border bg-surface shadow-2xs text-center hover:shadow-md hover:-translate-y-0.5 transition">
                <div class="size-11 rounded-xl bg-purple-50 flex items-center justify-center mx-auto mb-3">
                    <flux:icon icon="circle-stack" class="size-5 text-purple-600" />
                </div>
                <p class="text-xs font-bold text-zinc-800">Storage</p>
            </div>
            <div class="p-5 rounded-2xl border border-border bg-surface shadow-2xs text-center hover:shadow-md hover:-translate-y-0.5 transition">
                <div class="size-11 rounded-xl bg-amber-50 flex items-center justify-center mx-auto mb-3">
                    <flux:icon icon="computer-desktop" class="size-5 text-amber-600" />
                </div>
                <p class="text-xs font-bold text-zinc-800">Peripherals</p>
            </div>
            <div class="p-5 rounded-2xl border border-border bg-surface shadow-2xs text-center hover:shadow-md hover:-translate-y-0.5 transition">
                <div class="size-11 rounded-xl bg-orange-50 flex items-center justify-center mx-auto mb-3">
                    <flux:icon icon="bolt" class="size-5 text-orange-600" />
                </div>
                <p class="text-xs font-bold text-zinc-800">Power & Accessories</p>
            </div>
            <div class="p-5 rounded-2xl border border-border bg-surface shadow-2xs text-center hover:shadow-md hover:-translate-y-0.5 transition">
                <div class="size-11 rounded-xl bg-indigo-50 flex items-center justify-center mx-auto mb-3">
                    <flux:icon icon="printer" class="size-5 text-indigo-600" />
                </div>
                <p class="text-xs font-bold text-zinc-800">Printing</p>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="mb-16">
        <div class="mb-6 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Why Businesses Choose Us</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">A few reasons our clients keep coming back.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <div class="size-11 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center mb-4">
                    <flux:icon icon="shield-check" class="size-5 text-blue-600 dark:text-blue-400" />
                </div>
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white mb-1.5">100% Genuine Products</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">Every product is sourced directly from authorized brand partners with full manufacturer warranty.</p>
            </div>

            <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <div class="size-11 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center mb-4">
                    <flux:icon icon="truck" class="size-5 text-blue-600 dark:text-blue-400" />
                </div>
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white mb-1.5">Fast, Reliable Delivery</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">Well-stocked inventory and dependable logistics mean your infrastructure projects stay on schedule.</p>
            </div>

            <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <div class="size-11 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center mb-4">
                    <flux:icon icon="users" class="size-5 text-blue-600 dark:text-blue-400" />
                </div>
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white mb-1.5">Expert Consultation</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">Our engineers help you spec the right solution instead of just selling you the most expensive one.</p>
            </div>

            <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <div class="size-11 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center mb-4">
                    <flux:icon icon="wrench-screwdriver" class="size-5 text-blue-600 dark:text-blue-400" />
                </div>
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white mb-1.5">Installation & Support</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">On-site setup and responsive after-sales support keep your systems running smoothly.</p>
            </div>

            <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <div class="size-11 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center mb-4">
                    <flux:icon icon="currency-rupee" class="size-5 text-blue-600 dark:text-blue-400" />
                </div>
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white mb-1.5">Transparent Pricing</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">No hidden costs — what you see is what you pay, with bulk pricing available for larger orders.</p>
            </div>

            <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <div class="size-11 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center mb-4">
                    <flux:icon icon="chat-bubble-left-right" class="size-5 text-blue-600 dark:text-blue-400" />
                </div>
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white mb-1.5">Dedicated Support Team</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">A real team you can call or email — no bots, no endless ticket queues.</p>
            </div>
        </div>
    </section>

    <!-- CTA Banner -->
    <section class="relative overflow-hidden rounded-3xl bg-surface border border-border p-8 sm:p-12 text-center shadow-2xs">
        <div class="relative z-10 max-w-xl mx-auto">
            <h2 class="text-xl sm:text-2xl font-bold text-zinc-900 mb-2">Ready to upgrade your IT infrastructure?</h2>
            <p class="text-sm text-zinc-600 mb-6">Explore our full enterprise catalog or get in touch with our team.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('shop.products') }}" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-hover text-white text-xs font-bold transition shadow-xs w-full sm:w-auto" wire:navigate>
                    Browse Products
                </a>
                <a href="{{ route('contact') }}" class="px-5 py-2.5 rounded-xl bg-surface-muted hover:bg-zinc-200 text-zinc-800 text-xs font-bold border border-border transition w-full sm:w-auto" wire:navigate>
                    Contact Us
                </a>
            </div>
        </div>
    </section>

</main>
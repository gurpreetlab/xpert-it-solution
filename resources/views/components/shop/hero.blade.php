@props(['search'])

<section class="relative overflow-hidden rounded-3xl bg-zinc-950 text-white py-16 px-6 sm:px-12 lg:px-16 mb-16 shadow-2xl border border-zinc-900">
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-blue-500/20 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-indigo-600/15 blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/3 w-72 h-72 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-3xl mx-auto text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-zinc-900 text-blue-400 border border-zinc-800 mb-6">
            <span class="size-2 rounded-full bg-blue-500 animate-pulse"></span>
            Enterprise IT & Security Hardware Partner
        </span>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white mb-6 leading-tight">
            Enterprise-Grade <span class="bg-gradient-to-r from-blue-400 via-indigo-300 to-purple-400 bg-clip-text text-transparent">IT Solutions</span> for Modern Businesses
        </h1>

        <p class="text-base sm:text-lg text-zinc-300 mb-10 max-w-2xl mx-auto leading-relaxed">
            Discover top-tier networking hardware, advanced CCTV surveillance, resilient power backups, high-speed storage drives, and premium computing essentials.
        </p>

        <div class="max-w-xl mx-auto mb-12">
            <div class="flex gap-2 p-1.5 bg-zinc-900/90 backdrop-blur-sm border border-zinc-800 rounded-xl shadow-xl focus-within:ring-2 focus-within:ring-blue-500 transition-all">
                <div class="flex-1 flex items-center px-3">
                    <flux:icon icon="magnifying-glass" class="size-5 text-zinc-400 mr-2 shrink-0" />
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search cameras, routers, hard disks, monitors..."
                        class="w-full bg-transparent border-0 text-white placeholder-zinc-500 focus:outline-none focus:ring-0 text-sm py-2"
                        id="hero-search-input"
                    />
                </div>
                <flux:button href="#products" class="bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium px-5 rounded-lg text-sm transition">
                    Browse Store
                </flux:button>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-8 border-t border-zinc-900 max-w-4xl mx-auto">
            <div>
                <div class="text-3xl font-extrabold text-white">43+</div>
                <div class="text-xs text-zinc-400 uppercase tracking-wider font-semibold mt-1">Leading Brands</div>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-white">50+</div>
                <div class="text-xs text-zinc-400 uppercase tracking-wider font-semibold mt-1">Tech Categories</div>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-white">100%</div>
                <div class="text-xs text-zinc-400 uppercase tracking-wider font-semibold mt-1">Genuine Products</div>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-white">24/7</div>
                <div class="text-xs text-zinc-400 uppercase tracking-wider font-semibold mt-1">IT Expert Support</div>
            </div>
        </div>
    </div>
</section>

<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 mb-2">
        <a href="{{ route('home') }}" class="hover:text-primary transition" wire:navigate>Home</a>
        <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
        <span class="text-zinc-900 font-semibold">Bulk Purchasing & B2B Quotes</span>
    </nav>

    <!-- Hero Header -->
    <div class="bg-gradient-to-r from-slate-900 to-blue-950 text-white rounded-2xl p-6 sm:p-8 space-y-3 shadow-sm">
        <span class="px-2.5 py-1 rounded-md bg-blue-500/20 text-blue-300 text-xs font-bold border border-blue-400/30 inline-block">
            Corporate Procurement & System Integrators
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Request Corporate Bulk Pricing & Tax Invoices</h1>
        <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
            Buying hardware for offices, schools, cyber cafes, or infrastructure deployments? Get volume pricing, GST invoices, and dedicated account support.
        </p>
    </div>

    <!-- Form & Benefits -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Form (Cols 1-7) -->
        <div class="lg:col-span-7 bg-surface rounded-2xl border border-border p-6 shadow-2xs space-y-6">
            <h2 class="text-lg font-bold text-zinc-900">Request a Custom Quote</h2>

            <form wire:submit="submitQuote" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1">Company / Business Name</label>
                        <flux:input wire:model="company_name" placeholder="e.g. Acme Tech Solutions Pvt Ltd" />
                        @error('company_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1">GSTIN (Optional)</label>
                        <flux:input wire:model="gstin" placeholder="22AAAAA0000A1Z5" />
                        @error('gstin') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1">Contact Person</label>
                        <flux:input wire:model="contact_person" placeholder="Full Name" />
                        @error('contact_person') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1">Email Address</label>
                        <flux:input type="email" wire:model="email" placeholder="procurement@company.com" />
                        @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1">Phone Number</label>
                        <flux:input wire:model="phone" placeholder="+91 9876543210" />
                        @error('phone') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1">Product Model / Category Required</label>
                        <flux:input wire:model="product_requirement" placeholder="e.g. TP-Link Archer AX55 or 1TB NVMe SSDs" />
                        @error('product_requirement') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1">Quantity Needed</label>
                        <flux:input type="number" wire:model="quantity" min="1" />
                        @error('quantity') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1">Additional Requirements / Specs</label>
                    <textarea
                        wire:model="additional_notes"
                        rows="3"
                        placeholder="Mention any delivery timelines, warranty requirements or payment terms..."
                        class="w-full text-xs p-3 rounded-xl border border-border bg-surface-muted text-zinc-900 focus:outline-hidden focus:ring-2 focus:ring-primary transition"></textarea>
                </div>

                <button
                    type="submit"
                    class="w-full py-3 rounded-xl bg-primary hover:bg-primary-hover text-white font-bold text-xs transition cursor-pointer shadow-2xs">
                    Submit Bulk Quote Request
                </button>
            </form>
        </div>

        <!-- B2B Benefits (Cols 8-12) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="p-6 rounded-2xl border border-border bg-surface space-y-4">
                <h3 class="text-base font-bold text-zinc-900">Why Partner With Us?</h3>

                <div class="space-y-3 text-xs text-zinc-600">
                    <div class="flex items-start gap-3">
                        <flux:icon icon="shield-check" class="size-5 text-primary shrink-0 mt-0.5" />
                        <div>
                            <strong class="text-zinc-900 block font-bold">GST Tax Invoices</strong>
                            <span>All corporate orders receive GST tax invoices for input tax credit claims.</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <flux:icon icon="arrow-trending-down" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                        <div>
                            <strong class="text-zinc-900 block font-bold">Tiered Volume Discounts</strong>
                            <span>Significant price reductions on bulk orders starting from 10+ units.</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <flux:icon icon="user" class="size-5 text-purple-600 shrink-0 mt-0.5" />
                        <div>
                            <strong class="text-zinc-900 block font-bold">Dedicated B2B Manager</strong>
                            <span>Assigned technical manager to assist with deployment and warranty claims.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

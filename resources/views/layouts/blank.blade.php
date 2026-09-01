<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-background text-zinc-900 antialiased selection:bg-primary selection:text-white transition-colors duration-200 pb-16 md:pb-0">

        <div class="w-full">
            <!-- Header Navigation -->
            <header class="sticky top-0 z-40 w-full border-b border-border bg-surface/90 backdrop-blur-md transition-colors duration-200">
                <div class="mx-auto flex max-w-7xl h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-6">
                        <!-- Brand Logo & Name -->
                        <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                            <div class="flex aspect-square size-9 items-center justify-center rounded-xl bg-primary text-white shadow-2xs group-hover:scale-105 transition-transform duration-200 font-black text-sm">
                                IT
                            </div>
                            <span class="text-lg sm:text-xl font-extrabold tracking-tight text-zinc-900">
                                Xpert <span class="text-primary">IT Solution</span>
                            </span>
                        </a>
                    </div>

                    <!-- Main Nav Links -->
                    <nav class="hidden md:flex items-center gap-6 text-xs font-bold text-zinc-600">
                        <a href="{{ route('home') }}" class="hover:text-primary transition" wire:navigate>Home</a>
                        <a href="{{ route('shop.products') }}" class="hover:text-primary transition" wire:navigate>Catalog</a>
                        <a href="{{ route('shop.bulk-orders') }}" class="hover:text-primary transition" wire:navigate>Bulk Quotes</a>
                        <a href="{{ route('about') }}" class="hover:text-primary transition" wire:navigate>About</a>
                        <a href="{{ route('contact') }}" class="hover:text-primary transition" wire:navigate>Contact</a>
                    </nav>

                    <!-- Utility Actions & Profile -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('shop.compare') }}" class="relative text-zinc-600 hover:text-primary transition cursor-pointer p-1.5 rounded-lg hover:bg-surface-muted" title="Compare Products" wire:navigate aria-label="Compare Products">
                            <flux:icon icon="scale" class="size-5" />
                            @if(count(session()->get('compared_product_ids', [])) > 0)
                                <span class="-right-1 -top-1 absolute bg-primary text-white text-[9px] font-extrabold flex h-4 w-4 items-center justify-center rounded-full">
                                    {{ count(session()->get('compared_product_ids', [])) }}
                                </span>
                            @endif
                        </a>

                        <livewire:shop.partials.wishlist-count />
                        <livewire:shop._partials.cart-count />

                        @if (Route::has('login'))
                            @auth
                                <flux:dropdown>
                                    <flux:profile avatar="" name="{{ Auth::user()->name }}" class="cursor-pointer" />

                                    <flux:navmenu>
                                        @role('super-admin')
                                            <flux:navmenu.item href="{{ route('dashboard') }}" icon="home">{{ __('Dashboard') }}</flux:navmenu.item>
                                        @endrole

                                        @role('customer')
                                            <flux:navmenu.item href="{{ route('shop.orders') }}" icon="shopping-bag" wire:navigate>{{ __('My Orders') }}</flux:navmenu.item>
                                            <flux:navmenu.item href="{{ route('shop.wishlist') }}" icon="heart" wire:navigate>{{ __('My Wishlist') }}</flux:navmenu.item>
                                            <flux:navmenu.item href="{{ route('shop.compare') }}" icon="scale" wire:navigate>{{ __('Compare List') }}</flux:navmenu.item>
                                        @endrole

                                        <flux:navmenu.item href="{{ route('profile.edit') }}" icon="user-circle">{{ __('Profile') }}</flux:navmenu.item>

                                        <flux:menu.separator />

                                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                                            @csrf
                                            <flux:navmenu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer">
                                                {{ __('Log out') }}
                                            </flux:navmenu.item>
                                        </form>
                                    </flux:navmenu>
                                </flux:dropdown>
                            @else
                                <flux:button href="{{ route('login') }}" variant="ghost" size="sm" class="text-xs font-bold text-zinc-600 hover:bg-surface-muted" wire:navigate>
                                    Log in
                                </flux:button>
                                @if (Route::has('register'))
                                    <flux:button href="{{ route('register') }}" variant="filled" size="sm" class="text-xs font-bold bg-primary hover:bg-primary-hover text-white border-0" wire:navigate>
                                        Register
                                    </flux:button>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            {{ $slot }}

            <!-- Mobile Sticky Bottom Navigation -->
            <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-surface/95 backdrop-blur-md border-t border-border flex items-center justify-around h-14 px-2 shadow-lg">
                <a href="{{ route('home') }}" wire:navigate class="flex flex-col items-center justify-center text-zinc-600 hover:text-primary transition p-1">
                    <flux:icon icon="home" class="size-4" />
                    <span class="text-[9px] font-bold mt-0.5">Home</span>
                </a>
                <a href="{{ route('shop.products') }}" wire:navigate class="flex flex-col items-center justify-center text-zinc-600 hover:text-primary transition p-1">
                    <flux:icon icon="squares-2x2" class="size-4" />
                    <span class="text-[9px] font-bold mt-0.5">Catalog</span>
                </a>
                <a href="{{ route('shop.wishlist') }}" wire:navigate class="flex flex-col items-center justify-center text-zinc-600 hover:text-primary transition p-1">
                    <flux:icon icon="heart" class="size-4" />
                    <span class="text-[9px] font-bold mt-0.5">Wishlist</span>
                </a>
                <a href="{{ route('shop.compare') }}" wire:navigate class="flex flex-col items-center justify-center text-zinc-600 hover:text-primary transition p-1">
                    <flux:icon icon="scale" class="size-4" />
                    <span class="text-[9px] font-bold mt-0.5">Compare</span>
                </a>
                <a href="{{ route('shop.orders') }}" wire:navigate class="flex flex-col items-center justify-center text-zinc-600 hover:text-primary transition p-1">
                    <flux:icon icon="user" class="size-4" />
                    <span class="text-[9px] font-bold mt-0.5">Account</span>
                </a>
            </nav>

            <!-- Footer -->
            <footer id="contact" class="bg-zinc-950 text-zinc-400 border-t border-zinc-900 py-12 mt-12 transition-colors duration-200">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8 pb-8 border-b border-zinc-900">
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 text-white">
                                <div class="flex aspect-square size-8 items-center justify-center rounded-lg bg-primary text-white font-black text-xs">
                                    IT
                                </div>
                                <span class="text-base font-bold">Xpert IT Solution</span>
                            </div>
                            <p class="text-xs leading-relaxed text-zinc-500">Premium IT Infrastructure, CCTV surveillance, enterprise storage, and networking hardware provider.</p>
                        </div>
                        <div>
                            <h5 class="text-white text-xs font-bold uppercase tracking-wider mb-3">Shop Categories</h5>
                            <ul class="space-y-2 text-xs">
                                <li><a href="{{ route('shop.products') }}" class="hover:text-white transition" wire:navigate>Networking & Routers</a></li>
                                <li><a href="{{ route('shop.products') }}" class="hover:text-white transition" wire:navigate>CCTV & Surveillance</a></li>
                                <li><a href="{{ route('shop.products') }}" class="hover:text-white transition" wire:navigate>NVMe & Hard Drives</a></li>
                                <li><a href="{{ route('shop.products') }}" class="hover:text-white transition" wire:navigate>Computer Peripherals</a></li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="text-white text-xs font-bold uppercase tracking-wider mb-3">Corporate Services</h5>
                            <ul class="space-y-2 text-xs">
                                <li><a href="{{ route('shop.bulk-orders') }}" class="hover:text-white transition" wire:navigate>Bulk Orders & Quotes</a></li>
                                <li><a href="{{ route('shop.privacy-policy') }}" class="hover:text-white transition" wire:navigate>Privacy Policy</a></li>
                                <li><a href="{{ route('shop.terms-and-conditions') }}" class="hover:text-white transition" wire:navigate>Terms &amp; Conditions</a></li>
                                <li><a href="{{ route('shop.shipping-policy') }}" class="hover:text-white transition" wire:navigate>Shipping & Warranty Policy</a></li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="text-white text-xs font-bold uppercase tracking-wider mb-3">Get In Touch</h5>
                            <ul class="space-y-2 text-xs text-zinc-400">
                                <li class="flex items-center gap-2">
                                    <flux:icon icon="envelope" class="size-4 shrink-0 text-zinc-500" />
                                    <span>{{ shop()->email }}</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <flux:icon icon="phone" class="size-4 shrink-0 text-zinc-500" />
                                    <span>+91 {{ shop()->phone }}</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <flux:icon icon="map-pin" class="size-4 shrink-0 text-zinc-500" />
                                    <span>{{ shop()->address_line1 . ', ' . shop()->state }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-zinc-500">
                        <span>&copy; {{ date('Y') }} Xpert IT Solution. All rights reserved.</span>
                        <span class="mt-2 sm:mt-0 text-[11px]">100% Authentic IT Hardware & GST Invoices</span>
                    </div>
                </div>
            </footer>

            <!-- Toast Notifications -->
            @persist('toast')
                <flux:toast.group>
                    <flux:toast />
                </flux:toast.group>
            @endpersist

            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
            @fluxScripts
        </div>
    </body>
</html>

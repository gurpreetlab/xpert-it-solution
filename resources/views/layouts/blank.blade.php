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
                            <img src="{{ asset('logo-xpert-it-solution.png') }}" alt="{{ shop()->name }}" class="h-9 w-auto object-contain" />
                        </a>
                    </div>

                    <!-- Main Nav Links -->
                    <nav class="hidden md:flex items-center gap-6 text-xs font-bold text-zinc-600">
                        <a href="{{ route('home') }}" class="hover:text-primary transition" wire:navigate>Home</a>
                        <a href="{{ route('shop.products') }}" class="hover:text-primary transition" wire:navigate>Catalog</a>
                        <a href="{{ route('about') }}" class="hover:text-primary transition" wire:navigate>About Us</a>
                        <a href="{{ route('contact') }}" class="hover:text-primary transition" wire:navigate>Contact</a>
                    </nav>

                    <!-- Utility Actions & Profile -->
                    <div class="flex items-center gap-3">
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
                                <flux:button href="{{ route('login') }}" variant="ghost" size="sm" class="text-xs font-bold text-zinc-700 hover:bg-surface-muted border border-border" wire:navigate>
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
                <a href="{{ route('shop.orders') }}" wire:navigate class="flex flex-col items-center justify-center text-zinc-600 hover:text-primary transition p-1">
                    <flux:icon icon="user" class="size-4" />
                    <span class="text-[9px] font-bold mt-0.5">Account</span>
                </a>
            </nav>

            <!-- Light Theme Footer -->
            <footer id="contact" class="bg-surface-muted text-zinc-600 border-t border-border py-12 mt-12 transition-colors duration-200">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8 pb-8 border-b border-border">
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 text-zinc-900">
                                <img src="{{ asset('logo-xpert-it-solution.png') }}" alt="{{ shop()->name }}" class="h-8 w-auto object-contain" />
                            </div>
                            <p class="text-xs leading-relaxed text-zinc-500">Premium IT Infrastructure, CCTV surveillance, enterprise storage, and networking hardware provider.</p>
                        </div>
                        <div>
                            <h5 class="text-zinc-900 text-xs font-bold uppercase tracking-wider mb-3">Shop Categories</h5>
                            <ul class="space-y-2 text-xs">
                                <li><a href="{{ route('shop.products') }}" class="hover:text-primary transition" wire:navigate>Networking & Routers</a></li>
                                <li><a href="{{ route('shop.products') }}" class="hover:text-primary transition" wire:navigate>CCTV & Surveillance</a></li>
                                <li><a href="{{ route('shop.products') }}" class="hover:text-primary transition" wire:navigate>NVMe & Hard Drives</a></li>
                                <li><a href="{{ route('shop.products') }}" class="hover:text-primary transition" wire:navigate>Computer Peripherals</a></li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="text-zinc-900 text-xs font-bold uppercase tracking-wider mb-3">Company Information</h5>
                            <ul class="space-y-2 text-xs">
                                <li><a href="{{ route('about') }}" class="hover:text-primary transition" wire:navigate>About Us</a></li>
                                <li><a href="{{ route('contact') }}" class="hover:text-primary transition" wire:navigate>Contact Us</a></li>
                                <li><a href="{{ route('shop.privacy-policy') }}" class="hover:text-primary transition" wire:navigate>Privacy Policy</a></li>
                                <li><a href="{{ route('shop.terms-and-conditions') }}" class="hover:text-primary transition" wire:navigate>Terms &amp; Conditions</a></li>
                                <li><a href="{{ route('shop.shipping-policy') }}" class="hover:text-primary transition" wire:navigate>Shipping & Warranty Policy</a></li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="text-zinc-900 text-xs font-bold uppercase tracking-wider mb-3">Get In Touch</h5>
                            <ul class="space-y-2 text-xs text-zinc-600">
                                <li class="flex items-center gap-2">
                                    <flux:icon icon="envelope" class="size-4 shrink-0 text-zinc-400" />
                                    <span>{{ shop()->email }}</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <flux:icon icon="phone" class="size-4 shrink-0 text-zinc-400" />
                                    <span>+91 {{ shop()->phone }}</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <flux:icon icon="map-pin" class="size-4 shrink-0 text-zinc-400" />
                                    <span>{{ shop()->address_line1 . ', ' . shop()->state }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-zinc-500">
                        <span>&copy; {{ date('Y') }} Xpert IT Solution. All rights reserved.</span>
                        <span class="mt-2 sm:mt-0 text-[11px] font-semibold text-zinc-600">100% Authentic IT Hardware & GST Invoices</span>
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

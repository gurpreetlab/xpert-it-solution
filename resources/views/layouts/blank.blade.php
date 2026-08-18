<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased selection:bg-blue-500 selection:text-white transition-colors duration-300">

        <div class="w-full">
            <!-- Navigation Bar -->
            <header class="sticky top-0 z-50 w-full border-b border-zinc-200/80 bg-white/80 backdrop-blur-md transition-colors duration-300">
                <div class="mx-auto flex max-w-7xl h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-6">
                        <!-- Brand Logo & Name -->
                        <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                            <div class="flex aspect-square size-9 items-center justify-center rounded-lg bg-zinc-100 text-zinc-800 shadow-sm group-hover:scale-105 transition-transform duration-200">
                                <!--<x-app-logo-icon class="size-5 fill-current" />-->
                                <img src="{{ asset('storage/' . shop()->logo_path) }}" alt="{{ shop()->name }}" />
                            </div>
                            <span class="text-xl font-bold tracking-tight text-zinc-900">
                                Xpert <span class="text-blue-600 font-semibold">IT Solution</span>
                            </span>

                        </a>
                    </div>

                    <!-- Main Nav (Anchor links) -->
                    <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-zinc-600">
                        <a href="{{ route('home') }}" class="hover:text-blue-600 transition duration-200">Home</a>
                        <a href="{{ route('shop.products') }}" class="hover:text-blue-600 transition duration-200">Products</a>
                        <a href="{{ route('about') }}" class="hover:text-blue-600 transition duration-200">About</a>
                        <a href="{{ route('contact') }}" class="hover:text-blue-600 transition duration-200">Contact</a>
                    </nav>

                    <!-- Right Buttons (Auth) -->
                    <div class="flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth

                                @role('customer')
                                    <a href="{{ route('shop.compare') }}" class="relative text-zinc-600 hover:text-blue-500 transition cursor-pointer mr-1" title="Compare Products" wire:navigate>
                                        <flux:icon icon="scale" class="size-6" />
                                        @if(count(session()->get('compared_product_ids', [])) > 0)
                                            <span class="-right-2 -top-2 absolute bg-blue-600 flex h-4 w-4 items-center justify-center rounded-full text-[10px] text-white font-bold">
                                                {{ count(session()->get('compared_product_ids', [])) }}
                                            </span>
                                        @endif
                                    </a>
                                    <livewire:shop.partials.wishlist-count />
                                    <livewire:shop._partials.cart-count />
                                @endrole

                                <flux:dropdown>
                                    <flux:profile avatar="" name="{{ Auth::user()->name }}" />

                                    <flux:navmenu>
                                        @role('super-admin')
                                            <flux:navmenu.item href="{{ route('dashboard') }}" icon="home">{{ __('Dashboard') }}</flux:navmenu.item>
                                        @endrole

                                        @role('customer')
                                            <flux:navmenu.item href="{{ route('shop.compare') }}" icon="scale" wire:navigate>{{ __('Compare Products') }}</flux:navmenu.item>
                                            <flux:navmenu.item href="{{ route('shop.wishlist') }}" icon="heart" wire:navigate>{{ __('My Wishlist') }}</flux:navmenu.item>
                                            <flux:navmenu.item href="{{ route('shop.orders') }}" icon="shopping-bag" wire:navigate>{{ __('My Orders') }}</flux:navmenu.item>
                                        @endrole

                                        <flux:navmenu.item href="{{ route('profile.edit') }}" icon="user-circle">{{ __('Profile') }}</flux:navmenu.item>

                                        <flux:menu.separator />

                                        <!-- Logout -->
                                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                                            @csrf
                                            <flux:navmenu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer">
                                                {{ __('Log out') }}
                                            </flux:navmenu.item>
                                        </form>
                                    </flux:navmenu>
                                </flux:dropdown>

                            @else
                                <flux:button href="{{ route('login') }}" variant="ghost" size="sm" class="text-zinc-600 hover:bg-zinc-100" wire:navigate>
                                    Log in
                                </flux:button>
                                @if (Route::has('register'))
                                    <flux:button href="{{ route('register') }}" variant="filled" size="sm" class="bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium" wire:navigate>
                                        Register
                                    </flux:button>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </header>

            <!--  -->
            {{ $slot }}

            <!-- Footer -->
            <footer id="contact" class="bg-zinc-900 text-zinc-400 border-t border-zinc-800 py-12 mt-12 transition-colors duration-300">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8 pb-8 border-b border-zinc-800">
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-white">
                                <div class="flex aspect-square size-9 items-center justify-center rounded-lg bg-zinc-100 text-zinc-800 shadow-sm group-hover:scale-105 transition-transform duration-200">
                                    <!--<x-app-logo-icon class="size-5 fill-current" />-->
                                    <img src="{{ asset('storage/' . shop()->logo_path) }}" alt="{{ shop()->name }}" />
                                </div>
                                <span class="text-lg font-bold">Xpert IT Solution</span>
                            </div>
                            <p class="text-xs leading-relaxed text-zinc-500">Premium IT Infrastructure, CCTV surveillance networking systems, enterprise back-ups, and storage solutions supplier.</p>
                        </div>
                        <div>
                            <h5 class="text-white text-xs font-semibold uppercase tracking-wider mb-4">Quick Links</h5>
                            <ul class="space-y-2 text-xs">
                                <li><a href="{{ route('home') }}" class="hover:text-white transition" wire:navigate>Home</a></li>
                                <li><a href="{{ route('shop.products') }}" class="hover:text-white transition" wire:navigate>Shop All Products</a></li>
                                <li><a href="{{ route('about') }}" class="hover:text-white transition" wire:navigate>About Us</a></li>
                                <li><a href="{{ route('contact') }}" class="hover:text-white transition" wire:navigate>Contact Us</a></li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="text-white text-xs font-semibold uppercase tracking-wider mb-4">Corporate Info</h5>
                            <ul class="space-y-2 text-xs">
                                <li><a href="{{ route('shop.privacy-policy') }}" class="hover:text-white transition" wire:navigate>Privacy Policy</a></li>
                                <li><a href="{{ route('shop.terms-and-conditions') }}" class="hover:text-white transition" wire:navigate>Terms &amp; Conditions</a></li>
                                <li><a href="{{ route('shop.shipping-policy') }}" class="hover:text-white transition" wire:navigate>Shipping Policy</a></li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="text-white text-xs font-semibold uppercase tracking-wider mb-4">Get In Touch</h5>
                            <ul class="space-y-2 text-xs">
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
                                    <span>{{ shop()->address_line1 . ' ' . shop()->address_line2 . ', ' . shop()->state }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-zinc-500">
                        <span>&copy; {{ date('Y') }} Xpert IT Solution. All rights reserved.</span>
                        <span class="mt-2 sm:mt-0">Designed by gurpreetlab</span>
                    </div>
                </div>
            </footer>

            <!-- Toast Notifications Support -->
            @persist('toast')
                <flux:toast.group>
                    <flux:toast />
                </flux:toast.group>
            @endpersist

            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
            <!-- Flux Scripts -->
            @fluxScripts
        </div>
    </body>
</html>
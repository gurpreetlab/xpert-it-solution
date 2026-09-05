<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    @include('partials.head')
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        html {
            font-size: 15px;
        }

        @media (max-width: 640px) {
            html {
                font-size: 13px;
            }
        }
    </style>
</head>

<body
    class="min-h-screen bg-zinc-50 text-zinc-900 antialiased selection:bg-blue-500 selection:text-white transition-colors duration-300">

    <div class="w-full">
        <!-- Navigation Bar -->
        <header
            class="sticky top-0 z-50 w-full border-b border-zinc-200/80 bg-white/80 backdrop-blur-md transition-colors duration-300">
            <div class="mx-auto flex max-w-7xl gap-4 h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-6">
                    <!-- Brand Logo & Name -->
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <div class="flex aspect-square size-11 items-center justify-center rounded-lg">
                            <img src="{{ asset('storage/' . shop()->logo_path) }}" alt="{{ shop()->name }}" />
                        </div>
                    </a>
                </div>

                <!-- Search -->
                <div>
                    <!--  -->
                </div>

                <!-- Right Utility Links (Compare, Wishlist, Cart) & Auth -->
                <div class="flex items-center gap-4">
                    <livewire:shop.partials.wishlist-count />

                    <div class="relative" x-data>
                        <a
                            href="{{ route('cart.index') }}"
                            class="p-2 relative flex items-center text-zinc-700 hover:text-black"
                            aria-label="View Cart">
                            <flux:icon icon="shopping-bag" class="size-6" />

                            <template x-if="$store.cart.totalCount > 0">
                                <span
                                    x-text="$store.cart.totalCount"
                                    class="absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center shadow-sm"></span>
                            </template>
                        </a>
                    </div>

                    @auth
                    <flux:dropdown position="bottom" align="end">

                        <button type="button"
                            class="flex items-center gap-2 rounded-lg p-1.5 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                            <flux:avatar name="{{ Auth::user()->name }}" color="auto" class="size-8" />

                            <div class="hidden sm:block text-left">
                                <div class="text-sm font-medium text-zinc-900 dark:text-white leading-tight">
                                    {{ Auth::user()->name }}
                                </div>

                                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                    Account
                                </div>
                            </div>

                            <flux:icon name="chevron-down" variant="mini" class="hidden sm:block text-zinc-400" />
                        </button>

                        <flux:menu class="w-64">

                            {{-- Account header --}}
                            <div class="px-3 py-3">
                                <div class="flex items-center gap-3">

                                    <flux:avatar name="{{ Auth::user()->name }}" color="auto" class="size-10" />

                                    <div class="min-w-0">
                                        <div class="font-medium truncate">
                                            {{ Auth::user()->name }}
                                        </div>

                                        <div class="text-xs text-zinc-500 truncate">
                                            {{ Auth::user()->email }}
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <flux:menu.separator />

                            @role('super-admin')
                            <flux:menu.item href="{{ route('dashboard') }}" icon="squares-2x2">
                                Dashboard
                            </flux:menu.item>
                            @endrole

                            <flux:menu.item href="{{ route('profile.edit') }}" icon="user-circle">
                                Profile
                            </flux:menu.item>

                            <flux:menu.item href="{{ route('shop.orders') }}" icon="shopping-bag">
                                My Orders
                            </flux:menu.item>

                            <flux:menu.separator />

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                    variant="danger" class="w-full">
                                    Log out
                                </flux:menu.item>
                            </form>

                        </flux:menu>

                    </flux:dropdown>
                    @else
                    <flux:button href="{{ route('login') }}" variant="ghost" icon="user">
                        Login / Register
                    </flux:button>

                    @endauth
                </div>
            </div>
        </header>

        <div class="mx-auto max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <!--  -->
            {{ $slot }}
        </div>

        <!-- Footer -->
        <footer id="contact"
            class="bg-zinc-900 text-zinc-400 border-t border-zinc-800 py-12 mt-12 transition-colors duration-300">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8 pb-8 border-b border-zinc-800">
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-white">
                            <div
                                class="flex aspect-square size-9 items-center justify-center rounded-lg bg-zinc-100 text-zinc-800 shadow-sm group-hover:scale-105 transition-transform duration-200">
                                <!--<x-app-logo-icon class="size-5 fill-current" />-->
                                <img src="{{ asset('storage/' . shop()->logo_path) }}" alt="{{ shop()->name }}" />
                            </div>
                            <span class="text-lg font-bold">Xpert IT Solution</span>
                        </div>
                        <p class="text-xs leading-relaxed text-zinc-500">Premium IT Infrastructure, CCTV surveillance
                            networking systems, enterprise back-ups, and storage solutions supplier.</p>
                    </div>
                    <div>
                        <h5 class="text-white text-xs font-semibold uppercase tracking-wider mb-4">Quick Links</h5>
                        <ul class="space-y-2 text-xs">
                            <li><a href="{{ route('home') }}" class="hover:text-white transition"
                                    wire:navigate>Home</a></li>
                            <li><a href="{{ route('shop.products') }}" class="hover:text-white transition"
                                    wire:navigate>Shop All Products</a></li>
                            <li><a href="{{ route('about') }}" class="hover:text-white transition" wire:navigate>About
                                    Us</a></li>
                            <li><a href="{{ route('contact') }}" class="hover:text-white transition"
                                    wire:navigate>Contact Us</a></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="text-white text-xs font-semibold uppercase tracking-wider mb-4">Corporate Info</h5>
                        <ul class="space-y-2 text-xs">
                            <li><a href="{{ route('shop.privacy-policy') }}" class="hover:text-white transition"
                                    wire:navigate>Privacy Policy</a></li>
                            <li><a href="{{ route('shop.terms-and-conditions') }}" class="hover:text-white transition"
                                    wire:navigate>Terms &amp; Conditions</a></li>
                            <li><a href="{{ route('shop.shipping-policy') }}" class="hover:text-white transition"
                                    wire:navigate>Shipping Policy</a></li>
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

        <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <!-- Flux Scripts -->
        @fluxScripts



        <script src="js/owl.carousel.min.js"></script>

        @stack('scripts')
    </div>
</body>

</html>
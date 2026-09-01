<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-background text-zinc-900 antialiased selection:bg-primary selection:text-white transition-colors duration-200 pb-16 md:pb-0">

        <div class="w-full">
            <!-- Header Navigation Component -->
            <x-navigation.header />

            <!-- Main Content Area -->
            {{ $slot }}

            <!-- Mobile Sticky Bottom Navigation -->
            <x-navigation.mobile-bottom-nav />

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

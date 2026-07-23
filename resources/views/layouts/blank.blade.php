<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased selection:bg-blue-500 selection:text-white transition-colors duration-300">

        <div class="w-full">
            <!-- Navigation Bar -->
            <header class="sticky top-0 z-50 w-full border-b border-zinc-200/80 bg-white/80 backdrop-blur-md dark:border-zinc-800/80 dark:bg-zinc-950/80 transition-colors duration-300">
                <div class="mx-auto flex max-w-7xl h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-6">
                        <!-- Brand Logo & Name -->
                        <a href="/" class="flex items-center gap-2.5 group">
                            <div class="flex aspect-square size-9 items-center justify-center rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow-md group-hover:scale-105 transition-transform duration-200">
                                <x-app-logo-icon class="size-5 fill-current" />
                            </div>
                            <span class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">
                                Xpert <span class="text-blue-600 dark:text-blue-500 font-semibold">IT Solution</span>
                            </span>
                        </a>
                    </div>

                    <!-- Main Nav (Anchor links) -->
                    <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-zinc-600 dark:text-zinc-400">
                        <a href="{{ route('home') . '#categories' }}" class="hover:text-blue-600 dark:hover:text-blue-500 transition duration-200">Categories</a>
                        <a href="{{ route('home') . '#featured' }}" class="hover:text-blue-600 dark:hover:text-blue-500 transition duration-200">Featured</a>
                        <a href="{{ route('home') . '#products' }}" class="hover:text-blue-600 dark:hover:text-blue-500 transition duration-200">Products</a>
                    </nav>

                    <!-- Right Buttons (Auth) -->
                    <div class="flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                @role('super-admin')
                                    <flux:button href="{{ route('dashboard') }}" variant="filled" size="sm" class="bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium">
                                        Dashboard
                                    </flux:button>
                                @endrole

                                <div class="relative">
                                    <flux:icon.shopping-cart class="size-5" />
                                    <span class="-right-2 -top-2 absolute bg-red-800 flex h-full items-center justify-center rounded-full text-xs w-full overflow-hidden">0</span>
                                </div>

                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <flux:button type="submit" variant="ghost" size="sm" class="text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900">
                                        Log out
                                    </flux:button>
                                </form>

                            @else
                                <flux:button href="{{ route('login') }}" variant="ghost" size="sm" class="text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900">
                                    Log in
                                </flux:button>
                                @if (Route::has('register'))
                                    <flux:button href="{{ route('register') }}" variant="filled" size="sm" class="bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium">
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
                                <div class="flex aspect-square size-8 items-center justify-center rounded bg-blue-600 text-white">
                                    <x-app-logo-icon class="size-4.5 fill-current" />
                                </div>
                                <span class="text-lg font-bold">Xpert IT Solution</span>
                            </div>
                            <p class="text-xs leading-relaxed text-zinc-500">Premium IT Infrastructure, CCTV surveillance networking systems, enterprise back-ups, and storage solutions supplier.</p>
                        </div>
                        <div>
                            <h5 class="text-white text-xs font-semibold uppercase tracking-wider mb-4">Product Domains</h5>
                            <ul class="space-y-2 text-xs">
                                <li><a href="#products" wire:click="$set('selectedCategoryId', '2')" class="hover:text-white transition">CCTV Surveillance Cameras</a></li>
                                <li><a href="#products" wire:click="$set('selectedCategoryId', '1')" class="hover:text-white transition">Enterprise Wifi & Networking</a></li>
                                <li><a href="#products" wire:click="$set('selectedCategoryId', '3')" class="hover:text-white transition">Network Storage & HDDs</a></li>
                                <li><a href="#products" wire:click="$set('selectedCategoryId', '5')" class="hover:text-white transition">Industrial UPS Systems</a></li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="text-white text-xs font-semibold uppercase tracking-wider mb-4">Corporate Info</h5>
                            <ul class="space-y-2 text-xs">
                                <li><a href="#" class="hover:text-white transition">About Us</a></li>
                                <li><a href="#" class="hover:text-white transition">Case Studies</a></li>
                                <li><a href="#" class="hover:text-white transition">Careers</a></li>
                                <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="text-white text-xs font-semibold uppercase tracking-wider mb-4">Get In Touch</h5>
                            <ul class="space-y-2 text-xs">
                                <li class="flex items-center gap-2">
                                    <flux:icon icon="envelope" class="size-4 shrink-0 text-zinc-500" />
                                    <span>info@xpertitsolution.com</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <flux:icon icon="phone" class="size-4 shrink-0 text-zinc-500" />
                                    <span>+91 98765 43210</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <flux:icon icon="map-pin" class="size-4 shrink-0 text-zinc-500" />
                                    <span>Gurpreet Lab Complex, Phase 8, Mohali, Punjab</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-zinc-500">
                        <span>&copy; {{ date('Y') }} Xpert IT Solution. All rights reserved.</span>
                        <span class="mt-2 sm:mt-0">Designed by Senior UX/UI Engineer</span>
                    </div>
                </div>
            </footer>

            <!-- Toast Notifications Support -->
            @persist('toast')
                <flux:toast.group>
                    <flux:toast />
                </flux:toast.group>
            @endpersist

            <!-- Flux Scripts -->
            @fluxScripts
        </div>
    </body>
</html>

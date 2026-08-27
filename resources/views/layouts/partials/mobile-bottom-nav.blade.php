<div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-zinc-200 px-4 py-2 shadow-2xl transition-all duration-300">
    <div class="max-w-md mx-auto flex items-center justify-around">
        <!-- Home -->
        <a href="{{ route('home') }}" wire:navigate class="flex flex-col items-center gap-1 text-xs font-medium {{ request()->routeIs('home') ? 'text-zinc-900 font-bold' : 'text-zinc-500 hover:text-zinc-800' }}">
            <div class="p-1 rounded-full {{ request()->routeIs('home') ? 'bg-zinc-100' : '' }}">
                <flux:icon icon="home" class="size-5" />
            </div>
            <span>Home</span>
        </a>

        <!-- Products / Search -->
        <a href="{{ route('shop.products') }}" wire:navigate class="flex flex-col items-center gap-1 text-xs font-medium {{ request()->routeIs('shop.products*') ? 'text-zinc-900 font-bold' : 'text-zinc-500 hover:text-zinc-800' }}">
            <div class="p-1 rounded-full {{ request()->routeIs('shop.products*') ? 'bg-zinc-100' : '' }}">
                <flux:icon icon="magnifying-glass" class="size-5" />
            </div>
            <span>Products</span>
        </a>

        <!-- Center Floating Action / Cart -->
        <a href="{{ route('shop.cart') }}" wire:navigate class="relative -top-4 bg-zinc-900 text-white p-3.5 rounded-full shadow-lg hover:bg-zinc-800 active:scale-95 transition-transform duration-150 flex items-center justify-center">
            <flux:icon icon="shopping-bag" class="size-6 text-white" />
            <livewire:shop._partials.cart-count />
        </a>

        <!-- Wishlist -->
        <a href="{{ route('shop.wishlist') }}" wire:navigate class="flex flex-col items-center gap-1 text-xs font-medium relative {{ request()->routeIs('shop.wishlist') ? 'text-zinc-900 font-bold' : 'text-zinc-500 hover:text-zinc-800' }}">
            <div class="p-1 rounded-full {{ request()->routeIs('shop.wishlist') ? 'bg-zinc-100' : '' }}">
                <flux:icon icon="heart" class="size-5" />
            </div>
            <span>Wishlist</span>
            <livewire:shop._partials.wishlist-count />
        </a>

        <!-- Account -->
        @auth
            <a href="{{ route('profile.edit') }}" wire:navigate class="flex flex-col items-center gap-1 text-xs font-medium {{ request()->routeIs('profile.edit') || request()->routeIs('dashboard') ? 'text-zinc-900 font-bold' : 'text-zinc-500 hover:text-zinc-800' }}">
                <div class="p-1 rounded-full {{ request()->routeIs('profile.edit') ? 'bg-zinc-100' : '' }}">
                    <flux:icon icon="user" class="size-5" />
                </div>
                <span>Account</span>
            </a>
        @else
            <a href="{{ route('login') }}" wire:navigate class="flex flex-col items-center gap-1 text-xs font-medium {{ request()->routeIs('login') ? 'text-zinc-900 font-bold' : 'text-zinc-500 hover:text-zinc-800' }}">
                <div class="p-1 rounded-full {{ request()->routeIs('login') ? 'bg-zinc-100' : '' }}">
                    <flux:icon icon="arrow-right-end-on-rectangle" class="size-5" />
                </div>
                <span>Login</span>
            </a>
        @endauth
    </div>
</div>

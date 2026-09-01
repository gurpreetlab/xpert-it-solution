<header class="sticky top-0 z-40 w-full border-b border-border bg-surface/90 backdrop-blur-md transition-colors duration-200">
    <div class="mx-auto flex max-w-7xl h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
        <!-- Brand Logo & Name -->
        <div class="flex items-center gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                <img src="{{ asset('logo-xpert-it-solution.png') }}" alt="{{ shop()->name }}" class="h-9 w-auto object-contain" />
            </a>
        </div>

        <!-- Desktop Navigation Links -->
        <x-navigation.desktop-nav />

        <!-- Utility Actions & Profile Dropdown -->
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
                    <x-ui.button href="{{ route('login') }}" variant="secondary" size="sm" wire:navigate>
                        Log in
                    </x-ui.button>
                    @if (Route::has('register'))
                        <x-ui.button href="{{ route('register') }}" variant="primary" size="sm" wire:navigate>
                            Register
                        </x-ui.button>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</header>

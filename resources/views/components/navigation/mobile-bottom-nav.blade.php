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

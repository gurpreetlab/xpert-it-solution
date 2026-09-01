<nav class="hidden md:flex items-center gap-6 text-xs font-bold text-zinc-600">
    <a href="{{ route('home') }}" class="hover:text-primary transition" wire:navigate>Home</a>
    <a href="{{ route('shop.products') }}" class="hover:text-primary transition" wire:navigate>Catalog</a>
    <a href="{{ route('about') }}" class="hover:text-primary transition" wire:navigate>About Us</a>
    <a href="{{ route('contact') }}" class="hover:text-primary transition" wire:navigate>Contact</a>
</nav>

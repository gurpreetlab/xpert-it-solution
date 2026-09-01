<div class="block md:hidden">
    <!-- App Bar (Top Header) -->
    <header class="sticky top-0 z-40 flex h-14 items-center justify-between px-4 gap-4 bg-white">
        <button class="p-1">
            <img src="{{ asset('storage/' . shop()->logo_path) }}" alt="{{ shop()->name }}" class="w-14"/>
        </button>
        <flux:input type="search" icon="magnifying-glass" placeholder="Search" />
        <div class="w-6">
            <livewire:shop._partials.cart-count />
        </div> <!-- Spacer for balance -->
    </header>

    <!-- Main Native-like Scrollable Screen Area -->
    <main class="p-4 pb-20">
        {{ $slot }}
    </main>

    <!-- Native Bottom Navigation Bar -->
    <nav class="fixed bottom-0 left-0 right-0 z-50 flex h-16 items-center justify-around border-t bg-white shadow-lg pb-safe">
        <a href="#" class="flex flex-col items-center text-xs font-medium text-blue-600">
            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l1.293 1.293a1 1 0 001.414-1.414l-7-7z" />
            </svg>
            Home
        </a>

        <a href="#" class="flex flex-col items-center text-xs font-medium text-gray-500">
            <flux:icon.heart />
            Wishlist
        </a>

        <a href="#" class="flex flex-col items-center text-xs font-medium text-gray-500">
            <flux:icon.shopping-cart />
            Cart
        </a>
        <a href="#" class="flex flex-col items-center text-xs font-medium text-gray-500">
            <flux:icon.user-circle />
            Profile
        </a>
    </nav>
</div>

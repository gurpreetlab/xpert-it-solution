<div class="hidden md:block">
    <!-- Traditional Desktop Header Navigation -->
    <header class="flex items-center justify-between px-8 py-4 gap-4">
        <div class="text-xl font-bold">
            <img src="{{ asset('storage/' . shop()->logo_path) }}" alt="{{ shop()->name }}" class="w-14"/>
        </div>
        <flux:input type="search" icon="magnifying-glass" placeholder="Search" />
        <nav class="flex space-x-6 text-sm font-medium">
            <a href="#" class="hover:text-blue-600">Home</a>
            <a href="#" class="hover:text-blue-600">Features</a>
            <a href="#" class="hover:text-blue-600">Pricing</a>
            <a href="#" class="hover:text-blue-600">Contact</a>
        </nav>
    </header>

    <!-- Sidebar + Main Content Layout -->
    <main class="flex-1 p-6">
        {{ $slot }}
    </main>
</div>

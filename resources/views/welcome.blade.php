<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased selection:bg-blue-500 selection:text-white transition-colors duration-300">

        <!-- Livewire Shop Landing Page -->
        <livewire:shop.home />

        <!-- Toast Notifications Support -->
        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        <!-- Flux Scripts -->
        @fluxScripts
    </body>
</html>

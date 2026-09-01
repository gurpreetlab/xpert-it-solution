<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="bg-gradient-to-br from-blue-50 via-slate-50 to-indigo-50 relative hidden h-full flex-col p-10 text-zinc-900 lg:flex border-r border-border">
                <a href="{{ route('home') }}" class="relative z-20 flex items-center gap-3 font-bold text-lg" wire:navigate>
                    <img src="{{ asset('logo-xpert-it-solution.png') }}" alt="{{ shop()->name }}" class="h-10 w-auto object-contain" />
                </a>

                <div class="relative z-20 my-auto space-y-6 max-w-md">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold border border-primary/20">
                        <flux:icon icon="shield-check" class="size-4" />
                        <span>Verified IT Hardware Marketplace</span>
                    </div>

                    <h2 class="text-3xl font-extrabold tracking-tight text-zinc-900 leading-tight">
                        Enterprise Networking, Storage & Surveillance Solutions
                    </h2>

                    <p class="text-xs text-zinc-600 leading-relaxed">
                        Access official manufacturer warranties, instant GST invoices for corporate billing, and nationwide fast shipping.
                    </p>

                    <div class="space-y-3 pt-4 border-t border-border/80">
                        <div class="flex items-center gap-3 text-xs text-zinc-700 font-semibold">
                            <flux:icon icon="check-circle" class="size-4 text-emerald-600 shrink-0" />
                            <span>100% Authentic Brand Products</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-zinc-700 font-semibold">
                            <flux:icon icon="document-text" class="size-4 text-primary shrink-0" />
                            <span>B2B GST Invoicing & Corporate Purchase Support</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-zinc-700 font-semibold">
                            <flux:icon icon="truck" class="size-4 text-indigo-600 shrink-0" />
                            <span>Same-Day & Express Dispatch</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-20 text-xs text-zinc-400 font-medium">
                    &copy; {{ date('Y') }} Xpert IT Solution. All rights reserved.
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[380px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden mb-2" wire:navigate>
                        <img src="{{ asset('logo-xpert-it-solution.png') }}" alt="{{ shop()->name }}" class="h-10 w-auto object-contain" />
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>

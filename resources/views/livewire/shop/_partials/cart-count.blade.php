<div class="relative">
    <a href="{{ route('shop.cart') }}" wire:navigate>
        <flux:icon.shopping-cart class="size-5" />
        @if($count > 0)
            <span class="-right-2 -top-2 absolute bg-red-800 flex h-4 w-4 items-center justify-center rounded-full text-[10px] text-white font-bold">
                {{ $count }}
            </span>
        @endif
    </a>
</div>

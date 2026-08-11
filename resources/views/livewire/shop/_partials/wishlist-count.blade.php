<div class="relative">
    <a href="{{ route('shop.wishlist') }}" wire:navigate class="relative text-zinc-600 dark:text-zinc-400 hover:text-rose-500 transition cursor-pointer" title="Wishlist">
        <flux:icon icon="heart" class="size-5" />
        @if($count > 0)
            <span class="-right-2 -top-2 absolute bg-rose-600 flex h-4 w-4 items-center justify-center rounded-full text-[10px] text-white font-bold">
                {{ $count }}
            </span>
        @endif
    </a>
</div>

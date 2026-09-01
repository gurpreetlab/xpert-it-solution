<div class="mb-4 md:mt-4">
    <h3 class="font-semibold mb-4">Shop by Categories</h3>
    <div class="flex gap-4 overflow-x-auto scrollbar-none">
        @foreach ($categories as $category)
            <div class="flex flex-1 flex-col items-center">
                <div class="bg-gray-100 p-3 rounded-full">
                    <flux:icon :name="$category->icon" />
                </div>
                <span class="text-center text-xs mt-2 line-clamp-1">{{ $category->name }}</span>
            </div>
        @endforeach
    </div>
</div>

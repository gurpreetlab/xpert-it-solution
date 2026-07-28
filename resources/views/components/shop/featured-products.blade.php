@props(['products'])

<section id="featured" class="mb-16 scroll-mt-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Featured Technology Products</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Specially selected top-performing IT hardware from verified brands.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($products as $product)
            <x-shop.featured-product-card :product="$product" />
        @endforeach
    </div>
</section>

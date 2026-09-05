<x-layouts::blank :title="__('Home')">
    <!-- Banners -->
    <div class="owl-carousel owl-carousel-banners mt-4 rounded-xl">
        <div>
            <img src="{{ asset('storage/banners/banner-1.webp') }}" alt="Banner 1" class="w-full h-56 md:h-80 lg:h-96 object-cover rounded-xl">
        </div>
        <div>
            <img src="{{ asset('storage/banners/banner-2.webp') }}" alt="Banner 2" class="w-full h-56 md:h-80 lg:h-96 object-cover rounded-xl">
        </div>
        <div>
            <img src="{{ asset('storage/banners/banner-3.webp') }}" alt="Banner 3" class="w-full h-56 md:h-80 lg:h-96 object-cover rounded-xl">
        </div>
    </div>

    <!-- Categories -->
    <section>
        <div class="flex gap-4 overflow-x-auto py-4">
            @foreach($categories as $category_name)
            <a href="#" class="whitespace-nowrap bg-gray-100 hover:bg-rose-100 hover:text-rose-500 px-4 py-2 rounded">{{ $category_name }}</a>
            @endforeach
        </div>
    </section>

    <!-- New Arrivals -->
    <section class="mt-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold">New Arrivals</h2>
                <span class="text-sm text-zinc-500">Be the first to shop our newest arrivals before they sell out.</span>
            </div>
            <a href="#" class="text-rose-500 hover:text-rose-600">View All</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
            @foreach($newArrivals as $product)
            <x-shop.product-card :product="$product" />
            @endforeach
        </div>
    </section>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $(".owl-carousel-banners").owlCarousel({
                items: 1,
                loop: true,
                autoplay: true,
                lazyLoad: true,
            });
        });
    </script>
    @endpush
</x-layouts::blank>
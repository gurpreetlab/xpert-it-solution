<div>
    <h3 class="font-semibold mb-4">Featured Products</h3>
    <div class="owl-carousel owl-theme featured-products-owl-carousel">
        @foreach ($featuredProducts as $product)
            <div class="relative">
                <img class="mb-2" src="{{ asset('storage/' . $product->primary_image_path) }}" alt="{{ $product->name }}" />
                <span class="truncate line-clamp-1 text-sm">{{ $product->name }}</span>

                @if($product->discount > 0)
                    <span class="absolute top-3 right-3 inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-500 text-white shadow-sm">
                        {{ $product->discount }}% OFF
                    </span>
                @endif

                <div class="flex items-center gap-2">
                    <span class="text-base text-rose-500 text-sm font-semibold">₹{{ number_format($product->sale_price, 2) }}</span>

                    @if($product->mrp > $product->sale_price)
                        <span class="text-xs text-zinc-400 line-through">₹{{ number_format($product->mrp, 2) }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

@script
    <script>
    function initFeaturedCarousel() {
        var $carousel = $(".featured-products-owl-carousel");

        if ($carousel.length) {
            $carousel.trigger('destroy.owl.carousel');

            $carousel.owlCarousel({
                loop: true,
                margin: 10,
                responsiveClass: true,
                responsive: {
                    0: { items: 2, nav: false },
                    600: { items: 5, nav: false }
                }
            });
        }
    }

    $(document).ready(function() {
        initFeaturedCarousel();
    });
    </script>
@endscript

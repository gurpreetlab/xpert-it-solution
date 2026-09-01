<div class="owl-carousel">
    @foreach ($banners as $banner)
        <img
            src="{{ $banner }}"
            class="aspect-video w-full rounded-lg object-cover"
            alt="Banner"
        />
    @endforeach
</div>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    @include('partials.head')

    <link rel="stylesheet" href="/owlcarousel/owl.carousel.min.css" />
    <link rel="stylesheet" href="/owlcarousel/owl.theme.default.min.css" />
</head>

<body class="min-h-screen">
    <div class="w-full">
        <div class="mx-auto max-w-7xl">
            {{ $slot }}
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/owlcarousel/owl.carousel.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".owl-carousel").owlCarousel({
                loop: true,
                margin: 10,
                responsiveClass: true,
                autoplay: true,
                autoplayTimeout: 2000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: false
                    },
                    600: {
                        items: 3,
                        nav: false
                    }
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
<meta name="theme-color" content="#ffffff" />
<meta name="mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="default" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
</title>

<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="manifest" href="/manifest.json">
<link rel="icon" href="/logo-xpert-it-solution.png" sizes="any">
<link rel="icon" href="/logo-xpert-it-solution.png" type="image/svg+xml">
<link rel="apple-touch-icon" href="/logo-xpert-it-solution.png">

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
<!-- @fluxAppearance -->

<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js').catch(() => {});
        });
    }
</script>
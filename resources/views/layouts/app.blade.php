<!DOCTYPE html>
<html lang="en-IN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $pageTitle = $title ?? config('site.full_name').' | NEP 2020 School Books for CBSE and State Boards, Nursery to Grade 10';
        $pageDescription = Str::limit($description ?? 'Shubhanga Publications, Hyderabad: NEP 2020 aligned pre-primary, primary and high school books from Nursery to Grade 10 for CBSE and state boards in eleven states. Specimen copies for schools.', 160);
        $pageImage = $image ?? config('site.hero_image');
        $pageImage = Str::startsWith($pageImage, 'http') ? $pageImage : url($pageImage);
        $noindex = $noindex ?? false;
        $canonical = $canonical ?? url()->current();
        $ogType = $ogType ?? 'website';
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $canonical }}">
    <link rel="alternate" hreflang="en-IN" href="{{ $canonical }}">
    <link rel="alternate" hreflang="x-default" href="{{ $canonical }}">
    <meta name="robots" content="{{ $noindex ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1' }}">
    <meta name="author" content="{{ config('site.full_name') }}">
    <meta name="geo.region" content="IN-TG">
    <meta name="geo.placename" content="Hyderabad">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:site_name" content="{{ config('site.full_name') }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:image:alt" content="{{ $imageAlt ?? $pageTitle }}">
    <meta property="og:locale" content="en_IN">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $pageImage }}">
    {{-- Publisher and site search, on every page --}}
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => route('home').'#organization',
                'name' => config('site.full_name'),
                'alternateName' => config('site.name'),
                'url' => route('home'),
                'logo' => url(config('site.logo')),
                'slogan' => config('site.tagline'),
                'foundingDate' => '2024',
                'email' => config('site.contact.email'),
                'sameAs' => array_values(config('site.social')),
                'address' => ['@type' => 'PostalAddress', 'streetAddress' => config('site.contact.address'), 'addressLocality' => 'Hyderabad', 'addressRegion' => 'Telangana', 'postalCode' => '500032', 'addressCountry' => 'IN'],
                'contactPoint' => collect(config('site.contact.phones'))->map(fn ($p) => ['@type' => 'ContactPoint', 'telephone' => $p['number'], 'contactType' => $p['label'], 'areaServed' => 'IN'])->values()->all(),
            ],
            [
                '@type' => 'WebSite',
                '@id' => route('home').'#website',
                'url' => route('home'),
                'name' => config('site.full_name'),
                'inLanguage' => 'en-IN',
                'publisher' => ['@id' => route('home').'#organization'],
                'potentialAction' => ['@type' => 'SearchAction', 'target' => ['@type' => 'EntryPoint', 'urlTemplate' => route('books.index').'?q={search_term_string}'], 'query-input' => 'required name=search_term_string'],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#c8102e">
    <link rel="icon" type="image/png" href="{{ asset('images/brand/favicon-192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/favicon-192.png') }}">
    <script>
        // Mark JS as available, and skip the preloader after the first page of a visit.
        (function (d) {
            d.classList.add('js');
            // The home page always plays it; inner pages only on the first page of a visit.
            try { if (location.pathname !== '/' && sessionStorage.getItem('sp-seen')) d.classList.add('is-return'); } catch (e) {}
        })(document.documentElement);
    </script>
    <script>window.__cart = @json(\App\Support\Cart::summary());</script>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="@yield('body_class')">
    <a href="#main" class="skip-link">Skip to content</a>

    <div class="preloader" aria-hidden="true" data-preloader>
        <div class="preloader-frame"></div>
        <div class="preloader-center">
            <img src="{{ asset('images/brand/shubhanga-logo.webp') }}" alt="" class="preloader-logo" width="320" height="158">
            <span class="preloader-tag">{{ config('site.tagline') }}</span>
        </div>
        <span class="preloader-count" data-preloader-count>0</span>
    </div>

    @include('partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.cart-drawer')

    <div class="toast" role="status" aria-live="polite" data-toast></div>
</body>
</html>

<!DOCTYPE html>
<html lang="en-IN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $pageTitle = $title ?? config('site.full_name').': '.config('site.tagline');
        $pageDescription = $description ?? 'NEP 2020 aligned textbooks, skill books and practice books from Nursery to Grade 10 for CBSE, Karnataka, Andhra Pradesh and Telangana schools.';
        $pageImage = $image ?? config('site.hero_image');
        $pageImage = Str::startsWith($pageImage, 'http') ? $pageImage : url($pageImage);
        $noindex = $noindex ?? false;
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    @if ($noindex)<meta name="robots" content="noindex">@endif
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('site.full_name') }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:locale" content="en_IN">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#c8102e">
    <link rel="icon" type="image/png" href="{{ asset('images/brand/favicon-192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/favicon-192.png') }}">
    <script>
        // Mark JS as available, and skip the preloader after the first page of a visit.
        (function (d) {
            d.classList.add('js');
            try { if (sessionStorage.getItem('sp-seen')) d.classList.add('is-return'); } catch (e) {}
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

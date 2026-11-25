{{-- Compact page header for inner pages: breadcrumb, eyebrow, title, intro. --}}
@php
    $crumbItems = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')]];
    foreach ($crumbs ?? [] as $label => $url) {
        $crumbItems[] = ['@type' => 'ListItem', 'position' => count($crumbItems) + 1, 'name' => strip_tags($label), 'item' => $url ?: url()->current()];
    }
@endphp
@push('head')
    <script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $crumbItems], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
<section class="page-head">
    <div class="wrap">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <ol>
                <li><a href="{{ route('home') }}">Home</a></li>
                @foreach ($crumbs ?? [] as $label => $url)
                    <li>
                        @if ($url)
                            <a href="{{ $url }}">{{ $label }}</a>
                        @else
                            <span aria-current="page">{{ $label }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
        @isset($eyebrow)<p class="eyebrow">{{ $eyebrow }}</p>@endisset
        <h1 class="display page-title" data-split>{!! $title !!}</h1>
        @isset($intro)<p class="page-intro">{{ $intro }}</p>@endisset
    </div>
</section>

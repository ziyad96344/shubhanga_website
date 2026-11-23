{{-- Compact page header for inner pages: breadcrumb, eyebrow, title, intro. --}}
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

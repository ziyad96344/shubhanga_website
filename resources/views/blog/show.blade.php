@extends('layouts.app', ['title' => $post['title'].' | '.config('site.full_name'), 'description' => $post['excerpt'], 'image' => $post['image'], 'imageAlt' => $post['title'], 'ogType' => 'article'])

@push('head')
    <script type="application/ld+json">{!! json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post['title'],
        'description' => $post['excerpt'],
        'image' => url($post['image']),
        'url' => url()->current(),
        'mainEntityOfPage' => url()->current(),
        'articleSection' => $post['tag'],
        'datePublished' => $post['date'] ?: null,
        'inLanguage' => 'en-IN',
        'author' => ['@id' => route('home').'#organization'],
        'publisher' => ['@id' => route('home').'#organization'],
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $post['title'], 'item' => url()->current()],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
    <article>
        <header class="article-head wrap">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><span aria-current="page">{{ $post['tag'] }}</span></li>
                </ol>
            </nav>
            <p class="eyebrow">{{ $post['tag'] }}</p>
            <h1 class="article-title" data-split>{{ $post['title'] }}</h1>
            @if ($post['date'])<time class="article-date">{{ $post['date'] }}</time>@endif
        </header>

        <div class="wrap article-figure">
            <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" loading="eager">
        </div>

        <div class="wrap narrow-page prose prose--article">
            <p class="prose-lede">{{ $post['excerpt'] }}</p>
            @foreach ($post['body'] as $para)
                <p>{{ $para }}</p>
            @endforeach
        </div>
    </article>

    <section class="section">
        <div class="wrap">
            <div class="section-head section-head--split">
                <div style="display: grid; gap: 22px; justify-items: start">
                    <p class="eyebrow">Keep reading</p>
                    <h2>More <em>articles</em></h2>
                </div>
                <a href="{{ route('blog.index') }}" class="link-arrow">All articles
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
            <div class="posts posts--page">
                @foreach ($more as $other)
                    <article class="post">
                        <a href="{{ route('blog.show', $other['slug']) }}">
                            <div class="post-thumb"><img src="{{ $other['image'] }}" alt="" loading="lazy"></div>
                            <span class="post-tag">{{ $other['tag'] }}</span>
                            <h3>{{ $other['title'] }}</h3>
                            @if ($other['date'])<time>{{ $other['date'] }}</time>@endif
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection

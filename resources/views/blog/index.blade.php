@extends('layouts.app', ['title' => 'Blog | '.config('site.full_name'), 'description' => 'Exam updates, reading tips for parents and stories from behind our books.'])

@section('content')
    @include('partials.page-head', [
        'crumbs' => ['Blog' => null],
        'eyebrow' => 'Journal',
        'title' => 'From the <em>blog</em>',
        'intro' => 'Exam updates, reading tips for parents, and how our books are made.',
    ])

    <section class="section">
        <div class="wrap posts posts--page" data-reveal-cards>
            @foreach ($posts as $post)
                <article class="post">
                    <a href="{{ route('blog.show', $post['slug']) }}">
                        <div class="post-thumb"><img src="{{ $post['image'] }}" alt="" loading="lazy"></div>
                        <span class="post-tag">{{ $post['tag'] }}</span>
                        <h2 class="post-title">{{ $post['title'] }}</h2>
                        <p class="post-excerpt">{{ $post['excerpt'] }}</p>
                        @if ($post['date'])<time>{{ $post['date'] }}</time>@endif
                    </a>
                </article>
            @endforeach
        </div>
    </section>
@endsection

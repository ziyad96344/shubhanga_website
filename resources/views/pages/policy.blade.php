@extends('layouts.app', ['title' => $policy['title'].' | '.config('site.full_name')])

@section('content')
    @include('partials.page-head', [
        'crumbs' => ['Policies' => null, $policy['title'] => null],
        'eyebrow' => 'Policies',
        'title' => $policy['title'],
    ])

    <section class="wrap policy-layout">
        <nav class="policy-nav" aria-label="Policies">
            <ul>
                @foreach ($policies as $key => $item)
                    <li><a href="{{ route('policy', $key) }}" @if ($key === $slug) aria-current="page" @endif>{{ $item['title'] }}</a></li>
                @endforeach
            </ul>
        </nav>

        <article class="prose">
            @foreach ($policy['sections'] as $section)
                <h2>{{ $section['heading'] }}</h2>
                <p>{{ $section['text'] }}</p>
            @endforeach
            <p class="prose-muted">Last updated {{ now()->format('j F Y') }}. Questions? <a href="{{ route('forms.show', 'contact') }}">Contact us</a>.</p>
        </article>
    </section>
@endsection

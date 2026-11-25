@extends('layouts.app', ['title' => 'FAQ | '.config('site.full_name'), 'description' => 'Answers about delivery, payment, returns, specimen copies and school orders.'])

@push('head')
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect(config('content.faq'))->map(fn ($f) => ['@type' => 'Question', 'name' => $f['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']]])->values()->all(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
    @include('partials.page-head', [
        'crumbs' => ['FAQ' => null],
        'eyebrow' => 'Help',
        'title' => 'Questions <em>and answers</em>',
        'intro' => 'Orders, delivery, returns and school supply. If your question is not here, write to us.',
    ])

    <section class="wrap narrow-page">
        <div class="faq-list">
            @foreach ($faqs as $faq)
                <details class="faq" @if ($loop->first) open @endif>
                    <summary>
                        {{ $faq['q'] }}
                        <span class="faq-icon" aria-hidden="true"></span>
                    </summary>
                    <p>{{ $faq['a'] }}</p>
                </details>
            @endforeach
        </div>

        <div class="help-card">
            <div>
                <h2>Still need help?</h2>
                <p>Our team replies within one working day.</p>
            </div>
            <a href="{{ route('forms.show', 'contact') }}" class="btn">Contact us</a>
        </div>
    </section>
@endsection

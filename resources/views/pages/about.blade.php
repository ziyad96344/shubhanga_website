@extends('layouts.app', ['title' => 'About us | '.config('site.full_name'), 'description' => $about['intro']])

@section('content')
    @include('partials.page-head', [
        'crumbs' => ['About us' => null],
        'eyebrow' => 'Founded in 2024',
        'title' => 'Education first, <em>business next</em>',
        'intro' => $about['intro'],
    ])

    <section class="section about-story">
        <div class="wrap about-grid">
            <div class="about-figure about-figure--wide">
                <img src="{{ $about['image'] }}" alt="Shubhanga Publications" loading="lazy">
            </div>
            <div class="about-values">
                <p class="eyebrow">Our story</p>
                <p class="about-lede">{{ $about['story'] }}</p>
                <div class="value">
                    <h2>Our vision</h2>
                    <p>{{ $about['vision'] }}</p>
                </div>
                <div class="value">
                    <h2>Our mission</h2>
                    <p>{{ $about['mission'] }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section about-timeline">
        <div class="wrap">
            <div class="section-head">
                <p class="eyebrow">Why choose us</p>
                <h2 data-split>Because education deserves <em>accuracy, clarity and care</em></h2>
            </div>
            <ol class="timeline timeline--values" data-reveal-cards>
                @foreach ($about['values'] as $value)
                    <li>
                        <span class="timeline-year">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3>{{ $value['title'] }}</h3>
                        <p>{{ $value['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <div class="section-head section-head--split">
                <div style="display: grid; gap: 22px; justify-items: start">
                    <p class="eyebrow">Our journey</p>
                    <h2 data-split>Growing with <em>our schools</em></h2>
                </div>
            </div>
            <ol class="timeline" data-reveal-cards>
                @foreach ($about['timeline'] as $step)
                    <li>
                        <span class="timeline-year">{{ $step['year'] }}</span>
                        <p>{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
            <div class="about-gallery" data-reveal-cards>
                @foreach ($about['gallery'] as $photo)
                    <img src="{{ $photo }}" alt="" loading="lazy">
                @endforeach
            </div>
        </div>
    </section>

    <section class="section cta-band">
        <div class="wrap cta-inner">
            <h2 class="display">Partner with <em>Shubhanga</em></h2>
            <div class="cta-actions">
                <a href="{{ route('forms.show', 'specimen-copy') }}" class="btn btn--gold">Request a specimen copy</a>
                <a href="{{ route('forms.show', 'contact') }}" class="btn btn--ghost-light">Contact us</a>
            </div>
        </div>
    </section>
@endsection

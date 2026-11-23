@php
    $books = \App\Support\Catalog::books();
    $stageNumbers = ['N', '1', '6', '9'];
@endphp

<section class="journey" aria-labelledby="journey-title" data-journey>
    <div class="journey-track" data-journey-track>
        <div class="journey-intro">
            <p class="eyebrow eyebrow--light">Nursery to Grade 10</p>
            <h2 id="journey-title" data-split>Nursery to Grade 10, <em>one publisher</em></h2>
            <p>Each stage has its own series, written for how children learn at that age. Scroll to walk through them.</p>
        </div>

        @foreach (config('catalog.stages') as $i => $stage)
            <article class="stage">
                <div>
                    <div class="stage-step">Stage {{ $i + 1 }} of {{ count(config('catalog.stages')) }}</div>
                    <h3>{{ $stage['name'] }}</h3>
                    <div class="stage-classes">{{ $stage['classes'] }}</div>
                    <p style="margin-top: 20px">{{ $stage['text'] }}</p>
                    <a href="{{ route('books.index', ['class' => ['Nursery', '1', '6', '9'][$i] ?? null]) }}" class="link-arrow" style="margin-top: 28px">Browse {{ strtolower($stage['name']) }} books
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>
                <div class="stage-books" aria-hidden="true">
                    @foreach ($stage['books'] as $series)
                        @php($book = $books->firstWhere('series', $series) ?? $books->first())
                        <x-book-cover :book="$book" />
                    @endforeach
                </div>
                <span class="stage-big" aria-hidden="true">{{ $stageNumbers[$i] }}</span>
            </article>
        @endforeach
    </div>

    <div class="journey-progress" aria-hidden="true"><span data-journey-progress></span></div>
</section>

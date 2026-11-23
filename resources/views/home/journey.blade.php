@php
    $books = \App\Support\Catalog::books()->keyBy('slug');
    $stages = config('catalog.stages');
@endphp

<section class="journey" aria-labelledby="journey-title" data-journey>
    <div class="journey-track" data-journey-track>
        <div class="journey-intro">
            <p class="eyebrow eyebrow--light">Nursery to Grade 10</p>
            <h2 id="journey-title" data-split>Nursery to Grade 10, <em>one publisher</em></h2>
            <p>Each stage has its own series, written for how children learn at that age. Scroll to walk through them.</p>
        </div>

        @foreach ($stages as $i => $stage)
            @php($count = $books->whereIn('class', $stage['grades'])->count())
            <article class="stage">
                <div class="stage-copy">
                    <div class="stage-top">
                        <span class="stage-step">Stage {{ $i + 1 }} of {{ count($stages) }}</span>
                        <span class="stage-count">{{ $count }} titles</span>
                    </div>
                    <h3>{{ $stage['name'] }}</h3>
                    <div class="stage-classes">{{ $stage['classes'] }}</div>
                    <p>{{ $stage['text'] }}</p>
                    <ul class="stage-tags" aria-label="Includes">
                        @foreach ($stage['tags'] as $tag)
                            <li>{{ $tag }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('books.index', ['class' => $stage['grades'][0]]) }}" class="link-arrow">Browse {{ strtolower($stage['name']) }} books
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>
                <div class="stage-books" aria-hidden="true">
                    @foreach ($stage['covers'] as $slug)
                        @if ($book = $books->get($slug))
                            <x-book-cover :book="$book" :eager="true" />
                        @endif
                    @endforeach
                </div>
                <span class="stage-big" aria-hidden="true">{{ $stage['name'] }}</span>
            </article>
        @endforeach
    </div>

    <div class="journey-progress" aria-hidden="true"><span data-journey-progress></span></div>
</section>

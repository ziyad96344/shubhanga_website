@php($books = \App\Support\Catalog::books()->where('new', true))

<section class="section" aria-labelledby="new-title" style="padding-bottom: clamp(48px, 6vw, 96px)">
    <div class="wrap section-head section-head--split">
        <div style="display: grid; gap: 22px; justify-items: start">
            <p class="eyebrow">New releases</p>
            <h2 id="new-title" data-split>New this <em>session</em></h2>
            <p>Smart Scores for CBSE Grades 6 to 10: concept-driven practice with mind maps, worksheets and board-based assessments.</p>
        </div>
        <div class="carousel-nav">
            <button type="button" class="round-btn" aria-label="Previous books" data-track-prev>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
            </button>
            <button type="button" class="round-btn" aria-label="Next books" data-track-next>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
            </button>
        </div>
    </div>

    <div class="track" data-track data-reveal-cards>
        @foreach ($books as $book)
            <x-book-card :book="$book" />
        @endforeach
    </div>

    <div class="wrap" style="margin-top: 24px">
        <a href="{{ route('books.index', ['sort' => 'new']) }}" class="link-arrow">See all new books
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
    </div>
</section>

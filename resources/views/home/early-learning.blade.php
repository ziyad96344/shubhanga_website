@php($books = \App\Support\Catalog::books()->where('type', 'Pre-primary')->where('class', 'PP1')->take(4))

<section class="section early section--waves" aria-labelledby="early-title">
    <div class="wrap">
        <div class="section-head section-head--split">
            <div style="display: grid; gap: 22px; justify-items: start">
                <p class="eyebrow">Early learning</p>
                <h2 id="early-title" data-split>For the <em>youngest</em> readers</h2>
                <p>Thirteen pre-primary series for Nursery, PP1 and PP2: Rhymewing, FableNest, Mathspire, Soundnest, Tinkerhive and more.</p>
            </div>
            <a href="{{ route('books.index', ['section' => 'Pre-primary']) }}" class="btn btn--ghost">See all early learning</a>
        </div>
        <div class="book-grid" data-reveal-cards>
            @foreach ($books as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>
    </div>
</section>

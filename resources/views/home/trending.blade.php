@php($books = \App\Support\Catalog::books()->sortByDesc('reviews'))

<section class="section trending" aria-labelledby="trending-title">
    <div class="wrap">
        <div class="section-head section-head--split trending-head">
            <div style="display: grid; gap: 18px; justify-items: start">
                <p class="eyebrow">Curated collection</p>
                <h2 id="trending-title" data-split>Explore <em>our books</em></h2>
                <p>Pre-primary series, semester-wise textbooks, skill books and CBSE Smart Scores.</p>
            </div>

        <div class="tabs" role="tablist" aria-label="Book type" data-tabs>
            <span class="tab-pill" aria-hidden="true" data-tab-pill></span>
            @foreach (config('catalog.trending_tabs') as $label => $type)
                <button type="button" class="tab" role="tab"
                        id="tab-{{ $loop->index }}"
                        aria-controls="panel-{{ $loop->index }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        tabindex="{{ $loop->first ? '0' : '-1' }}">{{ $label }}</button>
            @endforeach
        </div>
        </div>

        @foreach (config('catalog.trending_tabs') as $label => $type)
            <div class="tab-panel" role="tabpanel" id="panel-{{ $loop->index }}" aria-labelledby="tab-{{ $loop->index }}"
                 @unless ($loop->first) hidden @endunless>
                <div class="book-grid">
                    @foreach ($books->where('type', $type)->take(4) as $book)
                        <x-book-card :book="$book" />
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

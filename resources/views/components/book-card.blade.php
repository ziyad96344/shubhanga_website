@props(['book'])

@php
    $off = $book['mrp'] > $book['price'] ? round((1 - $book['price'] / $book['mrp']) * 100) : 0;
    $classLabel = \App\Support\Catalog::classLabel($book['class']);
    $name = $book['name'] ?? trim($book['title'].' '.$book['subject']);
    $boardLabel = $book['board_label'] ?? $book['board'] ?? '';
@endphp

<article {{ $attributes->merge(['class' => 'book-card']) }}>
    @if ($book['badge'])
        <span class="badge {{ $book['badge'] === 'New' ? 'badge--new' : '' }}">{{ $book['badge'] }}</span>
    @endif

    <a href="{{ route('books.show', $book['slug']) }}" class="cover-link" aria-label="{{ $name }}, {{ $classLabel }}">
        <x-book-cover :book="$book" />
    </a>

    <div class="book-meta">
        <h3 class="book-title"><a href="{{ route('books.show', $book['slug']) }}">{{ $name }}</a></h3>
        <div class="book-chips">
            <span class="chip">{{ $classLabel }}</span>
            <span class="chip">{{ $boardLabel }}</span>
        </div>
        <div class="rating">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2.5 2.9 6 6.6.8-4.9 4.6 1.3 6.6L12 17.3l-5.9 3.2 1.3-6.6-4.9-4.6 6.6-.8z"/></svg>
            {{ number_format($book['rating'], 1) }} <span>({{ $book['reviews'] }} reviews)</span>
        </div>
        <div class="price">
            <strong>₹{{ number_format($book['price']) }}</strong>
            @if ($off)
                <s>₹{{ number_format($book['mrp']) }}</s>
                <em>{{ $off }}% off</em>
            @endif
        </div>
        @if (($book['stock'] ?? 1) <= 0)
            <span class="add-btn add-btn--off">Out of stock</span>
        @else
        <button type="button" class="add-btn"
                data-add-to-cart
                data-id="{{ $book['slug'] }}"
                data-name="{{ $name }}"
                data-meta="{{ $classLabel }}, {{ $boardLabel }}"
                data-price="{{ $book['price'] }}"
                data-image="{{ $book['image'] }}">
            Add to cart
        </button>
        @endif
    </div>
</article>

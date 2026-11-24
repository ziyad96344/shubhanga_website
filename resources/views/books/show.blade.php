@extends('layouts.app', [
    'title' => $book['name'].', '.$book['class_label'].' | '.config('site.full_name'),
    'description' => Str::limit($book['description'], 155),
    'image' => $book['image'],
])

@php
    $whatsapp = 'https://wa.me/'.preg_replace('/\D/', '', config('site.contact.phones.0.number'))
        .'?text='.rawurlencode("Hi, I'd like to order {$book['name']} ({$book['class_label']}, {$book['board_label']}).");
@endphp

@push('head')
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Book',
        'name' => $book['name'],
        'image' => url($book['image']),
        'bookFormat' => 'https://schema.org/Paperback',
        'inLanguage' => $book['language'],
        'educationalLevel' => $book['class_label'],
        'publisher' => ['@type' => 'Organization', 'name' => config('site.full_name')],
        'aggregateRating' => ['@type' => 'AggregateRating', 'ratingValue' => $book['rating'], 'reviewCount' => $book['reviews']],
        'offers' => ['@type' => 'Offer', 'price' => $book['price'], 'priceCurrency' => 'INR', 'availability' => 'https://schema.org/InStock', 'url' => url()->current()],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Books', 'item' => route('books.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $book['name'], 'item' => url()->current()],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
    <section class="product">
        <div class="wrap">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('books.index') }}">Books</a></li>
                    <li><a href="{{ route('books.index', ['class' => $book['class']]) }}">{{ $book['class_label'] }}</a></li>
                    <li><span aria-current="page">{{ $book['name'] }}</span></li>
                </ol>
            </nav>

            <div class="product-grid">
                {{-- Cover --}}
                <div class="product-media">
                    <div class="product-cover" data-tilt>
                        @if ($book['badge'])
                            <span class="badge {{ $book['badge'] === 'New' ? 'badge--new' : '' }}">{{ $book['badge'] }}</span>
                        @endif
                        <x-book-cover :book="$book" :eager="true" />
                    </div>
                    <p class="product-media-note">Sample chapters are available with every specimen copy.</p>
                </div>

                {{-- Buy box --}}
                <div class="product-info">
                    <p class="eyebrow">{{ $book['series'] }}</p>
                    <h1 class="product-title">{{ $book['name'] }}</h1>

                    <div class="book-chips">
                        <span class="chip">{{ $book['class_label'] }}</span>
                        <span class="chip">{{ $book['board_label'] }}</span>
                        <span class="chip">{{ $book['section_label'] }}</span>
                    </div>

                    <div class="rating">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2.5 2.9 6 6.6.8-4.9 4.6 1.3 6.6L12 17.3l-5.9 3.2 1.3-6.6-4.9-4.6 6.6-.8z"/></svg>
                        {{ number_format($book['rating'], 1) }} <span>({{ $book['reviews'] }} reviews)</span>
                    </div>

                    <div class="product-price">
                        <strong>₹{{ number_format($book['price']) }}</strong>
                        @if ($book['off'])
                            <s>₹{{ number_format($book['mrp']) }}</s>
                            <em>{{ $book['off'] }}% off</em>
                        @endif
                        <small>Inclusive of all taxes</small>
                    </div>

                    <p class="product-lede">{{ $book['description'] }}</p>

                    <div class="buy-row">
                        <div class="qty qty--large" role="group" aria-label="Quantity" data-qty-picker>
                            <button type="button" data-step="-1" aria-label="Remove one">−</button>
                            <span data-qty-value>1</span>
                            <button type="button" data-step="1" aria-label="Add one">+</button>
                        </div>
                        <button type="button" class="btn buy-add"
                                data-add-to-cart
                                data-id="{{ $book['slug'] }}"
                                data-name="{{ $book['name'] }}"
                                data-meta="{{ $book['class_label'] }}, {{ $book['board_label'] }}"
                                data-price="{{ $book['price'] }}"
                                data-image="{{ $book['image'] }}">
                            Add to cart
                        </button>
                    </div>
                    <a href="{{ $whatsapp }}" class="btn btn--ghost buy-whatsapp" target="_blank" rel="noopener">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.4.1-.6.3-.2.2-.8.8-.8 2s.8 2.3.9 2.4c.1.2 1.6 2.5 4 3.5 1.5.6 2.1.7 2.8.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2l-.5-.3Z"/></svg>
                        Order on WhatsApp
                    </a>

                    <ul class="assurances">
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 7h11v9H3zM14 10h4l3 3v3h-7zM7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4ZM17 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></svg>
                            <span><strong>Free shipping</strong> on prepaid orders above ₹499</span>
                        </li>
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18"/></svg>
                            <span><strong>Cash on delivery</strong> and UPI accepted</span>
                        </li>
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 8v4l3 2"/><circle cx="12" cy="12" r="9"/></svg>
                            <span><strong>Dispatched in 2 working days</strong> from Hyderabad</span>
                        </li>
                    </ul>

                    <div class="specimen-box">
                        <div>
                            <strong>Are you a teacher?</strong>
                            <p>Get a free specimen copy with the teacher manual for your school.</p>
                        </div>
                        <a href="{{ route('forms.show', 'specimen-copy') }}" class="link-arrow">Request a copy
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Details --}}
    <section class="section product-details">
        <div class="wrap details-grid">
            <div>
                <div class="tabs" role="tablist" aria-label="Book details" data-tabs>
                    <span class="tab-pill" aria-hidden="true" data-tab-pill></span>
                    <button type="button" class="tab" role="tab" id="dtab-0" aria-controls="dpanel-0" aria-selected="true" tabindex="0">About</button>
                    <button type="button" class="tab" role="tab" id="dtab-1" aria-controls="dpanel-1" aria-selected="false" tabindex="-1">What's inside</button>
                    <button type="button" class="tab" role="tab" id="dtab-2" aria-controls="dpanel-2" aria-selected="false" tabindex="-1">What schools say</button>
                </div>

                <div class="tab-panel detail-panel" role="tabpanel" id="dpanel-0" aria-labelledby="dtab-0">
                    <p>{{ $book['description'] }}</p>
                    <ul class="checklist">
                        <li>Mapped to the {{ count($book['boards']) >= 6 ? 'syllabus your school follows' : $book['board_label'].' syllabus' }}</li>
                        <li>Clear language, one idea per page, with worked examples</li>
                        <li>Answer key and teacher manual free with school orders</li>
                    </ul>
                </div>
                <div class="tab-panel detail-panel" role="tabpanel" id="dpanel-1" aria-labelledby="dtab-1" hidden>
                    <ol class="contents-list">
                        @foreach ($book['contents'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ol>
                </div>
                <div class="tab-panel detail-panel" role="tabpanel" id="dpanel-2" aria-labelledby="dtab-2" hidden>
                    <div class="review-summary">
                        <strong>{{ number_format($book['rating'], 1) }}</strong>
                        <span>out of 5, from {{ $book['reviews'] }} reviews</span>
                    </div>
                    @foreach (array_slice(config('catalog.stories'), 0, 2) as $story)
                        <blockquote class="review">“{{ $story['quote'] }}”<cite>{{ $story['name'] }}, {{ $story['role'] }}</cite></blockquote>
                    @endforeach
                </div>
            </div>

            <dl class="spec-table">
                <div><dt>Grade</dt><dd>{{ $book['class_label'] }}</dd></div>
                <div><dt>Board</dt><dd>{{ implode(', ', $book['boards']) }}</dd></div>
                <div><dt>Section</dt><dd>{{ $book['section_label'] }}</dd></div>
                <div><dt>Subject</dt><dd>{{ $book['subject'] }}</dd></div>
                <div><dt>Book type</dt><dd>{{ $book['type'] }}</dd></div>
                <div><dt>Language</dt><dd>{{ $book['language'] }}</dd></div>
            </dl>
        </div>
    </section>

    {{-- Related --}}
    <section class="section related">
        <div class="wrap">
            <div class="section-head section-head--split">
                <div style="display: grid; gap: 22px; justify-items: start">
                    <p class="eyebrow">You may also need</p>
                    <h2 data-split>More from <em>this shelf</em></h2>
                </div>
                <a href="{{ route('books.index') }}" class="link-arrow">All books
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
            <div class="book-grid" data-reveal-cards>
                @foreach ($related as $other)
                    <x-book-card :book="$other" />
                @endforeach
            </div>
        </div>
    </section>
@endsection

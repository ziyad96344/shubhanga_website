@php
    $seoParts = array_filter([
        $active['board'] ?? null,
        isset($active['section']) ? \App\Support\Catalog::sectionLabel($active['section']) : null,
        isset($active['class']) ? \App\Support\Catalog::classLabel($active['class']) : null,
        $active['subject'] ?? null,
    ]);
    $seoTitle = $seoParts ? implode(' ', $seoParts).' books' : 'School books, Nursery to Grade 10';
    $seoDescription = $seoParts
        ? 'Shubhanga '.implode(', ', $seoParts).' books: NEP 2020 aligned, syllabus-mapped and error-free. See every title with price and order online or through your school.'
        : 'Browse every Shubhanga book by board, section, grade and subject: pre-primary, primary, State Board and CBSE high school books for eleven states.';
    $seoCanonical = route('books.index', array_intersect_key($active, array_flip(['board', 'section', 'class', 'subject'])));
@endphp
@extends('layouts.app', [
    'title' => $seoTitle.' | '.config('site.full_name'),
    'description' => $seoDescription,
    'canonical' => $seoCanonical,
    'noindex' => request()->hasAny(['q', 'sort', 'page']) || count($active) > 2,
])

@php
    $labels = ['board' => 'Board', 'section' => 'Section', 'class' => 'Grade', 'subject' => 'Subject'];
    $display = fn ($key, $value) => match ($key) {
        'class' => \App\Support\Catalog::classLabel($value),
        'section' => \App\Support\Catalog::sectionLabel($value),
        default => $value,
    };
    $heading = match (true) {
        $q !== '' => 'Results for <em>“'.e($q).'”</em>',
        isset($active['board']) => e($active['board']).' <em>books</em>',
        isset($active['class']) => \App\Support\Catalog::classLabel($active['class']).' <em>books</em>',
        default => 'School <em>books</em>',
    };
@endphp

@section('content')
    @include('partials.page-head', [
        'crumbs' => ['Books' => null],
        'eyebrow' => 'Catalogue',
        'title' => $heading,
        'intro' => 'Nursery to Grade 10 for CBSE and eleven state boards. Pick your board, then the section and grade, just like our book lists by state.',
    ])

    <form method="get" action="{{ route('books.index') }}" class="wrap catalogue" data-catalogue>
        @if ($q !== '')<input type="hidden" name="q" value="{{ $q }}">@endif
        {{-- Filters: a sidebar on desktop, a slide-in panel on mobile --}}
        <aside class="filters" id="filters" data-filters aria-label="Filters">
            <div class="filters-head">
                <h2>Filters</h2>
                <button type="button" class="icon-btn filters-close" aria-label="Close filters" data-filters-close>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </div>

            @foreach ($filters as $key => $options)
                <fieldset class="filter-group">
                    <legend>{{ $labels[$key] }}</legend>
                    <div class="filter-options {{ $key === 'class' ? 'filter-options--chips' : '' }} {{ $key === 'subject' ? 'filter-options--scroll' : '' }}" @if ($key === 'subject') data-lenis-prevent @endif>
                        <label class="filter-option">
                            <input type="radio" name="{{ $key }}" value="" @checked(! isset($active[$key]))>
                            <span>All</span>
                        </label>
                        @foreach ($options as $option)
                            <label class="filter-option">
                                <input type="radio" name="{{ $key }}" value="{{ $option }}" @checked(($active[$key] ?? null) === $option)>
                                <span>{{ $key === 'section' ? \App\Support\Catalog::sectionLabel($option) : $option }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            @endforeach

            <div class="filters-foot">
                <button type="submit" class="btn">Show results</button>
                @if ($active)
                    <a href="{{ route('books.index') }}" class="link-arrow">Clear all</a>
                @endif
            </div>
        </aside>

        <div class="catalogue-main">
            <div class="toolbar">
                <p class="result-count"><strong>{{ $books->total() }}</strong> {{ Str::plural('book', $books->total()) }}</p>

                <div class="toolbar-right">
                    <button type="button" class="btn btn--ghost filters-open" aria-controls="filters" aria-expanded="false" data-filters-open>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
                        Filters @if ($active)({{ count($active) }})@endif
                    </button>
                    <label class="sort">
                        <span>Sort</span>
                        <select name="sort" class="select" data-autosubmit>
                            @foreach ($sorts as $value => $label)
                                <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>

            @if ($active || $q !== '')
                <ul class="active-filters" aria-label="Active filters">
                    @if ($q !== '')
                        <li>
                            <a href="{{ route('books.index', Arr::except(request()->query(), ['q', 'page'])) }}" aria-label="Clear search">
                                “{{ $q }}”
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                            </a>
                        </li>
                    @endif
                    @foreach ($active as $key => $value)
                        <li>
                            <a href="{{ route('books.index', Arr::except(request()->query(), [$key, 'page'])) }}" aria-label="Remove {{ $labels[$key] }}: {{ $display($key, $value) }}">
                                {{ $display($key, $value) }}
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            @if ($books->count())
                <div class="book-grid book-grid--catalogue" data-reveal-cards>
                    @foreach ($books as $book)
                        <x-book-card :book="$book" />
                    @endforeach
                </div>

                @if ($books->hasPages())
                    <nav class="pager" aria-label="Pages">
                        @if ($books->onFirstPage())
                            <span class="round-btn is-disabled" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M15 6l-6 6 6 6"/></svg>
                            </span>
                        @else
                            <a class="round-btn" href="{{ $books->previousPageUrl() }}" aria-label="Previous page">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
                            </a>
                        @endif
                        <span class="pager-status">Page {{ $books->currentPage() }} of {{ $books->lastPage() }}</span>
                        @if ($books->hasMorePages())
                            <a class="round-btn" href="{{ $books->nextPageUrl() }}" aria-label="Next page">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
                            </a>
                        @else
                            <span class="round-btn is-disabled" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M9 6l6 6-6 6"/></svg>
                            </span>
                        @endif
                    </nav>
                @endif
            @else
                <div class="empty-state">
                    <h2>No books match these filters</h2>
                    <p>Try removing a filter, or browse the full catalogue.</p>
                    <a href="{{ route('books.index') }}" class="btn">See all books</a>
                </div>
            @endif
        </div>
    </form>

    <div class="filters-backdrop" data-filters-close></div>
@endsection

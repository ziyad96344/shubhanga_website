@php
    $catalog = config('catalog');
@endphp

<header class="site-header {{ str_contains($__env->yieldContent('body_class'), 'has-hero') ? 'is-clear' : '' }}" data-header>
    <div class="wrap header-row">
        @include('partials.logo')

        <nav class="main-nav" aria-label="Main">
            <ul>
                <li><a href="{{ route('books.index', ['section' => 'Pre-primary']) }}">Pre-primary</a></li>
                <li>
                    <button type="button" aria-expanded="false" aria-controls="mega-school" data-mega-toggle>
                        School books
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                </li>
                <li><a href="{{ route('books.index', ['section' => 'CBSE High School']) }}">Smart Scores</a></li>
                <li><a href="{{ route('resources') }}">Free resources</a></li>
                <li><a href="{{ route('forms.show', 'specimen-copy') }}">For teachers</a></li>
                <li><a href="{{ route('blog.index') }}">Blog</a></li>
            </ul>
        </nav>

        <div class="header-actions">
            <button type="button" class="icon-btn" aria-label="Search books" aria-expanded="false" aria-controls="site-search" data-search-open>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            </button>
            <a href="{{ route('orders.track') }}" class="icon-btn account-link" aria-label="Track your order">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
            </a>
            <button type="button" class="icon-btn" aria-label="Open cart" data-cart-open>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 7h14l-1.2 12.1a2 2 0 0 1-2 1.9H8.2a2 2 0 0 1-2-1.9z"/><path d="M9 10V6a3 3 0 0 1 6 0v4"/></svg>
                <span class="cart-count" data-cart-count>0</span>
            </button>
            <button type="button" class="icon-btn menu-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-menu" data-menu-open>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
            </button>
        </div>
    </div>

    <div class="search-panel" id="site-search" data-search hidden>
        <form action="{{ route('books.index') }}" method="get" class="wrap search-form" role="search">
            <label for="search-q" class="sr-only">Search books</label>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input id="search-q" type="search" name="q" placeholder="Search by title, subject or class, e.g. class 10 science" autocomplete="off" data-search-input data-suggest-url="{{ route('books.suggest') }}">
            <button type="button" class="icon-btn" aria-label="Close search" data-search-close>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
            </button>
        </form>
        <div class="wrap">
            <ul class="search-results" data-search-results aria-live="polite"></ul>
        </div>
    </div>

    @php
        $bookCount = count($catalog['books']);
        $gradeGroups = [
            'Pre-primary' => ['Nursery', 'PP1', 'PP2'],
            'Primary' => ['1', '2', '3', '4', '5'],
            'Middle and high' => ['6', '7', '8', '9', '10'],
        ];
    @endphp
    <div class="mega" id="mega-school" data-mega>
        <div class="wrap mega-grid">
            <div class="mega-item">
                <h3>By board</h3>
                <ul class="mega-boards">
                    @foreach ($catalog['boards'] as $board)
                        <li><a href="{{ route('books.index', ['board' => $board]) }}">{{ $board }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="mega-item">
                <h3>By grade</h3>
                <div class="mega-grades">
                    @foreach ($gradeGroups as $group => $classes)
                        <div class="mega-grade-row">
                            <span class="mega-grade-label">{{ $group }}</span>
                            <ul>
                                @foreach ($classes as $class)
                                    <li><a href="{{ route('books.index', ['class' => $class]) }}" aria-label="{{ \App\Support\Catalog::classLabel($class) }}">{{ $class }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mega-item">
                <h3>By section</h3>
                <ul class="mega-list">
                    @foreach ($catalog['sections'] as $section => $info)
                        <li>
                            <a href="{{ route('books.index', ['section' => $section]) }}">
                                <span>{{ $info['label'] }}</span>
                                <small>{{ $info['note'] }}</small>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="mega-item mega-side">
                <a href="{{ route('books.index', ['section' => 'CBSE High School']) }}" class="mega-feature">
                    <x-book-cover :book="\App\Support\Catalog::books()->firstWhere('series', 'Smart Scores')" />
                    <p><strong>Smart Scores</strong>CBSE practice books, Grades 6 to 10.</p>
                </a>
                <a href="{{ route('books.index') }}" class="link-arrow mega-all">All {{ $bookCount }} books
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
        </div>
    </div>
</header>

<div class="mobile-menu" id="mobile-menu" data-mobile-menu aria-hidden="true">
    <div class="top">
        @include('partials.logo')
        <button type="button" class="icon-btn" aria-label="Close menu" data-menu-close>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
        </button>
    </div>
    <nav aria-label="Mobile">
        <a href="{{ route('books.index', ['section' => 'Pre-primary']) }}">Pre-primary</a>
        <a href="{{ route('books.index') }}">School books</a>
        <a href="{{ route('books.index', ['section' => 'CBSE High School']) }}">Smart Scores</a>
        <a href="{{ route('resources') }}">Free resources</a>
        <a href="{{ route('forms.show', 'specimen-copy') }}">For teachers</a>
        <a href="{{ route('blog.index') }}">Blog</a>
        <a href="{{ route('about') }}">About us</a>
        <a href="{{ route('forms.show', 'contact') }}">Contact</a>
    </nav>
    <div class="classes">
        @foreach ($catalog['classes'] as $class)
            <a href="{{ route('books.index', ['class' => $class]) }}">{{ $class }}</a>
        @endforeach
    </div>
    @include('partials.social', ['class' => 'social--menu'])
</div>

@php($catalog = config('catalog'))

<section class="hero" aria-labelledby="hero-title">
    <div class="hero-bg" aria-hidden="true">
        <img src="{{ config('site.hero_image') }}" alt="" fetchpriority="high" data-hero-bg>
    </div>
    <div class="hero-frame" aria-hidden="true"></div>


    <div class="wrap hero-grid">
        <div>
            <p class="eyebrow eyebrow--light" data-hero-fade>NEP 2020 aligned school books</p>
            <h1 id="hero-title" class="display" data-hero-title>
                <span class="line">From the first alphabet</span>
                <span class="line"><em>to the board exam.</em></span>
            </h1>
            <p class="hero-lede" data-hero-fade>
                Textbooks, skill books and practice books from Nursery to Grade 10 for CBSE and state board schools across eleven states.
            </p>
            <div class="hero-ctas" data-hero-fade>
                <a href="{{ route('books.index') }}" class="btn btn--gold">Browse books
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
                <a href="{{ route('forms.show', 'specimen-copy') }}" class="btn btn--ghost-light">Request a specimen copy</a>
            </div>
        </div>

        <div class="finder" data-hero-fade>
            <h2>Find your books</h2>
            <p>Choose a syllabus and grade to see the full book list.</p>
            <form action="{{ route('books.index') }}" method="get">
                <div class="row">
                    <label>Syllabus
                        <select class="select" name="board">
                            @foreach ($catalog['boards'] as $board)
                                <option>{{ $board }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>Grade
                        <select class="select" name="class">
                            @foreach ($catalog['classes'] as $class)
                                <option value="{{ $class }}" @selected($class === '6')>{{ \App\Support\Catalog::classLabel($class) }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <button type="submit" class="btn">Show books
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </button>
            </form>
        </div>
    </div>

</section>

<section class="section tone-dark stories" aria-labelledby="stories-title">
    <div class="wrap section-head section-head--split stories-head">
        <div style="display: grid; gap: 18px; justify-items: start">
            <p class="eyebrow">In their words</p>
            <h2 id="stories-title" data-split>Inspiring minds, <em>enriching lives</em></h2>
            <p>Feedback from students, teachers and principals who use our books every day.</p>
        </div>
        <div class="carousel-nav">
            <button type="button" class="round-btn" aria-label="Previous reviews" data-track-prev>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
            </button>
            <button type="button" class="round-btn" aria-label="Next reviews" data-track-next>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
            </button>
        </div>
    </div>

    <div class="track story-track" data-track data-reveal-cards>
        @foreach (config('catalog.stories') as $story)
            @php
                $initials = collect(explode(' ', preg_replace('/^(Mrs?\.|Ms\.)\s*/', '', $story['name'])))
                    ->map(fn ($part) => mb_substr($part, 0, 1))
                    ->take(2)
                    ->implode('');
            @endphp
            <figure class="story-card">
                <div class="story-top">
                    <span class="story-quote" aria-hidden="true">
                        <svg width="18" height="14" viewBox="0 0 34 26"><path d="M0 26V15C0 6.7 4.4 1.7 13.2 0l1.6 3.6C10 5 7.7 7.9 7.4 12H14v14H0Zm19 0V15c0-8.3 4.4-13.3 13.2-15l1.6 3.6C29 5 26.7 7.9 26.4 12H33v14H19Z" fill="currentColor"/></svg>
                    </span>
                    <span class="story-stars" aria-label="5 out of 5">
                        @for ($i = 0; $i < 5; $i++)
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2.5 2.9 6 6.6.8-4.9 4.6 1.3 6.6L12 17.3l-5.9 3.2 1.3-6.6-4.9-4.6 6.6-.8z"/></svg>
                        @endfor
                    </span>
                </div>
                <blockquote>{{ $story['quote'] }}</blockquote>
                <figcaption class="story-meta">
                    <span class="story-avatar" aria-hidden="true">{{ $initials }}</span>
                    <span>
                        <strong>{{ $story['name'] }}</strong>
                        <span>{{ $story['role'] }}</span>
                    </span>
                </figcaption>
            </figure>
        @endforeach
    </div>
</section>

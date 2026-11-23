<section class="section" aria-labelledby="stories-title">
    <div class="wrap">
        <div class="section-head section-head--split">
            <div style="display: grid; gap: 22px; justify-items: start">
                <p class="eyebrow">In their words</p>
                <h2 id="stories-title" data-split>Inspiring minds, <em>enriching lives</em></h2>
            </div>
            <p>Feedback from students, teachers and principals who use our books every day.</p>
        </div>

        <div class="story-grid" data-reveal-cards>
            @foreach (config('catalog.stories') as $story)
                @php
                    $initials = collect(explode(' ', preg_replace('/^(Mrs?\.|Ms\.)\s*/', '', $story['name'])))
                        ->map(fn ($part) => mb_substr($part, 0, 1))
                        ->take(2)
                        ->implode('');
                @endphp
                <figure class="story-card">
                    <div class="story-top">
                        <svg class="story-quote" width="34" height="26" viewBox="0 0 34 26" aria-hidden="true"><path d="M0 26V15C0 6.7 4.4 1.7 13.2 0l1.6 3.6C10 5 7.7 7.9 7.4 12H14v14H0Zm19 0V15c0-8.3 4.4-13.3 13.2-15l1.6 3.6C29 5 26.7 7.9 26.4 12H33v14H19Z" fill="currentColor"/></svg>
                        @if ($story['score'])<span class="story-score">{{ $story['score'] }}</span>@endif
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
    </div>
</section>

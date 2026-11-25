<section class="section tone-doodle" aria-label="Free resources and articles">
    <div class="wrap res-grid">
        <div>
            <div class="section-head" style="margin-bottom: 32px">
                <p class="eyebrow">Resources</p>
                <h2 data-split>Free to <em>download</em></h2>
                <p>Solutions, mind maps and syllabus files for students and teachers.</p>
            </div>
            <ul class="res-list">
                @foreach (config('catalog.resources') as $res)
                    <li>
                        <a href="{{ route('resources') }}">
                            <span><strong>{{ $res['title'] }}</strong><span>{{ $res['meta'] }}</span></span>
                            <span class="res-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16205B" stroke-width="2.6" stroke-linecap="round"><path d="M12 4v12M6 11l6 6 6-6M5 20h14"/></svg>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <div class="section-head section-head--split" style="margin-bottom: 32px">
                <div style="display: grid; gap: 22px; justify-items: start"><p class="eyebrow">Journal</p><h2 data-split>From the <em>blog</em></h2></div>
                <a href="{{ route('blog.index') }}" class="link-arrow">All articles
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
            <div class="posts">
                @foreach (config('content.posts') as $post)
                    <article class="post">
                        <a href="{{ route('blog.show', $post['slug']) }}">
                            <div class="post-thumb" data-thumb><img src="{{ $post['image'] }}" alt="" loading="lazy"></div>
                            <span class="post-tag">{{ $post['tag'] }}</span>
                            <h3>{{ $post['title'] }}</h3>
                            @if ($post['date'])<time>{{ $post['date'] }}</time>@endif
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

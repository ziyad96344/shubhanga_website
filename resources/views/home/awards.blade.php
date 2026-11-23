@php($awards = config('catalog.awards'))

<section class="awards" aria-label="Highlights">
    <div class="marquee">
        @foreach ([false, true] as $duplicate)
            <ul @if ($duplicate) aria-hidden="true" @endif>
                @foreach ($awards as $award)
                    <li>
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="9" r="6"/><path d="m8.5 14-1.5 8 5-3 5 3-1.5-8"/></svg>
                        {{ $award }}
                    </li>
                @endforeach
            </ul>
        @endforeach
    </div>
</section>

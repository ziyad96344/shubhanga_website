@php($book = \App\Support\Catalog::books()->firstWhere('slug', 'smart-scores-mathematics-part-1-class-10-cbse') ?? \App\Support\Catalog::books()->first())

<section class="inside" aria-labelledby="inside-title" data-inside>
    <div class="wrap inside-grid">
        <div class="inside-copy">
            <p class="eyebrow">Look inside</p>
            <h2 id="inside-title" data-split>Open any chapter <em>before you order</em></h2>
            <p>Every book page has sample chapters, so teachers can check the language, exercises and layout first.</p>
            <ul>
                <li>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="m4 12 5 5L20 6"/></svg>
                    Aligned to NEP 2020 and NCF 2023
                </li>
                <li>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="m4 12 5 5L20 6"/></svg>
                    An activity or worked example on every spread
                </li>
                <li>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="m4 12 5 5L20 6"/></svg>
                    Multi-reviewed, error-free content
                </li>
            </ul>
            <a href="{{ route('forms.show', 'specimen-copy') }}" class="btn btn--ghost" style="margin-top: 32px">Request a specimen copy</a>
        </div>

        {{-- A real book: the cover is hinged on the spine. Its inside face IS the
             left page, so turning the cover lays Chapter 4 down on the left while
             the exercise page sits underneath on the right. --}}
        <div class="book3d" data-book3d>
            <div class="spread">
                <div class="page-slot" aria-hidden="true"></div>
                <div class="page page--right">
                    <div class="page-q">Try these</div>
                    @php($tick = '<svg class="tick" viewBox="0 0 24 20" aria-hidden="true"><path d="M2 11l6 6L22 3" fill="none" stroke="#c8102e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" data-mark/></svg>')
                    <ol>
                        <li>½ + ¼ = <span class="ans">¾</span> {!! $tick !!}</li>
                        <li>Shade ⅔ of a square. <span class="ans">done</span> {!! $tick !!}</li>
                        <li>Which is bigger, ⅗ or ½? <span class="ans">⅗</span> {!! $tick !!}</li>
                        <li>Write 0.75 as a fraction.
                            <span class="ans ringed">¾
                                <svg viewBox="0 0 60 40" aria-hidden="true"><path d="M8 26C6 10 40 2 52 14s-8 24-30 22S6 20 20 8" fill="none" stroke="#c8102e" stroke-width="2.4" stroke-linecap="round" data-mark/></svg>
                            </span>
                        </li>
                    </ol>
                    <span class="page-num">49</span>
                    <span class="hand very-good" data-mark-text>Very good!</span>
                </div>
            </div>
            <div class="front-cover" data-front-cover>
                <div class="face face--front"><x-book-cover :book="$book" /></div>
                <div class="face face--back">
                    <div class="page page--left">
                        <div class="page-chapter">Chapter 4</div>
                        <h4>Fractions</h4>
                        <p>Riya cuts a chapati into 4 equal parts and eats 1. What part of the chapati is left?</p>
                        <svg class="pie" viewBox="0 0 100 100" aria-hidden="true">
                            <circle cx="50" cy="50" r="44" fill="#f6c94c" stroke="#221518" stroke-width="3"/>
                            <path d="M50 50V6A44 44 0 0 1 94 50Z" fill="#fffdf8" stroke="#221518" stroke-width="3" stroke-linejoin="round"/>
                            <path d="M50 6v88M6 50h88" stroke="#221518" stroke-width="3"/>
                        </svg>
                        <span class="page-num">48</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

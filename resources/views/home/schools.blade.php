<section class="section board tone-paper" aria-labelledby="schools-title">
    <div class="wrap" style="position: relative">
        <div class="section-head section-head--split">
            <div style="display: grid; gap: 22px; justify-items: start">
                <p class="eyebrow">For schools</p>
                <h2 id="schools-title" data-split>Working with <em>schools and booksellers</em></h2>
                <p>Trusted by 1000+ schools across India for quality, error-free, syllabus-mapped learning resources.</p>
            </div>
            <span class="chalk" aria-hidden="true">replies within 2 working days</span>
        </div>

        <div class="board-rows">
            <div class="board-row" data-board-row>
                <h3><span>for teachers</span>Get a specimen copy</h3>
                <p>Tell us your school, class and subject. We post a free inspection copy with the teacher manual.</p>
                <a href="{{ route('forms.show', 'specimen-copy') }}" class="btn">Request a copy</a>
            </div>
            <div class="board-row" data-board-row>
                <h3><span>for booksellers</span>Become a distributor</h3>
                <p>Margins, credit terms and territory details for bookshops and stockists who want to carry our list.</p>
                <a href="{{ route('forms.show', 'become-a-distributor') }}" class="btn btn--ghost">Apply to stock our books</a>
            </div>
            <div class="board-row" data-board-row>
                <h3><span>for every region</span>Order from your region</h3>
                <p>Regional order desks for Karnataka, Andhra Pradesh, Telangana, CBSE schools and more.</p>
                <a href="{{ route('bookshops') }}" class="btn btn--ghost">See regional contacts</a>
            </div>
        </div>
    </div>
</section>

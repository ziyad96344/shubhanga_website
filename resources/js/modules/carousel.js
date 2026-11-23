/**
 * Horizontal book track: arrow buttons plus mouse drag-to-scroll.
 * Touch devices use native scrolling with snap points.
 */
export function initCarousel() {
    document.querySelectorAll('[data-track]').forEach((track) => {
        const section = track.closest('section');
        const prev = section.querySelector('[data-track-prev]');
        const next = section.querySelector('[data-track-next]');

        const step = () => {
            const card = track.firstElementChild;
            const gap = parseFloat(getComputedStyle(track).columnGap) || 24;
            return (card?.offsetWidth || 240) + gap;
        };

        const update = () => {
            const max = track.scrollWidth - track.clientWidth - 2;
            if (prev) prev.disabled = track.scrollLeft <= 2;
            if (next) next.disabled = track.scrollLeft >= max;
        };

        prev?.addEventListener('click', () => track.scrollBy({ left: -step() * 2, behavior: 'smooth' }));
        next?.addEventListener('click', () => track.scrollBy({ left: step() * 2, behavior: 'smooth' }));
        track.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        update();

        // Mouse drag
        let startX = 0;
        let startScroll = 0;
        let down = false;
        let moved = false;

        track.addEventListener('pointerdown', (e) => {
            if (e.pointerType !== 'mouse' || e.target.closest('button')) return;
            down = true;
            moved = false;
            startX = e.clientX;
            startScroll = track.scrollLeft;
        });

        window.addEventListener('pointermove', (e) => {
            if (!down) return;
            const dx = e.clientX - startX;
            if (Math.abs(dx) > 5 && !moved) {
                moved = true;
                track.classList.add('is-dragging');
            }
            if (moved) track.scrollLeft = startScroll - dx;
        });

        window.addEventListener('pointerup', () => {
            if (!down) return;
            down = false;
            track.classList.remove('is-dragging');
        });

        track.addEventListener('dragstart', (e) => e.preventDefault());
    });
}

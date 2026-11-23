/**
 * Catalogue filters and the book page quantity picker.
 *
 * Desktop: changing any filter or the sort order reloads results at once.
 * Mobile: filters live in a slide-in panel; choices apply on "Show results".
 */
export function initCatalogue(lenis) {
    const form = document.querySelector('[data-catalogue]');
    if (form) {
        const panel = form.querySelector('[data-filters]');
        const opener = form.querySelector('[data-filters-open]');
        const backdrop = document.querySelector('.filters-backdrop');
        const isMobile = () => window.matchMedia('(max-width: 999px)').matches;

        const submit = () => {
            // Drop empty params ("All") so URLs stay clean.
            [...form.elements].forEach((el) => {
                if (el.name && (!el.value || (el.name === 'sort' && el.value === 'popular'))) el.disabled = true;
            });
            form.submit();
        };

        form.addEventListener('change', (e) => {
            if (e.target.matches('[data-autosubmit]') || (!isMobile() && e.target.type === 'radio')) submit();
        });
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            submit();
        });

        const setOpen = (open) => {
            panel.classList.toggle('is-open', open);
            backdrop?.classList.toggle('is-open', open);
            opener?.setAttribute('aria-expanded', String(open));
            open ? lenis?.stop() : lenis?.start();
        };
        opener?.addEventListener('click', () => setOpen(true));
        document.querySelectorAll('[data-filters-close]').forEach((el) => el.addEventListener('click', () => setOpen(false)));
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && panel.classList.contains('is-open')) setOpen(false);
        });
    }

    // Quantity picker next to "Add to cart" on the book page.
    document.querySelectorAll('[data-qty-picker]').forEach((picker) => {
        const value = picker.querySelector('[data-qty-value]');
        const addBtn = picker.parentElement.querySelector('[data-add-to-cart]');
        let qty = 1;
        picker.addEventListener('click', (e) => {
            const step = e.target.closest('[data-step]');
            if (!step) return;
            qty = Math.min(50, Math.max(1, qty + Number(step.dataset.step)));
            value.textContent = qty;
            if (addBtn) addBtn.dataset.qty = qty;
        });
    });
}

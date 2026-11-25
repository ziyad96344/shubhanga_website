/**
 * Admin panel behaviour: the mobile sidebar, delete confirmations, cover
 * preview, filters that submit on change, and the slug helper.
 */
document.addEventListener('DOMContentLoaded', () => {
    // Mobile sidebar
    const side = document.querySelector('[data-side]');
    const backdrop = document.querySelector('[data-backdrop]');
    const setOpen = (open) => {
        side?.classList.toggle('is-open', open);
        backdrop?.classList.toggle('is-open', open);
    };
    document.querySelector('[data-menu]')?.addEventListener('click', () => setOpen(true));
    backdrop?.addEventListener('click', () => setOpen(false));
    document.addEventListener('keydown', (e) => e.key === 'Escape' && setOpen(false));

    // "Are you sure?" on destructive forms
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            if (!window.confirm(form.dataset.confirm)) e.preventDefault();
        });
    });

    // Filters: selects submit straight away
    document.querySelectorAll('[data-autosubmit]').forEach((el) => {
        el.addEventListener('change', () => el.form.requestSubmit());
    });

    // Cover upload preview
    document.querySelectorAll('[data-cover-input]').forEach((input) => {
        const img = document.querySelector(input.dataset.coverInput);
        input.addEventListener('change', () => {
            const file = input.files?.[0];
            if (file && img) img.src = URL.createObjectURL(file);
        });
    });

    // Flash messages fade after a few seconds
    document.querySelectorAll('[data-flash]').forEach((flash) => {
        setTimeout(() => {
            flash.style.transition = 'opacity .5s, transform .5s';
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-6px)';
            setTimeout(() => flash.remove(), 500);
        }, 5000);
    });
});

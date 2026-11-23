/**
 * Sticky header that hides on scroll down, the "School books" mega menu
 * and the full-screen mobile menu.
 */
export function initHeader(lenis) {
    const header = document.querySelector('[data-header]');
    if (!header) return;

    // Hide on scroll down, show on scroll up.
    let lastY = window.scrollY;
    const onScroll = () => {
        const y = window.scrollY;
        const goingDown = y > lastY && y > 240;
        header.classList.toggle('is-hidden', goingDown && !mega?.classList.contains('is-open'));
        lastY = y;
    };
    window.addEventListener('scroll', onScroll, { passive: true });

    // Mega menu: opens on hover (desktop pointer) and on click / keyboard.
    const toggle = header.querySelector('[data-mega-toggle]');
    const mega = header.querySelector('[data-mega]');
    let closeTimer;

    // Transparent while the hero sits under the bar; solid once it has
    // scrolled past, or whenever the mega menu is open.
    const hero = document.querySelector('.hero');
    let overHero = false;
    const updateClear = () => {
        header.classList.toggle('is-clear', overHero && !mega?.classList.contains('is-open'));
    };
    if (hero && document.body.classList.contains('has-hero')) {
        const observe = () => {
            const io = new IntersectionObserver(([entry]) => {
                overHero = entry.isIntersecting;
                updateClear();
            }, { rootMargin: `-${header.offsetHeight}px 0px 0px 0px` });
            io.observe(hero);
        };
        overHero = hero.getBoundingClientRect().bottom > header.offsetHeight;
        updateClear();
        observe();
    }

    const setMega = (open) => {
        clearTimeout(closeTimer);
        mega.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', String(open));
        updateClear();
    };

    if (toggle && mega) {
        toggle.addEventListener('click', () => setMega(!mega.classList.contains('is-open')));

        const canHover = window.matchMedia('(hover: hover)').matches;
        if (canHover) {
            toggle.addEventListener('mouseenter', () => setMega(true));
            [toggle, mega].forEach((el) => {
                el.addEventListener('mouseleave', () => {
                    closeTimer = setTimeout(() => setMega(false), 180);
                });
                el.addEventListener('mouseenter', () => clearTimeout(closeTimer));
            });
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mega.classList.contains('is-open')) {
                setMega(false);
                toggle.focus();
            }
        });

        document.addEventListener('click', (e) => {
            if (!header.contains(e.target)) setMega(false);
        });
    }

    // Mobile menu
    const menu = document.querySelector('[data-mobile-menu]');
    const openBtn = document.querySelector('[data-menu-open]');
    const closeBtn = document.querySelector('[data-menu-close]');

    const setMenu = (open) => {
        menu.classList.toggle('is-open', open);
        menu.setAttribute('aria-hidden', String(!open));
        openBtn.setAttribute('aria-expanded', String(open));
        document.body.style.overflow = open ? 'hidden' : '';
        open ? lenis?.stop() : lenis?.start();
        if (open) closeBtn.focus();
        else openBtn.focus();
    };

    if (menu && openBtn && closeBtn) {
        openBtn.addEventListener('click', () => setMenu(true));
        closeBtn.addEventListener('click', () => setMenu(false));
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && menu.classList.contains('is-open')) setMenu(false);
        });
    }
}

import { gsap } from 'gsap';
import { prefersReducedMotion } from './smooth-scroll';

/**
 * Accessible tabs with a sliding pill and a short card flip when the
 * panel changes.
 */
export function initTabs() {
    document.querySelectorAll('[data-tabs]').forEach((list) => {
        const tabs = [...list.querySelectorAll('[role="tab"]')];
        const pill = list.querySelector('[data-tab-pill]');

        const movePill = (tab) => {
            pill.style.width = tab.offsetWidth + 'px';
            pill.style.transform = `translateX(${tab.offsetLeft}px)`;
        };

        const select = (tab, focus = false) => {
            tabs.forEach((t) => {
                const on = t === tab;
                t.setAttribute('aria-selected', String(on));
                t.tabIndex = on ? 0 : -1;
                const panel = document.getElementById(t.getAttribute('aria-controls'));
                panel.hidden = !on;
                if (on && !prefersReducedMotion()) {
                    gsap.fromTo(panel.querySelectorAll('.book-card'),
                        { y: 40, rotationY: -25, opacity: 0, transformPerspective: 900 },
                        { y: 0, rotationY: 0, opacity: 1, duration: 0.7, stagger: 0.07, ease: 'power3.out', clearProps: 'transform' });
                }
            });
            movePill(tab);
            if (focus) tab.focus();
        };

        tabs.forEach((tab, i) => {
            tab.addEventListener('click', () => select(tab));
            tab.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') select(tabs[(i + 1) % tabs.length], true);
                if (e.key === 'ArrowLeft') select(tabs[(i - 1 + tabs.length) % tabs.length], true);
            });
        });

        const current = tabs.find((t) => t.getAttribute('aria-selected') === 'true') || tabs[0];
        movePill(current);
        window.addEventListener('resize', () => movePill(tabs.find((t) => t.getAttribute('aria-selected') === 'true')));
        document.fonts?.ready.then(() => movePill(tabs.find((t) => t.getAttribute('aria-selected') === 'true')));
    });
}

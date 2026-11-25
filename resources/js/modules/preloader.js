import { gsap } from 'gsap';
import { prefersReducedMotion } from './smooth-scroll';

/**
 * First-visit preloader: the Shubhanga logo, tagline and a 0–100 counter,
 * then the paper wipes upward. Skipped on later pages of the same visit
 * (the inline script in the layout adds `is-return`) and for reduced motion.
 *
 * Resolves when the page is ready to show, so the hero intro can wait.
 */
export function runPreloader() {
    const el = document.querySelector('[data-preloader]');
    const root = document.documentElement;

    const done = () => {
        el?.remove();
        try { sessionStorage.setItem('sp-seen', '1'); } catch { /* storage blocked */ }
    };

    if (!el || root.classList.contains('is-return') || prefersReducedMotion()) {
        done();
        return Promise.resolve();
    }

    return new Promise((resolve) => {
        const count = el.querySelector('[data-preloader-count]');
        const counter = { v: 0 };

        // The counter runs to 100 over ~2.6s; the wipe waits for that and
        // for the page (fonts and hero photo) to finish loading.
        const loaded = document.readyState === 'complete'
            ? Promise.resolve()
            : new Promise((r) => window.addEventListener('load', r, { once: true }));

        const intro = gsap.timeline()
            .from(el.querySelector('.preloader-logo'), { y: 30, scale: 0.92, opacity: 0, duration: 1.1, ease: 'power4.out' }, 0)
            .from(el.querySelector('.preloader-tag'), { opacity: 0, y: 10, duration: 0.8 }, 0.5)
            .to(counter, {
                v: 100,
                duration: 2.6,
                ease: 'power2.inOut',
                onUpdate: () => (count.textContent = Math.round(counter.v)),
            }, 0)
            .to({}, { duration: 0.35 }); // a beat at 100

        Promise.all([intro.then(), loaded]).then(() => {
            gsap.timeline({ onComplete: done })
                .add(() => resolve(), 0.15)
                .to(el, { clipPath: 'inset(0% 0% 100% 0%)', duration: 1.1, ease: 'power4.inOut' }, 0);
        });
    });
}

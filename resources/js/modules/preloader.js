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

        gsap.timeline({ onComplete: () => { done(); } })
            .from(el.querySelector('.preloader-logo'), { y: 30, scale: 0.92, opacity: 0, duration: 0.9, ease: 'power4.out' }, 0)
            .from(el.querySelector('.preloader-tag'), { opacity: 0, y: 10, duration: 0.6 }, 0.35)
            .to(counter, {
                v: 100,
                duration: 1.4,
                ease: 'power2.inOut',
                onUpdate: () => (count.textContent = Math.round(counter.v)),
            }, 0)
            .add(() => resolve(), 1.55)
            .to(el, { clipPath: 'inset(0% 0% 100% 0%)', duration: 0.9, ease: 'power4.inOut' }, 1.5);
    });
}

import Lenis from 'lenis';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export const prefersReducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Lenis smooth scrolling, driven by GSAP's ticker so ScrollTrigger
 * and Lenis stay in sync. Skipped when the user prefers reduced motion.
 */
export function initSmoothScroll() {
    if (prefersReducedMotion()) return null;

    const lenis = new Lenis({ lerp: 0.11, smoothWheel: true });

    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((time) => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);

    return lenis;
}

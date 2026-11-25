import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);

const $ = (sel, root = document) => root.querySelector(sel);
const $$ = (sel, root = document) => [...root.querySelectorAll(sel)];

/** Prepare an SVG path so it can be "drawn" by animating its dash offset. */
const primeStroke = (path) => {
    const len = path.getTotalLength();
    path.style.strokeDasharray = len;
    path.style.strokeDashoffset = len;
    return path;
};

/**
 * All scroll and load animations. Every effect lives inside
 * gsap.matchMedia so nothing runs when reduced motion is preferred,
 * and desktop-only pinned sections are reverted on small screens.
 */
export function initAnimations(ready = Promise.resolve(), lenis = null) {
    const mm = gsap.matchMedia();

    const motion = '(prefers-reduced-motion: no-preference)';

    document.fonts.ready.then(() => {
        // Pinned sections first, in page order, so later triggers measure
        // positions with the pin spacing already in place. They are split by
        // breakpoint so crossing 1000px only rebuilds these two sections.
        mm.add(`${motion} and (min-width: 1000px)`, () => {
            const cleanup = journey(true, lenis);
            lookInside(true);
            return cleanup;
        });
        mm.add(`${motion} and (max-width: 999px)`, () => {
            journey(false);
            lookInside(false);
        });

        // Everything else never depends on the breakpoint.
        mm.add(motion, () => {
            heroIntro(ready);
            headings();
            cardReveals();
            chalkboard();
            blogThumbs();
            footerWord();
            sectionCopy();
        });

        // Phones and tablets get their own touches in place of the pinned scenes.
        mm.add(`${motion} and (max-width: 999px)`, () => {
            mobileJourneyCovers();
            mobileCovers();
        });

        ScrollTrigger.sort();
        ScrollTrigger.refresh();
    });
}

/* Hero: the one orchestrated load sequence. The photo settles from a slow
   zoom, headline lines rise out of a mask, then the text and finder. */
function heroIntro(ready) {
    const title = $('[data-hero-title]');
    if (!title) return;

    const split = SplitText.create(title, { type: 'lines', mask: 'lines', linesClass: 'split-line' });
    const bg = $('[data-hero-bg]');

    // Held until the preloader has wiped away.
    const tl = gsap.timeline({ paused: true, defaults: { ease: 'power4.out' } });
    ready.then(() => tl.play());
    tl.from(bg, { scale: 1.18, duration: 2.4, ease: 'power2.out' }, 0)
        .from(split.lines, { yPercent: 110, duration: 1.1, stagger: 0.12 }, 0.2)
        .from('[data-hero-fade]', { y: 40, opacity: 0, duration: 0.9, stagger: 0.12 }, 0.5);

    // Photo drifts slower than the page as the hero scrolls away.
    gsap.to(bg, {
        yPercent: -12,
        ease: 'none',
        scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true },
    });
}

/* Section headings: lines slide up from a mask when they enter. */
function headings() {
    $$('[data-split]').forEach((el) => {
        const split = SplitText.create(el, { type: 'lines', mask: 'lines', linesClass: 'split-line' });
        gsap.from(split.lines, {
            yPercent: 105,
            duration: 1,
            stagger: 0.1,
            ease: 'power4.out',
            scrollTrigger: { trigger: el, start: 'top 85%', once: true },
        });
    });
}

/* Book grids and tracks: cards rise in a stagger when the row enters. */
function cardReveals() {
    const small = window.matchMedia('(max-width: 999px)').matches;
    $$('[data-reveal-cards]').forEach((group) => {
        // Sliders on phones: cards come in from the right edge instead.
        const slide = small && group.classList.contains('track');
        gsap.from(group.children, {
            y: slide ? 0 : 70,
            x: slide ? 120 : 0,
            opacity: 0,
            rotation: (i) => (slide ? 0 : i % 2 ? 2 : -2),
            duration: 0.9,
            stagger: 0.08,
            ease: 'power3.out',
            clearProps: 'transform,opacity',
            scrollTrigger: { trigger: group, start: 'top 85%', once: true },
        });
    });
}

/* Class journey: on desktop the section pins and vertical scroll moves the
   stages sideways. Covers fan out as each stage reaches the centre. */
function journey(desktop, lenis = null) {
    const section = $('[data-journey]');
    const track = $('[data-journey-track]');
    if (!section || !track) return;

    const stages = $$('.stage', track);

    if (!desktop) {
        stages.forEach((stage) => {
            gsap.from(stage, {
                y: 60,
                opacity: 0,
                duration: 0.8,
                ease: 'power3.out',
                scrollTrigger: { trigger: stage, start: 'top 85%', once: true },
            });
        });
        return;
    }

    const distance = () => track.scrollWidth - window.innerWidth;

    const scroller = gsap.to(track, {
        x: () => -distance(),
        ease: 'none',
        scrollTrigger: {
            trigger: section,
            start: 'top top',
            end: () => '+=' + distance(),
            pin: true,
            scrub: 0.8,
            invalidateOnRefresh: true,
        },
    });

    gsap.to('[data-journey-progress]', {
        scaleX: 1,
        ease: 'none',
        scrollTrigger: { trigger: section, start: 'top top', end: () => '+=' + distance(), scrub: true },
    });

    // Dots and arrows: each stage is a stop on the scrubbed scroll.
    const st = scroller.scrollTrigger;
    const dots = $$('.journey-dots span', section);
    const stops = () => stages.map((stage, i) => (i === 0 ? 0 : Math.min(1, (stage.offsetLeft - stages[0].offsetLeft) / distance())));
    const current = () => {
        const s = stops();
        return s.reduce((best, stop, i) => (Math.abs(stop - st.progress) < Math.abs(s[best] - st.progress) ? i : best), 0);
    };
    const syncDots = () => {
        const i = current();
        dots.forEach((dot, j) => dot.classList.toggle('is-active', j === i));
    };
    const go = (step) => {
        const s = stops();
        const i = Math.max(0, Math.min(s.length - 1, current() + step));
        const y = st.start + s[i] * (st.end - st.start);
        lenis ? lenis.scrollTo(y, { duration: 1.2 }) : window.scrollTo({ top: y, behavior: 'smooth' });
    };
    const prev = $('[data-journey-prev]', section);
    const next = $('[data-journey-next]', section);
    const onPrev = () => go(-1);
    const onNext = () => go(1);
    prev?.addEventListener('click', onPrev);
    next?.addEventListener('click', onNext);
    gsap.ticker.add(syncDots);

    stages.forEach((stage) => {
        const covers = $$('.cover', stage);
        gsap.from(covers, {
            y: 120,
            rotation: (i) => [-24, 0, 24][i] ?? 0,
            opacity: 0,
            stagger: 0.08,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: stage,
                containerAnimation: scroller,
                start: 'left 85%',
                end: 'left 35%',
                scrub: 1,
            },
        });
    });

    return () => {
        prev?.removeEventListener('click', onPrev);
        next?.removeEventListener('click', onNext);
        gsap.ticker.remove(syncDots);
    };
}

/* Look inside: a closed book sits centred, the cover turns on its spine and
   lays Chapter 4 down on the left (the cover's inside face is that page),
   the book slides so the open spread is centred, then the teacher's
   red-pen marks draw in. Pinned and scrubbed on desktop, played once on
   smaller screens. */
function lookInside(desktop) {
    const section = $('[data-inside]');
    const book = $('[data-book3d]');
    if (!section || !book) return;

    const marks = $$('[data-mark]', book).map(primeStroke);
    const cover = $('[data-front-cover]', book);
    const text = $('[data-mark-text]', book);

    const tl = gsap.timeline({ defaults: { ease: 'power2.inOut' } });
    tl.to({}, { duration: 0.15 }) // a beat with the book closed
        .fromTo(book, { xPercent: -25 }, { xPercent: 0, duration: 1, immediateRender: true }, 0.15)
        .fromTo(cover, { rotationY: 0 }, { rotationY: -180, duration: 1, immediateRender: true }, 0.15)
        .to(marks, { strokeDashoffset: 0, duration: 0.25, stagger: 0.15, ease: 'none' }, 1.2)
        .from(text, { opacity: 0, scale: 0.6, rotation: -20, duration: 0.3, ease: 'back.out(2)' }, '>-0.1');

    if (desktop) {
        ScrollTrigger.create({
            trigger: section,
            start: 'top top',
            end: '+=140%',
            pin: true,
            scrub: 1,
            animation: tl,
        });
    } else {
        tl.pause();
        ScrollTrigger.create({ trigger: book, start: 'top 75%', once: true, onEnter: () => tl.duration(2.4).play() });
    }
}

/* Schools section rows: wiped in from the left. */
function chalkboard() {
    $$('[data-board-row]').forEach((row) => {
        gsap.from(row.children, {
            clipPath: 'inset(0 100% 0 0)',
            x: -20,
            duration: 1,
            stagger: 0.12,
            ease: 'power3.out',
            clearProps: 'clipPath,transform',
            scrollTrigger: { trigger: row, start: 'top 85%', once: true },
        });
    });
}

/* Blog thumbnails open from the bottom edge. */
function blogThumbs() {
    $$('[data-thumb]').forEach((thumb, i) => {
        gsap.from(thumb, {
            clipPath: 'inset(100% 0% 0% 0% round 18px)',
            duration: 1.1,
            delay: i * 0.1,
            ease: 'expo.out',
            scrollTrigger: { trigger: thumb, start: 'top 90%', once: true },
        });
    });
}

/* Supporting copy under each heading (intro line, buttons, checklists)
   rises in just after the heading lines. */
function sectionCopy() {
    const groups = [
        ...$$('.section-head'),
        ...$$('.inside-copy'),
        ...$$('.journey-intro'),
        ...$$('.page-head .wrap'),
    ];
    groups.forEach((group) => {
        const items = $$(':scope > p:not(.eyebrow), :scope > div > p:not(.eyebrow), :scope > .btn, :scope > .carousel-nav, :scope > .tabs, :scope > ul > li, :scope > .journey-cta, :scope > .chalk', group);
        if (!items.length) return;
        gsap.from(items, {
            y: 28,
            opacity: 0,
            duration: 0.8,
            stagger: 0.08,
            ease: 'power3.out',
            clearProps: 'transform,opacity',
            scrollTrigger: { trigger: group, start: 'top 82%', once: true },
        });
    });

    // Eyebrow rules draw in from the left.
    $$('.eyebrow').forEach((eyebrow) => {
        gsap.from(eyebrow, {
            clipPath: 'inset(0 100% 0 0)',
            duration: 0.9,
            ease: 'power3.out',
            clearProps: 'clipPath',
            scrollTrigger: { trigger: eyebrow, start: 'top 90%', once: true },
        });
    });
}

/* Mobile journey: each stage's three covers start stacked, then fan out
   as the card scrolls into view. */
function mobileJourneyCovers() {
    $$('.journey .stage').forEach((stage) => {
        const covers = $$('.stage-books .cover', stage);
        if (!covers.length) return;
        gsap.from(covers, {
            x: (i) => (1 - i) * 60,
            y: 40,
            rotation: (i) => [-14, 0, 14][i] ?? 0,
            opacity: 0,
            duration: 0.9,
            stagger: 0.1,
            ease: 'back.out(1.4)',
            clearProps: 'transform,opacity',
            scrollTrigger: { trigger: stage.querySelector('.stage-books'), start: 'top 88%', once: true },
        });
        gsap.from($$('.stage-tags li', stage), {
            scale: 0.6,
            opacity: 0,
            duration: 0.5,
            stagger: 0.04,
            ease: 'back.out(2)',
            clearProps: 'transform,opacity',
            scrollTrigger: { trigger: stage, start: 'top 75%', once: true },
        });
    });
}

/* Mobile: book covers settle from a slight tilt as they scroll past. */
function mobileCovers() {
    $$('.book-grid .book-card .cover, .track .book-card .cover').forEach((cover, i) => {
        gsap.fromTo(cover, { rotation: i % 2 ? 2.5 : -2.5 }, {
            rotation: 0,
            ease: 'none',
            scrollTrigger: { trigger: cover, start: 'top bottom', end: 'center 55%', scrub: true },
        });
    });
}

/* Footer wordmark rises as the page ends. */
function footerWord() {
    const word = $('[data-footer-word]');
    if (!word) return;
    gsap.from(word, {
        yPercent: 35,
        ease: 'none',
        // Trigger on the footer: the word itself overflows the footer edge,
        // so its own bottom never reaches the viewport bottom.
        scrollTrigger: { trigger: '.site-footer', start: 'center bottom', end: 'bottom bottom', scrub: true },
    });
}

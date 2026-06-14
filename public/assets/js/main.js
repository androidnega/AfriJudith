/**
 * AfriJudith.online — front-end behaviour.
 * Intentionally tiny: no frameworks, no deps.
 */

(function () {
    'use strict';

    /* Preloader: hide once everything has painted + minimum dwell time */
    window.addEventListener('load', () => {
        const loader = document.getElementById('loader');
        if (!loader) return;
        window.setTimeout(() => loader.classList.add('hidden'), 1400);
        window.setTimeout(() => loader.remove(), 2200);
    });

    /* ---------------------------------------------------------
       Mobile nav drawer
       --------------------------------------------------------- */
    const toggle   = document.querySelector('[data-nav-toggle]');
    const nav      = document.querySelector('[data-nav]');
    const backdrop = document.querySelector('[data-nav-backdrop]');

    if (toggle && nav) {
        const body = document.body;
        let lockedScrollY = 0;

        // The CSS pins `body { position: fixed }` while the drawer is
        // open so iOS Safari doesn't bounce or collapse the URL bar.
        // To keep the page from jumping to the top, we stash the scroll
        // offset and restore it when the drawer closes.
        const lockBodyScroll = () => {
            lockedScrollY = window.scrollY || window.pageYOffset || 0;
            body.style.top = `-${lockedScrollY}px`;
            body.classList.add('nav-open');
        };

        const unlockBodyScroll = () => {
            body.classList.remove('nav-open');
            body.style.top = '';
            window.scrollTo(0, lockedScrollY);
        };

        const setOpen = (open) => {
            const isOpen = nav.classList.contains('open');
            if (open === isOpen) return;

            nav.classList.toggle('open', open);
            backdrop && backdrop.classList.toggle('open', open);
            toggle.setAttribute('aria-expanded', String(open));
            toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');

            if (open) lockBodyScroll();
            else      unlockBodyScroll();
        };

        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            setOpen(!nav.classList.contains('open'));
        });

        backdrop && backdrop.addEventListener('click', () => setOpen(false));

        nav.querySelectorAll('a').forEach((a) => {
            a.addEventListener('click', () => setOpen(false));
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && nav.classList.contains('open')) setOpen(false);
        });

        // Snap closed when crossing back into desktop layout so the
        // body lock + transform aren't left dangling on resize.
        const mql = window.matchMedia('(min-width: 769px)');
        const onChange = (e) => { if (e.matches) setOpen(false); };
        mql.addEventListener ? mql.addEventListener('change', onChange) : mql.addListener(onChange);
    }

    /* Reveal-on-scroll using IntersectionObserver */
    const revealTargets = document.querySelectorAll([
        '.hero-copy', '.hero-visual',
        '.page-hero-grid > *', '.about-aside',
        '.card', '.skill', '.work',
        '.contact-card', '.contact-info', '.contact-form',
        '.section-head', '.landing-inner > *'
    ].join(', '));
    revealTargets.forEach((el) => el.classList.add('reveal'));

    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in');
                        io.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
        );
        revealTargets.forEach((el) => io.observe(el));
    } else {
        revealTargets.forEach((el) => el.classList.add('in'));
    }

    /* Smooth-scroll for in-page anchors (browsers that don't honour scroll-behavior) */
    document.querySelectorAll('a[href^="#"]').forEach((a) => {
        a.addEventListener('click', (e) => {
            const id = a.getAttribute('href');
            if (!id || id === '#') return;
            const target = document.querySelector(id);
            if (!target) return;
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
})();

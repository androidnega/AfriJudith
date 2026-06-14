/**
 * AfriJudith.online — front-end behaviour.
 * Intentionally tiny: no frameworks, no deps.
 */

(function () {
    'use strict';

    /* ---------------------------------------------------------
       Preloader
       ---------------------------------------------------------
       The loader is purely visual (CSS pointer-events: none lets
       taps pass straight through to the page underneath), but we
       still want it to disappear promptly. We trigger the hide on
       whichever fires first: DOMContentLoaded (almost immediate),
       window load (slow networks / external fonts), or a hard
       2-second cap so a stuck resource never traps the loader on
       screen.
       --------------------------------------------------------- */
    (function preloader() {
        const loader = document.getElementById('loader');
        if (!loader) return;
        let hidden = false;
        const hide = () => {
            if (hidden) return;
            hidden = true;
            loader.classList.add('hidden');
            window.setTimeout(() => loader.remove(), 700);
        };
        if (document.readyState === 'complete') hide();
        else {
            document.addEventListener('DOMContentLoaded', () => window.setTimeout(hide, 600));
            window.addEventListener('load', () => window.setTimeout(hide, 200));
            window.setTimeout(hide, 2000);
        }
    })();

    /* ---------------------------------------------------------
       Reveal-on-scroll using IntersectionObserver
       ---------------------------------------------------------
       Above-the-fold hero content (the landing screen) is intentionally
       NOT in this list — it must always be visible on first paint, even
       if the observer hasn't fired yet (which happened on phones where
       the centred layout left some hero items just below the trigger
       margin and they stayed at opacity: 0 forever).
       --------------------------------------------------------- */
    const revealTargets = document.querySelectorAll([
        '.hero-copy', '.hero-visual',
        '.page-hero-grid > *', '.about-aside',
        '.card', '.skill', '.work',
        '.contact-card', '.contact-info', '.contact-form',
        '.section-head'
    ].join(', '));
    revealTargets.forEach((el) => el.classList.add('reveal'));

    /* Generous trigger so phones with short viewports always pick up
       items even before any scroll happens. */
    const revealOptions = { threshold: 0.05, rootMargin: '0px 0px 10% 0px' };

    const showAll = () => revealTargets.forEach((el) => el.classList.add('in'));

    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    io.unobserve(entry.target);
                }
            });
        }, revealOptions);
        revealTargets.forEach((el) => io.observe(el));

        /* Safety net: anything still hidden after 1.2s — e.g. the tab
           was backgrounded so observers paused — gets revealed anyway,
           so visitors never see a blank section. */
        window.setTimeout(showAll, 1200);
    } else {
        showAll();
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

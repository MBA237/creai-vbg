/**
 * Feature Slider — Articles & Événements fusionnés sur l'accueil.
 * - Onglets pour basculer entre les 2 panneaux
 * - Chaque panneau a son propre slider (auto-play, flèches, points, swipe)
 * - Seul le panneau actif tourne
 */
document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    //  GESTION DES ONGLETS
    // ============================================================
    const tabs   = document.querySelectorAll('.feature-tab');
    const panels = document.querySelectorAll('.feature-panel');

    let sliders = {};   // { slug: { play, stop, goTo } }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            const target = tab.getAttribute('data-target');
            if (!target) return;

            // Bascule des onglets actifs
            tabs.forEach(function (t) {
                const isActive = t === tab;
                t.classList.toggle('is-active', isActive);
                t.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            // Bascule des panneaux
            panels.forEach(function (p) {
                const isActive = p.getAttribute('data-panel') === target;
                p.classList.toggle('is-active', isActive);
                if (isActive) {
                    p.removeAttribute('hidden');
                } else {
                    p.setAttribute('hidden', '');
                }
            });

            // Stop l'auto-play de tous les sliders, redémarre celui qui est actif
            Object.keys(sliders).forEach(function (key) {
                if (key !== target) sliders[key].stop();
            });
            if (sliders[target]) sliders[target].play();
        });
    });

    // ============================================================
    //  INITIALISATION DES SLIDERS DANS CHAQUE PANNEAU
    // ============================================================
    document.querySelectorAll('.feature-panel').forEach(function (panel) {
        const slug   = panel.getAttribute('data-panel');
        const slides = panel.querySelectorAll('.feature-slide');
        const dots   = panel.querySelectorAll('.feature-slider-dot');
        const prev   = panel.querySelector('.feature-slider-nav--prev');
        const next   = panel.querySelector('.feature-slider-nav--next');

        if (slides.length === 0) return;

        let current = 0;
        let autoplayTimer = null;
        const AUTOPLAY_MS = 6000;

        function goTo(index) {
            if (index < 0) index = slides.length - 1;
            if (index >= slides.length) index = 0;

            slides.forEach(function (s, i) {
                s.classList.toggle('is-active', i === index);
            });

            dots.forEach(function (d, i) {
                d.classList.toggle('is-active', i === index);
            });

            current = index;
        }

        function nextSlide() { goTo(current + 1); }
        function prevSlide() { goTo(current - 1); }

        // --- Flèches ---
        if (next) next.addEventListener('click', function () { nextSlide(); restartAutoplay(); });
        if (prev) prev.addEventListener('click', function () { prevSlide(); restartAutoplay(); });

        // --- Points ---
        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                const idx = parseInt(dot.getAttribute('data-index'), 10) || 0;
                goTo(idx);
                restartAutoplay();
            });
        });

        // --- Clavier ---
        panel.setAttribute('tabindex', '0');
        panel.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowRight') { e.preventDefault(); nextSlide(); restartAutoplay(); }
            if (e.key === 'ArrowLeft')  { e.preventDefault(); prevSlide(); restartAutoplay(); }
        });

        // --- Swipe tactile ---
        let touchStartX = 0;
        let touchEndX = 0;

        panel.addEventListener('touchstart', function (e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        panel.addEventListener('touchend', function (e) {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > 50) {
                if (diff > 0) nextSlide();
                else prevSlide();
                restartAutoplay();
            }
        }, { passive: true });

        // --- Auto-play ---
        function startAutoplay() {
            if (slides.length <= 1) return;
            stopAutoplay();
            autoplayTimer = setInterval(nextSlide, AUTOPLAY_MS);
        }

        function stopAutoplay() {
            if (autoplayTimer) {
                clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        }

        function restartAutoplay() {
            stopAutoplay();
            // Ne redémarre que si ce panneau est actif
            if (panel.classList.contains('is-active')) {
                startAutoplay();
            }
        }

        // Pause au survol (uniquement si actif)
        panel.addEventListener('mouseenter', function () {
            if (panel.classList.contains('is-active')) stopAutoplay();
        });
        panel.addEventListener('mouseleave', function () {
            if (panel.classList.contains('is-active')) startAutoplay();
        });

        // Pause quand l'onglet du navigateur est masqué
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) stopAutoplay();
            else if (panel.classList.contains('is-active')) startAutoplay();
        });

        // Initialisation
        goTo(0);

        // Démarre l'auto-play seulement si le panneau est actif au chargement
        if (panel.classList.contains('is-active')) {
            startAutoplay();
        }

        // Enregistre les contrôles du slider
        sliders[slug] = {
            play: startAutoplay,
            stop: stopAutoplay,
            goTo: goTo,
        };
    });

});
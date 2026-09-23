/**
 * Feature Slider — carrousel unique sur l'accueil (Hero + Actualités + Événements).
 * Un seul track, avance automatique toutes les 5s, boucle en continu.
 * Flèches, points, clavier, swipe tactile, pause au survol / onglet masqué.
 */
document.addEventListener('DOMContentLoaded', function () {

    const slider = document.getElementById('mainSlider');
    if (!slider) return;

    const track  = slider.querySelector('.feature-slider-track');
    const slides = track ? Array.from(track.querySelectorAll('.feature-slide')) : [];
    const dots   = Array.from(slider.querySelectorAll('.feature-slider-dot'));
    const prev   = slider.querySelector('.feature-slider-nav--prev');
    const next   = slider.querySelector('.feature-slider-nav--next');

    if (slides.length === 0) return;

    const SLIDE_MS = 5000;
    let current = 0;
    let autoplayTimer = null;

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
    slider.setAttribute('tabindex', '0');
    slider.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowRight') { e.preventDefault(); nextSlide(); restartAutoplay(); }
        if (e.key === 'ArrowLeft')  { e.preventDefault(); prevSlide(); restartAutoplay(); }
    });

    // --- Swipe tactile ---
    let touchStartX = 0;
    let touchEndX = 0;

    slider.addEventListener('touchstart', function (e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    slider.addEventListener('touchend', function (e) {
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
        autoplayTimer = setInterval(nextSlide, SLIDE_MS);
    }

    function stopAutoplay() {
        if (autoplayTimer) {
            clearInterval(autoplayTimer);
            autoplayTimer = null;
        }
    }

    function restartAutoplay() {
        stopAutoplay();
        startAutoplay();
    }

    // Pause au survol
    slider.addEventListener('mouseenter', stopAutoplay);
    slider.addEventListener('mouseleave', startAutoplay);

    // Pause quand l'onglet du navigateur est masqué
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) stopAutoplay();
        else startAutoplay();
    });

    // Initialisation
    goTo(0);
    startAutoplay();

});

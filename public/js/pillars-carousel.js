/**
 * Carrousel horizontal "Nos 5 piliers d'action" (page d'accueil)
 * - Scroll fluide avec snap
 * - Flèches gauche/droite (visibles au survol desktop, toujours en mobile)
 * - Masque les flèches aux extrémités
 * - Support tactile + clavier + molette horizontale
 */
document.addEventListener('DOMContentLoaded', function () {
    const scrollEl = document.getElementById('pillarsScroll');
    const prevBtn  = document.getElementById('pillarsPrev');
    const nextBtn  = document.getElementById('pillarsNext');

    if (!scrollEl || !prevBtn || !nextBtn) return;

    function getStep() {
        const card = scrollEl.querySelector('.pillar-card');
        if (!card) return 320;

        const cardWidth = card.offsetWidth;
        const track = scrollEl.querySelector('.pillars-track');
        const gap = track ? parseInt(getComputedStyle(track).columnGap || '24', 10) : 24;

        return cardWidth + gap;
    }

    function updateArrows() {
        const maxScroll = scrollEl.scrollWidth - scrollEl.clientWidth;
        const pos = scrollEl.scrollLeft;

        prevBtn.disabled = pos <= 4;
        nextBtn.disabled = pos >= maxScroll - 4;
    }

    nextBtn.addEventListener('click', function () {
        scrollEl.scrollBy({ left: getStep(), behavior: 'smooth' });
    });

    prevBtn.addEventListener('click', function () {
        scrollEl.scrollBy({ left: -getStep(), behavior: 'smooth' });
    });

    scrollEl.addEventListener('scroll', updateArrows, { passive: true });
    window.addEventListener('resize', updateArrows);

    // --- Support clavier ---
    scrollEl.setAttribute('tabindex', '0');
    scrollEl.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowRight') {
            e.preventDefault();
            scrollEl.scrollBy({ left: getStep(), behavior: 'smooth' });
        } else if (e.key === 'ArrowLeft') {
            e.preventDefault();
            scrollEl.scrollBy({ left: -getStep(), behavior: 'smooth' });
        }
    });

    // --- Support molette horizontale ---
    scrollEl.addEventListener('wheel', function (e) {
        if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) return;
        e.preventDefault();
        scrollEl.scrollLeft += e.deltaY;
    }, { passive: false });

    requestAnimationFrame(updateArrows);
    window.addEventListener('load', updateArrows);
});
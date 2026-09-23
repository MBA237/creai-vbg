/**
 * Carrousel horizontal "Les violences que nous combattons"
 * - Scroll fluide avec snap
 * - Flèches gauche/droite (visibles au survol desktop, toujours en mobile)
 * - Masque les flèches aux extrémités
 * - Support tactile + clavier + molette horizontale
 */
document.addEventListener('DOMContentLoaded', function () {
    const scrollEl = document.getElementById('vbgScroll');
    const prevBtn  = document.getElementById('vbgPrev');
    const nextBtn  = document.getElementById('vbgNext');

    if (!scrollEl || !prevBtn || !nextBtn) return;

    /**
     * Calcule la distance de scroll = largeur d'une carte + gap
     */
    function getStep() {
        const card = scrollEl.querySelector('.vbg-card');
        if (!card) return 320;

        const cardWidth = card.offsetWidth;
        const track = scrollEl.querySelector('.vbg-track');
        const gap = track ? parseInt(getComputedStyle(track).columnGap || '24', 10) : 24;

        return cardWidth + gap;
    }

    /**
     * Met à jour l'état des flèches selon la position du scroll
     */
    function updateArrows() {
        const maxScroll = scrollEl.scrollWidth - scrollEl.clientWidth;
        const pos = scrollEl.scrollLeft;

        // Tolérance de 4px pour les arrondis
        prevBtn.disabled = pos <= 4;
        nextBtn.disabled = pos >= maxScroll - 4;
    }

    // --- Boutons ---
    nextBtn.addEventListener('click', function () {
        scrollEl.scrollBy({ left: getStep(), behavior: 'smooth' });
    });

    prevBtn.addEventListener('click', function () {
        scrollEl.scrollBy({ left: -getStep(), behavior: 'smooth' });
    });

    // --- Mise à jour en temps réel ---
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

    // --- Support molette horizontale (trackpad) ---
    scrollEl.addEventListener('wheel', function (e) {
        // Si le scroll vertical est plus important, on laisse passer
        if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) return;

        e.preventDefault();
        scrollEl.scrollLeft += e.deltaY;
    }, { passive: false });

    // --- État initial ---
    requestAnimationFrame(updateArrows);
    window.addEventListener('load', updateArrows);
});
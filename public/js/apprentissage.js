// Centre d'apprentissage : bouton « Partager » d'un lien de formation.
// Partage la page du CREAI-VBG pointant vers cette formation (#formation-ID).
// Utilise le partage natif du téléphone quand il existe, sinon copie le lien.
document.addEventListener('click', async function (e) {
    const btn = e.target.closest('[data-share]');
    if (!btn) return;

    const url   = location.origin + location.pathname + '#' + btn.dataset.anchor;
    const title = btn.dataset.title || document.title;

    function copied() {
        const label = btn.getAttribute('aria-label');
        btn.classList.add('is-copied');
        btn.setAttribute('aria-label', 'Lien copié');
        clearTimeout(btn._t);
        btn._t = setTimeout(function () {
            btn.classList.remove('is-copied');
            btn.setAttribute('aria-label', label);
        }, 2200);
    }

    try {
        if (navigator.share) {
            await navigator.share({ title: title, text: title + ' — CREAI-VBG', url: url });
            return;
        }
    } catch (err) {
        if (err && err.name === 'AbortError') return; // partage annulé par la personne
    }

    try {
        await navigator.clipboard.writeText(url);
        copied();
    } catch (err) {
        // Dernier recours : sélection manuelle
        window.prompt('Copiez ce lien :', url);
    }
});

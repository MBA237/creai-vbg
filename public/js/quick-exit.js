// Bouton « Quitter rapidement » (pages d'aide et de signalement).
// Pour les personnes qui craignent d'être surprises : un clic, ou trois pressions
// sur la touche Échap en moins de 2 secondes, ferment la page et la remplacent par
// un site neutre. « replace » évite que le bouton Retour ramène sur cette page.
(function () {
    'use strict';

    var SAFE_URL = 'https://www.google.com';

    function leave() {
        window.location.replace(SAFE_URL);
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-quick-exit]');
        if (!btn) return;
        e.preventDefault();
        leave();
    });

    var presses = [];
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        var now = Date.now();
        presses = presses.filter(function (t) { return now - t < 2000; });
        presses.push(now);
        if (presses.length >= 3) leave();
    });
})();

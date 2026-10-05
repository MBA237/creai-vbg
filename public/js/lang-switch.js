// Choix de la langue : français (par défaut) ou anglais.
// L'anglais passe par le widget Google Traduction, chargé uniquement si le visiteur
// le demande : en français, aucune requête vers Google n'est faite. Le choix est
// mémorisé dans le cookie « googtrans » que lit le widget (format /langue-source/langue-cible).
(function () {
    'use strict';

    var COOKIE = 'googtrans';
    var ONE_YEAR = 60 * 60 * 24 * 365;

    function getLang() {
        var m = document.cookie.match(/(?:^|;\s*)googtrans=\/[a-z-]+\/([a-z-]+)/i);
        return m && m[1] === 'en' ? 'en' : 'fr';
    }

    function setCookie(value, maxAge) {
        var base = COOKIE + '=' + value + ';path=/;max-age=' + maxAge + ';SameSite=Lax';
        document.cookie = base;
        // Google lit aussi la variante liée au domaine : on la pose/supprime en parallèle
        var host = location.hostname;
        if (host.indexOf('.') !== -1) {
            document.cookie = base + ';domain=' + host;
            document.cookie = base + ';domain=.' + host;
        }
    }

    function choose(lang) {
        if (lang === getLang()) return;
        if (lang === 'en') {
            setCookie('/fr/en', ONE_YEAR);
        } else {
            setCookie('', 0);
        }
        location.reload();
    }

    function loadTranslator() {
        window.googleTranslateInit = function () {
            new google.translate.TranslateElement({
                pageLanguage: 'fr',
                includedLanguages: 'fr,en',
                autoDisplay: false
            }, 'google_translate_element');
        };
        var s = document.createElement('script');
        s.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateInit';
        s.async = true;
        document.head.appendChild(s);
    }

    function init() {
        var current = getLang();

        document.querySelectorAll('[data-lang]').forEach(function (btn) {
            var active = btn.getAttribute('data-lang') === current;
            btn.classList.toggle('is-active', active);
            btn.setAttribute('aria-pressed', active ? 'true' : 'false');
            btn.addEventListener('click', function () {
                choose(btn.getAttribute('data-lang'));
            });
        });

        if (current === 'en') loadTranslator();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

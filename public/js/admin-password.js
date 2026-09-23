// Outils de mot de passe (admin) : génération d'un mot de passe fort et affichage/masquage.
// Chargé depuis un fichier (la politique de sécurité de l'admin interdit les scripts dans la page).
(function () {
    'use strict';

    var SETS = {
        lower: 'abcdefghijkmnopqrstuvwxyz',   // sans « l »
        upper: 'ABCDEFGHJKLMNPQRSTUVWXYZ',    // sans « I » ni « O »
        digit: '23456789',                    // sans « 0 » ni « 1 »
        sym:   '!@#$%*+-?='
    };

    // Entier aléatoire uniforme dans [0, max[ (rejet des valeurs qui biaiseraient le tirage)
    function randomInt(max) {
        var limit = Math.floor(0x100000000 / max) * max;
        var buf = new Uint32Array(1);
        do { crypto.getRandomValues(buf); } while (buf[0] >= limit);
        return buf[0] % max;
    }

    function pick(chars) {
        return chars.charAt(randomInt(chars.length));
    }

    function generate(length) {
        var all = SETS.lower + SETS.upper + SETS.digit + SETS.sym;
        // au moins un caractère de chaque famille
        var chars = [pick(SETS.lower), pick(SETS.upper), pick(SETS.digit), pick(SETS.sym)];
        while (chars.length < length) chars.push(pick(all));
        // mélange (Fisher-Yates)
        for (var i = chars.length - 1; i > 0; i--) {
            var j = randomInt(i + 1);
            var t = chars[i]; chars[i] = chars[j]; chars[j] = t;
        }
        return chars.join('');
    }

    function fields(btn) {
        return (btn.getAttribute('data-target') || '').split(',').map(function (id) {
            return document.getElementById(id.trim());
        }).filter(Boolean);
    }

    function status(btn, text) {
        var box = btn.parentNode.querySelector('[data-pw-status]');
        if (box) box.textContent = text;
    }

    document.addEventListener('click', function (e) {
        var gen = e.target.closest('[data-pw-generate]');
        if (gen) {
            var pwd = generate(16);
            var targets = fields(gen);
            targets.forEach(function (f) { f.value = pwd; f.type = 'text'; });
            status(gen, 'Mot de passe généré : copiez-le maintenant, il ne sera plus affiché.');
            if (targets[0]) { targets[0].focus(); targets[0].select(); }
            return;
        }

        var tog = e.target.closest('[data-pw-toggle]');
        if (tog) {
            var list = fields(tog);
            var show = list.length && list[0].type === 'password';
            list.forEach(function (f) { f.type = show ? 'text' : 'password'; });
        }
    });
})();

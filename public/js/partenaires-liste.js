// Liste des partenaires (page « Qui sommes-nous ») : 2 lignes sur toute la largeur du navigateur,
// avec une pagination quand tous les partenaires ne tiennent pas sur 2 lignes.
// Le nombre de colonnes vient du CSS (variable --cols) : le nombre de partenaires par page
// s'adapte donc à la largeur de l'écran. Sans JavaScript, tous les partenaires restent affichés.
(function () {
    'use strict';

    function init(liste) {
        var grid  = liste.querySelector('.partenaires-grid');
        var pager = liste.querySelector('.partenaires-pager');
        if (!grid || !pager) return;

        var items = Array.prototype.slice.call(grid.children);
        var rows  = parseInt(liste.getAttribute('data-rows'), 10) || 2;
        var page  = 1;
        var perPage = items.length;

        function columns() {
            var cols = parseInt(getComputedStyle(liste).getPropertyValue('--cols'), 10);
            return cols > 0 ? cols : 2;
        }

        function pageCount() {
            return Math.max(1, Math.ceil(items.length / perPage));
        }

        // Pages affichées dans la pagination : toutes si peu nombreuses, sinon une fenêtre avec « … »
        function visiblePages(total) {
            if (total <= 7) {
                return Array.from({ length: total }, function (_, i) { return i + 1; });
            }
            var out = [1];
            var from = Math.max(2, page - 1);
            var to = Math.min(total - 1, page + 1);
            if (from > 2) out.push('…');
            for (var i = from; i <= to; i++) out.push(i);
            if (to < total - 1) out.push('…');
            out.push(total);
            return out;
        }

        function button(label, ariaLabel, target, opts) {
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'partenaires-pager-btn' + (opts && opts.arrow ? ' partenaires-pager-btn--arrow' : '');
            b.textContent = label;
            b.setAttribute('aria-label', ariaLabel);
            if (opts && opts.current) {
                b.classList.add('is-current');
                b.setAttribute('aria-current', 'page');
            }
            if (opts && opts.disabled) {
                b.disabled = true;
            } else {
                b.addEventListener('click', function () { goTo(target, true); });
            }
            return b;
        }

        function buildPager() {
            var total = pageCount();
            pager.innerHTML = '';
            pager.hidden = total <= 1;
            if (total <= 1) return;

            pager.appendChild(button('‹', 'Page précédente', page - 1, { arrow: true, disabled: page === 1 }));
            visiblePages(total).forEach(function (p) {
                if (p === '…') {
                    var gap = document.createElement('span');
                    gap.className = 'partenaires-pager-gap';
                    gap.textContent = '…';
                    gap.setAttribute('aria-hidden', 'true');
                    pager.appendChild(gap);
                } else {
                    pager.appendChild(button(String(p), 'Page ' + p, p, { current: p === page }));
                }
            });
            pager.appendChild(button('›', 'Page suivante', page + 1, { arrow: true, disabled: page === total }));
        }

        function render() {
            var start = (page - 1) * perPage;
            items.forEach(function (li, i) {
                li.hidden = !(i >= start && i < start + perPage);
            });
            // Les fiches d'une seule ligne (ou moins) sont centrées
            liste.classList.toggle('is-single-row', items.length <= perPage / rows);
            buildPager();
        }

        function goTo(target, animate) {
            var total = pageCount();
            page = Math.min(Math.max(1, target), total);
            if (animate) {
                grid.classList.remove('is-turning');
                void grid.offsetWidth; // relance l'animation
                grid.classList.add('is-turning');
            }
            render();
        }

        function layout() {
            var firstShown = (page - 1) * perPage;
            perPage = columns() * rows;
            page = Math.floor(firstShown / perPage) + 1;
            render();
        }

        var resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(layout, 120);
        });

        liste.classList.add('is-ready');
        layout();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-partenaires-liste]').forEach(init);
    });
})();

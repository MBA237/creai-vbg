document.addEventListener('DOMContentLoaded', function () {
    const tabs   = document.querySelectorAll('.role-tab');
    const panels = document.querySelectorAll('.role-panel');

    if (!tabs.length || !panels.length) return;

    // 100vw inclut la barre de défilement verticale : on la compense pour que le rail
    // de cartes couvre exactement la largeur visible du navigateur.
    function syncScrollbarWidth() {
        const sbw = window.innerWidth - document.documentElement.clientWidth;
        document.documentElement.style.setProperty('--sbw', sbw + 'px');
    }
    syncScrollbarWidth();

    // Rails de cartes : les flèches ne se calculent correctement que si le panneau est visible.
    const cardNavUpdaters = [];

    function refreshCardNavs() {
        cardNavUpdaters.forEach(function (update) { update(); });
    }

    window.addEventListener('resize', function () {
        syncScrollbarWidth();
        refreshCardNavs();
    });

    function activate(tab) {
        const role = tab.getAttribute('data-role');

        tabs.forEach(function (t) {
            const active = t === tab;
            t.classList.toggle('is-active', active);
            t.setAttribute('aria-selected', active ? 'true' : 'false');
            t.setAttribute('tabindex', active ? '0' : '-1');
        });

        panels.forEach(function (p) {
            const active = p.getAttribute('data-role-panel') === role;
            p.classList.toggle('is-active', active);
            if (active) {
                p.removeAttribute('hidden');
            } else {
                p.setAttribute('hidden', '');
            }
        });

        refreshCardNavs();
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            activate(tab);
        });
    });

    // Support clavier (flèches ← →)
    const tabList = document.querySelector('.role-tabs');
    if (tabList) {
        tabList.addEventListener('keydown', function (e) {
            const tabsArr = Array.from(tabs);
            const idx = tabsArr.indexOf(document.activeElement);
            if (idx === -1) return;

            let next = null;
            if (e.key === 'ArrowRight') {
                next = tabsArr[(idx + 1) % tabsArr.length];
            } else if (e.key === 'ArrowLeft') {
                next = tabsArr[(idx - 1 + tabsArr.length) % tabsArr.length];
            }

            if (next) {
                e.preventDefault();
                next.focus();
                activate(next);
            }
        });
    }

    // Boutons de défilement des rails de cartes
    document.querySelectorAll('.role-cards-wrap').forEach(function (wrap) {
        const track = wrap.querySelector('.role-cards-scroll');
        const prevBtn = wrap.querySelector('.role-cards-nav--prev');
        const nextBtn = wrap.querySelector('.role-cards-nav--next');
        if (!track || !prevBtn || !nextBtn) return;

        function scrollStep() {
            const card = track.querySelector('.role-block');
            if (!card) return track.clientWidth;
            const gap = parseFloat(getComputedStyle(track).columnGap) || 20;
            return card.getBoundingClientRect().width + gap;
        }

        function updateNav() {
            // Panneau masqué : rien à mesurer, on recalculera à son affichage.
            if (track.clientWidth === 0) return;
            const maxScroll = track.scrollWidth - track.clientWidth - 1;
            prevBtn.disabled = track.scrollLeft <= 0;
            nextBtn.disabled = maxScroll <= 0 || track.scrollLeft >= maxScroll;
        }

        prevBtn.addEventListener('click', function () {
            track.scrollBy({ left: -scrollStep(), behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', function () {
            track.scrollBy({ left: scrollStep(), behavior: 'smooth' });
        });

        track.addEventListener('scroll', updateNav);
        cardNavUpdaters.push(updateNav);
        updateNav();
    });

    // Lien direct vers un onglet depuis une autre page : besoin-aide.php#proche
    // (#besoin, #proche, #pro, #violent). On ne modifie jamais l'URL au clic : le choix
    // de la personne ne doit pas apparaître dans son historique de navigation.
    function openTabFromHash(scroll) {
        const role = decodeURIComponent(window.location.hash.slice(1));
        const tab = document.querySelector('.role-tab[data-role="' + role + '"]');
        if (!tab) return;
        activate(tab);
        if (scroll) {
            const section = document.querySelector('.role-section');
            if (section) section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    window.addEventListener('hashchange', function () { openTabFromHash(true); });
    openTabFromHash(true);
});

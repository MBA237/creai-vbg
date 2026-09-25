document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('navToggle');
    const links  = document.getElementById('navLinks');

    if (!toggle || !links) return;

    // Point de bascule vers le menu hamburger (doit rester aligné sur base.css)
    const MOBILE_MAX = 1359;

    const items = links.querySelectorAll('.nav-item');

    function closeItems(except) {
        items.forEach(function (item) {
            if (item === except) return;
            item.classList.remove('open');
            const btn = item.querySelector('.nav-item-toggle');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        });
    }

    function closeMenu() {
        links.classList.remove('open');
        toggle.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Ouvrir le menu');
        closeItems();
    }

    toggle.addEventListener('click', function () {
        const isOpen = links.classList.toggle('open');
        toggle.classList.toggle('open', isOpen);
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        toggle.setAttribute('aria-label', isOpen ? 'Fermer le menu' : 'Ouvrir le menu');
    });

    // Sous-menus : clic / Entrée / Espace sur le bouton
    items.forEach(function (item) {
        const btn = item.querySelector('.nav-item-toggle');
        if (!btn) return;

        btn.addEventListener('click', function () {
            const isOpen = item.classList.toggle('open');
            btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            closeItems(item);
        });
    });

    // Ferme le menu si on clique sur un lien (utile après navigation)
    links.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', closeMenu);
    });

    // Ferme les sous-menus au clic en dehors
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.nav-item')) closeItems();
    });

    // Échap ferme les sous-menus et redonne le focus au bouton
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        const openItem = links.querySelector('.nav-item.open');
        if (!openItem) return;
        closeItems();
        const btn = openItem.querySelector('.nav-item-toggle');
        if (btn) btn.focus();
    });

    // Ombre sous la barre dès que la page défile
    const nav = document.querySelector('.nav');
    function onScroll() {
        if (nav) nav.classList.toggle('is-scrolled', window.scrollY > 8);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    // Ferme le menu si on repasse en grand écran (resize)
    window.addEventListener('resize', () => {
        if (window.innerWidth > MOBILE_MAX) closeMenu();
    });
});

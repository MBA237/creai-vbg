document.addEventListener('DOMContentLoaded', function () {
    const tabs   = document.querySelectorAll('.equipe-tab');
    const panels = document.querySelectorAll('.equipe-panel');

    if (!tabs.length || !panels.length) return;

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            const slug = tab.getAttribute('data-pole');
            if (!slug) return;

            // Retirer l'état actif de tous
            tabs.forEach(t => {
                t.classList.remove('is-active');
                t.setAttribute('aria-selected', 'false');
            });
            panels.forEach(p => p.classList.remove('is-active'));

            // Activer le bon onglet + panneau
            tab.classList.add('is-active');
            tab.setAttribute('aria-selected', 'true');

            const panel = document.querySelector('.equipe-panel[data-pole="' + slug + '"]');
            if (panel) {
                panel.classList.add('is-active');
            }
        });
    });

    // Support clavier (flèches ← →)
    const tabList = document.querySelector('.equipe-tabs');
    if (tabList) {
        tabList.addEventListener('keydown', function (e) {
            const tabsArr = Array.from(tabs);
            const current = document.activeElement;
            const idx = tabsArr.indexOf(current);
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
                next.click();
            }
        });
    }
});
// Confirmation avant les actions destructrices.
// Un formulaire portant data-confirm="Message" demande confirmation à l'envoi.
// (Les attributs onsubmit="..." inline sont bloqués par la CSP de l'admin.)
document.addEventListener('submit', function (e) {
    const message = e.target.getAttribute && e.target.getAttribute('data-confirm');
    if (message && !window.confirm(message)) {
        e.preventDefault();
    }
});

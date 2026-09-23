document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.field-icon.toggle');

    toggles.forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = button.getAttribute('data-target');
            const input    = document.getElementById(targetId);

            if (!input) {
                console.warn('Champ introuvable pour data-target="' + targetId + '"');
                return;
            }

            const isHidden = input.type === 'password';

            // Bascule le type de l'input
            input.type = isHidden ? 'text' : 'password';

            // Bascule la classe sur le bouton → le CSS change l'icône
            button.classList.toggle('is-visible', isHidden);

            // Met à jour l'aria-label
            button.setAttribute(
                'aria-label',
                isHidden ? 'Masquer le mot de passe' : 'Afficher le mot de passe'
            );

            // Remet le focus sur l'input
            input.focus({ preventScroll: true });
        });
    });
});
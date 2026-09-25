document.addEventListener('DOMContentLoaded', function () {
    const modal       = document.getElementById('modalEdit');
    const modalClose  = document.getElementById('modalClose');
    const modalCancel = document.getElementById('modalCancel');

    if (!modal) return;

    const form = modal.querySelector('form');

    function openModal(btn) {
        document.getElementById('edit_id').value          = btn.getAttribute('data-id');
        document.getElementById('edit_nom').value         = btn.getAttribute('data-nom');
        document.getElementById('edit_categorie').value   = btn.getAttribute('data-categorie');
        document.getElementById('edit_site_web').value    = btn.getAttribute('data-site-web');
        document.getElementById('edit_description').value = btn.getAttribute('data-description');
        document.getElementById('edit_ordre').value       = btn.getAttribute('data-ordre');
        document.getElementById('edit_statut').value      = btn.getAttribute('data-statut');

        // Logo actuel : aperçu + case « retirer »
        const logo    = btn.getAttribute('data-logo');
        const current = document.getElementById('editLogoCurrent');
        document.getElementById('editLogoPreview').src = logo || '';
        current.hidden = !logo;
        form.elements['remove_logo'].checked = false;
        form.elements['logo'].value = '';

        modal.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.hidden = true;
        document.body.style.overflow = '';
    }

    document.querySelectorAll('[data-edit]').forEach(function (btn) {
        btn.addEventListener('click', function () { openModal(btn); });
    });

    if (modalClose)  modalClose.addEventListener('click', closeModal);
    if (modalCancel) modalCancel.addEventListener('click', closeModal);

    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.hidden) closeModal();
    });
});

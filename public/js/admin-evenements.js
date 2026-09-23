document.addEventListener('DOMContentLoaded', function () {
    const modal       = document.getElementById('modalEdit');
    const modalClose  = document.getElementById('modalClose');
    const modalCancel = document.getElementById('modalCancel');
    const editButtons = document.querySelectorAll('[data-edit]');

    if (!modal) return;

    function toDatetimeLocal(str) {
        if (!str) return '';
        return str.substring(0, 16).replace(' ', 'T');
    }

    function openModal(d) {
        document.getElementById('edit_id').value            = d.id;
        document.getElementById('edit_titre').value         = d.titre;
        document.getElementById('edit_description').value   = d.description;
        document.getElementById('edit_contenu').value       = d.contenu;
        document.getElementById('edit_lieu').value          = d.lieu;
        document.getElementById('edit_date_debut').value    = toDatetimeLocal(d.dateDebut);
        document.getElementById('edit_date_fin').value      = toDatetimeLocal(d.dateFin);
        document.getElementById('edit_organisateur').value  = d.organisateur;
        document.getElementById('edit_statut').value        = d.statut;
        document.getElementById('edit_current_image').value = d.image;

        modal.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.hidden = true;
        document.body.style.overflow = '';
    }

    editButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            openModal({
                id:           btn.getAttribute('data-id'),
                titre:        btn.getAttribute('data-titre'),
                description:  btn.getAttribute('data-description'),
                contenu:      btn.getAttribute('data-contenu'),
                lieu:         btn.getAttribute('data-lieu'),
                dateDebut:    btn.getAttribute('data-date-debut'),
                dateFin:      btn.getAttribute('data-date-fin'),
                organisateur: btn.getAttribute('data-organisateur'),
                statut:       btn.getAttribute('data-statut'),
                image:        btn.getAttribute('data-image'),
            });
        });
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
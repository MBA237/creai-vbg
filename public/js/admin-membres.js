document.addEventListener('DOMContentLoaded', function () {
    const modal      = document.getElementById('modalEdit');
    const modalClose = document.getElementById('modalClose');
    const modalCancel = document.getElementById('modalCancel');
    const editButtons = document.querySelectorAll('[data-edit]');

    if (!modal) return;

    function openModal(data) {
        document.getElementById('edit_id').value            = data.id;
        document.getElementById('edit_nom').value           = data.nom;
        document.getElementById('edit_pole_id').value       = data.poleId;
        document.getElementById('edit_poste').value         = data.poste;
        document.getElementById('edit_profession').value    = data.profession;
        document.getElementById('edit_current_photo').value = data.photo;
        document.getElementById('edit_ordre').value         = data.ordre;

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
                id:         btn.getAttribute('data-id'),
                nom:        btn.getAttribute('data-nom'),
                poleId:     btn.getAttribute('data-pole-id'),
                poste:      btn.getAttribute('data-poste'),
                profession: btn.getAttribute('data-profession'),
                photo:      btn.getAttribute('data-photo'),
                ordre:      btn.getAttribute('data-ordre'),
            });
        });
    });

    if (modalClose)  modalClose.addEventListener('click', closeModal);
    if (modalCancel) modalCancel.addEventListener('click', closeModal);

    // Fermer au clic sur l'overlay
    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });

    // Fermer avec Échap
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.hidden) closeModal();
    });
});
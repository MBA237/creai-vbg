/**
 * Modal Événement — ouvre une popup avec les détails d'un événement.
 * Déclenché par tout élément avec [data-event-modal].
 */
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('eventModal');
    if (!modal) return;

    const modalImage       = document.getElementById('eventModalImage');
    const modalTitle       = document.getElementById('eventModalTitle');
    const modalDate        = document.getElementById('eventModalDate');
    const modalTime        = document.getElementById('eventModalTime');
    const modalLocation    = document.getElementById('eventModalLocation');
    const modalDescription = document.getElementById('eventModalDescription');
    const modalContent     = document.getElementById('eventModalContent');
    const modalCalendar    = document.getElementById('eventModalCalendar');

    // ============================================================
    //  Helpers
    // ============================================================

    function formatDateFr(datetime) {
        if (!datetime) return '';
        const date = new Date(datetime.replace(' ', 'T'));
        if (isNaN(date)) return datetime;

        const jours = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
        const mois  = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin',
                       'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

        return jours[date.getDay()] + ' ' + date.getDate() + ' ' + mois[date.getMonth()] + ' ' + date.getFullYear();
    }

    function formatTimeFr(datetime) {
        if (!datetime) return '';
        const date = new Date(datetime.replace(' ', 'T'));
        if (isNaN(date)) return '';
        return String(date.getHours()).padStart(2, '0') + 'h' + String(date.getMinutes()).padStart(2, '0');
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str || '';
        return div.innerHTML;
    }

    function buildCalendarLink(data) {
        // Google Calendar
        const start = new Date(data.dateDebut.replace(' ', 'T'));
        const end   = data.dateFin
            ? new Date(data.dateFin.replace(' ', 'T'))
            : new Date(start.getTime() + 2 * 60 * 60 * 1000); // +2h par défaut

        function toGCal(d) {
            return d.toISOString().replace(/[-:]/g, '').replace(/\.\d{3}/, '');
        }

        const params = new URLSearchParams({
            action:  'TEMPLATE',
            text:    data.titre,
            dates:   toGCal(start) + '/' + toGCal(end),
            details: data.description + (data.contenu ? '\n\n' + data.contenu : ''),
            location: data.lieu,
        });

        return 'https://calendar.google.com/calendar/render?' + params.toString();
    }

    // ============================================================
    //  Ouverture
    // ============================================================

    function openModal(data) {
        modalImage.src = data.image || '/images/evenements/default.jpg';
        modalImage.alt = data.titre || '';

        modalTitle.textContent = data.titre || '';

        modalDate.textContent     = formatDateFr(data.dateDebut);
        modalTime.textContent     = formatTimeFr(data.dateDebut)
            + (data.dateFin ? ' – ' + formatTimeFr(data.dateFin) : '');
        modalLocation.textContent = data.lieu || '';

        modalDescription.textContent = data.description || '';

        // Contenu : on échappe puis on autorise les sauts de ligne
        if (data.contenu) {
            modalContent.innerHTML = escapeHtml(data.contenu).replace(/\n/g, '<br>');
        } else {
            modalContent.innerHTML = '';
        }

        // Lien calendrier
        modalCalendar.href = buildCalendarLink(data);

        modal.hidden = false;
        document.body.classList.add('event-modal-open');
    }

    function closeModal() {
        modal.hidden = true;
        document.body.classList.remove('event-modal-open');
    }

    // ============================================================
    //  Délégation d'événements
    // ============================================================

    document.addEventListener('click', function (e) {
        // Ouverture
        const trigger = e.target.closest('[data-event-modal]');
        if (trigger) {
            e.preventDefault();

            openModal({
                id:          trigger.getAttribute('data-event-id'),
                titre:       trigger.getAttribute('data-event-titre'),
                description: trigger.getAttribute('data-event-description'),
                contenu:     trigger.getAttribute('data-event-contenu'),
                lieu:        trigger.getAttribute('data-event-lieu'),
                dateDebut:   trigger.getAttribute('data-event-date-debut'),
                dateFin:     trigger.getAttribute('data-event-date-fin'),
                image:       trigger.getAttribute('data-event-image'),
                slug:        trigger.getAttribute('data-event-slug'),
            });

            return;
        }

        // Fermeture
        if (e.target.closest('[data-close-modal]')) {
            e.preventDefault();
            closeModal();
        }
    });

    // Fermeture clavier (Échap)
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.hidden) {
            closeModal();
        }
    });
});
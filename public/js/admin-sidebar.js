document.addEventListener('DOMContentLoaded', function () {
    const sidebar  = document.getElementById('adminSidebar');
    const toggle   = document.getElementById('sidebarToggle');
    const close    = document.getElementById('sidebarClose');
    const overlay  = document.getElementById('adminOverlay');

    if (!sidebar || !toggle) return;

    function openSidebar() {
        sidebar.classList.add('is-open');
        if (overlay) overlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('is-open');
        if (overlay) overlay.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    toggle.addEventListener('click', openSidebar);
    if (close) close.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSidebar();
    });
});
/**
 * Page Article — copie du lien de partage
 */
document.addEventListener('DOMContentLoaded', function () {
    const copyBtn = document.querySelector('.article-copy');

    if (!copyBtn) return;

    copyBtn.addEventListener('click', function () {
        const url = copyBtn.getAttribute('data-url');

        if (!url) return;

        navigator.clipboard.writeText(url).then(function () {
            // Feedback visuel
            copyBtn.classList.add('is-copied');

            const originalHTML = copyBtn.innerHTML;
            copyBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';

            setTimeout(function () {
                copyBtn.classList.remove('is-copied');
                copyBtn.innerHTML = originalHTML;
            }, 2000);
        }).catch(function (err) {
            console.error('Erreur lors de la copie :', err);
            alert('Impossible de copier le lien. Copiez-le manuellement : ' + url);
        });
    });
});
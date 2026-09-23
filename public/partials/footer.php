<?php if (empty($widePage)): ?>
    </main>
<?php endif; ?>


<footer class="footer">
    <div class="footer-inner">

        <div class="footer-brand">
            <p class="footer-name">CREAI-VBG</p>
            <p>Centre de Recherche, d'Éducation et d'Action Intégrée contre les VBG</p>
            <p class="footer-tagline">« Recherche, innovation et accompagnement holistique »</p>
        </div>

        <nav class="footer-col" aria-label="Le CREAI-VBG">
            <h2 class="footer-heading">Le CREAI-VBG</h2>
            <ul>
                <li><a href="/">Accueil</a></li>
                <li><a href="/apropos.php">Qui sommes-nous</a></li>
                <li><a href="/notre-equipe.php">Notre équipe</a></li>
                <li><a href="/piliers.php">Nos piliers</a></li>
            </ul>
        </nav>

        <nav class="footer-col" aria-label="Ressources">
            <h2 class="footer-heading">Ressources</h2>
            <ul>
                <li><a href="/actualites.php">Actualités</a></li>
                <li><a href="/connaissances.php">Centre de connaissances</a></li>
                <li><a href="/apprentissage.php">Centre d'apprentissage</a></li>
            </ul>
        </nav>

        <nav class="footer-col" aria-label="S'engager">
            <h2 class="footer-heading">S'engager</h2>
            <ul>
                <li><a href="/rejoindre.php">Nous rejoindre</a></li>
                <li><a href="/soutenir.php">Nous soutenir</a></li>
                <li><a href="/contact.php">Contact</a></li>
                <li><a href="/besoin-aide.php" class="footer-help">Besoin d'aide ?</a></li>
                <li><a href="/signalement.php">Signaler une situation</a></li>
            </ul>
        </nav>

    </div>

    <div class="footer-bottom">
        <p>Association apolitique à but non lucratif régie par la loi n° 90/053 du 19 décembre 1990 relative à la liberté d'association en République du Cameroun.</p>
        <p>
            &copy; <?= date('Y') ?> CREAI-VBG. Tous droits réservés.
            &middot; <a href="/mentions-legales.php" class="footer-legal-link">Mentions légales</a>
        </p>
    </div>
</footer>

<!-- Chat Widget -->
<link rel="stylesheet" href="/css/chat-widget.css">
<?php require __DIR__ . '/chat-widget.php'; ?>
<script src="/js/chat-widget.js" defer></script>
</body>
</html>

<!-- ============================================================
     MODAL ÉVÉNEMENT — Partagée sur tout le site
     ============================================================ -->
<div class="event-modal" id="eventModal" hidden>
    <div class="event-modal-overlay" data-close-modal></div>

    <div class="event-modal-content" role="dialog" aria-labelledby="eventModalTitle">

        <button type="button" class="event-modal-close" data-close-modal aria-label="Fermer">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>

        <div class="event-modal-image">
            <img id="eventModalImage" src="" alt="">
            <div class="event-modal-image-overlay"></div>
            <span class="event-modal-badge">Événement</span>
        </div>

        <div class="event-modal-body">

            <div class="event-modal-meta">
                <span class="event-modal-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span id="eventModalDate"></span>
                </span>

                <span class="event-modal-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span id="eventModalTime"></span>
                </span>

                <span class="event-modal-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <span id="eventModalLocation"></span>
                </span>
            </div>

            <h2 class="event-modal-title" id="eventModalTitle"></h2>

            <div class="event-modal-description" id="eventModalDescription"></div>

            <div class="event-modal-content-html" id="eventModalContent"></div>

            <div class="event-modal-footer">
                <button type="button" class="event-modal-btn event-modal-btn--ghost" data-close-modal>
                    Fermer
                </button>

                <a href="#" class="event-modal-btn event-modal-btn--primary" id="eventModalCalendar" target="_blank" rel="noopener">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Ajouter au calendrier
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
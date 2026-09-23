<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
?>

<!-- Bouton flottant -->
<button type="button"
        class="chat-fab"
        id="chatFab"
        data-csrf="<?= htmlspecialchars($_SESSION['csrf']) ?>"
        aria-label="Ouvrir l'assistant CREAI-VBG"
        aria-expanded="false">
    <svg class="chat-fab-icon chat-fab-icon--chat" xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
    </svg>
    <svg class="chat-fab-icon chat-fab-icon--close" xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="6" x2="6" y2="18"/>
        <line x1="6" y1="6" x2="18" y2="18"/>
    </svg>
</button>

<!-- Panneau de chat -->
<div class="chat-panel" id="chatPanel" aria-hidden="true">

    <header class="chat-header">
        <div class="chat-header-avatar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        </div>
        <div class="chat-header-info">
            <strong class="chat-header-name">Assistant CREAI-VBG</strong>
            <span class="chat-header-status">
                <span class="chat-status-dot"></span>
                Mode hors ligne
            </span>
        </div>
        <button type="button"
                class="chat-header-close"
                id="chatClose"
                aria-label="Fermer le chat">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </header>

    <div class="chat-messages" id="chatMessages">
        <div class="chat-message chat-message--bot">
            <div class="chat-bubble">
                Bonjour, je suis l'assistant CREAI-VBG. Je suis là pour vous écouter
                et vous orienter en toute sécurité. Pour commencer, pourriez-vous
                me dire quel âge vous avez ?
            </div>
        </div>
    </div>

    <div class="chat-quick-replies" id="chatQuickReplies">
        <button type="button" class="chat-quick-btn" data-reply="J'ai besoin d'aide">
            J'ai besoin d'aide
        </button>
        <button type="button" class="chat-quick-btn" data-reply="Juste des infos">
            Juste des infos
        </button>
        <button type="button" class="chat-quick-btn chat-quick-btn--coral" data-reply="Urgence">
            Urgence
        </button>
    </div>

    <form class="chat-input" id="chatForm" autocomplete="off">
        <input type="text"
               id="chatInput"
               class="chat-input-field"
               placeholder="Écrivez votre message..."
               aria-label="Écrire un message"
               maxlength="500">
        <button type="submit" class="chat-send" aria-label="Envoyer">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"/>
                <polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
        </button>
    </form>

</div>
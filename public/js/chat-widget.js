document.addEventListener('DOMContentLoaded', function () {
    const fab          = document.getElementById('chatFab');
    const panel        = document.getElementById('chatPanel');
    const closeBtn     = document.getElementById('chatClose');
    const form         = document.getElementById('chatForm');
    const input        = document.getElementById('chatInput');
    const messages     = document.getElementById('chatMessages');
    const quickReplies = document.getElementById('chatQuickReplies');

    if (!fab || !panel) return;

    const CSRF_TOKEN = fab.getAttribute('data-csrf') || '';
    const API_URL    = '/api/chat.php';

    const history = [];

    // ---------- Ouverture / fermeture ----------

    function openChat() {
        panel.classList.add('is-open');
        fab.classList.add('is-open');
        fab.setAttribute('aria-expanded', 'true');
        panel.setAttribute('aria-hidden', 'false');
        setTimeout(() => input.focus(), 250);
    }

    function closeChat() {
        panel.classList.remove('is-open');
        fab.classList.remove('is-open');
        fab.setAttribute('aria-expanded', 'false');
        panel.setAttribute('aria-hidden', 'true');
    }

    function toggleChat() {
        if (panel.classList.contains('is-open')) closeChat();
        else openChat();
    }

    fab.addEventListener('click', toggleChat);
    if (closeBtn) closeBtn.addEventListener('click', closeChat);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && panel.classList.contains('is-open')) {
            closeChat();
        }
    });

    // ---------- Ajout de messages ----------

    function addMessage(text, type) {
        const wrapper = document.createElement('div');
        wrapper.className = 'chat-message chat-message--' + (type === 'urgent' ? 'urgent' : type);

        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble';
        bubble.textContent = text;

        wrapper.appendChild(bubble);
        messages.appendChild(wrapper);
        messages.scrollTop = messages.scrollHeight;
    }

    function showTyping() {
        const wrapper = document.createElement('div');
        wrapper.className = 'chat-message chat-message--bot chat-typing';
        wrapper.id = 'chatTypingIndicator';

        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble';
        bubble.innerHTML = '<span class="chat-typing-dot"></span><span class="chat-typing-dot"></span><span class="chat-typing-dot"></span>';

        wrapper.appendChild(bubble);
        messages.appendChild(wrapper);
        messages.scrollTop = messages.scrollHeight;
    }

    function hideTyping() {
        const typing = document.getElementById('chatTypingIndicator');
        if (typing) typing.remove();
    }

    // ---------- Envoi API ----------

    async function sendToApi(text) {
        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    message: text,
                    history: history,
                    csrf:    CSRF_TOKEN,
                }),
            });

            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }

            const data = await response.json();

            if (data.error && !data.reply) {
                throw new Error(data.error);
            }

            return {
                reply:  data.reply  || "Je n'ai pas de réponse pour l'instant.",
                urgent: !!data.urgent,
            };

        } catch (error) {
            console.error('[chat] Erreur :', error);
            return {
                reply:  "Je n'arrive pas à contacter l'assistant pour le moment. "
                      + "En cas d'urgence, appelez le 112 ou le 3919.",
                urgent: true,
            };
        }
    }

    // ---------- Orchestration ----------

    let sending = false;

    async function handleSend(text) {
        text = (text || '').trim();
        if (text === '' || sending) return;

        sending = true;

        addMessage(text, 'user');
        input.value = '';
        history.push({ role: 'user', content: text });
        showTyping();

        const result = await sendToApi(text);

        hideTyping();
        addMessage(result.reply, result.urgent ? 'urgent' : 'bot');
        history.push({ role: 'assistant', content: result.reply });

        sending = false;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        handleSend(input.value);
    });

    // ---------- Réponses rapides ----------

    if (quickReplies) {
        quickReplies.addEventListener('click', function (e) {
            const btn = e.target.closest('.chat-quick-btn');
            if (!btn) return;
            const reply = btn.getAttribute('data-reply') || btn.textContent.trim();
            handleSend(reply);
        });
    }

    // ---------- Mobile : bloque le scroll ----------

    const mediaQuery = window.matchMedia('(max-width: 600px)');

    function handleMobileScroll() {
        if (mediaQuery.matches && panel.classList.contains('is-open')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }

    mediaQuery.addEventListener('change', handleMobileScroll);
    handleMobileScroll();

    const observer = new MutationObserver(handleMobileScroll);
    observer.observe(panel, { attributes: true, attributeFilter: ['class'] });
});
<?php
declare(strict_types=1);

class AiService
{
    private array $config;

    public function __construct()
    {
        // Charge d'abord les variables d'environnement
        require_once __DIR__ . '/../environment.php';

        // Puis la config
        $this->config = require __DIR__ . '/../config/ai.php';
    }

    /**
     * Envoie un message à Claude et retourne sa réponse.
     *
     * @param string $message
     * @param array  $history  [{role: 'user'|'assistant', content: '...'}]
     * @return array ['reply' => string, 'urgent' => bool, 'error' => ?string]
     */
    public function send(string $message, array $history = []): array
    {
        $message = trim($message);
        if ($message === '') {
            return ['reply' => '', 'urgent' => false, 'error' => 'Message vide.'];
        }

        if ($this->config['api_url'] === '' || $this->config['api_key'] === '') {
            return [
                'reply'  => "L'assistant est momentanément indisponible. "
                          . "En cas d'urgence, appelez le 112 ou le 3919.",
                'urgent' => true,
                'error'  => 'Configuration IA manquante.',
            ];
        }

        // ---------- Messages pour Claude ----------
        // Claude : PAS de message "system" dans le tableau messages.
        // Le system prompt est un champ séparé du payload.
        $messages = [];

        foreach (array_slice($history, -10) as $item) {
            if (!isset($item['role'], $item['content'])) continue;
            if (!in_array($item['role'], ['user', 'assistant'], true)) continue;

            $messages[] = [
                'role'    => $item['role'],
                'content' => (string) $item['content'],
            ];
        }

        // Message actuel
        $messages[] = ['role' => 'user', 'content' => $message];

        // ---------- Payload Claude ----------
        $payload = [
            'model'      => $this->config['model'],
            'max_tokens' => 1024,
            'system'     => $this->config['system_prompt'],
            'messages'   => $messages,
        ];

        // ---------- Requête HTTP ----------
        $ch = curl_init($this->config['api_url']);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->config['timeout'],
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'x-api-key: ' . $this->config['api_key'],
                'anthropic-version: 2023-06-01',
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
        ]);

        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        // ---------- Gestion des erreurs réseau ----------
        if ($response === false) {
            error_log('[AiService] Erreur cURL : ' . $curlErr);
            return $this->fallbackError();
        }

        if ($httpCode !== 200) {
            error_log("[AiService] HTTP $httpCode : " . substr((string) $response, 0, 500));
            return $this->fallbackError();
        }

        // ---------- Parse de la réponse Claude ----------
        $data = json_decode((string) $response, true);
        if (!is_array($data)) {
            error_log('[AiService] JSON invalide');
            return $this->fallbackError();
        }

        // Claude retourne : { content: [{ type: 'text', text: '...' }], ... }
        $reply = '';
        if (isset($data['content']) && is_array($data['content'])) {
            foreach ($data['content'] as $block) {
                if (isset($block['type'], $block['text']) && $block['type'] === 'text') {
                    $reply .= $block['text'];
                }
            }
        }
        $reply = trim($reply);

        if ($reply === '') {
            error_log('[AiService] Réponse vide : ' . substr((string) $response, 0, 500));
            return $this->fallbackError();
        }

        // Détection d'urgence
        $urgent = $this->isUrgent($message);

        return [
            'reply'  => $reply,
            'urgent' => (bool) $urgent,
            'error'  => null,
        ];
    }

    /**
     * Détection basique d'urgence dans le message utilisateur.
     */
    private function isUrgent(string $text): bool
    {
        $keywords = [
            'urgence', 'urgent', 'danger', 'maintenant', 'immédiat',
            'peur', 'menace', 'menacée', 'menacé', 'frappe', 'blesse',
            'blessée', 'blessé', 'sang', 'arme', 'tue', 'tuer', 'mourir',
        ];

        $lower = mb_strtolower($text, 'UTF-8');
        foreach ($keywords as $kw) {
            if (mb_strpos($lower, $kw) !== false) {
                return true;
            }
        }
        return false;
    }

    private function fallbackError(): array
    {
        return [
            'reply'  => "Je n'arrive pas à contacter l'assistant pour le moment. "
                      . "En cas d'urgence, appelez le 112 ou le 3919.",
            'urgent' => true,
            'error'  => 'Service indisponible.',
        ];
    }
}
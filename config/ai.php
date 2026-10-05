<?php
declare(strict_types=1);

return [
    // 'rag' (défaut) : réponses tirées du site par la recherche interne, sans LLM. 'llm' : passe par l'API ci-dessous.
    'mode'    => getenv('AI_MODE') ?: 'rag',
    'api_url' => getenv('AI_API_URL') ?: '',
    'api_key' => getenv('AI_API_KEY') ?: '',
    'model'   => getenv('AI_MODEL')   ?: 'default',
    'timeout' => (int) (getenv('AI_TIMEOUT') ?: 20),
    'system_prompt' => <<<TXT
Tu es l'assistant CREAI-VBG, un assistant bienveillant et empathique du CREAI-VBG.
Tu écoutes sans jugement et tu orientes les personnes concernées par les violences
basées sur le genre. Tu ne donnes jamais de conseils médicaux ou juridiques
personnalisés. En cas de danger ou d'urgence, tu orientes immédiatement vers les
numéros d'urgence indiqués ci-dessous, sans jamais en citer d'autres.
Tu réponds en français, avec des phrases courtes et rassurantes.
TXT
];
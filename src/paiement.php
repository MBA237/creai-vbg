<?php
declare(strict_types=1);

const PAIEMENT_MOYENS = [
    'carte'    => 'Carte bancaire',
    'mobile'   => 'Mobile Money',
    'paypal'   => 'PayPal',
    'virement' => 'Virement bancaire',
];

const PAIEMENT_OPERATEURS = [
    'mtn'    => 'MTN Mobile Money',
    'orange' => 'Orange Money',
    'autre'  => 'Autre',
];

/**
 * Remplace les marqueurs d'un lien de paiement par les valeurs du don, encodées pour une URL :
 * {montant} (ex. 15 ou 12.5), {reference}, {mode} (unique|mensuel), {email}.
 * Un lien sans marqueur est renvoyé tel quel.
 */
function paiement_url(string $modele, array $ctx): string
{
    $valeurs = [
        '{montant}'   => (string) ($ctx['montant'] ?? ''),
        '{reference}' => (string) ($ctx['reference'] ?? ''),
        '{mode}'      => (string) ($ctx['mode'] ?? ''),
        '{email}'     => (string) ($ctx['email'] ?? ''),
    ];
    return strtr(trim($modele), array_map('rawurlencode', $valeurs));
}

/**
 * Moyens de paiement réellement configurés dans config/paiement.php.
 * $moyen filtre sur un moyen (carte, mobile, paypal, virement), $operateur sur un opérateur Mobile Money.
 * $ctx (montant, reference, mode, email) alimente les marqueurs des liens carte et PayPal et
 * personnalise le bouton (« Payer 15 € par carte »).
 *
 * @return array<int, array{titre: string, url: string, cta: string, lignes: array<string, string>}>
 */
function paiement_moyens(?string $moyen = null, ?string $operateur = null, array $ctx = []): array
{
    $cfg = require __DIR__ . '/../config/paiement.php';
    $out = [];

    $filled = static fn (mixed $v): bool => is_string($v) && trim($v) !== '';
    $safeUrl = static fn (mixed $v): bool => is_string($v) && preg_match('#^https?://#i', trim($v)) === 1;
    $want = static fn (string $key): bool => $moyen === null || $moyen === '' || $moyen === $key;

    $prix = isset($ctx['montant']) && $ctx['montant'] !== '' ? ' ' . $ctx['montant'] . ' €' : '';

    if ($want('carte') && $safeUrl($cfg['carte_url'] ?? null)) {
        $out[] = ['titre' => PAIEMENT_MOYENS['carte'], 'url' => paiement_url($cfg['carte_url'], $ctx),
                  'cta' => 'Payer' . $prix . ' par carte', 'lignes' => []];
    }

    if ($want('mobile')) {
        foreach ($cfg['mobile_money'] ?? [] as $key => $m) {
            if ($operateur && $operateur !== 'autre' && $operateur !== $key) continue;
            if (!$filled($m['numero'] ?? null)) continue;

            $lignes = ['Numéro' => trim($m['numero'])];
            if ($filled($m['titulaire'] ?? null)) $lignes['Titulaire'] = trim($m['titulaire']);
            $out[] = ['titre' => (string) ($m['libelle'] ?? PAIEMENT_MOYENS['mobile']), 'url' => '', 'cta' => '', 'lignes' => $lignes];
        }
    }

    if ($want('paypal') && $safeUrl($cfg['paypal_url'] ?? null)) {
        $out[] = ['titre' => PAIEMENT_MOYENS['paypal'], 'url' => paiement_url($cfg['paypal_url'], $ctx),
                  'cta' => 'Payer' . $prix . ' avec PayPal', 'lignes' => []];
    }

    $v = $cfg['virement'] ?? [];
    if ($want('virement') && $filled($v['iban'] ?? null)) {
        $lignes = [];
        if ($filled($v['banque'] ?? null))    $lignes['Banque']    = trim($v['banque']);
        if ($filled($v['titulaire'] ?? null)) $lignes['Titulaire'] = trim($v['titulaire']);
        $lignes['IBAN'] = trim($v['iban']);
        if ($filled($v['bic'] ?? null))       $lignes['BIC']       = trim($v['bic']);
        $out[] = ['titre' => PAIEMENT_MOYENS['virement'], 'url' => '', 'cta' => '', 'lignes' => $lignes];
    }

    return $out;
}

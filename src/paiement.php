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
 * Moyens de paiement réellement configurés dans config/paiement.php.
 * $moyen filtre sur un moyen (carte, mobile, paypal, virement), $operateur sur un opérateur Mobile Money.
 *
 * @return array<int, array{titre: string, url: string, lignes: array<string, string>}>
 */
function paiement_moyens(?string $moyen = null, ?string $operateur = null): array
{
    $cfg = require __DIR__ . '/../config/paiement.php';
    $out = [];

    $filled = static fn (mixed $v): bool => is_string($v) && trim($v) !== '';
    $safeUrl = static fn (mixed $v): bool => is_string($v) && preg_match('#^https?://#i', trim($v)) === 1;
    $want = static fn (string $key): bool => $moyen === null || $moyen === '' || $moyen === $key;

    if ($want('carte') && $safeUrl($cfg['carte_url'] ?? null)) {
        $out[] = ['titre' => PAIEMENT_MOYENS['carte'], 'url' => trim($cfg['carte_url']), 'lignes' => []];
    }

    if ($want('mobile')) {
        foreach ($cfg['mobile_money'] ?? [] as $key => $m) {
            if ($operateur && $operateur !== 'autre' && $operateur !== $key) continue;
            if (!$filled($m['numero'] ?? null)) continue;

            $lignes = ['Numéro' => trim($m['numero'])];
            if ($filled($m['titulaire'] ?? null)) $lignes['Titulaire'] = trim($m['titulaire']);
            $out[] = ['titre' => (string) ($m['libelle'] ?? PAIEMENT_MOYENS['mobile']), 'url' => '', 'lignes' => $lignes];
        }
    }

    if ($want('paypal') && $safeUrl($cfg['paypal_url'] ?? null)) {
        $out[] = ['titre' => PAIEMENT_MOYENS['paypal'], 'url' => trim($cfg['paypal_url']), 'lignes' => []];
    }

    $v = $cfg['virement'] ?? [];
    if ($want('virement') && $filled($v['iban'] ?? null)) {
        $lignes = [];
        if ($filled($v['banque'] ?? null))    $lignes['Banque']    = trim($v['banque']);
        if ($filled($v['titulaire'] ?? null)) $lignes['Titulaire'] = trim($v['titulaire']);
        $lignes['IBAN'] = trim($v['iban']);
        if ($filled($v['bic'] ?? null))       $lignes['BIC']       = trim($v['bic']);
        $out[] = ['titre' => PAIEMENT_MOYENS['virement'], 'url' => '', 'lignes' => $lignes];
    }

    return $out;
}

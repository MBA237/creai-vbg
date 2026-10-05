<?php
declare(strict_types=1);

require_once __DIR__ . '/Mailer.php';
require_once __DIR__ . '/paiement.php';

/**
 * Gabarits et envois des e-mails du site (dons, adhésions, contacts).
 * Chaque fonction mail_*() enveloppe Mailer::send() : elle ne lève jamais d'exception
 * et renvoie false si le mail n'est pas parti (voir src/Mailer.php).
 * Tout texte saisi par un visiteur passe par mail_h() avant d'entrer dans le HTML.
 */

function mail_h(mixed $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

/** Montant lisible : « 15 € » ou « 12,50 € ». */
function mail_montant(mixed $m): string
{
    $n = (float) $m;
    return rtrim(rtrim(number_format($n, 2, ',', ' '), '0'), ',') . ' €';
}

/** Habillage commun (styles en ligne : seuls compatibles avec les messageries). */
function mail_layout(string $titre, string $contenu): string
{
    return '<!DOCTYPE html><html lang="fr"><body style="margin:0;padding:0;background:#f4f3f8;">'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f3f8;padding:24px 12px;">'
        . '<tr><td align="center">'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:10px;overflow:hidden;font-family:Arial,Helvetica,sans-serif;color:#2b2b3a;">'
        . '<tr><td style="background:#4c4494;padding:22px 28px;color:#ffffff;font-size:20px;font-weight:bold;">CREAI-VBG</td></tr>'
        . '<tr><td style="padding:28px;font-size:15px;line-height:1.6;">'
        . '<h1 style="margin:0 0 16px;font-size:20px;color:#33143a;">' . mail_h($titre) . '</h1>'
        . $contenu
        . '</td></tr>'
        . '<tr><td style="padding:18px 28px;background:#f4f3f8;font-size:12px;color:#77758a;">'
        . 'CREAI-VBG · Dschang, Région de l\'Ouest, Cameroun<br>Ceci est un message automatique, vous pouvez y répondre.'
        . '</td></tr></table></td></tr></table></body></html>';
}

/** Bloc HTML listant les moyens de paiement renvoyés par paiement_moyens(). */
function mail_moyens_html(array $moyens): string
{
    if (!$moyens) {
        return '<p>Nos moyens de paiement en ligne sont en cours d\'ouverture : '
            . 'nous vous écrivons très prochainement avec les instructions pour régler.</p>';
    }
    $html = '<ul style="padding-left:18px;margin:8px 0 16px;">';
    foreach ($moyens as $m) {
        $html .= '<li style="margin-bottom:10px;"><strong>' . mail_h($m['titre']) . '</strong>';
        if ($m['url'] !== '') {
            $html .= '<br><a href="' . mail_h($m['url']) . '" style="display:inline-block;margin-top:6px;padding:9px 16px;background:#4c4494;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:bold;">'
                . mail_h(($m['cta'] ?? '') !== '' ? $m['cta'] : 'Payer en ligne') . '</a>';
        }
        foreach ($m['lignes'] as $label => $valeur) {
            $html .= '<br>' . mail_h($label) . ' : <strong>' . mail_h($valeur) . '</strong>';
        }
        $html .= '</li>';
    }
    return $html . '</ul>';
}

/** Tableau « libellé : valeur » pour l'équipe. */
function mail_lignes_html(array $lignes): string
{
    $html = '<table role="presentation" cellpadding="0" cellspacing="0" style="font-size:14px;margin:8px 0 16px;">';
    foreach ($lignes as $label => $valeur) {
        if ($valeur === null || $valeur === '') continue;
        $html .= '<tr><td style="padding:3px 14px 3px 0;color:#77758a;vertical-align:top;">' . mail_h($label)
            . '</td><td style="padding:3px 0;">' . nl2br(mail_h($valeur)) . '</td></tr>';
    }
    return $html . '</table>';
}

/** Notification à l'équipe (adresses de MAIL_TEAM_TO). $replyTo : la personne concernée. */
function mail_equipe(string $sujet, string $titre, array $lignes, ?string $replyTo = null): bool
{
    $to = Mailer::team();
    if (!$to) {
        return false;
    }
    return Mailer::send($to, $sujet, mail_layout($titre, mail_lignes_html($lignes)), $replyTo);
}

// ---------------------------------------------------------------- Dons

/**
 * Don enregistré sur /don.php : instructions de paiement au donateur + alerte à l'équipe.
 * @param array $d reference, mode (unique|mensuel), montant, moyen, moyen_libelle,
 *                 prenom, nom, email, telephone, moyens (voir paiement_moyens())
 */
function mail_don_declare(array $d): void
{
    $mensuel = $d['mode'] === 'mensuel';
    $montant = mail_montant($d['montant']) . ($mensuel ? ' par mois' : '');

    $contenu = '<p>Bonjour ' . mail_h($d['prenom']) . ',</p>'
        . '<p>Merci pour votre générosité ! Nous avons bien enregistré votre demande de don de <strong>'
        . mail_h($montant) . '</strong>. <strong>Aucun paiement n\'a encore été prélevé.</strong></p>'
        . '<p>Votre référence : <strong style="font-size:17px;">' . mail_h($d['reference']) . '</strong><br>'
        . '<span style="color:#77758a;">Merci de l\'indiquer lors de votre règlement.</span></p>'
        . '<h2 style="font-size:16px;margin:22px 0 4px;color:#33143a;">Comment régler ('
        . mail_h($d['moyen_libelle']) . ')</h2>'
        . mail_moyens_html($d['moyens'])
        . '<p>Vous recevrez un message de confirmation dès la réception de votre don. '
        . 'Ne communiquez jamais votre numéro de carte par e-mail.</p>';

    Mailer::send($d['email'], 'Votre don au CREAI-VBG — ' . $d['reference'], mail_layout('Merci pour votre don', $contenu));

    mail_equipe('Nouveau don ' . $d['reference'] . ' — ' . mail_montant($d['montant']), 'Nouveau don à suivre', [
        'Référence' => $d['reference'],
        'Type'      => $mensuel ? 'Don mensuel' : 'Don unique',
        'Montant'   => $montant,
        'Moyen'     => $d['moyen_libelle'],
        'Donateur'  => trim($d['prenom'] . ' ' . $d['nom']),
        'Email'     => $d['email'],
        'Téléphone' => $d['telephone'],
    ], $d['email']);
}

/** Don marqué « reçu » dans l'admin : reçu de remerciement. $don = ligne de la table dons. */
function mail_don_recu(array $don): bool
{
    $mensuel = $don['mode'] === 'mensuel';
    $contenu = '<p>Bonjour ' . mail_h($don['prenom']) . ',</p>'
        . '<p>Nous avons bien reçu votre don de <strong>' . mail_h(mail_montant($don['montant']))
        . ($mensuel ? ' par mois' : '') . '</strong> (référence <strong>' . mail_h($don['reference']) . '</strong>). '
        . 'Toute l\'équipe du CREAI-VBG vous remercie sincèrement.</p>'
        . '<p>Grâce à vous, nous pouvons maintenir notre ligne d\'écoute, nos actions de prévention '
        . 'et l\'accompagnement des personnes victimes de violences basées sur le genre.</p>'
        . '<p>Conservez ce message comme confirmation de votre don.</p>';

    return Mailer::send($don['email'], 'Merci ! Nous avons reçu votre don — ' . $don['reference'],
        mail_layout('Votre don est bien reçu', $contenu));
}

// ---------------------------------------------------------------- Adhésions

/** Demande d'adhésion enregistrée : accusé de réception + alerte à l'équipe. */
function mail_adhesion_recue(string $nom, string $email, string $categorieLibelle, string $message = ''): void
{
    $contenu = '<p>Bonjour ' . mail_h($nom) . ',</p>'
        . '<p>Nous avons bien reçu votre demande d\'adhésion au CREAI-VBG en tant que <strong>'
        . mail_h($categorieLibelle) . '</strong>. Merci de votre confiance !</p>'
        . '<p>Votre demande va être examinée par notre Conseil d\'Administration. '
        . 'Nous revenons vers vous par e-mail dès qu\'une décision est prise, avec, si elle est favorable, '
        . 'les instructions pour régler votre adhésion.</p>';

    Mailer::send($email, 'Votre demande d\'adhésion au CREAI-VBG', mail_layout('Demande d\'adhésion reçue', $contenu));

    mail_equipe('Nouvelle demande d\'adhésion — ' . $nom, 'Nouvelle demande d\'adhésion', [
        'Nom'       => $nom,
        'Email'     => $email,
        'Catégorie' => $categorieLibelle,
        'Message'   => $message,
    ], $email);
}

/** Décision du Conseil (admin) : « agree » envoie aussi les moyens de règlement. $membre = ligne memberships. */
function mail_adhesion_decision(array $membre, string $statut, string $categorieLibelle): bool
{
    if ($statut === 'agree') {
        $contenu = '<p>Bonjour ' . mail_h($membre['nom']) . ',</p>'
            . '<p>Bonne nouvelle : votre demande d\'adhésion en tant que <strong>' . mail_h($categorieLibelle)
            . '</strong> a été <strong>agréée</strong> par notre Conseil d\'Administration. Bienvenue dans la communauté CREAI-VBG !</p>'
            . '<h2 style="font-size:16px;margin:22px 0 4px;color:#33143a;">Régler votre adhésion</h2>'
            . mail_moyens_html(paiement_moyens())
            . '<p>Une fois votre règlement effectué, répondez simplement à ce message pour nous le signaler.</p>';
        return Mailer::send($membre['email'], 'Votre adhésion au CREAI-VBG est agréée',
            mail_layout('Bienvenue au CREAI-VBG', $contenu));
    }

    if ($statut === 'refuse') {
        $contenu = '<p>Bonjour ' . mail_h($membre['nom']) . ',</p>'
            . '<p>Après examen, nous ne sommes malheureusement pas en mesure de donner suite à votre demande d\'adhésion '
            . 'en tant que <strong>' . mail_h($categorieLibelle) . '</strong>.</p>'
            . '<p>Vous pouvez continuer à nous soutenir autrement (don, bénévolat, partenariat), '
            . 'et nous restons à votre écoute pour toute question.</p>';
        return Mailer::send($membre['email'], 'Votre demande d\'adhésion au CREAI-VBG',
            mail_layout('Suite donnée à votre demande', $contenu));
    }

    return false;
}

// ---------------------------------------------------------------- Contact

/** Message du formulaire de contact : accusé de réception + copie à l'équipe. */
function mail_contact_recu(string $nom, string $email, string $sujetLibelle, string $message): void
{
    $contenu = '<p>Bonjour ' . mail_h($nom) . ',</p>'
        . '<p>Nous avons bien reçu votre message (« ' . mail_h($sujetLibelle) . ' ») et vous répondrons '
        . 'dans les plus brefs délais.</p>'
        . '<p style="color:#77758a;">Si vous êtes en danger immédiat, n\'attendez pas notre réponse : '
        . 'consultez la page « Besoin d\'aide » de notre site.</p>';

    Mailer::send($email, 'Nous avons bien reçu votre message', mail_layout('Message reçu', $contenu));

    mail_equipe('Nouveau message : ' . $sujetLibelle, 'Nouveau message de contact', [
        'Nom'     => $nom,
        'Email'   => $email,
        'Sujet'   => $sujetLibelle,
        'Message' => $message,
    ], $email);
}

<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Contact.php';
require_once __DIR__ . '/Benevole.php';

/**
 * Traite les formulaires « bénévole » et « partenariat » (champ POST form_type).
 * Retourne l'état à afficher par partials/section-benevolat.php et partials/section-partenaires.php.
 * La session doit être démarrée par la page appelante.
 */
function engagement_forms_handle(): array
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    $emptyBenevole = [
        'email' => '', 'nom' => '', 'prenom' => '', 'telephone' => '', 'date_naissance' => '',
        'ville' => '', 'connu_par' => '', 'motivations' => '', 'experience' => '', 'canal' => '', 'notes' => '',
    ];
    $emptyPartenaire = ['nom' => '', 'telephone' => '', 'email' => '', 'organisation' => '', 'objet' => '', 'question' => ''];

    $state = [
        'canaux'     => Benevole::CANAUX,
        'benevole'   => ['errors' => [], 'success' => false, 'old' => $emptyBenevole],
        'partenaire' => ['errors' => [], 'success' => false, 'old' => $emptyPartenaire],
    ];

    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return $state;
    }

    $formType = $_POST['form_type'] ?? '';
    $csrfOk   = hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? ''));

    if ($formType === 'benevole') {
        $errors = [];
        if (!$csrfOk) $errors[] = 'Token de sécurité invalide.';

        $email         = trim($_POST['email'] ?? '');
        $nom           = trim($_POST['nom'] ?? '');
        $prenom        = trim($_POST['prenom'] ?? '');
        $telephone     = trim($_POST['telephone'] ?? '');
        $dateNaissance = trim($_POST['date_naissance'] ?? '');
        $ville         = trim($_POST['ville'] ?? '');
        $connuPar      = trim($_POST['connu_par'] ?? '');
        $motivations   = trim($_POST['motivations'] ?? '');
        $experience    = trim($_POST['experience'] ?? '');
        $canal         = trim($_POST['canal'] ?? '');
        $notes         = trim($_POST['notes'] ?? '');
        $state['benevole']['old'] = [
            'email' => $email, 'nom' => $nom, 'prenom' => $prenom, 'telephone' => $telephone,
            'date_naissance' => $dateNaissance, 'ville' => $ville, 'connu_par' => $connuPar,
            'motivations' => $motivations, 'experience' => $experience, 'canal' => $canal, 'notes' => $notes,
        ];

        $dateOk = \DateTime::createFromFormat('Y-m-d', $dateNaissance) !== false;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))     $errors[] = 'Email invalide.';
        if ($nom === '')                                    $errors[] = 'Le nom est obligatoire.';
        if ($prenom === '')                                 $errors[] = 'Le prénom est obligatoire.';
        if ($telephone === '')                              $errors[] = 'Le numéro de téléphone est obligatoire.';
        if (!$dateOk)                                       $errors[] = 'Date de naissance invalide.';
        if ($ville === '')                                  $errors[] = 'La ville est obligatoire.';
        if (!array_key_exists($canal, $state['canaux']))    $errors[] = 'Veuillez choisir un canal de communication préféré.';

        if (empty($errors)) {
            try {
                (new Benevole())->create(
                    $email, $nom, $prenom, $telephone, $dateNaissance,
                    $ville, $connuPar, $motivations, $experience, $canal, $notes
                );
                $state['benevole']['success'] = true;
                $state['benevole']['old']     = $emptyBenevole;
                $_SESSION['csrf'] = bin2hex(random_bytes(32));
            } catch (Throwable $ex) {
                $errors[] = 'Une erreur est survenue. Merci de réessayer.';
            }
        }
        $state['benevole']['errors'] = $errors;
    }

    if ($formType === 'partenariat') {
        $errors = [];
        if (!$csrfOk) $errors[] = 'Token de sécurité invalide.';

        $nom          = trim($_POST['nom'] ?? '');
        $telephone    = trim($_POST['telephone'] ?? '');
        $email        = trim($_POST['email'] ?? '');
        $organisation = trim($_POST['organisation'] ?? '');
        $objet        = trim($_POST['objet'] ?? '');
        $question     = trim($_POST['question'] ?? '');
        $state['partenaire']['old'] = compact('nom', 'telephone', 'email', 'organisation', 'objet', 'question');

        if ($nom === '')                                $errors[] = 'Le nom est obligatoire.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
        if ($objet === '')                              $errors[] = "L'objet de la demande est obligatoire.";
        if (mb_strlen($question) < 10)                  $errors[] = 'Merci de détailler votre demande (10 caractères min).';

        if (empty($errors)) {
            try {
                $lines = ["Objet : {$objet}"];
                if ($organisation !== '') $lines[] = "Organisation : {$organisation}";
                if ($telephone !== '')    $lines[] = "Téléphone : {$telephone}";
                $composed = implode("\n", $lines) . "\n\n" . $question;

                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                (new Contact())->create($nom, $email, 'partenariat', $composed, $ip);
                $state['partenaire']['success'] = true;
                $state['partenaire']['old']     = $emptyPartenaire;
                $_SESSION['csrf'] = bin2hex(random_bytes(32));
            } catch (Throwable $ex) {
                $errors[] = 'Une erreur est survenue. Merci de réessayer.';
            }
        }
        $state['partenaire']['errors'] = $errors;
    }

    return $state;
}

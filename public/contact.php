<?php
declare(strict_types=1);
session_start();

// Téléphone de la ligne d'écoute : config/aide.php (masqué tant qu'il est vide)
$aideConfig = require __DIR__ . '/../config/aide.php';
$contactTel  = $aideConfig['ligne_ecoute']['telephone'];
$contactTelHref = preg_replace('/[^\d+]/', '', $contactTel) ?? '';

require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Contact.php';

$errors  = [];
$success = false;
$old     = ['nom' => '', 'email' => '', 'sujet' => '', 'message' => ''];

// Sujets proposés
$sujets = [
    'information'    => 'Demande d\'information',
    'partenariat'    => 'Proposition de partenariat',
    'benevolat'      => 'Bénévolat',
    'don'            => 'Faire un don',
    'presse'         => 'Presse / Média',
    'signalement'    => 'Signaler une situation',
    'autre'          => 'Autre',
];

// Pré-sélection du sujet via un lien (ex. /contact.php?sujet=don depuis « Nous soutenir »)
$sujetUrl = $_GET['sujet'] ?? '';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && is_string($sujetUrl) && array_key_exists($sujetUrl, $sujets)) {
    $old['sujet'] = $sujetUrl;
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $errors[] = 'Token de sécurité invalide.';
    }

    $nom     = trim($_POST['nom'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $sujet   = trim($_POST['sujet'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $old     = compact('nom', 'email', 'sujet', 'message');

    // Validation
    if ($nom === '')                                 $errors[] = 'Le nom est obligatoire.';
    if (mb_strlen($nom) > 100)                       $errors[] = 'Le nom est trop long (100 caractères max).';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))  $errors[] = 'Email invalide.';
    if (!array_key_exists($sujet, $sujets))          $errors[] = 'Veuillez choisir un sujet.';
    if ($message === '')                             $errors[] = 'Le message est obligatoire.';
    if (mb_strlen($message) < 10)                    $errors[] = 'Le message est trop court (10 caractères min).';
    if (mb_strlen($message) > 5000)                  $errors[] = 'Le message est trop long (5000 caractères max).';

    if (empty($errors)) {
        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            (new Contact())->create($nom, $email, $sujet, $message, $ip);

            $success = true;
            $old = ['nom' => '', 'email' => '', 'sujet' => '', 'message' => ''];
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        } catch (Throwable $e) {
            error_log('[contact.php] ' . $e->getMessage());
            $errors[] = 'Une erreur est survenue. Merci de réessayer.';
        }
    }
}

$pageTitle = 'Contact — CREAI-VBG';
$pageCss   = 'contact.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<div class="contact-page">

    <!-- ============================================================
         HERO
         ============================================================ -->
    <section class="contact-hero">
        <div class="contact-hero-content">
            <span class="contact-badge">Contact</span>
            <h1>Parlons de votre projet, de vos questions</h1>
            <p class="contact-lead">
                Que vous souhaitiez en savoir plus sur notre travail, explorer les
                possibilités de partenariat, vous impliquer ou nous faire part de vos
                commentaires, nous sommes à votre écoute.
            </p>

            <div class="hero-don"><?php require __DIR__ . '/partials/hero-don.php'; ?></div>
        </div>
    </section>

    <!-- ============================================================
         CONTENU — coordonnées + formulaire
         ============================================================ -->
    <section class="contact-section">
        <div class="contact-container">
            <div class="contact-layout">

                <!-- --- Colonne info (gauche) --- -->
                <aside class="contact-info">

                    <span class="eyebrow eyebrow--teal">Nos coordonnées</span>

                    <div class="contact-info-list">

                        <div class="contact-info-item">
                            <div class="contact-info-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div>
                                <h3>Siège social</h3>
                                <p>Dschang, Région de l'Ouest<br>Cameroun</p>
                            </div>
                        </div>

                        <div class="contact-info-item">
                            <div class="contact-info-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </div>
                            <div>
                                <h3>Email</h3>
                                <p>
                                    <a href="mailto:contact@creai-vbg.org">contact@creai-vbg.org</a>
                                </p>
                            </div>
                        </div>

                        <?php if (strlen(preg_replace('/\D/', '', $contactTel) ?? '') >= 6): ?>

                        <div class="contact-info-item">
                            <div class="contact-info-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </div>
                            <div>
                                <h3>Téléphone</h3>
                                <p>
                                    <a href="tel:<?= htmlspecialchars($contactTelHref) ?>"><?= htmlspecialchars($contactTel) ?></a>
                                </p>
                            </div>
                        </div>

                        <?php endif; ?>

                        <div class="contact-info-item">
                            <div class="contact-info-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <div>
                                <h3>Horaires</h3>
                                <p>Lundi au vendredi<br>9h00 – 17h00</p>
                            </div>
                        </div>

                    </div>

                    <!-- Réseaux sociaux -->
                    <div class="contact-socials">
                        <h3>Suivez-nous</h3>
                        <div class="contact-socials-list">
                            <a href="#" aria-label="Facebook" class="contact-social-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" aria-label="LinkedIn" class="contact-social-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            <a href="#" aria-label="Instagram" class="contact-social-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                </aside>

                <!-- --- Colonne formulaire (droite) --- -->
                <div class="contact-form-wrapper">

                    <?php if ($success): ?>
                        <div class="contact-success">
                            <div class="contact-success-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </div>
                            <h2>Merci pour votre message</h2>
                            <p>
                                Nous avons bien reçu votre demande et vous répondrons
                                dans les plus brefs délais.
                            </p>
                            <a href="/" class="btn btn--outline-teal">Retour à l'accueil</a>
                        </div>
                    <?php else: ?>

                        <span class="eyebrow eyebrow--teal">Envoyez-nous un message</span>

                        <?php if ($errors): ?>
                            <div class="contact-alert contact-alert--error">
                                <strong>Veuillez corriger les erreurs suivantes :</strong>
                                <ul>
                                    <?php foreach ($errors as $e): ?>
                                        <li><?= htmlspecialchars($e) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="post" class="contact-form">

                            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">

                            <div class="contact-form-grid">

                                <div class="contact-form-row">
                                    <label for="nom">Nom complet <span class="req">*</span></label>
                                    <input type="text"
                                           id="nom"
                                           name="nom"
                                           required
                                           maxlength="100"
                                           value="<?= htmlspecialchars($old['nom']) ?>"
                                           placeholder="Ex : Marie Ngo Bassong">
                                </div>

                                <div class="contact-form-row">
                                    <label for="email">Email <span class="req">*</span></label>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           required
                                           value="<?= htmlspecialchars($old['email']) ?>"
                                           placeholder="vous@exemple.com">
                                </div>

                                <div class="contact-form-row contact-form-row--full">
                                    <label for="sujet">Sujet <span class="req">*</span></label>
                                    <select id="sujet" name="sujet" required>
                                        <option value="">— Choisir un sujet —</option>
                                        <?php foreach ($sujets as $value => $label): ?>
                                            <option value="<?= htmlspecialchars($value) ?>"
                                                <?= $old['sujet'] === $value ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($label) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="contact-form-row contact-form-row--full">
                                    <label for="message">Message <span class="req">*</span></label>
                                    <textarea id="message"
                                              name="message"
                                              rows="7"
                                              required
                                              minlength="10"
                                              maxlength="5000"
                                              placeholder="Décrivez votre demande..."><?= htmlspecialchars($old['message']) ?></textarea>
                                    <small>10 à 5000 caractères</small>
                                </div>

                            </div>

                            <div class="contact-form-footer">
                                <p class="contact-form-note">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    Vos informations restent confidentielles.
                                </p>
                                <button type="submit" class="btn btn--primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="22" y1="2" x2="11" y2="13"/>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                                    </svg>
                                    Envoyer le message
                                </button>
                            </div>

                        </form>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
         URGENCE — renvoi
         ============================================================ -->
    <section class="contact-emergency">
        <div class="contact-container">
            <div class="contact-emergency-box">
                <div class="contact-emergency-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="M12 8v4"/>
                        <circle cx="12" cy="16" r="0.75" fill="currentColor" stroke="none"/>
                    </svg>
                </div>
                <div class="contact-emergency-content">
                    <h3>Vous êtes en situation d'urgence ?</h3>
                    <p>
                        Si vous êtes en danger immédiat, ne passez pas par ce formulaire.
                        Utilisez nos canaux d'urgence dédiés.
                    </p>
                </div>
                <a href="/besoin-aide.php" class="btn btn--coral">Besoin d'aide</a>
            </div>
        </div>
    </section>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
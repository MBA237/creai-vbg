<?php
declare(strict_types=1);
session_start();

require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Contact.php';
require __DIR__ . '/../src/Benevole.php';

$canaux = Benevole::CANAUX;

// --- État par section (chaque formulaire garde ses propres erreurs / succès) ---
$benevoleErrors  = [];
$benevoleSuccess = false;
$benevoleOld     = [
    'email' => '', 'nom' => '', 'prenom' => '', 'telephone' => '', 'date_naissance' => '',
    'ville' => '', 'connu_par' => '', 'motivations' => '', 'experience' => '', 'canal' => '', 'notes' => '',
];

$partenaireErrors  = [];
$partenaireSuccess = false;
$partenaireOld     = ['nom' => '', 'telephone' => '', 'email' => '', 'organisation' => '', 'objet' => '', 'question' => ''];

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['form_type'] ?? '';
    $csrfOk   = hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? ''));

    // ---------------------------------------------------------------
    // Formulaire bénévole
    // ---------------------------------------------------------------
    if ($formType === 'benevole') {
        if (!$csrfOk) $benevoleErrors[] = 'Token de sécurité invalide.';

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
        $benevoleOld   = compact(
            'email', 'nom', 'prenom', 'telephone', 'date_naissance',
            'ville', 'connu_par', 'motivations', 'experience', 'canal', 'notes'
        );

        $dateOk = \DateTime::createFromFormat('Y-m-d', $dateNaissance) !== false;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $benevoleErrors[] = 'Email invalide.';
        if ($nom === '')                                $benevoleErrors[] = 'Le nom est obligatoire.';
        if ($prenom === '')                             $benevoleErrors[] = 'Le prénom est obligatoire.';
        if ($telephone === '')                          $benevoleErrors[] = 'Le numéro de téléphone est obligatoire.';
        if (!$dateOk)                                    $benevoleErrors[] = 'Date de naissance invalide.';
        if ($ville === '')                              $benevoleErrors[] = 'La ville est obligatoire.';
        if (!array_key_exists($canal, $canaux))          $benevoleErrors[] = 'Veuillez choisir un canal de communication préféré.';

        if (empty($benevoleErrors)) {
            try {
                (new Benevole())->create(
                    $email, $nom, $prenom, $telephone, $dateNaissance,
                    $ville, $connuPar, $motivations, $experience, $canal, $notes
                );
                $benevoleSuccess = true;
                $benevoleOld = [
                    'email' => '', 'nom' => '', 'prenom' => '', 'telephone' => '', 'date_naissance' => '',
                    'ville' => '', 'connu_par' => '', 'motivations' => '', 'experience' => '', 'canal' => '', 'notes' => '',
                ];
                $_SESSION['csrf'] = bin2hex(random_bytes(32));
            } catch (Throwable $e) {
                $benevoleErrors[] = 'Une erreur est survenue. Merci de réessayer.';
            }
        }
    }

    // ---------------------------------------------------------------
    // Formulaire partenariat / sponsoring
    // ---------------------------------------------------------------
    if ($formType === 'partenariat') {
        if (!$csrfOk) $partenaireErrors[] = 'Token de sécurité invalide.';

        $nom          = trim($_POST['nom'] ?? '');
        $telephone    = trim($_POST['telephone'] ?? '');
        $email        = trim($_POST['email'] ?? '');
        $organisation = trim($_POST['organisation'] ?? '');
        $objet        = trim($_POST['objet'] ?? '');
        $question     = trim($_POST['question'] ?? '');
        $partenaireOld = compact('nom', 'telephone', 'email', 'organisation', 'objet', 'question');

        if ($nom === '')                                $partenaireErrors[] = 'Le nom est obligatoire.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $partenaireErrors[] = 'Email invalide.';
        if ($objet === '')                              $partenaireErrors[] = "L'objet de la demande est obligatoire.";
        if (mb_strlen($question) < 10)                  $partenaireErrors[] = 'Merci de détailler votre demande (10 caractères min).';

        if (empty($partenaireErrors)) {
            try {
                $lines = ["Objet : {$objet}"];
                if ($organisation !== '') $lines[] = "Organisation : {$organisation}";
                if ($telephone !== '')    $lines[] = "Téléphone : {$telephone}";
                $composed = implode("\n", $lines) . "\n\n" . $question;

                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                (new Contact())->create($nom, $email, 'partenariat', $composed, $ip);
                $partenaireSuccess = true;
                $partenaireOld = ['nom' => '', 'telephone' => '', 'email' => '', 'organisation' => '', 'objet' => '', 'question' => ''];
                $_SESSION['csrf'] = bin2hex(random_bytes(32));
            } catch (Throwable $e) {
                $partenaireErrors[] = 'Une erreur est survenue. Merci de réessayer.';
            }
        }
    }
}

$pageTitle = 'Nous soutenir — CREAI-VBG';
$pageCss   = 'soutenir.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<main class="page-content">

<!-- ============================================================
     HERO
     ============================================================ -->
<section class="page-hero">
    <div class="page-hero-content">
        <span class="page-badge">Nous soutenir</span>
        <h1>Lutte pour l'égalité de genre et la fin des violences sexistes et sexuelles</h1>
        <p class="page-lead">
            Notre action dépend de la mobilisation de ressources humaines, financières et
            techniques. Faites un don, adhérez, devenez bénévole ou associez votre
            organisation à nos actions.
        </p>
        <div class="page-hero-cta">
            <a href="/don.php" class="btn-cta btn-cta--primary">Je fais un don</a>
        </div>
    </div>
</section>

<!-- ============================================================
     À QUOI SERVENT VOS DONS ?
     ============================================================ -->
<section class="page-section" id="don">
    <div class="page-container">
        <h2 class="section-title">À quoi servent vos dons ?</h2>
        <ul class="check-list">
            <li>Maintenir notre ligne d'écoute ouverte 5 jours sur 7 afin d'aider et d'accompagner toujours plus de personnes vivant des violences sexistes et sexuelles.</li>
            <li>Financer la formation et la disponibilité de nos écoutant(e)s.</li>
            <li>Développer et déployer notre chatbot et notre application AidGBV.</li>
            <li>Soutenir les activités de recherche et de transfert de connaissances.</li>
            <li>Multiplier et développer les actions de prévention auprès des communautés et renforcer les formations auprès des professionnel(le)s.</li>
            <li>Améliorer et enrichir la sensibilisation numérique via notre site web et nos réseaux sociaux.</li>
            <li>Développer la compréhension des phénomènes de violences sexistes et sexuelles chez les jeunes à travers la recherche.</li>
        </ul>
    </div>
</section>

<!-- ============================================================
     COMMENT NOUS SOUTENIR — DONS
     ============================================================ -->
<section class="page-section page-section--alt">
    <div class="page-container">
        <h2 class="section-title">Comment nous soutenir ?</h2>
        <p class="section-intro">
            Les comptes de paiement en ligne sont en cours d'ouverture. En attendant,
            écrivez-nous via le formulaire de contact en précisant l'option choisie —
            notre équipe revient vers vous rapidement pour finaliser votre don.
        </p>

        <div class="cards-grid cards-grid--2">

            <article class="info-card info-card--coral">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </div>
                <h3>Faire un don ponctuel</h3>
                <p>
                    Vos dons font nos actions ! Que ce soit un petit geste ou une contribution
                    plus importante, chaque montant compte et peut faire une différence
                    significative. Ensemble, nous pouvons transformer des vies.
                </p>
                <a href="/don.php" class="info-card-link">Faire un don ponctuel →</a>
            </article>

            <article class="info-card info-card--teal">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 1 1-9-9"/>
                        <polyline points="21 3 21 9 15 9"/>
                    </svg>
                </div>
                <h3>Faire un don mensuel</h3>
                <p>
                    Les dons réguliers sont pour nous extrêmement importants : ils nous
                    permettent d'assurer la pérennité de nos actions sur le long terme,
                    garantir notre indépendance et mener des projets qui changent des vies.
                </p>
                <a href="/don.php" class="info-card-link">Faire un don mensuel →</a>
            </article>

            <article class="info-card info-card--purple">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3>Créer une cagnotte</h3>
                <p>
                    Créez une cagnotte solidaire en ligne et rassemblez votre entourage
                    autour de votre projet de collecte au profit du CREAI-VBG.
                </p>
                <a href="/don.php" class="info-card-link">Créer une cagnotte →</a>
            </article>

            <article class="info-card info-card--gray">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286z"/>
                    </svg>
                </div>
                <h3>Devenir partenaire technique ou financier</h3>
                <p>
                    Institutions, entreprises et organisations sont invitées à nous
                    contacter pour construire des partenariats sur mesure.
                </p>
                <a href="#partenaires" class="info-card-link">Devenir partenaire →</a>
            </article>

        </div>
    </div>
</section>

<!-- ============================================================
     BÉNÉVOLAT
     ============================================================ -->
<section class="rejoindre-section rejoindre-section--alt" id="benevolat">
    <div class="rejoindre-container">
        <span class="page-badge page-badge--dark">S'engager</span>
        <h2 class="section-title section-title--purple">Je souhaite devenir bénévole</h2>
        <p class="section-intro">
            Tout au long de l'année, nous avons besoin de vous pour vous mobiliser en
            ligne, suivre nos formations, engager votre entreprise, faire un don ou
            encore nous proposer du mécénat de compétences. Chez CREAI-VBG, les
            bénévoles sont mobilisé(e)s lors des événements importants de l'année,
            mais aussi pour des missions supports, des campagnes de sensibilisation
            numérique et des enquêtes scientifiques de terrain. Nous imaginons la
            communauté de bénévoles comme un espace bienveillant et militant dans
            lequel chacun·e peut participer à la lutte contre les violences.
        </p>

        <div class="adhesion-grid">

            <div class="adhesion-steps">
                <h2>Être bénévole chez CREAI-VBG, c'est notamment :</h2>
                <ul class="check-list">
                    <li>Lutter pour l'égalité des genres en faisant de la sensibilisation auprès des communautés.</li>
                    <li>Se faire former à la sensibilisation et à la lutte contre les violences sexistes et sexuelles.</li>
                    <li>Participer à des activités de recherche et de transfert de connaissances.</li>
                    <li>Animer des stands de sensibilisation lors de festivals ou d'événements partenaires.</li>
                    <li>Animer et confectionner des jeux de sensibilisation ludiques.</li>
                    <li>Faire des tournées dans les communautés pour promouvoir l'application AidGBV, ouverte à tous, gratuite et bienveillante.</li>
                    <li>Soutenir les activités de communication de l'association.</li>
                </ul>
            </div>

            <div class="form-card">
                <h2>Devenir bénévole</h2>
                <p class="section-intro" style="margin-bottom: 20px;">
                    Remplis ce formulaire en quelques minutes si tu souhaites t'engager
                    à nos côtés 💙 — nous reviendrons vers toi avec plus de précisions.
                </p>

                <?php if ($benevoleSuccess): ?>
                    <div class="alert alert-success">Merci beaucoup pour ta réponse ! Nous reviendrons vers toi avec plus de précisions 🌸</div>
                <?php endif; ?>

                <?php if ($benevoleErrors): ?>
                    <div class="alert alert-error">
                        <strong>Veuillez corriger :</strong>
                        <ul>
                            <?php foreach ($benevoleErrors as $e): ?>
                                <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" class="wizard-form">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                    <input type="hidden" name="form_type" value="benevole">

                    <div class="wizard-progress" aria-hidden="true">
                        <span class="wizard-dot is-active" data-dot="1"></span>
                        <span class="wizard-dot" data-dot="2"></span>
                        <span class="wizard-dot" data-dot="3"></span>
                    </div>
                    <p class="wizard-step-label">Étape <span data-current-step>1</span> sur 3</p>

                    <!-- Étape 1 : coordonnées -->
                    <div class="wizard-step" data-step="1">
                        <label for="b_email">Adresse e-mail *</label>
                        <input type="email" id="b_email" name="email" required
                               value="<?= htmlspecialchars($benevoleOld['email']) ?>">

                        <label for="b_nom">Ton nom *</label>
                        <input type="text" id="b_nom" name="nom" required maxlength="100"
                               value="<?= htmlspecialchars($benevoleOld['nom']) ?>">

                        <label for="b_prenom">Ton prénom *</label>
                        <input type="text" id="b_prenom" name="prenom" required maxlength="100"
                               value="<?= htmlspecialchars($benevoleOld['prenom']) ?>">

                        <label for="b_telephone">Ton numéro de téléphone *</label>
                        <input type="tel" id="b_telephone" name="telephone" required maxlength="30"
                               value="<?= htmlspecialchars($benevoleOld['telephone']) ?>">

                        <div class="wizard-actions">
                            <button type="button" class="wizard-next">Suivant</button>
                        </div>
                    </div>

                    <!-- Étape 2 : toi en quelques mots -->
                    <div class="wizard-step" data-step="2" hidden>
                        <label for="b_date_naissance">Ta date de naissance *</label>
                        <input type="date" id="b_date_naissance" name="date_naissance" required
                               value="<?= htmlspecialchars($benevoleOld['date_naissance']) ?>">

                        <label for="b_ville">Ta ville résidente *</label>
                        <input type="text" id="b_ville" name="ville" required maxlength="100"
                               value="<?= htmlspecialchars($benevoleOld['ville']) ?>">

                        <label for="b_connu_par">Comment as-tu connu CREAI-VBG ?</label>
                        <input type="text" id="b_connu_par" name="connu_par" maxlength="255"
                               value="<?= htmlspecialchars($benevoleOld['connu_par']) ?>">

                        <label for="b_motivations">Quelles sont tes motivations pour devenir bénévole ?</label>
                        <textarea id="b_motivations" name="motivations" rows="3"><?= htmlspecialchars($benevoleOld['motivations']) ?></textarea>

                        <label for="b_experience">As-tu déjà eu une activité bénévole ?</label>
                        <textarea id="b_experience" name="experience" rows="3"><?= htmlspecialchars($benevoleOld['experience']) ?></textarea>

                        <div class="wizard-actions">
                            <button type="button" class="wizard-prev">Précédent</button>
                            <button type="button" class="wizard-next">Suivant</button>
                        </div>
                    </div>

                    <!-- Étape 3 : préférences -->
                    <div class="wizard-step" data-step="3" hidden>
                        <label>Quel canal de communication préfères-tu ? *</label>
                        <div class="radio-group">
                            <?php foreach ($canaux as $value => $label): ?>
                                <label class="radio-option">
                                    <input type="radio" name="canal" value="<?= htmlspecialchars($value) ?>"
                                        <?= $benevoleOld['canal'] === $value ? 'checked' : '' ?> required>
                                    <?= htmlspecialchars($label) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <label for="b_notes">Y a-t-il autre chose que tu voudrais que l'on sache sur toi ? (facultatif)</label>
                        <textarea id="b_notes" name="notes" rows="3"><?= htmlspecialchars($benevoleOld['notes']) ?></textarea>

                        <div class="wizard-actions">
                            <button type="button" class="wizard-prev">Précédent</button>
                            <button type="submit">Envoyer ma candidature</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     DEVENIR PARTENAIRE OU SPONSOR
     ============================================================ -->
<section class="rejoindre-section" id="partenaires">
    <div class="rejoindre-container">
        <span class="page-badge page-badge--dark">Engagez votre entreprise</span>
        <h2 class="section-title">Devenir partenaire ou sponsor</h2>
        <p class="section-intro">
            Nous encourageons les dynamiques de responsabilité sociale des entreprises
            et pensons que l'union du monde associatif avec celui du privé peut créer
            de grandes et belles choses. Faites de votre entreprise une structure
            engagée dans la lutte pour l'égalité et la fin des violences basées sur le
            genre, fédérez vos collaborateurs et collaboratrices, et développez
            l'attractivité de votre marque employeur.
        </p>

        <div class="cards-grid cards-grid--3" style="margin-bottom: 48px;">
            <article class="info-card info-card--teal">
                <h3>Mécénat financier</h3>
                <p>Versement mensuel ou ponctuel, arrondis sur salaire, arrondis en caisse…</p>
            </article>
            <article class="info-card info-card--purple">
                <h3>Mécénat en nature</h3>
                <p>Don mobilier, immobilier, de compétences…</p>
            </article>
            <article class="info-card info-card--coral">
                <h3>Parrainage, sponsoring</h3>
                <p>Prestation de service, produit-partage…</p>
            </article>
        </div>

        <div class="adhesion-grid">

            <div class="adhesion-steps">
                <h2>Garanties d'indépendance inscrites dans notre ADN</h2>
                <p class="section-intro">Sponsoriser le CREAI-VBG ne signifie PAS :</p>
                <ul class="check-list check-list--cross">
                    <li>Avoir un droit de regard sur nos contenus, analyses ou prises de position.</li>
                    <li>Avoir accès aux données brutes ou individuelles collectées.</li>
                    <li>Disposer d'une subordination dans nos orientations stratégiques.</li>
                    <li>Influencer nos choix méthodologiques.</li>
                </ul>
                <p class="section-intro">
                    Nous nous réservons le droit de refuser un sponsoring si l'organisation
                    a des pratiques contraires à nos valeurs, s'il existe un conflit
                    d'intérêt manifeste, ou si son image publique pourrait nuire à notre
                    crédibilité.
                </p>

                <h2 style="margin-top: 32px;">Ce que votre soutien permet concrètement</h2>
                <ul class="check-list">
                    <li>La création d'emplois qualifiés (analystes, coordinateurs, formateurs, chargé(e)s de prévention et d'accompagnement des victimes et des auteur(e)s de violence…).</li>
                    <li>Le développement de nouveaux outils méthodologiques gratuits pour le terrain.</li>
                    <li>La collecte et l'analyse de données fiables et contextualisées.</li>
                    <li>L'organisation de tables rondes et de projets intersectoriels.</li>
                    <li>Une veille documentaire et législative mutualisée de qualité.</li>
                    <li>Des formations accessibles adaptées aux réalités du terrain.</li>
                    <li>L'opérationnalisation du service d'accompagnement des victimes de VBG.</li>
                    <li>Des campagnes de sensibilisation et des actions de prévention pérennes.</li>
                    <li>Le développement et le déploiement de solutions technologiques au service de la prévention et de la réponse aux VBG.</li>
                </ul>
            </div>

            <div class="form-card">
                <h2>Écrivez-nous</h2>
                <p class="section-intro" style="margin-bottom: 20px;">Nous vous répondrons dans les meilleurs délais.</p>

                <?php if ($partenaireSuccess): ?>
                    <div class="alert alert-success">Merci pour votre message, nous revenons vers vous rapidement.</div>
                <?php endif; ?>

                <?php if ($partenaireErrors): ?>
                    <div class="alert alert-error">
                        <strong>Veuillez corriger :</strong>
                        <ul>
                            <?php foreach ($partenaireErrors as $e): ?>
                                <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                    <input type="hidden" name="form_type" value="partenariat">

                    <label for="p_nom">Nom *</label>
                    <input type="text" id="p_nom" name="nom" required maxlength="100"
                           value="<?= htmlspecialchars($partenaireOld['nom']) ?>">

                    <label for="p_telephone">Numéro de téléphone</label>
                    <input type="tel" id="p_telephone" name="telephone" maxlength="30"
                           value="<?= htmlspecialchars($partenaireOld['telephone']) ?>">

                    <label for="p_email">Email *</label>
                    <input type="email" id="p_email" name="email" required
                           value="<?= htmlspecialchars($partenaireOld['email']) ?>">

                    <label for="p_organisation">Organisation</label>
                    <input type="text" id="p_organisation" name="organisation" maxlength="150"
                           value="<?= htmlspecialchars($partenaireOld['organisation']) ?>">

                    <label for="p_objet">Objet de votre demande *</label>
                    <input type="text" id="p_objet" name="objet" required maxlength="150"
                           value="<?= htmlspecialchars($partenaireOld['objet']) ?>">

                    <label for="p_question">Question *</label>
                    <textarea id="p_question" name="question" rows="5" required minlength="10" maxlength="5000"><?= htmlspecialchars($partenaireOld['question']) ?></textarea>

                    <button type="submit">Envoyer</button>
                </form>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     CTA FINAL
     ============================================================ -->
<section class="page-cta">
    <div class="page-container">
        <h2>Ensemble, rendons les VBG évitables</h2>
        <p>
            Faites un don, adhérez ou associez votre organisation à nos actions :
            nous vous répondrons pour définir la meilleure façon de contribuer.
        </p>
        <div class="cta-buttons">
            <a href="/don.php" class="btn-cta btn-cta--primary">Faire un don</a>
            <a href="/rejoindre.php" class="btn-cta btn-cta--ghost">Adhérer</a>
        </div>
    </div>
</section>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>

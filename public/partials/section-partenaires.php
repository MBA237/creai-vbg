<?php
/** Section « Devenir partenaire ou sponsor ». Attend $forms (voir src/engagement_forms.php). */
$pf = $forms['partenaire'];
$pOld = $pf['old'];
?>
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

        <p class="partenariat-tagline">Ensemble, construisons un partenariat basé sur nos valeurs communes.</p>

        <div class="adhesion-grid">

            <div class="adhesion-steps">
                <h2>Garanties d'indépendance inscrites dans notre ADN</h2>
                <p class="section-intro" style="margin-bottom: 18px;"><strong>Sponsoriser le CREAI-VBG ne signifie PAS :</strong></p>

                <div class="fact-card fact-card--compact">
                    <ul class="fact-list fact-list--compact">
                        <?php
                        $garanties = [
                            'Avoir un <strong>droit de regard</strong> sur nos contenus, analyses ou prises de position.',
                            'Avoir accès aux <strong>données brutes ou individuelles</strong> collectées.',
                            'Disposer d\'une <strong>subordination</strong> dans nos orientations stratégiques.',
                            'Influencer nos <strong>choix méthodologiques</strong>.',
                        ];
                        foreach ($garanties as $garantie): ?>
                            <li class="fact-item fact-item--inline">
                                <span class="fact-icon fact-icon--no" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"/>
                                        <line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                </span>
                                <p><?= $garantie ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="info-highlight" style="margin: 20px 0 0;">
                    <div class="info-highlight-row info-highlight-row--alert">
                        <div class="info-highlight-icon info-highlight-icon--alert" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <div class="info-highlight-text">
                            <h3>Un droit de refus</h3>
                            <p>
                                Nous nous réservons le droit de refuser un sponsoring si l'organisation
                                a des pratiques contraires à nos valeurs, s'il existe un conflit
                                d'intérêt manifeste, ou si son image publique pourrait nuire à notre
                                crédibilité.
                            </p>
                        </div>
                    </div>
                </div>

                <h2 style="margin-top: 48px;">Ce que votre soutien permet concrètement</h2>
                <p class="section-intro" style="margin-bottom: 18px;">
                    <strong>Plus de moyens = plus d'impact</strong> pour toute la communauté
                    professionnelle. Votre contribution finance :
                </p>

                <div class="fact-card fact-card--compact">
                    <ul class="fact-list fact-list--compact">
                        <?php
                        // [icône (contenu du <svg>), texte]
                        $financements = [
                            ['<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
                             '<strong>La création d\'emplois qualifiés</strong> (analystes, coordinateurs, formateurs, chargé(e)s de prévention et d\'accompagnement des victimes et des auteur(e)s de violence…).'],
                            ['<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
                             '<strong>Le développement de nouveaux outils méthodologiques</strong> gratuits pour le terrain.'],
                            ['<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
                             '<strong>La collecte et l\'analyse de données</strong> fiables et contextualisées.'],
                            ['<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
                             '<strong>L\'organisation de tables rondes</strong> et de projets intersectoriels.'],
                            ['<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
                             '<strong>Une veille documentaire et législative</strong> mutualisée de qualité.'],
                            ['<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>',
                             '<strong>Des formations accessibles</strong> adaptées aux réalités du terrain.'],
                            ['<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
                             '<strong>L\'opérationnalisation du service d\'accompagnement</strong> des victimes de VBG.'],
                            ['<path d="M3 11l18-8-8 18-2-8-8-2z"/>',
                             '<strong>Des campagnes de sensibilisation</strong> et des actions de prévention pérennes.'],
                            ['<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/>',
                             '<strong>Le développement et le déploiement de solutions technologiques</strong> au service de la prévention et de la réponse aux VBG.'],
                        ];
                        foreach ($financements as [$iconPaths, $texte]): ?>
                            <li class="fact-item fact-item--inline">
                                <span class="fact-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><?= $iconPaths ?></svg>
                                </span>
                                <p><?= $texte ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="info-highlight" style="margin: 20px 0 0;">
                    <div class="info-highlight-row">
                        <div class="info-highlight-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </div>
                        <div class="info-highlight-text">
                            <h3>Une action collective, en toute indépendance</h3>
                            <p>
                                Votre soutien renforce notre capacité d'action collective, sans
                                jamais compromettre notre indépendance.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <h2>Écrivez-nous</h2>
                <p class="section-intro" style="margin-bottom: 20px;">Nous vous répondrons dans les meilleurs délais.</p>

                <?php if ($pf['success']): ?>
                    <div class="alert alert-success">Merci pour votre message, nous revenons vers vous rapidement.</div>
                <?php endif; ?>

                <?php if ($pf['errors']): ?>
                    <div class="alert alert-error">
                        <strong>Veuillez corriger :</strong>
                        <ul>
                            <?php foreach ($pf['errors'] as $msg): ?>
                                <li><?= htmlspecialchars($msg) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="#partenaires">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                    <input type="hidden" name="form_type" value="partenariat">

                    <label for="p_nom">Nom *</label>
                    <input type="text" id="p_nom" name="nom" required maxlength="100"
                           value="<?= htmlspecialchars($pOld['nom']) ?>">

                    <label for="p_telephone">Numéro de téléphone</label>
                    <input type="tel" id="p_telephone" name="telephone" maxlength="30"
                           value="<?= htmlspecialchars($pOld['telephone']) ?>">

                    <label for="p_email">Email *</label>
                    <input type="email" id="p_email" name="email" required
                           value="<?= htmlspecialchars($pOld['email']) ?>">

                    <label for="p_organisation">Organisation</label>
                    <input type="text" id="p_organisation" name="organisation" maxlength="150"
                           value="<?= htmlspecialchars($pOld['organisation']) ?>">

                    <label for="p_objet">Objet de votre demande *</label>
                    <input type="text" id="p_objet" name="objet" required maxlength="150"
                           value="<?= htmlspecialchars($pOld['objet']) ?>">

                    <label for="p_question">Question *</label>
                    <textarea id="p_question" name="question" rows="5" required minlength="10" maxlength="5000"><?= htmlspecialchars($pOld['question']) ?></textarea>

                    <button type="submit">Envoyer</button>
                </form>
            </div>

        </div>
    </div>
</section>

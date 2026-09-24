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
                <p class="section-intro">
                    Plus de moyens = plus d'impact pour toute la communauté professionnelle.
                    Votre contribution finance :
                </p>
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
                <p class="section-intro" style="margin-top: 24px;">
                    <strong>Votre soutien renforce notre capacité d'action collective, sans
                    jamais compromettre notre indépendance.</strong>
                </p>
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

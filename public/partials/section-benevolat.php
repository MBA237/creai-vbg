<?php
/** Section « Devenir bénévole ». Attend $forms (voir src/engagement_forms.php). */
$bf = $forms['benevole'];
$bOld = $bf['old'];
?>
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

                <?php if ($bf['success']): ?>
                    <div class="alert alert-success">Merci beaucoup pour ta réponse ! Nous reviendrons vers toi avec plus de précisions 🌸</div>
                <?php endif; ?>

                <?php if ($bf['errors']): ?>
                    <div class="alert alert-error">
                        <strong>Veuillez corriger :</strong>
                        <ul>
                            <?php foreach ($bf['errors'] as $msg): ?>
                                <li><?= htmlspecialchars($msg) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="#benevolat" class="wizard-form">
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
                               value="<?= htmlspecialchars($bOld['email']) ?>">

                        <label for="b_nom">Ton nom *</label>
                        <input type="text" id="b_nom" name="nom" required maxlength="100"
                               value="<?= htmlspecialchars($bOld['nom']) ?>">

                        <label for="b_prenom">Ton prénom *</label>
                        <input type="text" id="b_prenom" name="prenom" required maxlength="100"
                               value="<?= htmlspecialchars($bOld['prenom']) ?>">

                        <label for="b_telephone">Ton numéro de téléphone *</label>
                        <input type="tel" id="b_telephone" name="telephone" required maxlength="30"
                               value="<?= htmlspecialchars($bOld['telephone']) ?>">

                        <div class="wizard-actions">
                            <button type="button" class="wizard-next">Suivant</button>
                        </div>
                    </div>

                    <!-- Étape 2 : toi en quelques mots -->
                    <div class="wizard-step" data-step="2" hidden>
                        <label for="b_date_naissance">Ta date de naissance *</label>
                        <input type="date" id="b_date_naissance" name="date_naissance" required
                               value="<?= htmlspecialchars($bOld['date_naissance']) ?>">

                        <label for="b_ville">Ta ville résidente *</label>
                        <input type="text" id="b_ville" name="ville" required maxlength="100"
                               value="<?= htmlspecialchars($bOld['ville']) ?>">

                        <label for="b_connu_par">Comment as-tu connu CREAI-VBG ?</label>
                        <input type="text" id="b_connu_par" name="connu_par" maxlength="255"
                               value="<?= htmlspecialchars($bOld['connu_par']) ?>">

                        <label for="b_motivations">Quelles sont tes motivations pour devenir bénévole ?</label>
                        <textarea id="b_motivations" name="motivations" rows="3"><?= htmlspecialchars($bOld['motivations']) ?></textarea>

                        <label for="b_experience">As-tu déjà eu une activité bénévole ?</label>
                        <textarea id="b_experience" name="experience" rows="3"><?= htmlspecialchars($bOld['experience']) ?></textarea>

                        <div class="wizard-actions">
                            <button type="button" class="wizard-prev">Précédent</button>
                            <button type="button" class="wizard-next">Suivant</button>
                        </div>
                    </div>

                    <!-- Étape 3 : préférences -->
                    <div class="wizard-step" data-step="3" hidden>
                        <label>Quel canal de communication préfères-tu ? *</label>
                        <div class="radio-group">
                            <?php foreach ($forms['canaux'] as $value => $label): ?>
                                <label class="radio-option">
                                    <input type="radio" name="canal" value="<?= htmlspecialchars($value) ?>"
                                        <?= $bOld['canal'] === $value ? 'checked' : '' ?> required>
                                    <?= htmlspecialchars($label) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <label for="b_notes">Y a-t-il autre chose que tu voudrais que l'on sache sur toi ? (facultatif)</label>
                        <textarea id="b_notes" name="notes" rows="3"><?= htmlspecialchars($bOld['notes']) ?></textarea>

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

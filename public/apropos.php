<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Qui sommes-nous — CREAI-VBG';
$pageCss   = 'apropos.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<main class="apropos-page">

<!-- ============================================================
     HERO
     ============================================================ -->
<section class="apropos-hero">
    <div class="apropos-hero-content">
        <span class="apropos-badge">Qui sommes-nous</span>
        <h1>Relier science, éducation, innovation et terrain</h1>
        <p class="apropos-lead">
            Le CREAI-VBG est né d'un constat partagé par ses membres fondateurs :
            la lutte contre les violences basées sur le genre ne peut plus se
            limiter à des réponses ponctuelles ou cloisonnées.
        </p>
    </div>
</section>
<!-- ============================================================
     NOTRE HISTOIRE
     ============================================================ -->
<section class="apropos-section">
    <div class="apropos-container">
        <span class="eyebrow eyebrow--teal">Notre histoire et notre raison d'être</span>

        <div class="histoire-layout">

            <!-- Colonne texte -->
            <div class="histoire-content">
                <p class="apropos-text">
                    Trop souvent, la recherche, l'éducation, l'innovation et l'intervention
                    de terrain avancent chacune de leur côté, sans jamais converger vers
                    une stratégie commune. Face à ce constat, il est nécessaire de relier
                    ces quatre dimensions au sein d'une seule et même structure.
                </p>
                <p class="apropos-text">
                    C'est cette conviction — que la rigueur scientifique, l'éducation
                    préventive, l'innovation technologique et l'action de terrain doivent
                    avancer ensemble — qui fonde aujourd'hui le CREAI-VBG.
                </p>
                <p class="apropos-text">
                    Notre approche repose sur un principe simple mais rarement mis en
                    œuvre : accompagner les survivant(e)s ne suffit pas à briser le cycle
                    de la violence si l'on n'agit pas aussi, en parallèle, sur la
                    responsabilisation des auteur(e)s et sur les racines sociales et
                    culturelles du problème.
                </p>
            </div>

            <!-- Colonne image -->
            <div class="histoire-image">
                <img src="/images/apropos/histoire.jpg"
                     alt="Le CREAI-VBG sur le terrain"
                     loading="lazy">
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     VISION / MISSION
     ============================================================ -->
<section class="apropos-section apropos-section--alt">
    <div class="apropos-container">
        <div class="vm-grid">
            <div class="vm-card vm-card--teal">
                <div class="vm-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </div>
                <span class="vm-label">Notre vision</span>
                <p>
                    Une société camerounaise, et africaine, affranchie des violences
                    basées sur le genre, où règnent l'équité, la dignité humaine,
                    la justice réparatrice et une culture de la prévention.
                </p>
            </div>
            <div class="vm-card vm-card--purple">
                <div class="vm-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <circle cx="12" cy="12" r="6"/>
                        <circle cx="12" cy="12" r="2"/>
                    </svg>
                </div>
                <span class="vm-label">Notre mission</span>
                <p>
                    Articuler quatre leviers complémentaires — recherche, éducation
                    et prévention, innovation technologique, action de terrain —
                    pour enrayer la récidive et briser durablement le cycle de la
                    violence.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     NOS VALEURS
     ============================================================ -->
<section class="apropos-section">
    <div class="apropos-container">
        <span class="eyebrow eyebrow--coral">Nos valeurs</span>
        <div class="values-grid">

            <div class="value-chip">
                <div class="value-chip-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 2h6v6l4 10H5L9 8V2z"/>
                        <path d="M9 2h6"/>
                    </svg>
                </div>
                <h3>Rigueur scientifique</h3>
                <p><em>Données probantes et méthodologie validée par des pairs.</em></p>
            </div>

            <div class="value-chip">
                <div class="value-chip-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <h3>Dignité et confidentialité</h3>
                <p><em>Protection non négociable des personnes accompagnées.</em></p>
            </div>

            <div class="value-chip">
                <div class="value-chip-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M2 12h20"/>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                </div>
                <h3>Approche holistique</h3>
                <p><em>Recherche, éducation, innovation et action avancent ensemble.</em></p>
            </div>

            <div class="value-chip">
                <div class="value-chip-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v20"/>
                        <path d="M5 7h14"/>
                        <path d="M5 7l-3 7a3.5 3.5 0 0 0 6 0L5 7z"/>
                        <path d="M19 7l-3 7a3.5 3.5 0 0 0 6 0l-3-7z"/>
                    </svg>
                </div>
                <h3>Indépendance et neutralité</h3>
                <p><em>Organisation apolitique, sans affiliation confessionnelle ni tribale.</em></p>
            </div>

            <div class="value-chip">
                <div class="value-chip-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18h6"/>
                        <path d="M10 22h4"/>
                        <path d="M12 2a7 7 0 0 0-7 7c0 2.5 1.5 4.5 3 6h8c1.5-1.5 3-3.5 3-6a7 7 0 0 0-7-7z"/>
                    </svg>
                </div>
                <h3>Innovation au service de l'humain</h3>
                <p><em>La technologie comme moyen, jamais comme fin — « Tech for Good ».</em></p>
            </div>

            <div class="value-chip">
                <div class="value-chip-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                </div>
                <h3>Diversité des savoirs</h3>
                <p><em>Expériences vécues, sagesse pratique et savoirs communautaires.</em></p>
            </div>

            <div class="value-chip">
                <div class="value-chip-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3>Leadership collectif</h3>
                <p><em>Des espaces sécuritaires pour explorer et collaborer.</em></p>
            </div>

            <div class="value-chip">
                <div class="value-chip-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                </div>
                <h3>Équité</h3>
                <p><em>Des besoins uniques reconnus dans la lutte contre la violence.</em></p>
            </div>

            <div class="value-chip">
                <div class="value-chip-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 11l18-8-8 18-2-8-8-2z"/>
                    </svg>
                </div>
                <h3>Plaidoyer</h3>
                <p><em>Amplifier les voix qui contestent et changent les conditions de la violence.</em></p>
            </div>

            <div class="value-chip">
                <div class="value-chip-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <h3>Partenariats</h3>
                <p><em>Soutenir la justice sociale par la collaboration.</em></p>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     NOS OBJECTIFS
     ============================================================ -->
<section class="apropos-section apropos-section--alt">
    <div class="apropos-container">
        <span class="eyebrow eyebrow--teal">Nos objectifs</span>
        <div class="objectifs-grid">

            <div class="objectif-item">
                <span class="objectif-num">01</span>
                <div class="objectif-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 2h6v6l4 10H5L9 8V2z"/>
                        <path d="M9 2h6"/>
                    </svg>
                </div>
                <h3>Éclairer par la science</h3>
                <p><em>Recherches empiriques et analyses rigoureuses pour guider les politiques publiques.</em></p>
            </div>

            <div class="objectif-item">
                <span class="objectif-num">02</span>
                <div class="objectif-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7z"/>
                        <circle cx="12" cy="9" r="2.5"/>
                    </svg>
                </div>
                <h3>Prévenir pour transformer</h3>
                <p><em>Formation, sensibilisation communautaire et éducation civique pour déconstruire les stéréotypes.</em></p>
            </div>

            <div class="objectif-item">
                <span class="objectif-num">03</span>
                <div class="objectif-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="14" rx="2"/>
                        <path d="M8 21h8M12 17v4"/>
                    </svg>
                </div>
                <h3>Innover et digitaliser</h3>
                <p><em>Chatbots d'assistance, plateformes de données et espaces mémoriels numériques.</em></p>
            </div>

            <div class="objectif-item">
                <span class="objectif-num">04</span>
                <div class="objectif-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3>Agir et accompagner sur le terrain</h3>
                <p><em>Écoute et orientation des survivantes, parcours de responsabilisation pour les auteurs.</em></p>
            </div>

            <div class="objectif-item">
                <span class="objectif-num">05</span>
                <div class="objectif-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 11l18-8-8 18-2-8-8-2z"/>
                    </svg>
                </div>
                <h3>Plaider et fédérer</h3>
                <p><em>Partenariats avec institutions, autorités locales et société civile.</em></p>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     OÙ NOUS INTERVENONS
     ============================================================ -->
<section class="apropos-section">
    <div class="apropos-container apropos-container--narrow apropos-container--center">
        <span class="eyebrow eyebrow--purple">Où nous intervenons ?</span>
        <p class="apropos-text">
            Le CREAI-VBG déploie ses activités sur l'ensemble du territoire du
            Cameroun. Nous envisageons, à terme et dans le cadre de partenariats
            régionaux, d'étendre notre champ d'action à d'autres pays d'Afrique.
        </p>
    </div>
</section>

<!-- ============================================================
     GOUVERNANCE
     ============================================================ -->
<section class="apropos-section apropos-section--alt">
    <div class="apropos-container">
        <span class="eyebrow eyebrow--teal">Notre gouvernance</span>
        <div class="gouvernance-grid">

            <div class="gouvernance-item">
                <div class="gouvernance-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18"/>
                        <path d="M5 21V10l7-5 7 5v11"/>
                        <path d="M9 21v-6h6v6"/>
                    </svg>
                </div>
                <h3>Assemblée Générale</h3>
                <p><em>Organe suprême, réunit membres fondateurs et actifs. Définit les orientations stratégiques.</em></p>
            </div>

            <div class="gouvernance-item">
                <div class="gouvernance-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                </div>
                <h3>Conseil d'Administration</h3>
                <p><em>Élu par l'Assemblée Générale, met en œuvre les orientations et supervise la gestion.</em></p>
            </div>

            <div class="gouvernance-item">
                <div class="gouvernance-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                </div>
                <h3>Bureau Exécutif</h3>
                <p><em>Gestion quotidienne des activités et coordination des cinq piliers d'action.</em></p>
            </div>

            <div class="gouvernance-item">
                <div class="gouvernance-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>
                <h3>Comité Scientifique et Éthique</h3>
                <p><em>Chercheurs, universitaires et praticiens veillant à la rigueur méthodologique et à l'éthique.</em></p>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     NOTRE ÉQUIPE
     ============================================================ -->
<?php
require_once __DIR__ . '/../src/Pole.php';
require_once __DIR__ . '/../src/Membre.php';

$poleModel   = new Pole();
$membreModel = new Membre();

$poles        = $poleModel->getAll();
$membresByPole = $membreModel->getAllGroupedByPole();
?>

<section class="apropos-section">
    <div class="apropos-container">
        <span class="eyebrow eyebrow--purple">Notre équipe</span>
        <p class="apropos-intro">
            Sept pôles organisent notre action au quotidien.
            <a href="/notre-equipe.php">Voir toute l'équipe →</a>
        </p>

        <!-- Onglets -->
        <div class="equipe-tabs" role="tablist">
            <?php foreach ($poles as $i => $pole): ?>
                <button type="button"
                        class="equipe-tab <?= $i === 0 ? 'is-active' : '' ?>"
                        role="tab"
                        data-pole="<?= htmlspecialchars($pole['slug']) ?>"
                        aria-selected="<?= $i === 0 ? 'true' : 'false' ?>">
                    <?= htmlspecialchars($pole['nom']) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Panneaux -->
        <div class="equipe-panels">
            <?php foreach ($poles as $i => $pole):
                $slug    = $pole['slug'];
                $membres = $membresByPole[$slug] ?? [];
            ?>
                <div class="equipe-panel <?= $i === 0 ? 'is-active' : '' ?>"
                     data-pole="<?= htmlspecialchars($slug) ?>"
                     role="tabpanel">

                    <?php if (empty($membres)): ?>
                        <p class="equipe-empty">
                            Les membres de ce pôle seront bientôt présentés.
                        </p>
                    <?php else: ?>
                        <div class="membres-grid">
                            <?php foreach ($membres as $m):
                                $photo = $m['photo'] ?: '/images/team/default-avatar.png';
                            ?>
                                <article class="membre-card">
                                    <div class="membre-photo">
                                        <img src="<?= htmlspecialchars($photo) ?>"
                                             alt="<?= htmlspecialchars($m['nom']) ?>"
                                             loading="lazy">
                                    </div>
                                    <div class="membre-body">
                                        <h3 class="membre-nom"><?= htmlspecialchars($m['nom']) ?></h3>
                                        <p class="membre-poste"><?= htmlspecialchars($m['poste']) ?></p>
                                        <p class="membre-profession"><?= htmlspecialchars($m['profession']) ?></p>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     CTA FINAL
     ============================================================ -->
<section class="apropos-cta">
    <div class="apropos-container">
        <h2>Il y a une place pour vous</h2>
        <p>
            Chercheur(e), professionnel(le) de santé, juriste, éducateur(rice),
            ou simplement convaincu(e) que la lutte contre les VBG est l'affaire
            de tous : rejoignez le CREAI-VBG.
        </p>
        <div class="cta-buttons">
            <a href="/rejoindre.php" class="btn-cta btn-cta--primary">Devenir membre</a>
            <a href="/contact.php" class="btn-cta btn-cta--outline">Nous contacter</a>
        </div>
    </div>
</section>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
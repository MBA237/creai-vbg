<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Nos piliers d\'action — CREAI-VBG';
$pageCss   = 'piliers.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<div class="piliers-page">

    <!-- ============================================================
         HERO
         ============================================================ -->
    <section class="piliers-hero">
        <div class="piliers-hero-content">
            <span class="piliers-badge">Ce que nous faisons</span>
            <h1>Nos 5 piliers d'action</h1>
            <p class="piliers-lead">
                Le CREAI-VBG répond à toutes les formes de violence basée sur le genre.
                Nos interventions s'articulent autour de cinq piliers complémentaires,
                conçus pour se renforcer mutuellement et briser durablement le cycle
                de la violence.
            </p>
        </div>
    </section>

    
    <!-- ============================================================
         PILIER 1 — RECHERCHE
         ============================================================ -->
    <section class="pilier-block" id="pilier-recherche">
        <div class="piliers-container">
            <h2 class="pilier-title">
                <span class="pilier-title-num">Pilier 1</span>
                <span class="pilier-title-text">Recherche &amp; Production de Données</span>
            </h2>

            <div class="pilier-layout">
                <div class="pilier-visual">
                    <div class="pilier-number">01</div>
                    <img src="/images/recherche.jpg" alt="Recherche & Production de Données" loading="lazy">
                </div>

                <div class="pilier-content">
                    <h3>Éclairer par la science</h3>

                    <p class="piliers-text">
                        Nous produisons des connaissances fondamentales et interdisciplinaires
                        sur les causes, les contextes et les réponses les plus efficaces à la
                        violence. Ces recherches sont intégrées dans nos solutions innovantes
                        et notre renforcement des capacités, afin de favoriser l'élimination
                        de la violence de genre.
                    </p>

                    <p class="piliers-text">
                        Nous menons des recherches qui éclairent les pratiques — notamment par
                        le biais de programmes éducatifs — et nous utilisons les enseignements
                        tirés de ces actions éducatives pour orienter nos initiatives de
                        recherche. Ce pilier fonde la crédibilité et la pertinence de tout ce
                        que nous entreprenons : <strong>on ne combat efficacement que ce que
                        l'on comprend précisément</strong>.
                    </p>

                    <p class="piliers-text">
                        Nos travaux sont menés en collaboration avec des institutions académiques
                        nationales et internationales et des organisations communautaires, et
                        sont soumis à la validation méthodologique de notre Comité Scientifique
                        et Éthique.
                    </p>
                </div>
            </div>

            <div class="pilier-cta-wrapper">
                <a href="/pilier-recherche.php" class="piliers-cta">
                    Découvrir nos recherches en cours →
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================================
         PILIER 2 — PRÉVENTION
         ============================================================ -->
    <section class="pilier-block pilier-block--alt" id="pilier-prevention">
        <div class="piliers-container">
            <h2 class="pilier-title">
                <span class="pilier-title-num">Pilier 2</span>
                <span class="pilier-title-text">Prévention &amp; Éducation Communautaire</span>
            </h2>

            <div class="pilier-layout pilier-layout--reverse">
                <div class="pilier-visual">
                    <div class="pilier-number">02</div>
                    <img src="/images/prevention.jpg" alt="Prévention & Éducation Communautaire" loading="lazy">
                </div>

                <div class="pilier-content">
                    <h3>Prévenir la violence avant qu'elle ne survienne</h3>

                    <p class="piliers-text">
                        Pour lutter efficacement pour une société sans violence sexiste, nous
                        avons besoin d'un <strong>changement de paradigme</strong> — un changement
                        qui nous permette d'envisager une réalité où il est possible de prévenir
                        la violence avant qu'elle ne se produise.
                    </p>

                    <p class="piliers-text">
                        Nous privilégions délibérément la <strong>prévention primaire</strong>,
                        c'est-à-dire empêcher les préjudices avant même qu'ils ne surviennent,
                        en apprenant aux individus comment éviter de nuire, plutôt que comment
                        éviter d'en être victimes.
                    </p>
                </div>
            </div>

            <div class="pilier-subsections pilier-subsections--grid">
                <div class="pilier-subsection">
                    <h3>
                        <span class="pilier-icon">
                            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                <circle cx="4" cy="16" r="1.3" fill="currentColor" stroke="none"/>
                                <path d="M4 14.5c0-4 4-3 6-6s-1-5 3-6" stroke-dasharray="2 2.2"/>
                                <circle cx="16" cy="3" r="1.3" fill="currentColor" stroke="none"/>
                            </svg>
                        </span>
                        École Itinérante de Prévention des VBG
                    </h3>
                    <p>
                        Actions de prévention grand public, en entreprises et auprès de la
                        jeunesse, pour promouvoir l'égalité entre les femmes et les hommes,
                        les filles et les garçons, et faire reculer l'ensemble des VBG et
                        pratiques néfastes.
                    </p>
                </div>

                <div class="pilier-subsection">
                    <h3>
                        <span class="pilier-icon">
                            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                <circle cx="10" cy="10" r="1.5" fill="currentColor" stroke="none"/>
                                <path d="M6.5 6.5a5 5 0 000 7M13.5 6.5a5 5 0 010 7M4 4a8.5 8.5 0 000 12M16 4a8.5 8.5 0 010 12"/>
                            </svg>
                        </span>
                        Campagnes de sensibilisation
                    </h3>
                    <p>
                        Campagnes nationales avec supports variés : brochures, quiz,
                        flyers, capsules vidéo, dossiers pédagogiques, événements grand
                        public.
                    </p>
                </div>

                <div class="pilier-subsection">
                    <h3>
                        <span class="pilier-icon">
                            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round">
                                <path d="M10 3l7 3.5L10 10 3 6.5 10 3z"/>
                                <path d="M3 10l7 3.5 7-3.5" stroke-linecap="round"/>
                                <path d="M3 13.5l7 3.5 7-3.5" stroke-linecap="round"/>
                            </svg>
                        </span>
                        Centre de formation
                    </h3>
                    <p>
                        Ateliers sur mesure pour les organismes et entreprises : violence
                        entre partenaires intimes, violence sexuelle, prévention en milieu
                        de travail, etc. Formations en ligne gratuites pour les
                        professionnels (police, santé, social, juridique, éducation).
                    </p>
                </div>
            </div>

            <div class="pilier-cta-wrapper">
                <a href="/pilier-prevention.php" class="piliers-cta">
                    Découvrir nos programmes de prévention →
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================================
         PILIER 3 — INNOVATION
         ============================================================ -->
    <section class="pilier-block" id="pilier-innovation">
        <div class="piliers-container">
            <h2 class="pilier-title">
                <span class="pilier-title-num">Pilier 3</span>
                <span class="pilier-title-text">Innovation technologique</span>
            </h2>

            <div class="pilier-layout">
                <div class="pilier-visual">
                    <div class="pilier-number">03</div>
                    <img src="/images/innovation.jpg" alt="Innovation technologique" loading="lazy">
                </div>

                <div class="pilier-content">
                    <h3>Tirer parti des outils numériques</h3>

                    <p class="piliers-text">
                        Nous avons la conviction que la technologie numérique a un potentiel
                        important d'amélioration de la <strong>disponibilité</strong>, de
                        l'<strong>accessibilité</strong> et de la <strong>qualité</strong> des
                        services de lutte contre la VBG, ainsi que d'augmentation de la portée
                        des efforts de prévention.
                    </p>
                </div>
            </div>

            <div class="pilier-subsections pilier-subsections--grid">
                <div class="pilier-subsection">
                    <h3>
                        <span class="pilier-icon">
                            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 3l6 2.2v4c0 4-2.6 6.8-6 7.8-3.4-1-6-3.8-6-7.8v-4L10 3z"/>
                                <path d="M7.5 10l1.8 1.8L12.5 8"/>
                            </svg>
                        </span>
                        Outils pour la sécurité et le rétablissement
                    </h3>
                    <p>
                        Application web et mobile pour informer les survivant(e)s,
                        soutenir la planification de la sécurité et mettre en relation
                        avec des services et un soutien professionnel et par les pairs.
                    </p>
                </div>

                <div class="pilier-subsection">
                    <h3>
                        <span class="pilier-icon">
                            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 17s5.5-5.2 5.5-9A5.5 5.5 0 004.5 8c0 3.8 5.5 9 5.5 9z"/>
                                <circle cx="10" cy="8" r="1.8"/>
                            </svg>
                        </span>
                        Cartographie interactive et géolocalisation mémorielle
                    </h3>
                    <p>
                        Cartes interactives et bases de données sécurisées au service
                        de la mémoire des victimes de féminicides. Ces mémoriaux
                        virtuels luttent contre l'effacement des victimes, humanisent
                        les statistiques et sensibilisent le public.
                    </p>
                </div>

                <div class="pilier-subsection">
                    <h3>
                        <span class="pilier-icon">
                            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 5.5h14v8H8.5L5 16.5v-3H3v-8z"/>
                            </svg>
                        </span>
                        Communication numérique et leaders
                    </h3>
                    <p>
                        Formation des jeunes et activistes à l'utilisation stratégique
                        des plateformes numériques et des médias sociaux pour créer
                        des contenus éducatifs et déconstruire les mythes et préjugés.
                    </p>
                </div>
            </div>

            <div class="pilier-cta-wrapper">
                <a href="/pilier-innovation.php" class="piliers-cta">
                    Découvrir nos outils numériques →
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================================
         PILIER 4 — ACCOMPAGNEMENT
         ============================================================ -->
    <section class="pilier-block pilier-block--alt" id="pilier-accompagnement">
        <div class="piliers-container">
            <h2 class="pilier-title">
                <span class="pilier-title-num">Pilier 4</span>
                <span class="pilier-title-text">Accompagnement holistique</span>
            </h2>

            <div class="pilier-layout pilier-layout--reverse">
                <div class="pilier-visual">
                    <div class="pilier-number">04</div>
                    <img src="/images/accompagnement.jpg" alt="Accompagnement holistique" loading="lazy">
                </div>

                <div class="pilier-content">
                    <h3>L'action de terrain, pour les survivant(e)s comme pour les auteur(e)s</h3>

                    <p class="piliers-text">
                        Nous assurons la prise en charge psychosociale, médicale, juridique
                        et économique des survivant(e)s, ainsi que leur orientation vers les
                        structures compétentes. En parallèle — et c'est ce qui distingue notre
                        approche — nous mettons en œuvre des <strong>parcours de
                        responsabilisation</strong> destinés aux auteur(e)s de VBG, dans une
                        logique assumée de prévention de la récidive.
                    </p>
                </div>
            </div>

            <div class="pilier-subsections pilier-subsections--grid">

                <!-- Services aux victimes -->
                <div class="pilier-subsection pilier-subsection--victimes">
                    <h3>
                        <span class="pilier-icon">
                            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                <path d="M3 13c1.5-5 4-7 7-7s5.5 2 7 7"/>
                                <path d="M6 13c0-2.5 1.5-4 4-4s4 1.5 4 4"/>
                            </svg>
                        </span>
                        Services offerts aux victimes
                    </h3>

                    <ul class="service-items">
                        <li class="service-item">
                            <span class="service-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </span>
                            <div class="service-item-body">
                                <strong>Ligne d'écoute téléphonique</strong>
                                <span>Du lundi au vendredi, 9h00 – 17h00</span>
                            </div>
                        </li>

                        <li class="service-item">
                            <span class="service-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                </svg>
                            </span>
                            <div class="service-item-body">
                                <strong>Accompagnement psycho-social</strong>
                                <span>Soutien psychologique individuel et collectif</span>
                            </div>
                        </li>

                        <li class="service-item">
                            <span class="service-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 3v18"/>
                                    <path d="M5 8h14"/>
                                    <path d="M5 8l-2 7a3 3 0 0 0 6 0l-2-7"/>
                                    <path d="M19 8l-2 7a3 3 0 0 0 6 0l-2-7"/>
                                </svg>
                            </span>
                            <div class="service-item-body">
                                <strong>Soutien juridique</strong>
                                <span>Information et accompagnement dans les démarches</span>
                            </div>
                        </li>

                        <li class="service-item">
                            <span class="service-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="9" y1="15" x2="15" y2="15"/>
                                </svg>
                            </span>
                            <div class="service-item-body">
                                <strong>Accompagnement au procès</strong>
                                <span>Préparation, présence et suivi</span>
                            </div>
                        </li>

                        <li class="service-item">
                            <span class="service-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </span>
                            <div class="service-item-body">
                                <strong>Groupes de soutien</strong>
                                <span>Pair-aidance et reconnaissance entre pairs</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Services aux auteur(e)s -->
                <div class="pilier-subsection pilier-subsection--auteurs">
                    <h3>
                        <span class="pilier-icon">
                            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15.5 8a5.5 5.5 0 10-1.4 5.4M15.5 4v4h-4"/>
                            </svg>
                        </span>
                        Services offerts aux auteur(e)s
                    </h3>

                    <ul class="service-items">
                        <li class="service-item">
                            <span class="service-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </span>
                            <div class="service-item-body">
                                <strong>Accompagnement collectif</strong>
                                <span>Groupe de parole, stage de responsabilisation</span>
                            </div>
                        </li>

                        <li class="service-item">
                            <span class="service-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            <div class="service-item-body">
                                <strong>Accompagnement individuel</strong>
                                <span>Entretien thérapeutique, suivi médical, social et professionnel</span>
                            </div>
                        </li>

                        <li class="service-item">
                            <span class="service-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <circle cx="12" cy="12" r="6"/>
                                    <circle cx="12" cy="12" r="2"/>
                                </svg>
                            </span>
                            <div class="service-item-body">
                                <strong>Objectifs</strong>
                                <span>Comprendre les mécanismes de la violence, prévenir la récidive, transmettre le principe d'égalité</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pilier-cta-wrapper">
                <a href="/pilier-accompagnement.php" class="piliers-cta">
                    Découvrir nos services d'accompagnement →
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================================
         PILIER 5 — PLAIDOYER
         ============================================================ -->
    <section class="pilier-block" id="pilier-plaidoyer">
        <div class="piliers-container">
            <h2 class="pilier-title">
                <span class="pilier-title-num">Pilier 5</span>
                <span class="pilier-title-text">Plaidoyer politique &amp; Partenariats</span>
            </h2>

            <div class="pilier-layout">
                <div class="pilier-visual">
                    <div class="pilier-number">05</div>
                    <img src="/images/plaidoyer.jpg" alt="Plaidoyer & Partenariats" loading="lazy">
                </div>

                <div class="pilier-content">
                    <h3>L'impact systémique</h3>

                    <p class="piliers-text">
                        Nous construisons des partenariats avec les <strong>acteurs
                        institutionnels</strong>, les collectivités territoriales
                        décentralisées, les organisations de la société civile, les
                        <strong>agences des Nations Unies</strong> et les partenaires
                        techniques et financiers.
                    </p>

                    <p class="piliers-text">
                        Notre ambition : contribuer directement à l'<strong>élaboration</strong>
                        et à l'<strong>évaluation</strong> des politiques publiques de lutte
                        contre les VBG.
                    </p>
                </div>
            </div>

            <div class="pilier-cta-wrapper">
                <a href="/pilier-plaidoyer.php" class="piliers-cta">
                    Découvrir nos actions de plaidoyer →
                </a>
            </div>
        </div>
    </section>
<!-- ============================================================
         INTRO — LES FORMES DE VBG
         ============================================================ -->
    <section class="vbg-section">
        <div class="piliers-container">
            <header class="vbg-header">
                <span class="eyebrow eyebrow--teal">Les violences que nous combattons</span>
                <p class="vbg-intro">
                    Le CREAI-VBG répond à toutes les formes de violence basée sur le genre.
                    Chacune d'elles appelle une réponse adaptée, coordonnée et respectueuse
                    des personnes concernées.
                </p>
            </header>
        </div>

        <div class="vbg-carousel">
            <button type="button" class="vbg-nav vbg-nav--prev" id="vbgPrev" aria-label="Violences précédentes">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </button>

            <div class="vbg-scroll" id="vbgScroll">
                <div class="vbg-track">
                    <article class="vbg-card">
                        <div class="vbg-card-image">
                            <img src="/images/vbg/domestique.jpg" alt="Violence domestique et familiale" loading="lazy">
                        </div>
                        <div class="vbg-card-body">
                            <h3>Violence domestique et familiale</h3>
                            <p><em>Violence exercée au sein du couple ou de la famille, souvent répétée et amplifiée par la proximité et la dépendance affective ou économique.</em></p>
                        </div>
                    </article>

                    <article class="vbg-card">
                        <div class="vbg-card-image">
                            <img src="/images/vbg/sexuelle.jpg" alt="Violence sexuelle et sexiste" loading="lazy">
                        </div>
                        <div class="vbg-card-body">
                            <h3>Violence sexuelle et sexiste</h3>
                            <p><em>Tout acte à caractère sexuel imposé sans consentement, ainsi que les comportements dégradants fondés sur le genre.</em></p>
                        </div>
                    </article>

                    <article class="vbg-card">
                        <div class="vbg-card-image">
                            <img src="/images/vbg/pratiques-nefastes.jpg" alt="Pratiques néfastes" loading="lazy">
                        </div>
                        <div class="vbg-card-body">
                            <h3>Pratiques néfastes</h3>
                            <p><em>Coutumes ou traditions qui portent atteinte à l'intégrité physique ou psychologique, comme les mariages forcés ou précoces.</em></p>
                        </div>
                    </article>

                    <article class="vbg-card">
                        <div class="vbg-card-image">
                            <img src="/images/vbg/feminicide.jpg" alt="Féminicide" loading="lazy">
                        </div>
                        <div class="vbg-card-body">
                            <h3>Féminicide</h3>
                            <p><em>Meurtre d'une femme ou d'une fille en raison de son genre, expression la plus extrême du continuum des violences basées sur le genre.</em></p>
                        </div>
                    </article>

                    <article class="vbg-card">
                        <div class="vbg-card-image">
                            <img src="/images/vbg/traite.jpg" alt="Traite des êtres humains" loading="lazy">
                        </div>
                        <div class="vbg-card-body">
                            <h3>Traite des êtres humains</h3>
                            <p><em>Recrutement, transport ou hébergement de personnes par la contrainte ou la tromperie, à des fins d'exploitation.</em></p>
                        </div>
                    </article>

                    <article class="vbg-card">
                        <div class="vbg-card-image">
                            <img src="/images/vbg/exploitation.jpg" alt="Exploitation sexuelle et économique" loading="lazy">
                        </div>
                        <div class="vbg-card-body">
                            <h3>Exploitation sexuelle et économique (travail)</h3>
                            <p><em>Profit tiré du corps ou du travail d'autrui dans des conditions abusives, souvent renforcé par une situation de vulnérabilité.</em></p>
                        </div>
                    </article>

                    <article class="vbg-card">
                        <div class="vbg-card-image">
                            <img src="/images/vbg/numerique.jpg" alt="VBG facilitées par la technologie" loading="lazy">
                        </div>
                        <div class="vbg-card-body">
                            <h3>VBG facilitées par la technologie</h3>
                            <p><em>Harcèlement, diffusion non consentie d'images intimes ou traque numérique, prolongeant la violence au-delà de l'espace physique.</em></p>
                        </div>
                    </article>
                </div>
            </div>

            <button type="button" class="vbg-nav vbg-nav--next" id="vbgNext" aria-label="Violences suivantes">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </button>
        </div>
    </section>

    <!-- ============================================================
         CTA FINAL
         ============================================================ -->
    <section class="piliers-cta-final">
        <div class="piliers-container">
            <h2>Agir avec nous</h2>
            <p>
                Que vous soyez chercheur(e), professionnel(le) de santé, juriste,
                éducateur(rice), ou simplement convaincu(e) que la lutte contre les
                VBG est l'affaire de tous, il y a une place pour vous.
            </p>
            <div class="piliers-cta-buttons">
                <a href="/rejoindre.php" class="btn btn--primary">Devenir membre</a>
                <a href="/contact.php" class="btn btn--outline">Nous contacter</a>
            </div>
        </div>
    </section>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
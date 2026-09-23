<?php

declare(strict_types=1);
session_start();

$pageTitle = 'Faire un don — CREAI-VBG';
$pageCss   = 'don.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<main class="don-page">
    <div class="don-intro">
        <h1>Votre don change des vies</h1>
        <p>
            Chaque contribution finance notre ligne d'écoute, nos actions de
            prévention et l'accompagnement des survivant(e)s de violences
            basées sur le genre. Merci de faire partie du changement.
        </p>
    </div>

    <section class="don-shell" aria-label="Formulaire de don">
        <article class="don-panel">
            <header class="don-panel-header">
                <h2>Mon don</h2>
            </header>

            <div class="don-panel-body">
                <div class="don-toggle" role="tablist" aria-label="Type de don">
                    <button type="button" class="is-active" data-mode="mensuel" aria-pressed="true">Don mensuel</button>
                    <button type="button" data-mode="unique" aria-pressed="false">Don unique</button>
                </div>

                <div class="don-amounts" aria-label="Montants de don" data-amounts>
                    <label class="amount-option">
                        <input type="radio" name="don_amount" value="7" checked>
                        <span class="amount-label">7 € <small>par mois</small></span>
                    </label>

                    <label class="amount-option">
                        <input type="radio" name="don_amount" value="12">
                        <span class="amount-label">12 € <small>par mois</small></span>
                    </label>

                    <label class="amount-option">
                        <input type="radio" name="don_amount" value="30">
                        <span class="amount-label">30 € <small>par mois</small></span>
                    </label>

                    <label class="amount-option amount-option--wide" data-freeform>
                        <input type="radio" name="don_amount" value="libre">
                        <span class="amount-label">Montant libre</span>
                    </label>
                </div>

                <div class="don-help">
                    <span class="don-help-tag">Montant populaire !</span>
                </div>
            </div>
        </article>

        <article class="don-panel">
            <header class="don-panel-header">
                <h2>Mes coordonnées</h2>
            </header>

            <div class="don-panel-body">
                <form class="don-form" method="post" action="/contact.php?sujet=don">
                    <div class="field field--email">
                        <label for="don-email">EMAIL *</label>
                        <div class="input-wrap input-wrap--icon">
                            <span class="field-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 6h16v12H4z"></path>
                                    <path d="m4 7 8 6 8-6"></path>
                                </svg>
                            </span>
                            <input id="don-email" type="email" name="email" placeholder="">
                        </div>
                    </div>

                    <label class="checkbox-inline">
                        <input type="checkbox" name="association" value="1">
                        <span>Je fais un don au nom d’une organisation ou d’une société</span>
                    </label>

                    <div class="field-grid">
                        <div class="field">
                            <label for="don-civilite">Civilité *</label>
                            <select id="don-civilite" name="civilite">
                                <option value=""> </option>
                                <option value="madame">Madame</option>
                                <option value="monsieur">Monsieur</option>
                            </select>
                        </div>

                        <div class="field">
                            <label for="don-prenom">Prénom *</label>
                            <input id="don-prenom" type="text" name="prenom">
                        </div>

                        <div class="field">
                            <label for="don-nom">Nom *</label>
                            <input id="don-nom" type="text" name="nom">
                        </div>

                        <div class="field field--full">
                            <label for="don-telephone">Téléphone *</label>
                            <div class="phone-field">
                                <select id="don-pays-tel" name="pays_tel" class="phone-country" aria-label="Indicatif pays">
                                    <option value="+237" selected>🇨🇲 +237</option>
                                    <option value="+33">🇫🇷 +33</option>
                                    <option value="+225">🇨🇮 +225</option>
                                    <option value="+221">🇸🇳 +221</option>
                                    <option value="+234">🇳🇬 +234</option>
                                    <option value="+235">🇹🇩 +235</option>
                                    <option value="+241">🇬🇦 +241</option>
                                    <option value="+242">🇨🇬 +242</option>
                                    <option value="+243">🇨🇩 +243</option>
                                    <option value="+229">🇧🇯 +229</option>
                                    <option value="+228">🇹🇬 +228</option>
                                    <option value="+223">🇲🇱 +223</option>
                                    <option value="+226">🇧🇫 +226</option>
                                    <option value="+227">🇳🇪 +227</option>
                                    <option value="+224">🇬🇳 +224</option>
                                    <option value="+32">🇧🇪 +32</option>
                                    <option value="+1">🇨🇦 +1</option>
                                    <option value="+44">🇬🇧 +44</option>
                                    <option value="+49">🇩🇪 +49</option>
                                    <option value="+41">🇨🇭 +41</option>
                                </select>
                                <input id="don-telephone" type="tel" name="telephone" placeholder="6 00 00 00 00">
                            </div>
                        </div>

                        <div class="field">
                            <label for="don-date">Date de naissance *</label>
                            <div class="input-wrap input-wrap--calendar">
                                <input id="don-date" type="date" name="date_naissance">
                                <span class="field-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                        <path d="M16 3v4M8 3v4M3 10h18"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="field field--full">
                            <label for="don-adresse">Adresse *</label>
                            <input id="don-adresse" type="text" name="adresse">
                        </div>

                        <div class="field field--full">
                            <label for="don-complement">Complément d'adresse</label>
                            <input id="don-complement" type="text" name="complement_adresse">
                        </div>

                        <div class="field">
                            <label for="don-codepostal">Code postal *</label>
                            <input id="don-codepostal" type="text" name="code_postal">
                        </div>

                        <div class="field">
                            <label for="don-ville">Ville *</label>
                            <input id="don-ville" type="text" name="ville">
                        </div>

                        <div class="field field--full">
                            <label for="don-pays">Pays *</label>
                            <input id="don-pays" type="text" name="pays" value="Cameroun">
                        </div>
                    </div>
                </form>
            </div>
        </article>

        <article class="don-panel don-panel--payment">
            <header class="don-panel-header">
                <h2>Mon règlement</h2>
            </header>

            <div class="don-panel-body">
                <div class="payment-intro">
                    <div class="payment-shield" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3 5 6v6c0 4.5 2.7 7.8 7 9 4.3-1.2 7-4.5 7-9V6l-7-3Z"></path>
                            <path d="m9 12 2 2 4-4"></path>
                        </svg>
                    </div>
                    <p>
                        Paiements sécurisés avec les derniers protocoles de chiffrement,
                        conçus pour respecter les normes les plus élevées de l'industrie.
                    </p>
                </div>

                <div class="payment-methods" aria-label="Moyens de paiement">
                    <button type="button" class="payment-method is-selected" data-method="carte"
                            aria-expanded="true" aria-controls="payment-panel-carte" aria-label="Carte bancaire">
                        <svg viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="1.5" y="1.5" width="29" height="19" rx="3" stroke="currentColor" stroke-width="2"></rect>
                            <rect x="1.5" y="7.5" width="29" height="4" fill="currentColor" opacity="0.18"></rect>
                            <path d="M6 15h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                        </svg>
                        <span>Carte bancaire</span>
                    </button>

                    <button type="button" class="payment-method" data-method="electronique"
                            aria-expanded="false" aria-controls="payment-panel-electronique" aria-label="Paiement électronique">
                        <svg viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="10" cy="11" r="6" stroke="currentColor" stroke-width="2"></circle>
                            <path d="M20 6c2.5 2.5 2.5 7.5 0 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                            <path d="M24 3c4.5 4.5 4.5 11.5 0 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                        </svg>
                        <span>Paiement électronique</span>
                    </button>
                </div>

                <div class="payment-panel is-open" id="payment-panel-carte" data-panel="carte">
                    <div class="field field--full">
                        <label for="don-carte-nom">Nom du titulaire *</label>
                        <input id="don-carte-nom" type="text" name="carte_nom" autocomplete="cc-name" placeholder="Comme inscrit sur la carte">
                    </div>

                    <div class="field field--full">
                        <label for="don-carte-numero">Numéro de carte *</label>
                        <div class="input-wrap input-wrap--icon">
                            <span class="field-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                    <path d="M2 10h20"></path>
                                </svg>
                            </span>
                            <input id="don-carte-numero" type="text" inputmode="numeric" autocomplete="cc-number"
                                   maxlength="19" placeholder="0000 0000 0000 0000" name="carte_numero">
                        </div>
                    </div>

                    <div class="field-grid">
                        <div class="field">
                            <label for="don-carte-expiration">Expiration (MM/AA) *</label>
                            <input id="don-carte-expiration" type="text" inputmode="numeric" autocomplete="cc-exp"
                                   maxlength="5" placeholder="MM/AA" name="carte_expiration">
                        </div>
                        <div class="field">
                            <label for="don-carte-cvv">CVV *</label>
                            <input id="don-carte-cvv" type="text" inputmode="numeric" autocomplete="cc-csc"
                                   maxlength="4" placeholder="123" name="carte_cvv">
                        </div>
                    </div>
                </div>

                <div class="payment-panel" id="payment-panel-electronique" data-panel="electronique">
                    <div class="field field--full">
                        <label for="don-mobile-operateur">Opérateur *</label>
                        <select id="don-mobile-operateur" name="mobile_operateur" disabled>
                            <option value="">— Choisir un opérateur —</option>
                            <option value="mtn">MTN Mobile Money</option>
                            <option value="orange">Orange Money</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <div class="field field--full">
                        <label for="don-mobile-numero">Numéro Mobile Money *</label>
                        <input id="don-mobile-numero" type="tel" name="mobile_numero" placeholder="6 00 00 00 00" disabled>
                    </div>
                </div>

                <p class="payment-note">
                    Vous serez redirigé(e) vers notre plateforme de paiement sécurisée
                    pour finaliser votre don en toute confidentialité.
                </p>

                <div class="don-submit-row">
                    <button type="submit" class="btn-submit">Valider</button>
                    <span class="submit-plus" aria-hidden="true">＋</span>
                </div>
            </div>
        </article>
    </section>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>

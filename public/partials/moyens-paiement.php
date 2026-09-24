<?php
/**
 * Liste des moyens de paiement configurés.
 * Attend $moyensPaiement (voir paiement_moyens() dans src/paiement.php) et,
 * optionnellement, $moyensVideMessage (texte affiché si rien n'est configuré).
 */
$moyensVideMessage = $moyensVideMessage ?? "Nos moyens de paiement en ligne sont en cours d'ouverture : notre équipe vous indiquera comment régler.";
?>
<?php if (empty($moyensPaiement)): ?>
    <p class="paiement-vide"><?= htmlspecialchars($moyensVideMessage) ?></p>
<?php else: ?>
    <ul class="paiement-liste">
        <?php foreach ($moyensPaiement as $m): ?>
            <li>
                <strong><?= htmlspecialchars($m['titre']) ?></strong>
                <?php if ($m['url'] !== ''): ?>
                    <a href="<?= htmlspecialchars($m['url']) ?>" target="_blank" rel="noopener noreferrer">Payer en ligne ↗</a>
                <?php endif; ?>
                <?php foreach ($m['lignes'] as $label => $valeur): ?>
                    <span><?= htmlspecialchars((string) $label) ?> : <b><?= htmlspecialchars($valeur) ?></b></span>
                <?php endforeach; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

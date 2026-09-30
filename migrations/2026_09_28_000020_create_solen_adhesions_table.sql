-- Demandes d'adhésion à la communauté d'entraide « Solen » (page /solen.php).
-- Données sensibles : téléphone de la personne ET d'un(e) proche de confiance, date de
-- naissance. Aucune adresse IP n'est enregistrée. À ne jamais exposer publiquement.
CREATE TABLE IF NOT EXISTS solen_adhesions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    pseudo VARCHAR(100) DEFAULT NULL,
    email VARCHAR(190) NOT NULL,
    telephone VARCHAR(30) NOT NULL,
    telephone_proche VARCHAR(30) NOT NULL,
    date_naissance DATE NOT NULL,
    profession VARCHAR(150) DEFAULT NULL,
    ville VARCHAR(150) NOT NULL,
    situation ENUM('soutien', 'engagement', 'soutien_engagement') NOT NULL,
    accepte_confidentialite TINYINT(1) NOT NULL DEFAULT 0,
    accepte_charte TINYINT(1) NOT NULL DEFAULT 0,
    accepte_whatsapp TINYINT(1) NOT NULL DEFAULT 0,
    accepte_newsletter TINYINT(1) NOT NULL DEFAULT 0,
    statut ENUM('nouveau', 'contacte', 'integre', 'clos') NOT NULL DEFAULT 'nouveau',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_statut (statut),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dons et adhésions financières déclarés sur /don.php.
-- Aucun paiement n'est traité par le site : le statut est mis à jour à la main par l'équipe
-- (admin/dons.php) une fois le règlement reçu via les moyens de config/paiement.php.
CREATE TABLE IF NOT EXISTS dons (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(20) NOT NULL UNIQUE,
    mode ENUM('unique', 'mensuel') NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    moyen ENUM('carte', 'mobile', 'paypal', 'virement') NOT NULL,
    mobile_operateur VARCHAR(20) DEFAULT NULL,
    mobile_numero VARCHAR(30) DEFAULT NULL,
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL,
    telephone VARCHAR(40) NOT NULL,
    organisation TINYINT(1) NOT NULL DEFAULT 0,
    adresse VARCHAR(500) DEFAULT NULL,
    statut ENUM('en_attente', 'recu', 'annule') NOT NULL DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_statut (statut),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

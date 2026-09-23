-- Signalements en ligne d'une situation de violence (page /signalement.php).
-- Données sensibles : aucune adresse IP n'est enregistrée, tous les champs de contact sont facultatifs.
CREATE TABLE IF NOT EXISTS signalements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(20) NOT NULL UNIQUE,
    pour_qui ENUM('moi', 'proche', 'temoin', 'professionnel') NOT NULL,
    danger_immediat TINYINT(1) NOT NULL DEFAULT 0,
    mineur ENUM('oui', 'non', 'inconnu') NOT NULL DEFAULT 'inconnu',
    types SET('physique', 'sexuelle', 'psychologique', 'economique', 'numerique', 'harcelement', 'mariage_force', 'autre') NOT NULL,
    description TEXT NOT NULL,
    lieu VARCHAR(150) DEFAULT NULL,
    date_faits VARCHAR(100) DEFAULT NULL,
    contact_nom VARCHAR(150) DEFAULT NULL,
    contact_moyen VARCHAR(255) DEFAULT NULL,
    contact_canal ENUM('telephone', 'whatsapp', 'sms', 'email') DEFAULT NULL,
    contact_sur TINYINT(1) NOT NULL DEFAULT 0,
    statut ENUM('nouveau', 'en_cours', 'traite') NOT NULL DEFAULT 'nouveau',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_statut (statut),
    INDEX idx_danger (danger_immediat),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS apprentissages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    type ENUM('ressource', 'webinaire', 'cours', 'pairs', 'opportunite') NOT NULL DEFAULT 'ressource',
    langue ENUM('fr', 'en') NOT NULL DEFAULT 'fr',
    source VARCHAR(150) DEFAULT NULL,
    url VARCHAR(500) NOT NULL,
    date_formation DATE DEFAULT NULL,
    statut ENUM('brouillon', 'publie') NOT NULL DEFAULT 'brouillon',
    publie_le DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_statut (statut),
    INDEX idx_type (type),
    INDEX idx_publie_le (publie_le)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

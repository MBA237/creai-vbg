CREATE TABLE IF NOT EXISTS articles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    extrait TEXT NOT NULL,
    contenu LONGTEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    auteur_id INT UNSIGNED DEFAULT NULL,
    categorie VARCHAR(80) DEFAULT NULL,
    statut ENUM('brouillon','publie') NOT NULL DEFAULT 'brouillon',
    publie_le DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_statut (statut),
    INDEX idx_publie_le (publie_le),
    INDEX idx_slug (slug),
    FOREIGN KEY (auteur_id) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
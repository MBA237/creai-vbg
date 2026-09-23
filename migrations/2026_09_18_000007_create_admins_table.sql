CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    role ENUM('super_admin','admin','editeur') NOT NULL DEFAULT 'admin',
    actif TINYINT(1) NOT NULL DEFAULT 1,
    derniere_connexion DATETIME DEFAULT NULL,
    tentatives_echecs INT UNSIGNED NOT NULL DEFAULT 0,
    bloque_jusqua DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
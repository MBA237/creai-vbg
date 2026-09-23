CREATE TABLE IF NOT EXISTS benevoles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(30) NOT NULL,
    date_naissance DATE NOT NULL,
    ville VARCHAR(100) NOT NULL,
    connu_par VARCHAR(255) NULL,
    motivations TEXT NULL,
    experience TEXT NULL,
    canal ENUM('whatsapp', 'mail', 'les_deux') NOT NULL DEFAULT 'mail',
    notes TEXT NULL,
    lu TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_lu (lu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

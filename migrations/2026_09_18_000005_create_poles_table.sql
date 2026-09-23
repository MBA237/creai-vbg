CREATE TABLE IF NOT EXISTS poles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(50) NOT NULL UNIQUE,
    nom VARCHAR(100) NOT NULL,
    ordre INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ordre (ordre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO poles (slug, nom, ordre) VALUES
('bureau', 'Le Bureau', 1),
('direction', 'La direction', 2),
('developpement', 'Le développement', 3),
('lab-prevention', 'Le Lab-Prévention', 4),
('accompagnement', 'Accompagnement', 5),
('research-lab', 'Research-Lab', 6),
('innovation-numerique', 'Innovation numérique', 7);
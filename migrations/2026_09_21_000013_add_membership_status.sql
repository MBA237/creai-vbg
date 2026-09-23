ALTER TABLE memberships
    ADD COLUMN statut ENUM('en_attente', 'agree', 'refuse') NOT NULL DEFAULT 'en_attente' AFTER message,
    ADD COLUMN traite_at TIMESTAMP NULL DEFAULT NULL AFTER statut,
    ADD INDEX idx_statut (statut);

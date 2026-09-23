ALTER TABLE articles
    ADD COLUMN auteur VARCHAR(150) DEFAULT NULL AFTER image;

UPDATE articles a
LEFT JOIN admins ad ON ad.id = a.auteur_id
SET a.auteur = ad.nom
WHERE a.auteur IS NULL AND a.auteur_id IS NOT NULL;
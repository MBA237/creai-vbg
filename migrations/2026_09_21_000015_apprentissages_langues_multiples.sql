-- Une formation peut être disponible en français, en anglais, ou dans les deux langues.
ALTER TABLE apprentissages
    MODIFY langue SET('fr', 'en') NOT NULL DEFAULT 'fr';

-- Un compte créé (ou réinitialisé) par un autre administrateur doit choisir son propre
-- mot de passe à la première connexion : l'administrateur qui l'a créé ne le garde pas.
ALTER TABLE admins
    ADD COLUMN doit_changer_mdp TINYINT(1) NOT NULL DEFAULT 0 AFTER actif;

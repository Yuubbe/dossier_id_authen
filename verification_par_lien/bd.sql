-- Création de la base de données (si ce n'est pas déjà fait)
CREATE DATABASE IF NOT EXISTS inscription;

-- Sélection de la base de données
USE inscription;

-- Création de la table 'utilisateurs' pour stocker les informations d'inscription
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,               -- Identifiant unique de l'utilisateur
    nom VARCHAR(255) NOT NULL,                        -- Nom de l'utilisateur
    email VARCHAR(255) NOT NULL UNIQUE,               -- Email unique de l'utilisateur
    motdepasse VARCHAR(255) NOT NULL,                 -- Mot de passe hashé de l'utilisateur
    verification_code VARCHAR(255) NOT NULL,          -- Code de vérification envoyé par email
    verified TINYINT(1) DEFAULT 0,                    -- 0 = non vérifié, 1 = vérifié
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Date et heure d'inscription
);

-- Index sur l'email pour garantir qu'il est unique
CREATE UNIQUE INDEX idx_email ON utilisateurs(email);

-- 
-- Fichier de création de la base de données et des tables pour le projet SRNails
-- Ce fichier contient les instructions nécessaires pour créer la base de données 
-- et les tables requises pour l'application SR Nails. Il initialise également
-- des valeurs par défaut pour les utilisateurs et les articles.
--
-- @package    SRNails
-- @subpackage Database
-- @version    1.0
--

-- Création de la base de données (si elle n'existe pas déjà)
CREATE DATABASE IF NOT EXISTS srnails_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE srnails_db;

-- Création de la table users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,   -- Nom d'utilisateur unique
    email VARCHAR(100) NOT NULL UNIQUE,      -- Adresse e-mail unique
    password VARCHAR(255) NOT NULL,          -- Mot de passe haché
    is_admin TINYINT(1) NOT NULL DEFAULT 0,  -- Indicateur si l'utilisateur est admin (0 = non, 1 = oui)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Date et heure de la création du compte
);

-- Ajout d'un utilisateur admin par défaut (mot de passe : "admin123")
-- Le mot de passe est stocké sous forme de hash (bcrypt)
INSERT INTO users (username, email, password, is_admin) 
VALUES ('admin', 'admin@example.com', '$2y$10$mx/BOsaodurgmHo5Sc1/rePrXkfk.Hu.6b/KyP/RtIbkFdZDWa196', 1)
ON DUPLICATE KEY UPDATE username = username;

-- Création de la table articles
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,              -- Titre de l'article
    description TEXT NOT NULL,                -- Description de l'article
    price DECIMAL(10,2) NOT NULL,             -- Prix de l'article
    img VARCHAR(255) DEFAULT NULL,            -- Chemin vers l'image de l'article
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Date et heure de création de l'article
);

-- Ajout de quelques articles par défaut
INSERT INTO articles (title, description, price, img) VALUES
('Kit Ongles Press-On', 'Kit de faux ongles de haute qualité.', 15.99, 'images/kit-ongles.jpg'),
('Vernis Semi-Permanent', 'Vernis longue tenue pour ongles.', 9.99, 'images/vernis-semi.jpg'),
('Lime à Ongles Professionnelle', 'Lime à ongles pour une finition parfaite.', 3.50, 'images/lime-ongles.jpg')
ON DUPLICATE KEY UPDATE title = title;

-- Création de la base de données (si elle n'existe pas déjà)
CREATE DATABASE IF NOT EXISTS srnails_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE srnails_db;

-- Création de la table users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ajout d'un utilisateur admin par défaut (mot de passe : "admin123")
INSERT INTO users (username, email, password, is_admin) 
VALUES ('admin', 'admin@example.com', '$2y$10$eW5sQ0JsP7p7U.p3BtcJ.Oe/PnIhLE0/XI.AHiYotcKfGfJjB5.zK', 1)
ON DUPLICATE KEY UPDATE username = username;

CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    img VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ajout de quelques articles par défaut
INSERT INTO articles (title, description, price, img) VALUES
('Kit Ongles Press-On', 'Kit de faux ongles de haute qualité.', 15.99, 'images/kit-ongles.jpg'),
('Vernis Semi-Permanent', 'Vernis longue tenue pour ongles.', 9.99, 'images/vernis-semi.jpg'),
('Lime à Ongles Professionnelle', 'Lime à ongles pour une finition parfaite.', 3.50, 'images/lime-ongles.jpg')
ON DUPLICATE KEY UPDATE title = title;
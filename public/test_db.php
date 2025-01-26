<?php
require_once __DIR__ . '/../core/Database.php';

use Core\Database;

try {
    $db = Database::getConnection();
    echo "Connexion à la base de données réussie !";
} catch (PDOException $e) {
    echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
}

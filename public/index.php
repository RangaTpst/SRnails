<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

spl_autoload_register(function ($class) {
    $file = __DIR__ . '../src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        echo "Erreur : Le fichier de la classe $class n'a pas été trouvé. Chemin recherché : $file";
    }
});

session_start();

use App\Database\Database;
use App\Models\User;

// Test de connexion avec Database
try {
    $db = new Database();
    $pdo = $db->getConnection();
    echo "Connexion réussie à la base de données.<br>";

    $user = new User();
    $allUsers = $user->getAllUsers();
    echo "<pre>";
    print_r($allUsers);
    echo "</pre>";
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}

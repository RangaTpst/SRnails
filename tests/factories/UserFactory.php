<?php
namespace Tests\Factories;

use Core\Database;

/**
 * Classe UserFactory
 * 
 * Cette classe permet de créer des utilisateurs factices dans la base de données pour les tests.
 * Elle garantit que les utilisateurs ont un nom d'utilisateur unique avant de les insérer.
 * 
 * @package Tests\Factories
 */
class UserFactory {
    /**
     * Crée un utilisateur avec des données par défaut ou personnalisées.
     *
     * Génère un utilisateur avec un nom d'utilisateur unique, un email généré aléatoirement,
     * un mot de passe par défaut (password123) et un statut admin (0 par défaut).
     * Les données personnalisées peuvent être fournies dans le tableau $data pour remplacer
     * les valeurs par défaut.
     * 
     * @param array $data Données personnalisées pour l'utilisateur (facultatif).
     * 
     * @return int L'ID de l'utilisateur créé.
     */
    public static function create(array $data = []) {
        $db = Database::getConnection();

        // Génère un nom d'utilisateur unique
        do {
            $username = 'user' . rand(1000, 9999); // Génère un username unique
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $count = $stmt->fetchColumn();
        } while ($count > 0); // ✅ Continue tant que le username existe déjà

        // Valeurs par défaut
        $defaults = [
            'username' => $username,
            'email' => 'user' . rand(1000, 9999) . '@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT), // Utilisation de password_hash pour la sécurité
            'is_admin' => 0 // L'utilisateur est un utilisateur standard par défaut
        ];

        // Fusionner les données par défaut avec celles passées en argument
        $userData = array_merge($defaults, $data);

        // Insérer l'utilisateur dans la base de données
        $stmt = $db->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userData['username'], $userData['email'], $userData['password'], $userData['is_admin']]);

        // Retourne l'ID de l'utilisateur inséré
        return (int) $db->lastInsertId(); // ✅ Retourne toujours un entier
    }
}

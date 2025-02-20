<?php
namespace Tests\Factories;

use Core\Database;

class UserFactory {
    public static function create(array $data = []) {
        $db = Database::getConnection();

        // Valeurs par défaut
        do {
            $username = 'user' . rand(1000, 9999); // Génère un username unique
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $count = $stmt->fetchColumn();
        } while ($count > 0); // ✅ Continue tant que le username existe déjà

        $defaults = [
            'username' => $username,
            'email' => 'user' . rand(1000, 9999) . '@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'is_admin' => 0
        ];

        $userData = array_merge($defaults, $data);

        // Insérer l'utilisateur en base
        $stmt = $db->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userData['username'], $userData['email'], $userData['password'], $userData['is_admin']]);

        return (int) $db->lastInsertId(); // ✅ Retourne toujours un entier
    }
}

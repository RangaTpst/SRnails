<?php
namespace App\Models;

use Core\Database;

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Trouver un utilisateur par son nom d'utilisateur
     */
    public function findUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
}

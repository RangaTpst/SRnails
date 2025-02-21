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

    /**
     * Trouver un utilisateur par son ID
     */
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT id, username, email, is_admin FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Vérifier si un utilisateur est admin
     */
    public function isAdmin($id) {
        $user = $this->getUserById($id);
        return $user && $user['is_admin'] == 1;
    }

    /**
     * Récupérer tous les utilisateurs (admin uniquement)
     */
    public function getAllUsers() {
        $stmt = $this->db->query("SELECT id, username, email, is_admin FROM users");
        return $stmt->fetchAll();
    }
}

<?php
namespace App\Models;

use Core\Database;

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // ✅ Trouver un utilisateur par son nom d'utilisateur
    public function findUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    // ✅ Récupérer un utilisateur par son ID
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT id, username, email, is_admin FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ✅ Vérifier si un utilisateur est administrateur
    public function isAdmin($id) {
        $user = $this->getUserById($id);
        return $user && $user['is_admin'] == 1;
    }

    // ✅ Récupérer tous les utilisateurs
    public function getAllUsers() {
        $stmt = $this->db->query("SELECT id, username, email, is_admin FROM users");
        return $stmt->fetchAll();
    }

    // ✅ Créer un nouvel utilisateur
    public function createUser($username, $email, $password, $is_admin = 0) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $email, $hashedPassword, $is_admin]);
    }

    // ✅ Mettre à jour les informations d'un utilisateur
    public function updateUser($id, $username, $email) {
        $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        return $stmt->execute([$username, $email, $id]);
    }

    // ✅ Mettre à jour le mot de passe d'un utilisateur
    public function updatePassword($id, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }

    // ✅ Supprimer un utilisateur par son ID
    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ✅ Vérifier si un email est déjà utilisé
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }
}

<?php
namespace App\Models;

use core\Database;

/**
 * Class UserModel
 * Modèle pour la gestion des utilisateurs dans la base de données.
 */
class UserModel {
    /**
     * @var \PDO Connexion à la base de données.
     */
    private $db;

    /**
     * UserModel constructor.
     * Initialise la connexion à la base de données en appelant la méthode statique de la classe Database.
     */
    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Trouver un utilisateur par son nom d'utilisateur.
     * 
     * @param string $username Le nom d'utilisateur à rechercher.
     * @return array|false L'utilisateur trouvé ou false si non trouvé.
     */
    public function findUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    /**
     * Récupérer un utilisateur par son ID.
     * 
     * @param int $id L'ID de l'utilisateur à récupérer.
     * @return array|false Les données de l'utilisateur ou false si non trouvé.
     */
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT id, username, email, is_admin FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Vérifier si un utilisateur est administrateur.
     * 
     * @param int $id L'ID de l'utilisateur.
     * @return bool Retourne true si l'utilisateur est administrateur, false sinon.
     */
    public function isAdmin($id) {
        $user = $this->getUserById($id);
        return $user && $user['is_admin'] == 1;
    }

    /**
     * Récupérer tous les utilisateurs.
     * 
     * @return array|false Liste des utilisateurs ou false si aucun utilisateur trouvé.
     */
    public function getAllUsers() {
        $stmt = $this->db->query("SELECT id, username, email, is_admin FROM users");
        return $stmt->fetchAll();
    }

    /**
     * Créer un nouvel utilisateur.
     * 
     * @param string $username Nom d'utilisateur.
     * @param string $email Email de l'utilisateur.
     * @param string $password Mot de passe de l'utilisateur.
     * @param int $is_admin Indique si l'utilisateur est administrateur (0 ou 1).
     * @return bool Retourne true si l'utilisateur a été créé, false sinon.
     */
    public function createUser($username, $email, $password, $is_admin = 0) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $email, $hashedPassword, $is_admin]);
    }

    /**
     * Mettre à jour les informations d'un utilisateur.
     * 
     * @param int $id L'ID de l'utilisateur à mettre à jour.
     * @param string $username Nouveau nom d'utilisateur.
     * @param string $email Nouveau email de l'utilisateur.
     * @return bool Retourne true si l'utilisateur a été mis à jour, false sinon.
     */
    public function updateUser($id, $username, $email) {
        $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        return $stmt->execute([$username, $email, $id]);
    }

    /**
     * Mettre à jour le mot de passe d'un utilisateur.
     * 
     * @param int $id L'ID de l'utilisateur.
     * @param string $password Nouveau mot de passe.
     * @return bool Retourne true si le mot de passe a été mis à jour, false sinon.
     */
    public function updatePassword($id, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }

    /**
     * Supprimer un utilisateur par son ID.
     * 
     * @param int $id L'ID de l'utilisateur à supprimer.
     * @return bool Retourne true si l'utilisateur a été supprimé, false sinon.
     */
    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Vérifier si un email est déjà utilisé.
     * 
     * @param string $email L'email à vérifier.
     * @return bool Retourne true si l'email est déjà utilisé, false sinon.
     */
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }

        /**
     * Valide les données utilisateur.
     *
     * @param array $data Données utilisateur (username, email, password, etc.)
     * @param bool $isUpdate Si true, on ne valide pas le mot de passe
     * @return array Liste des erreurs (vide si aucune erreur)
     */
    public function validate(array $data, bool $isUpdate = false): array {
        $errors = [];

        // Nom d'utilisateur
        if (empty($data['username'])) {
            $errors[] = "Le nom d'utilisateur est requis.";
        }

        // Email
        if (empty($data['email'])) {
            $errors[] = "L'email est requis.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email n'est pas valide.";
        }

        // Mot de passe
        if (!$isUpdate) {
            if (empty($data['password'])) {
                $errors[] = "Le mot de passe est requis.";
            }

            if (isset($data['password_confirm']) && $data['password'] !== $data['password_confirm']) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }
        }

        return $errors;
    }

}

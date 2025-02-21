<?php
namespace App\Controllers;

use Core\Database;

class UserController extends BaseController {
    
    // ✅ Afficher le formulaire de connexion
    public function loginForm() {
        $this->render('user/login', ['title' => 'Connexion']);
    }

    // ✅ Connexion de l'utilisateur
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return "Méthode non autorisée.";
        }
    
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
    
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
    
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            return "Nom d'utilisateur ou mot de passe incorrect.";
        }
    
        unset($_SESSION['login_attempts']);
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = (int) $user['is_admin'];
        echo"flag 2";
        // ✅ En mode PHPUnit, on retourne "Login success" au lieu de rediriger
        if (defined('PHPUNIT_RUNNING')) {
            echo"login success";
            return "Login success";
        
        }
    
        header('Location: ' . ($_SESSION['is_admin'] ? '/SRnails/public/admin/dashboard' : '/SRnails/public'));
        exit; // <-- Supprime ça en mode test
    }
    

    // ✅ Déconnexion de l'utilisateur
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /SRnails/public/user/login');
        exit;
    }

    // ✅ Affichage de tous les utilisateurs (Admin uniquement)
    public function index() {
        $this->checkAdminAccess();

        $db = Database::getConnection();
        $stmt = $db->query("SELECT id, username, email, is_admin FROM users");
        $users = $stmt->fetchAll();

        $this->render('user/index', ['title' => 'Liste des utilisateurs', 'users' => $users]);
    }

    // ✅ Création d'un nouvel utilisateur
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return "Méthode non autorisée.";
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
        $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt->execute([$_POST['username'], $_POST['email'], $hashedPassword, $_POST['is_admin'] ?? 0]);

        header('Location: /SRnails/public/user/index');
        exit;
    }

    // ✅ Récupérer un utilisateur par son ID
    public function show($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, username, email, is_admin FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!$user) {
            return "Utilisateur introuvable.";
        }

        $this->render('user/show', ['title' => "Profil de {$user['username']}", 'user' => $user]);
    }

    // ✅ Mise à jour d'un utilisateur
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return "Méthode non autorisée.";
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE users SET username = ?, email = ?, is_admin = ? WHERE id = ?");
        $stmt->execute([$_POST['username'], $_POST['email'], $_POST['is_admin'] ?? 0, $id]);

        header('Location: /SRnails/public/user/index');
        exit;
    }

    // ✅ Suppression d'un utilisateur
    public function delete($id) {
        $this->checkAdminAccess();

        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);

        header('Location: /SRnails/public/user/index');
        exit;
    }

    // ✅ Récupération de l'utilisateur connecté
    public static function getLoggedInUser() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, username, email, is_admin FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }

    // ✅ Vérification de l'accès Admin
    private function checkAdminAccess() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
            exit("Accès refusé.");
        }
    }
}

<?php 
namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

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

        // 🔹 Vérifier si l'utilisateur existe
        $user = $this->userModel->findUserByUsername($username);

        if (!$user) {
            return "Nom d'utilisateur incorrect.";
        }

        // 🔹 Vérification du mot de passe
        if (!password_verify($password, $user['password'])) {
            return "Mot de passe incorrect.";
        }

        // 🔹 Sécurisation de la session
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = (int) $user['is_admin'];

        // ✅ En mode PHPUnit, on retourne "Login success" au lieu de rediriger
        if (defined('PHPUNIT_RUNNING')) {
            return "Login success";
        }

        header('Location: ' . ($_SESSION['is_admin'] ? '/SRnails/public/admin/dashboard' : '/SRnails/public'));
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

        $userModel = new UserModel();
        return $userModel->getUserById($_SESSION['user_id']);
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
}

<?php
namespace App\Controllers;

use App\Models\UserModel;
use Core\Database;

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

        $user = $this->userModel->findUserByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            return "Nom d'utilisateur ou mot de passe incorrect.";
        }

        // 🔹 Sécurisation de la session
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = (int) $user['is_admin'];

        if (defined('PHPUNIT_RUNNING')) {
            return "Login success";
        }

        header('Location: ' . ($_SESSION['is_admin'] ? '/SRnails/public/admin/dashboard' : '/SRnails/public'));
        exit;
    }

    // ✅ Formulaire d'inscription
    public function registerForm() {
        $this->render('user/register', ['title' => 'Inscription']);
    }

    // ✅ Processus d'inscription
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return "Méthode non autorisée.";
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($email) || empty($password)) {
            return "Tous les champs doivent être remplis.";
        }

        // 🔹 Vérifier si l'utilisateur existe déjà
        if ($this->userModel->findUserByUsername($username)) {
            return "Ce nom d'utilisateur est déjà pris.";
        }

        // 🔹 Création de l'utilisateur
        if (!$this->userModel->createUser($username, $email, $password)) {
            return "Erreur lors de la création du compte.";
        }

        header('Location: /SRnails/public/user/login');
        exit;
    }

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

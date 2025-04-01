<?php
namespace App\Controllers;

use App\Models\UserModel;

/**
 * Class UserController
 * Gère les actions liées à l'utilisateur, telles que l'inscription, la connexion, et la gestion du profil utilisateur.
 */
class UserController extends BaseController {
    private $userModel;

    /**
     * Constructeur pour initialiser le modèle utilisateur.
     */
    public function __construct() {
        $this->userModel = new UserModel();
    }

    /**
     * Affiche le formulaire d'inscription.
     */
    public function registerForm() {
        $this->render('user/register', ['title' => 'Inscription']);
    }

    /**
     * Traite l'inscription de l'utilisateur.
     * Effectue des vérifications côté serveur avant de créer un utilisateur.
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return "Méthode non autorisée.";
        }

        // Récupération des données et vérifications
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        
        $errors = $this->userModel->validate([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'password_confirm' => $passwordConfirm
        ]);
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: /SRnails/public/user/register');
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "L'email n'est pas valide.";
            header('Location: /SRnails/public/user/register');
            exit;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
            header('Location: /SRnails/public/user/register');
            exit;
        }


        // Création de l'utilisateur
        if (!$this->userModel->createUser($username, $email, $password)) {
            $_SESSION['error'] = "Erreur lors de la création du compte.";
            header('Location: /SRnails/public/user/register');
            exit;
        }

        $_SESSION['success'] = "Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.";
        header('Location: /SRnails/public/user/login');
        exit;


exit;

    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function loginForm() {
        $this->render('user/login', ['title' => 'Connexion']);
    }

    /**
     * Connexion de l'utilisateur, vérifie les informations de connexion.
     */
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
            $_SESSION['login_error'] = "Nom d'utilisateur ou mot de passe incorrect.";
            header('Location: /SRnails/public/user/login');
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = (int) $user['is_admin'];

        header('Location: ' . ($_SESSION['is_admin'] ? '/SRnails/public/admin/dashboard' : '/SRnails/public/user/dashboard'));
        exit;
    }

    /**
     * Affiche le dashboard de l'utilisateur.
     */
    public function dashboard() {
        $user = self::getLoggedInUser();
        if (!$user) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        $this->render('user/dashboard', ['user' => $user, 'title' => 'Dashboard utilisateur']);
    }

    /**
     * Affiche le formulaire de mise à jour des informations de l'utilisateur.
     */
    public function updateForm() {
        $user = self::getLoggedInUser();
        if (!$user) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        $this->render('user/update', ['user' => $user, 'title' => 'Modifier mes informations']);
    }

    /**
     * Met à jour les informations de l'utilisateur (nom, email).
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return "Méthode non autorisée.";
        }

        $user = self::getLoggedInUser();
        if (!$user) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');

        $errors = $this->userModel->validate([
            'username' => $username,
            'email' => $email
        ], true);
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: /SRnails/public/user/update');
            exit;
        }
        

        if (!$this->userModel->updateUser($user['id'], $username, $email)) {
            $_SESSION['error'] = "Erreur lors de la mise à jour.";
            header('Location: /SRnails/public/user/update');
            exit;
        }

        header('Location: /SRnails/public/user/dashboard');
        exit;
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /SRnails/public/user/login');
        exit;
    }

    /**
     * Récupère l'utilisateur actuellement connecté.
     * 
     * @return array|null L'utilisateur connecté ou null si non connecté.
     */
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
}

<?php
namespace App\Controllers;

use Core\Database;

class UserController extends BaseController {
    // Affiche le formulaire de connexion
    public function loginForm() {
        $this->render('user/login', ['title' => 'Connexion']);
    }

    // Gère la soumission du formulaire de connexion
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($_POST['username']);
            $password = $_POST['password'];

            // Connexion à la base de données
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if (!$user) {
                exit("Nom d'utilisateur ou mot de passe incorrect.");
            }

            // Vérification du mot de passe sécurisé
            if (password_verify($password, $user['password'])) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['is_admin'] = (int) $user['is_admin']; // Conversion en entier

                // Redirection selon le rôle
                if ($_SESSION['is_admin'] === 1) {
                    header('Location: /SRnails/public/admin/dashboard');
                } else {
                    header('Location: /SRnails/public');
                }
                exit;
            } else {
                exit("Nom d'utilisateur ou mot de passe incorrect.");
            }
        } else {
            header('Location: /SRnails/public/user/login');
            exit;
        }
    }

    // Déconnecte l'utilisateur
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        session_destroy();
        header('Location: /SRnails/public/user/login');
        exit;
    }

    // Récupère les informations de l'utilisateur connecté
    public static function getLoggedInUser() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user_id'])) {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetch();
        }
        return null;
    }
}

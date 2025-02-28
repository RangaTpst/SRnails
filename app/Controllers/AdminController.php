<?php
namespace App\Controllers;

use App\Models\UserModel;

class AdminController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // ✅ Vérification et affichage du dashboard admin
    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifier si l'utilisateur est connecté et admin
        if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
            exit("⛔ Accès refusé. Vous n'avez pas les droits pour accéder à cette page.");
        }

        // Récupérer les utilisateurs enregistrés pour les afficher dans le dashboard
        $users = $this->userModel->getAllUsers();

        // Afficher la vue du dashboard avec les données
        $this->render('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'users' => $users
        ]);
    }
}

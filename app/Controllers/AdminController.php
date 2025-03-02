<?php
namespace App\Controllers;

use App\Models\UserModel;

/**
 * Class AdminController
 * Gère les actions administratives, telles que l'affichage du tableau de bord administrateur et la gestion des utilisateurs.
 */
class AdminController extends BaseController {
    private $userModel;

    /**
     * AdminController constructor.
     * Initialise le modèle des utilisateurs.
     */
    public function __construct() {
        $this->userModel = new UserModel();
    }

    /**
     * Affiche le tableau de bord de l'administrateur.
     * Vérifie si l'utilisateur est administrateur et affiche les utilisateurs enregistrés.
     */
    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifier si l'utilisateur est connecté et administrateur
        if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
            exit("⛔ Accès refusé. Vous n'avez pas les droits pour accéder à cette page.");
        }

        // Récupérer les utilisateurs enregistrés
        $users = $this->userModel->getAllUsers();

        // Afficher la vue du dashboard avec les données
        $this->render('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'users' => $users
        ]);
    }
}

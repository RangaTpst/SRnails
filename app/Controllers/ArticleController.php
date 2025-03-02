<?php
namespace App\Controllers;

use App\Models\ArticleModel;
use App\Controllers\BaseController;
use App\Controllers\UserController;

/**
 * Class ArticleController
 * Gère les actions liées aux articles pour l'administrateur, telles que la création, la mise à jour, la suppression, et l'affichage des articles.
 */
class ArticleController extends BaseController {
    private $articleModel;

    /**
     * Constructeur pour initialiser le modèle des articles.
     */
    public function __construct() {
        $this->articleModel = new ArticleModel();
    }

    /**
     * Affiche le tableau de bord de l'administrateur avec les articles et les utilisateurs.
     */
    public function adminDashboard() {
        if (!$this->isAdmin()) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        $userModel = new \App\Models\UserModel();
        $users = $userModel->getAllUsers();
        $articles = $this->articleModel->getAllArticles();

        $this->render('admin/dashboard', ['articles' => $articles, 'users' => $users, 'title' => 'Tableau de bord admin']);
    }

    /**
     * Crée un nouvel article.
     * 
     * @throws Exception Si un champ obligatoire est vide.
     */
    public function create() {
        if (!$this->isAdmin()) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['description'] ?? '');
        $image = trim($_POST['img'] ?? '');
        $price = trim($_POST['price'] ?? '');

        if (empty($title) || empty($content) || empty($price)) {
            echo "Tous les champs doivent être remplis.";
            exit;
        }

        $this->articleModel->createArticle($title, $content, $image, $price);
        header('Location: /SRnails/public/admin/dashboard');
        exit;
    }

    /**
     * Met à jour un article existant.
     * 
     * @param int $id L'ID de l'article à mettre à jour.
     */
    public function update($id) {
        if (!$this->isAdmin()) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        // Récupérer l'article par son ID
        $article = $this->articleModel->getArticleById($id);

        if (!$article) {
            $_SESSION['error'] = "L'article n'existe pas.";
            header('Location: /SRnails/public/admin/dashboard');
            exit;
        }

        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $image = trim($_POST['image'] ?? '');
            $price = trim($_POST['price'] ?? '');

            // Vérifier si les champs obligatoires sont remplis
            if (empty($title) || empty($content) || empty($price)) {
                $_SESSION['error'] = "Tous les champs doivent être remplis.";
                header("Location: /SRnails/public/article/{$id}/update");
                exit;
            }

            // Mettre à jour l'article dans la base de données
            $this->articleModel->updateArticle($id, $title, $content, $image, $price);
            $_SESSION['success'] = "L'article a été mis à jour avec succès.";
            header('Location: /SRnails/public/admin/dashboard');
            exit;
        }

        // Afficher le formulaire de mise à jour avec les données de l'article existant
        $this->render('admin/update_article', [
            'article' => $article,
            'title' => 'Mise à jour de l\'article'
        ]);
    }

    /**
     * Supprime un article.
     * 
     * @param int $id L'ID de l'article à supprimer.
     */
    public function delete($id) {
        if (!$this->isAdmin()) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        $this->articleModel->deleteArticle($id);
        header('Location: /SRnails/public/admin/dashboard');
        exit;
    }

    /**
     * Vérifie si l'utilisateur connecté est un administrateur.
     * 
     * @return bool Retourne true si l'utilisateur est un admin, sinon false.
     */
    private function isAdmin() {
        $user = UserController::getLoggedInUser();
        return $user && isset($user['is_admin']) && $user['is_admin'] == 1;
    }
}

<?php
namespace App\Controllers;

use App\Models\ArticleModel;
use App\Controllers\BaseController;
use App\Controllers\UserController;

class ArticleController extends BaseController {
    private $articleModel;

    public function __construct() {
        $this->articleModel = new ArticleModel();
    }

    // Affichage du tableau de bord admin avec les articles et utilisateurs
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

    // Création d'un nouvel article
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

    // Mise à jour d'un article existant
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

    // Suppression d'un article
    public function delete($id) {
        if (!$this->isAdmin()) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        $this->articleModel->deleteArticle($id);
        header('Location: /SRnails/public/admin/dashboard');
        exit;
    }

    // Vérifier si l'utilisateur est administrateur
    private function isAdmin() {
        $user = UserController::getLoggedInUser();
        return $user && isset($user['is_admin']) && $user['is_admin'] == 1;
    }
}

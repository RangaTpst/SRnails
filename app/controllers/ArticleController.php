<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ArticleModel;
use App\Controllers\UserController;

class ArticleController extends BaseController {
    private $articleModel;

    public function __construct() {
        $this->articleModel = new ArticleModel();
    }

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

    public function create() {
        if (!$this->isAdmin()) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $image = trim($_POST['image'] ?? '');
        $price = trim($_POST['price'] ?? '');

        if (empty($title) || empty($content) || empty($price)) {
            echo "Tous les champs doivent être remplis.";
            exit;
        }

        $this->articleModel->createArticle($title, $content, $image, $price);
        header('Location: /SRnails/public/admin/dashboard');
        exit;
    }

    public function update($id) {
        if (!$this->isAdmin()) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $image = trim($_POST['image'] ?? '');
        $price = trim($_POST['price'] ?? '');

        if (empty($title) || empty($content) || empty($price)) {
            echo "Tous les champs doivent être remplis.";
            exit;
        }

        $this->articleModel->updateArticle($id, $title, $content, $image, $price);
        header('Location: /SRnails/public/admin/dashboard');
        exit;
    }

    public function delete($id) {
        if (!$this->isAdmin()) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        $this->articleModel->deleteArticle($id);
        header('Location: /SRnails/public/admin/dashboard');
        exit;
    }

    private function isAdmin() {
        $user = UserController::getLoggedInUser();
        return $user && isset($user['is_admin']) && $user['is_admin'] == 1;
    }
}

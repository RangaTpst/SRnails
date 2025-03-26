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

    public function adminDashboard() {
        if (!$this->isAdmin()) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        $userModel = new \App\Models\UserModel();
        $users = $userModel->getAllUsers();
        $articles = $this->articleModel->getAllArticles();

        $this->render('admin/dashboard', [
            'articles' => $articles,
            'users' => $users,
            'title' => 'Tableau de bord admin'
        ]);
    }

    public function createForm() {
        if (!$this->isAdmin()) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        $this->render('admin/create_article', [
            'title' => 'Créer un article'
        ]);
    }

    public function create() {
        if (!$this->isAdmin()) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $price = trim($_POST['price'] ?? '');
            $imageName = null;

            if (empty($title) || empty($content) || empty($category) || empty($price)) {
                $_SESSION['error'] = "Tous les champs doivent être remplis.";
                header('Location: /SRnails/public/admin/article/create');
                exit;
            }

            if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
                $uploadDir = __DIR__ . '/../../public/assets/img/articles/';
                $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
            
                if (!file_exists($_FILES['image']['tmp_name'])) {
                    $_SESSION['error'] = "Fichier temporaire manquant.";
                    header('Location: /SRnails/public/admin/article/create');
                    exit;
                }
            
                $mimeType = mime_content_type($_FILES['image']['tmp_name']);
            
                if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimeTypes)) {
                    $_SESSION['error'] = "Format d'image non autorisé.";
                    header('Location: /SRnails/public/admin/article/create');
                    exit;
                }
            
                $imageName = uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $imageName;
            
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $_SESSION['error'] = "Erreur lors de l'upload de l'image.";
                    header('Location: /SRnails/public/admin/article/create');
                    exit;
                }
            } else {
                $_SESSION['error'] = "Aucun fichier image n’a été envoyé.";
                header('Location: /SRnails/public/admin/article/create');
                exit;
            }
            
            

            $success = $this->articleModel->createArticle($title, $content, $category, $imageName, $price);

            $_SESSION[$success ? 'success' : 'error'] = $success
                ? "Article créé avec succès."
                : "Erreur lors de la création de l'article.";

            header('Location: /SRnails/public/admin/dashboard');
            exit;
        }
    }

    public function update($id) {
        if (!$this->isAdmin()) {
            header('Location: /SRnails/public/user/login');
            exit;
        }

        $article = $this->articleModel->getArticleById($id);
        if (!$article) {
            $_SESSION['error'] = "L'article n'existe pas.";
            header('Location: /SRnails/public/admin/dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $price = trim($_POST['price'] ?? '');
            $image = $article['image'];

            if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
                $uploadDir = __DIR__ . '/../../public/assets/img/articles/';
                $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
            
                $mimeType = mime_content_type($_FILES['image']['tmp_name']);
            
                if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimeTypes)) {
                    $_SESSION['error'] = "Format d'image non autorisé.";
                    header("Location: /SRnails/public/article/{$id}/update");
                    exit;
                }
            
                $imageName = uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $imageName;
            
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $_SESSION['error'] = "Erreur lors de l'upload de l'image.";
                    header("Location: /SRnails/public/article/{$id}/update");
                    exit;
                }
            
                // supprimer l’ancienne image
                if (!empty($article['image'])) {
                    $oldImagePath = $uploadDir . $article['image'];
                    if (file_exists($oldImagePath)) unlink($oldImagePath);
                }
            
                $image = $imageName; // Remplace l'image seulement si l’upload est ok
            }
            
            
            

            if (empty($title) || empty($content) || empty($category) || empty($price)) {
                $_SESSION['error'] = "Tous les champs doivent être remplis.";
                header("Location: /SRnails/public/article/{$id}/update");
                exit;
            }

            $this->articleModel->updateArticle($id, $title, $content, $category, $image, $price);
            $_SESSION['success'] = "L'article a été mis à jour avec succès.";
            header('Location: /SRnails/public/admin/dashboard');
            exit;
        }

        $this->render('admin/update_article', [
            'article' => $article,
            'title' => 'Mise à jour de l\'article'
        ]);
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

    public function showList() {
        $search = trim($_GET['search'] ?? '');
        $priceFilters = $_GET['price'] ?? [];
        $order = $_GET['order'] ?? '';
        $categoryFilters = $_GET['category'] ?? [];


        $articles = $this->articleModel->getFilteredArticles($search, $priceFilters, $order, $categoryFilters);

        $this->render('article/list', [
            'articles' => $articles,
            'title' => 'Nos articles'
        ]);
    }

    public function show($id) {
        $article = $this->articleModel->getArticleById($id);

        if (!$article) {
            http_response_code(404);
            echo "Article non trouvé.";
            exit;
        }

        $this->render('article/show', [
            'article' => $article,
            'title' => $article['title']
        ]);
    }

    private function isAdmin() {
        $user = UserController::getLoggedInUser();
        return $user && isset($user['is_admin']) && $user['is_admin'] == 1;
    }
}

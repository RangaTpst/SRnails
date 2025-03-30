<?php
namespace Core;

/**
 * Classe Router
 *
 * Gère les requêtes entrantes et redirige vers le bon contrôleur/méthode.
 *
 * @package Core
 */
class Router {
    /**
     * Gère la requête en analysant l'URL et en appelant le contrôleur et la méthode appropriés.
     *
     * @return void
     */
    public function handleRequest() {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        // ✅ Route vers la page d'accueil
        if ($uri === '' || $uri === 'SRnails/public') {
            $controller = new \App\Controllers\HomeController();
            $controller->index();

        // ✅ Routes Utilisateur : Connexion, Déconnexion, Inscription, Dashboard
        } elseif ($uri === 'SRnails/public/user/login') {
            (new \App\Controllers\UserController())->loginForm();

        } elseif ($uri === 'SRnails/public/user/login/process') {
            (new \App\Controllers\UserController())->login();

        } elseif ($uri === 'SRnails/public/user/logout') {
            (new \App\Controllers\UserController())->logout();

        } elseif ($uri === 'SRnails/public/user/register') {
            (new \App\Controllers\UserController())->registerForm();

        } elseif ($uri === 'SRnails/public/user/register/process') {
            (new \App\Controllers\UserController())->register();

        } elseif ($uri === 'SRnails/public/user/update') {
            $controller = new \App\Controllers\UserController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->update();
            } else {
                $controller->updateForm();
            }

        } elseif ($uri === 'SRnails/public/user/dashboard') {
            (new \App\Controllers\UserController())->dashboard();

        // ✅ Dashboard Admin
        } elseif ($uri === 'SRnails/public/admin/dashboard') {
            (new \App\Controllers\ArticleController())->adminDashboard();

        // ✅ Liste publique des articles
        } elseif ($uri === 'SRnails/public/articles') {
            (new \App\Controllers\ArticleController())->showList();

        // ✅ Création d'article
        } elseif ($uri === 'SRnails/public/admin/article/create') {
            $controller = new \App\Controllers\ArticleController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->create();
            } else {
                $controller->createForm();
            }

        // ✅ Mise à jour article
        } elseif (preg_match('#^SRnails/public/article/(\d+)/update$#', $uri, $matches)) {
            (new \App\Controllers\ArticleController())->update($matches[1]);

        // ✅ Suppression article
        } elseif (preg_match('#^SRnails/public/article/(\d+)/delete$#', $uri, $matches)) {
            (new \App\Controllers\ArticleController())->delete($matches[1]);

        // ✅ Page de détail d’un article
        } elseif (preg_match('#^SRnails/public/article/(\d+)$#', $uri, $matches)) {
            (new \App\Controllers\ArticleController())->show($matches[1]);

        // ✅ Newsletter
        //} elseif ($uri === 'SRnails/public/newsletter/subscribe') {
        //    (new \App\Controllers\NewsletterController())->subscribe();

        // ✅ Contact
        //} elseif ($uri === 'SRnails/public/contact') {
        //    (new \App\Controllers\ContactController())->index();

        // ✅ À propos
        //} elseif ($uri === 'SRnails/public/about') {
        //    (new \App\Controllers\AboutController())->index();

        // ✅ Filtrage par catégorie (ex: /products?category=coffret)
        //} elseif (strpos($uri, 'SRnails/public/products') === 0) {
        //    $_GET['category'] = $_GET['category'] ?? null;
        //    (new \App\Controllers\ArticleController())->showList();

        // ✅ Route 404
        } else {
            http_response_code(404);
            echo "404 - Page non trouvée. URI : $uri";
        }
    }
}

<?php
namespace Core;

class Router {
    public function handleRequest() {
        // Récupération de l'URI demandée
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        // ✅ Route vers la page d'accueil
        if ($uri === '' || $uri === 'SRnails/public') {
            $controller = new \App\controllers\HomeController();
            $controller->index();

        // ✅ Route pour afficher le formulaire de connexion
        } elseif ($uri === 'SRnails/public/user/login') {
            $controller = new \App\controllers\UserController();
            $controller->loginForm();

        // ✅ Route pour traiter la connexion de l'utilisateur
        } elseif ($uri === 'SRnails/public/user/login/process') {
            $controller = new \App\Controllers\UserController();
            $controller->login();

        // ✅ Route pour déconnecter l'utilisateur
        } elseif ($uri === 'SRnails/public/user/logout') {
            $controller = new \App\Controllers\UserController();
            $controller->logout();

        // ✅ Route pour afficher le formulaire d'inscription
        } elseif ($uri === 'SRnails/public/user/register') {
            $controller = new \App\Controllers\UserController();
            $controller->registerForm();

        // ✅ Route pour traiter l'inscription d'un utilisateur
        } elseif ($uri === 'SRnails/public/user/register/process') {
            $controller = new \App\Controllers\UserController();
            $controller->register();

        // ✅ Route pour afficher le formulaire de mise à jour des informations de l'utilisateur
        } elseif ($uri === 'SRnails/public/user/update') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new \App\Controllers\UserController();
                $controller->update();  // Mise à jour des informations de l'utilisateur
            } else {
                $controller = new \App\Controllers\UserController();
                $controller->updateForm();  // Affichage du formulaire de mise à jour
            }

        // ✅ Route pour le tableau de bord utilisateur
        } elseif ($uri === 'SRnails/public/user/dashboard') {
            $controller = new \App\Controllers\UserController();
            $controller->dashboard();

        // ✅ Route pour le tableau de bord administrateur
        } elseif ($uri === 'SRnails/public/admin/dashboard') {
            $controller = new \App\Controllers\ArticleController();
            $controller->adminDashboard();  // Gestion centralisée depuis le tableau de bord admin

        // ✅ Route pour afficher la liste des articles (accessible à tous)
        } elseif ($uri === 'SRnails/public/article/list') {
            $controller = new \App\Controllers\ArticleController();
            $controller->list();

        // ✅ Route pour la création d'un nouvel article (admin uniquement)
        } elseif ($uri === 'SRnails/public/article/create') {
            $controller = new \App\Controllers\ArticleController();
            $controller->create();

        // ✅ Route pour la mise à jour d'un article existant (admin uniquement)
        } elseif (preg_match('#^SRnails/public/article/(\d+)/update$#', $uri, $matches)) {
            $controller = new \App\Controllers\ArticleController();
            $controller->update($matches[1]);

        // ✅ Route pour supprimer un article existant (admin uniquement)
        } elseif (preg_match('#^SRnails/public/article/(\d+)/delete$#', $uri, $matches)) {
            $controller = new \App\Controllers\ArticleController();
            $controller->delete($matches[1]);

        // ✅ Route 404 pour toute URI non trouvée
        } else {
            echo "404 - Page non trouvée. URI : $uri";
        }
    }
}

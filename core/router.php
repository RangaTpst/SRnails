<?php
namespace Core;

class Router {
    public function handleRequest() {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        if ($uri === '' || $uri === 'SRnails/public') {
            $controller = new \App\Controllers\HomeController();
            $controller->index();
        } elseif ($uri === 'SRnails/public/user/login') {
            $controller = new \App\Controllers\UserController();
            $controller->loginForm();
        } elseif ($uri === 'SRnails/public/user/login/process') {
            $controller = new \App\Controllers\UserController();
            $controller->login();
        } elseif ($uri === 'SRnails/public/user/logout') {
            $controller = new \App\Controllers\UserController();
            $controller->logout();
        } elseif ($uri === 'SRnails/public/user/register') {
            $controller = new \App\Controllers\UserController();
            $controller->registerForm();
        } elseif ($uri === 'SRnails/public/user/register/process') {
            $controller = new \App\Controllers\UserController();
            $controller->register();
        
        // ✅ Route pour afficher le formulaire de mise à jour
        } elseif ($uri === 'SRnails/public/user/update') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new \App\Controllers\UserController();
                $controller->update();
            } else {
                $controller = new \App\Controllers\UserController();
                $controller->updateForm();
            }

        // ✅ Route pour le dashboard utilisateur
        } elseif ($uri === 'SRnails/public/user/dashboard') {
            $controller = new \App\Controllers\UserController();
            $controller->dashboard();

        // ✅ Route pour le dashboard administrateur
        } elseif ($uri === 'SRnails/public/admin/dashboard') {
            $controller = new \App\Controllers\AdminController();
            $controller->dashboard();
        
        } else {
            echo "404 - Page non trouvée. URI : $uri";
        }
    }
}

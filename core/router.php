<?php
namespace Core;

class Router {
    public function handleRequest() {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        if ($uri === '' || $uri === 'SRnails/public') {
            $controller = new \App\controllers\HomeController();
            $controller->index();
        } elseif ($uri === 'SRnails/public/user/login') {
            $controller = new \App\controllers\UserController();
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
        
        // ✅ Route pour le dashboard admin
        } elseif ($uri === 'SRnails/public/admin/dashboard') {
            $controller = new \App\Controllers\AdminController();
            $controller->dashboard();
        
        } else {
            echo "404 - Page non trouvée. URI : $uri";
        }
    }
}

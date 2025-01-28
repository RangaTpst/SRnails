<?php
namespace Core;

class Router {
    public function handleRequest() {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        // Correspondance avec les routes
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
        } else {
            echo "404 - Page non trouvée. URI : $uri";
        }
    }
}
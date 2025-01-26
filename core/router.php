<?php
namespace Core;

class Router {
    public function handleRequest() {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        // Redirection de l'accueil
        if ($uri === '' || $uri === 'SRnails/public') {
            $controller = new \App\Controllers\HomeController();
            $controller->index();
        } else {
            echo "404 - Page non trouvée.";
        }
    }
}

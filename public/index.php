<?php
// Chargement automatique des classes
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require_once __DIR__ . '/../' . $class . '.php';
});

use Core\Router;

$router = new Router();
$router->handleRequest();

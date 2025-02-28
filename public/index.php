<?php
spl_autoload_register(function ($class) {
    $baseDir = realpath(__DIR__ . '/../'); // Résout le chemin absolu vers le dossier racine
    $classPath = str_replace(['\\', 'App'], ['/', 'app'], $class) . '.php';
    $filePath = $baseDir . '/' . $classPath; // Construit le chemin complet

    $resolvedPath = realpath($filePath); // Résout le chemin absolu final

    if ($resolvedPath && file_exists($resolvedPath)) {
        require_once $resolvedPath;
    } else {
        die("Fichier introuvable : $filePath<br>");
    }
});

use core\router;

$router = new Router();
$router->handleRequest();

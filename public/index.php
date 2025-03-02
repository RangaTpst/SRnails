<?php
/**
 * Fichier d'entrée principal de l'application.
 *
 * Ce fichier gère l'autoloading des classes et la gestion des routes de l'application.
 *
 * @package SRnails
 */

// Enregistre une fonction d'autoloading pour charger automatiquement les classes PHP
spl_autoload_register(function ($class) {
    // Résout le chemin absolu vers le dossier racine du projet
    $baseDir = realpath(__DIR__ . '/../');

    // Remplace les séparateurs de namespace par des séparateurs de chemin de fichier
    $classPath = str_replace(['\\', 'App'], ['/', 'app'], $class) . '.php';

    // Construit le chemin complet vers le fichier de la classe
    $filePath = $baseDir . '/' . $classPath;

    // Résout le chemin absolu final pour le fichier
    $resolvedPath = realpath($filePath);

    // Si le fichier existe, on l'inclut
    if ($resolvedPath && file_exists($resolvedPath)) {
        require_once $resolvedPath;
    } else {
        // Si le fichier n'est pas trouvé, on affiche une erreur et on arrête l'exécution
        die("Fichier introuvable : $filePath<br>");
    }
});

// Inclusion de la classe Router pour gérer les routes
use core\Router;

// Instancie un objet Router et traite la requête entrante
$router = new Router();
$router->handleRequest();


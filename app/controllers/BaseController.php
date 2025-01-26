<?php
namespace App\Controllers;

class BaseController {
    public function render($view, $data = []) {
        extract($data); // Rend les variables disponibles dans la vue

        // Utilisation d'un chemin basé sur la racine du projet
        $viewPath = dirname(__DIR__) . "/views/$view.php";

        // Vérifie si le fichier existe
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Erreur : La vue $viewPath est introuvable.");
        }
    }
}

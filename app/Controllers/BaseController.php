<?php
namespace App\Controllers;

/**
 * Classe de base pour les contrôleurs.
 *
 * Cette classe est responsable de rendre les vues de manière dynamique en extrayant les données
 * passées dans les vues et en incluant le fichier de vue correspondant.
 *
 * @package App\Controllers
 */
class BaseController {
    
    /**
     * Rendre une vue avec les données fournies.
     *
     * Cette méthode prend le nom de la vue et les données associées, les extrait en variables
     * et inclut le fichier de la vue correspondant. Si le fichier de la vue n'existe pas,
     * elle affiche un message d'erreur.
     *
     * @param string $view Le nom de la vue à rendre (par exemple "dashboard").
     * @param array $data Les données à transmettre à la vue. Par défaut, c'est un tableau vide.
     * 
     * @throws Exception Si le fichier de la vue n'existe pas.
     */
    public function render($view, $data = []) {
        extract($data); // Rend les variables disponibles dans la vue

        // Utilisation d'un chemin basé sur la racine du projet
        $viewPath = dirname(__DIR__) . "/Views/$view.php";

        // Vérifie si le fichier existe
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Erreur : La vue $viewPath est introuvable.");
        }
    }
}

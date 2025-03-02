<?php
namespace App\Controllers;

/**
 * Contrôleur principal pour la page d'accueil.
 *
 * Cette classe est responsable de l'affichage de la page d'accueil de l'application.
 * Elle hérite de la classe `BaseController` et utilise sa méthode `render` pour afficher
 * la vue d'accueil avec les données appropriées.
 *
 * @package App\Controllers
 */
class HomeController extends BaseController {
    
    /**
     * Afficher la page d'accueil.
     *
     * Cette méthode est responsable de l'affichage de la page d'accueil. Elle prépare les
     * données nécessaires (titre et message de bienvenue) et appelle la méthode `render` de la classe
     * `BaseController` pour afficher la vue correspondante.
     *
     * @return void
     */
    public function index() {
        // Données à passer à la vue
        $data = [
            'title' => 'Bienvenue sur SR Nails',
            'welcome_message' => 'Découvrez nos produits et services exceptionnels !'
        ];

        // Rendu de la vue avec les données
        $this->render('/home/main', $data);
    }
}

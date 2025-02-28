<?php
namespace App\Controllers;

class HomeController extends BaseController {
    public function index() {
        $data = [
            'title' => 'Bienvenue sur SR Nails',
            'welcome_message' => 'Découvrez nos produits et services exceptionnels !'
        ];
        $this->render('/Home/main', $data);
    }
}

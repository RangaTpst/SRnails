<?php
namespace App\Controllers;

class StaticController extends BaseController {
    public function about() {
        $this->render('home/about', ['title' => 'À propos de SR Nails']);
    }

    public function contact() {
        $this->render('home/contact', ['title' => 'Contact']);
    }

    public function legalNotice() {
        $this->render('home/mention_legal', ['title' => 'Mentions Légales']);
    }

    public function policy() {
        $this->render('home/policy', ['title' => 'Politiques de confidentialité']);
    }
}

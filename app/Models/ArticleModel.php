<?php
namespace App\Models;

use core\Database;

class ArticleModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAllArticles() {
        $stmt = $this->db->query("SELECT * FROM articles");
        return $stmt->fetchAll();
    }

    public function getArticleById($id) {
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createArticle($title, $description, $image, $price) {
        $stmt = $this->db->prepare("INSERT INTO articles (title, description, img, price) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $description, $image, $price]);
    }

    public function updateArticle($id, $title, $description, $image, $price) {
        $stmt = $this->db->prepare("UPDATE articles SET title = ?, description = ?, img = ?, price = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $image, $price, $id]);
    }

    public function deleteArticle($id) {
        $stmt = $this->db->prepare("DELETE FROM articles WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

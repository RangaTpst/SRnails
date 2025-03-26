<?php
namespace App\Models;

use core\Database;

/**
 * Class ArticleModel
 * Gère les opérations CRUD (Create, Read, Update, Delete) pour les articles dans la base de données.
 */
class ArticleModel {
    private $db;

    /**
     * ArticleModel constructor.
     * Initialise la connexion à la base de données.
     */
    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Récupère tous les articles.
     *
     * @return array Les articles présents dans la base de données.
     */
    public function getAllArticles() {
        $stmt = $this->db->query("SELECT * FROM articles");
        return $stmt->fetchAll();
    }

    /**
     * Récupère un article par son ID.
     *
     * @param int $id L'ID de l'article à récupérer.
     * @return array|false Les données de l'article ou false si l'article n'existe pas.
     */
    public function getArticleById($id) {
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Crée un nouvel article.
     *
     * @param string $title Le titre de l'article.
     * @param string $description La description de l'article.
     * @param string $image L'URL de l'image de l'article.
     * @param float $price Le prix de l'article.
     * @return bool True si l'article a été créé avec succès, false sinon.
     */
    public function createArticle($title, $content, $image, $price) {
        $stmt = $this->db->prepare("INSERT INTO articles (title, content, image, price) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $content, $image, $price]);
    }

    /**
     * Met à jour un article existant.
     *
     * @param int $id L'ID de l'article à mettre à jour.
     * @param string $title Le titre de l'article.
     * @param string $description La description de l'article.
     * @param string $image L'URL de l'image de l'article.
     * @param float $price Le prix de l'article.
     * @return bool True si l'article a été mis à jour avec succès, false sinon.
     */
    public function updateArticle($id, $title, $content, $image, $price) {
        $stmt = $this->db->prepare("UPDATE articles SET title = ?, content = ?, image = ?, price = ? WHERE id = ?");
        return $stmt->execute([$title, $content, $image, $price, $id]);
    }

    /**
     * Supprime un article.
     *
     * @param int $id L'ID de l'article à supprimer.
     * @return bool True si l'article a été supprimé avec succès, false sinon.
     */
    public function deleteArticle($id) {
        $stmt = $this->db->prepare("DELETE FROM articles WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

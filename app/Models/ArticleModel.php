<?php
namespace App\Models;

use PDO;
use core\Database;

/**
 * Class ArticleModel
 * Gère les opérations CRUD (Create, Read, Update, Delete) pour les articles dans la base de données.
 */
class ArticleModel extends BaseModel {

    /**
     * @var PDO Connexion à la base de données
     */
    protected $db;

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
     * @param string $content Le contenu de l'article.
     * @param string $image L'image de l'article (nom de fichier).
     * @param float $price Le prix de l'article.
     * @param string $category La catégorie de l'article.
     * @return bool True si l'article a été créé avec succès, false sinon.
     */
    public function createArticle($title, $content, $category, $image, $price) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO articles (title, content, category, image, price) 
                VALUES (:title, :content, :category, :image, :price)
            ");
            return $stmt->execute([
                ':title'    => $title,
                ':content'  => $content,
                ':category' => $category,
                ':image'    => $image,
                ':price'    => $price
            ]);
        } catch (\PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }
    

    /**
     * Met à jour un article existant.
     *
     * @param int $id L'ID de l'article à mettre à jour.
     * @param string $title Le titre de l'article.
     * @param string $content Le contenu de l'article.
     * @param string $image L'image de l'article (nom de fichier).
     * @param float $price Le prix de l'article.
     * @param string $category La catégorie de l'article.
     * @return bool True si l'article a été mis à jour avec succès, false sinon.
     */
    public function updateArticle($id, $title, $content, $category, $image, $price) {
        $sql = "UPDATE articles 
                SET title = :title, content = :content, category = :category, image = :image, price = :price 
                WHERE id = :id";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':category', $category);
        $stmt->bindValue(':image', $image);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
        return $stmt->execute();
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

    /**
     * Récupère les articles avec filtres facultatifs.
     *
     * @param string $search Recherche par mot-clé.
     * @param array $priceRanges Intervalles de prix.
     * @param string $order Tri par prix ou date.
     * @return array
     */
    public function getFilteredArticles($search = '', $priceRanges = [], $order = '', $categories = []) {
        $sql = "SELECT * FROM articles WHERE 1";
        $params = [];
    
        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR content LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
    
        if (!empty($priceRanges)) {
            $conditions = [];
            foreach ($priceRanges as $i => $range) {
                [$min, $max] = explode('-', $range);
                $conditions[] = "(price BETWEEN :min{$i} AND :max{$i})";
                $params[":min{$i}"] = $min;
                $params[":max{$i}"] = $max;
            }
            $sql .= " AND (" . implode(' OR ', $conditions) . ")";
        }
    
        if (!empty($categories)) {
            $placeholders = [];
            foreach ($categories as $i => $cat) {
                $placeholder = ":cat{$i}";
                $placeholders[] = $placeholder;
                $params[$placeholder] = $cat;
            }
            $sql .= " AND category IN (" . implode(', ', $placeholders) . ")";
        }
    
        switch ($order) {
            case 'price_asc':
                $sql .= " ORDER BY price ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY price DESC";
                break;
            case 'latest':
                $sql .= " ORDER BY created_at DESC";
                break;
        }
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    

    /**
     * Récupère les derniers articles par date de création.
     *
     * @param int $limit Nombre d'articles à retourner
     * @return array
     */
    public function getLatestArticles($limit = 3) {
        $stmt = $this->db->prepare("SELECT * FROM articles ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

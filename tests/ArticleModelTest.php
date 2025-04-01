<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\ArticleModel;
use Core\Database;

class ArticleModelTest extends TestCase
{
    private $db;
    private $articleModel;
    private string $title = "Test Article";
    private string $content = "This is a test article content.";
    private string $image = "test.jpg";
    private float $price = 19.99;
    private string $category = "Test Category";

    // Avant chaque test
    protected function setUp(): void
    {
        // Connexion à la base de données
        $this->db = Database::getConnection();
        $this->articleModel = new ArticleModel();

        // Nettoyage des anciens articles
        $this->db->prepare("DELETE FROM articles WHERE title = ?")->execute([$this->title]);
    }

    // Test de la création d'un article
    public function testCreateArticle(): void
    {
        $result = $this->articleModel->createArticle($this->title, $this->content, $this->category, $this->image, $this->price);
        $this->assertTrue($result, "L'article n'a pas été créé avec succès.");

        // Vérification en base
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE title = ?");
        $stmt->execute([$this->title]);
        $article = $stmt->fetch();
        $this->assertNotFalse($article, "L'article n'a pas été trouvé en base.");
    }

    // Test de la mise à jour de l'article
    public function testUpdateArticle(): void
    {
        $this->articleModel->createArticle($this->title, $this->content, $this->category, $this->image, $this->price);

        $newTitle = "Updated Article";
        $newContent = "This is an updated test article content.";
        $newCategory = "Updated Category";
        $newImage = "updated_test.jpg";
        $newPrice = 29.99;

        $result = $this->articleModel->updateArticle(1, $newTitle, $newContent, $newCategory, $newImage, $newPrice);

        $this->assertTrue($result, "La mise à jour de l'article a échoué.");

        // Vérification en base
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = 1");
        $stmt->execute();
        $article = $stmt->fetch();
        $this->assertEquals($newTitle, $article['title'], "Le titre de l'article n'a pas été mis à jour.");
    }

    // Test de la suppression de l'article
    public function testDeleteArticle(): void
    {
        $this->articleModel->createArticle($this->title, $this->content, $this->category, $this->image, $this->price);

        $stmt = $this->db->prepare("SELECT * FROM articles WHERE title = ?");
        $stmt->execute([$this->title]);
        $article = $stmt->fetch();
        $articleId = $article['id'];

        $result = $this->articleModel->deleteArticle($articleId);
        $this->assertTrue($result, "L'article n'a pas été supprimé.");

        // Vérification en base
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$articleId]);
        $article = $stmt->fetch();
        $this->assertFalse($article, "L'article est toujours présent en base.");
    }

    // Test de récupération des articles filtrés
    public function testGetFilteredArticles(): void
    {
        $this->articleModel->createArticle($this->title, $this->content, $this->category, $this->image, $this->price);

        $articles = $this->articleModel->getFilteredArticles('test', ['10-20'], 'price_asc', ['Test Category']);
        $this->assertNotEmpty($articles, "Aucun article trouvé avec les filtres.");
    }

    // Nettoyage après chaque test
    protected function tearDown(): void
    {
        $this->db->prepare("DELETE FROM articles WHERE title = ?")->execute([$this->title]);
    }
}

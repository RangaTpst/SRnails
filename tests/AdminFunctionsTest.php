<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\ArticleModel;
use App\Models\UserModel;
use Core\Database;

class AdminFunctionsTest extends TestCase
{
    private $db;
    private $articleModel;
    private $userModel;
    private string $adminUsername = "adminUser";
    private string $nonAdminUsername = "testUser";
    private string $email = "test@example.com";
    private string $password = "admin123";
    private string $hashedPassword;
    private $adminUserId;
    private $nonAdminUserId;

    // Avant chaque test
    protected function setUp(): void
    {
        // Connexion à la base de données
        $this->db = Database::getConnection();
        $this->articleModel = new ArticleModel();
        $this->userModel = new UserModel();

        // Générer un email unique pour chaque test
        $uniqueEmailAdmin = 'test_' . uniqid() . '@example.com';
        $uniqueEmailUser = 'user_' . uniqid() . '@example.com';
        
        // Hachage du mot de passe
        $this->hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

        // Création de l'utilisateur administrateur avec un email unique
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->adminUsername, $uniqueEmailAdmin, $this->hashedPassword, 1]);
        $this->adminUserId = $this->db->lastInsertId();

        // Création de l'utilisateur non-administrateur avec un email unique
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->nonAdminUsername, $uniqueEmailUser, $this->hashedPassword, 0]);
        $this->nonAdminUserId = $this->db->lastInsertId();
    }

    // Test de la création d'article par un administrateur
    public function testAdminCanCreateArticle(): void
    {
        // Connecter l'utilisateur administrateur
        $_SESSION['user_id'] = $this->adminUserId;
        $_SESSION['is_admin'] = 1;

        // Simuler la création d'un article
        $result = $this->articleModel->createArticle("Test Article", "Content of the article", "category1", "image.jpg", 25.50);
        $this->assertTrue($result, "L'article n'a pas été créé par l'administrateur.");

        // Vérification que l'article a bien été créé
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE title = ?");
        $stmt->execute(["Test Article"]);
        $article = $stmt->fetch();
        $this->assertNotFalse($article, "L'article créé par l'administrateur n'a pas été trouvé en base.");
    }

    // Test que l'utilisateur non-administrateur ne peut pas créer un article
    public function testNonAdminCannotCreateArticle(): void
    {
        // Connecter un utilisateur non-admin
        $_SESSION['user_id'] = $this->nonAdminUserId;
        $_SESSION['is_admin'] = 0;

        // Tenter de créer un article
        $result = $this->articleModel->createArticle("Test Article", "Content of the article", "category1", "image.jpg", 25.50);
        $this->assertFalse($result, "Un utilisateur non-admin a pu créer un article.");
    }

    // Test de la mise à jour d'article par un administrateur
    public function testAdminCanUpdateArticle(): void
    {
        // Créer un article d'abord
        $this->articleModel->createArticle("Old Title", "Old content", "category1", "oldimage.jpg", 10.50);

        // Connecter l'utilisateur administrateur
        $_SESSION['user_id'] = $this->adminUserId;
        $_SESSION['is_admin'] = 1;

        // Mettre à jour l'article
        $stmt = $this->db->prepare("SELECT id FROM articles WHERE title = ?");
        $stmt->execute(["Old Title"]);
        $article = $stmt->fetch();

        $result = $this->articleModel->updateArticle($article['id'], "Updated Title", "Updated content", "category2", "newimage.jpg", 20.50);
        $this->assertTrue($result, "L'administrateur n'a pas pu mettre à jour l'article.");

        // Vérification que les modifications ont bien été appliquées
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$article['id']]);
        $updatedArticle = $stmt->fetch();
        $this->assertEquals("Updated Title", $updatedArticle['title'], "Le titre de l'article n'a pas été mis à jour.");
    }

    // Test que l'utilisateur non-administrateur ne peut pas mettre à jour un article
    public function testNonAdminCannotUpdateArticle(): void
    {
        // Créer un article d'abord
        $this->articleModel->createArticle("Title", "Content", "category1", "image.jpg", 15.50);

        // Connecter un utilisateur non-admin
        $_SESSION['user_id'] = $this->nonAdminUserId;
        $_SESSION['is_admin'] = 0;

        // Essayer de mettre à jour l'article
        $stmt = $this->db->prepare("SELECT id FROM articles WHERE title = ?");
        $stmt->execute(["Title"]);
        $article = $stmt->fetch();

        $result = $this->articleModel->updateArticle($article['id'], "Updated Title", "Updated content", "category2", "newimage.jpg", 25.50);
        $this->assertFalse($result, "Un utilisateur non-admin a pu mettre à jour un article.");
    }

    // Test de la suppression d'article par un administrateur
    public function testAdminCanDeleteArticle(): void
    {
        // Créer un article d'abord
        $this->articleModel->createArticle("Article to Delete", "Content to delete", "category1", "image.jpg", 30.50);

        // Connecter l'utilisateur administrateur
        $_SESSION['user_id'] = $this->adminUserId;
        $_SESSION['is_admin'] = 1;

        // Supprimer l'article
        $stmt = $this->db->prepare("SELECT id FROM articles WHERE title = ?");
        $stmt->execute(["Article to Delete"]);
        $article = $stmt->fetch();

        $result = $this->articleModel->deleteArticle($article['id']);
        $this->assertTrue($result, "L'administrateur n'a pas pu supprimer l'article.");

        // Vérification que l'article a bien été supprimé
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$article['id']]);
        $deletedArticle = $stmt->fetch();
        $this->assertFalse($deletedArticle, "L'article n'a pas été supprimé en base.");
    }

    // Test que l'utilisateur non-administrateur ne peut pas supprimer un article
    public function testNonAdminCannotDeleteArticle(): void
    {
        // Créer un article d'abord
        $this->articleModel->createArticle("Article to Delete", "Content to delete", "category1", "image.jpg", 30.50);

        // Connecter un utilisateur non-admin
        $_SESSION['user_id'] = $this->nonAdminUserId;
        $_SESSION['is_admin'] = 0;

        // Essayer de supprimer l'article
        $stmt = $this->db->prepare("SELECT id FROM articles WHERE title = ?");
        $stmt->execute(["Article to Delete"]);
        $article = $stmt->fetch();

        $result = $this->articleModel->deleteArticle($article['id']);
        $this->assertFalse($result, "Un utilisateur non-admin a pu supprimer un article.");
    }

    // Nettoyage après chaque test
    protected function tearDown(): void
    {
        // Nettoyer les utilisateurs par email unique
        $this->db->prepare("DELETE FROM users WHERE email LIKE ?")->execute([ '%@example.com%' ]);
        
        // Nettoyer les articles de test
        $this->db->prepare("DELETE FROM articles WHERE title LIKE ?")->execute([ '%Test Article%' ]);
        
        // Réinitialiser l'auto-incrément pour éviter les conflits d'ID
        $this->db->exec("ALTER TABLE users AUTO_INCREMENT = 1");
        $this->db->exec("ALTER TABLE articles AUTO_INCREMENT = 1");
    }
}

<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\ArticleModel;
use App\Models\UserModel;
use App\Controllers\ArticleController;
use Core\Database;

class AdminFunctionsTest extends TestCase
{
    private $db;
    private $articleModel;
    private $userModel;
    private string $password = "admin123";
    private string $hashedPassword;
    private $adminUserId;
    private $nonAdminUserId;

    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->db = Database::getConnection();
        $this->articleModel = new ArticleModel();
        $this->userModel = new UserModel();

        $uniqueUsernameAdmin = 'admin_' . uniqid();
        $uniqueUsernameUser = 'user_' . uniqid();
        $uniqueEmailAdmin = 'test_' . uniqid() . '@example.com';
        $uniqueEmailUser = 'user_' . uniqid() . '@example.com';

        $this->hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$uniqueUsernameAdmin, $uniqueEmailAdmin, $this->hashedPassword, 1]);
        $this->adminUserId = $this->db->lastInsertId();

        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$uniqueUsernameUser, $uniqueEmailUser, $this->hashedPassword, 0]);
        $this->nonAdminUserId = $this->db->lastInsertId();
    }

    public function testAdminCanCreateArticle(): void
    {
        $_SESSION['user_id'] = $this->adminUserId;
        $_SESSION['is_admin'] = 1;

        $result = $this->articleModel->createArticle("Test Article", "Content of the article", "category1", "image.jpg", 25.50);
        $this->assertTrue($result, "L'article n'a pas été créé par l'administrateur.");

        $stmt = $this->db->prepare("SELECT * FROM articles WHERE title = ?");
        $stmt->execute(["Test Article"]);
        $article = $stmt->fetch();
        $this->assertNotFalse($article, "L'article créé par l'administrateur n'a pas été trouvé en base.");
    }

    public function testAdminCanUpdateArticle(): void
    {
        $this->articleModel->createArticle("Old Title", "Old content", "category1", "oldimage.jpg", 10.50);

        $_SESSION['user_id'] = $this->adminUserId;
        $_SESSION['is_admin'] = 1;

        $stmt = $this->db->prepare("SELECT id FROM articles WHERE title = ?");
        $stmt->execute(["Old Title"]);
        $article = $stmt->fetch();

        $result = $this->articleModel->updateArticle($article['id'], "Updated Title", "Updated content", "category2", "newimage.jpg", 20.50);
        $this->assertTrue($result, "L'administrateur n'a pas pu mettre à jour l'article.");

        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$article['id']]);
        $updatedArticle = $stmt->fetch();
        $this->assertEquals("Updated Title", $updatedArticle['title'], "Le titre de l'article n'a pas été mis à jour.");
    }

    public function testAdminCanDeleteArticle(): void
    {
        $this->articleModel->createArticle("Article to Delete", "Content to delete", "category1", "image.jpg", 30.50);

        $_SESSION['user_id'] = $this->adminUserId;
        $_SESSION['is_admin'] = 1;

        $stmt = $this->db->prepare("SELECT id FROM articles WHERE title = ?");
        $stmt->execute(["Article to Delete"]);
        $article = $stmt->fetch();

        $result = $this->articleModel->deleteArticle($article['id']);
        $this->assertTrue($result, "L'administrateur n'a pas pu supprimer l'article.");

        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$article['id']]);
        $deletedArticle = $stmt->fetch();
        $this->assertFalse($deletedArticle, "L'article n'a pas été supprimé en base.");
    }

    public function testAdminCanAccessCreateForm(): void
    {
        $_SESSION['user_id'] = $this->adminUserId;
        $_SESSION['is_admin'] = 1;

        $controller = new ArticleController();
        $controller->enableTestMode(); // ← empêche les exit()

        ob_start();
        $controller->createForm();
        $output = ob_get_clean();

        $this->assertStringContainsString('<form', $output, "L’admin devrait voir le formulaire de création.");
    }

    public function testNonAdminIsRedirectedFromCreateForm(): void
    {
        $_SESSION['user_id'] = $this->nonAdminUserId;
        $_SESSION['is_admin'] = 0;

        $controller = new ArticleController();
        $controller->enableTestMode(); // ← empêche l’appel à exit()

        ob_start();
        $controller->createForm();
        $output = ob_get_clean();

        $this->assertEmpty($output, "Un non-admin ne devrait pas accéder au formulaire de création.");
    }

    protected function tearDown(): void
    {
        $this->db->prepare("DELETE FROM users WHERE email LIKE ?")->execute(['%@example.com%']);
        $this->db->prepare("DELETE FROM users WHERE username LIKE 'admin_%' OR username LIKE 'user_%'")->execute();

        $this->db->prepare("DELETE FROM articles WHERE title LIKE ?")->execute(['%Test Article%']);
        $this->db->prepare("DELETE FROM articles WHERE title LIKE ?")->execute(['%Old Title%']);
        $this->db->prepare("DELETE FROM articles WHERE title LIKE ?")->execute(['%Article to Delete%']);
        $this->db->prepare("DELETE FROM articles WHERE title LIKE ?")->execute(['%Updated Title%']);

        $this->db->exec("ALTER TABLE users AUTO_INCREMENT = 1");
        $this->db->exec("ALTER TABLE articles AUTO_INCREMENT = 1");

        session_unset();
        session_destroy();
    }
}

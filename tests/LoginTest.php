<?php
declare(strict_types=1);

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Core\Database;
use App\Controllers\UserController;

final class LoginTest extends TestCase {
    private $db;
    private string $username = "testUser";
    private string $password = "admin123";
    private string $hashedPassword;

    protected function setUp(): void {
        $this->db = Database::getConnection();

        // 🔹 Nettoyage préalable pour éviter les doublons
        $this->db->prepare("DELETE FROM users WHERE username = ?")->execute([$this->username]);

        // 🔹 Création de l'utilisateur de test
        $this->hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->username, 'test@example.com', $this->hashedPassword, 0]);

        // 🔹 Vérification que l'utilisateur a bien été ajouté
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$this->username]);
        $user = $stmt->fetch();

        $this->assertNotFalse($user, "L'utilisateur n'a pas été trouvé en base.");
        $this->assertArrayHasKey('id', $user, "L'utilisateur inséré doit avoir un ID.");
    }

    /**
     * @covers \App\Controllers\UserController::login
     */
    public function testUserCanLogin(): void {
        // 🔹 Simuler une requête POST pour la connexion
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'username' => $this->username,
            'password' => $this->password
        ];

        // 🔹 Initialisation manuelle de la session pour PHPUnit
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 🔹 Exécuter la connexion
        ob_start();
        $userController = new UserController();
        $result = $userController->login();
        ob_end_clean();

        // 🔹 Vérifier la connexion réussie
        $this->assertIsString($result, "Le retour de login() doit être une chaîne.");
        $this->assertEquals("Login success", $result, "L'utilisateur doit pouvoir se connecter.");

        // 🔹 Vérifier que la session a bien été créée
        $this->assertArrayHasKey('user_id', $_SESSION, "L'ID utilisateur doit être en session après connexion.");
        $this->assertEquals($_SESSION['user_id'], $this->getUserId(), "L'ID utilisateur en session doit correspondre.");
    }

    // 🔹 Récupérer l'ID de l'utilisateur de test depuis la base
    private function getUserId(): ?int {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$this->username]);
        $user = $stmt->fetch();
        return $user['id'] ?? null;
    }

    protected function tearDown(): void {
        // 🔹 Nettoyage après le test
        $this->db->prepare("DELETE FROM users WHERE username = ?")->execute([$this->username]);
    }
}

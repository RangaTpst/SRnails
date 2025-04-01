<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\UserModel;
use Core\Database;

class UserModelTest extends TestCase
{
    private $db;
    private $userModel;
    private string $username;
    private string $email;
    private string $password;

    // Avant chaque test
    protected function setUp(): void
    {
        // Connexion à la base de données
        $this->db = Database::getConnection();
        $this->userModel = new UserModel();

        // Ajouter un nom d'utilisateur unique pour chaque test
        $this->username = 'testUser_' . uniqid();
        $this->email = "test@example.com";
        $this->password = "admin123";

        // Nettoyage des anciens utilisateurs
        $this->db->prepare("DELETE FROM users WHERE username = ?")->execute([$this->username]);
    }

    // Test de la création d'un utilisateur
    public function testCreateUser(): void
    {
        $result = $this->userModel->createUser($this->username, $this->email, $this->password);
        $this->assertTrue($result, "L'utilisateur n'a pas été créé avec succès.");

        // Vérification en base
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$this->username]);
        $user = $stmt->fetch();
        $this->assertNotFalse($user, "L'utilisateur n'a pas été trouvé en base.");
    }

    // Test de la mise à jour de l'utilisateur
    public function testUpdateUser(): void
    {
        $this->userModel->createUser($this->username, $this->email, $this->password);

        $newEmail = "new@example.com";
        
        // Récupération dynamique de l'ID de l'utilisateur
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$this->username]);
        $user = $stmt->fetch();
        $userId = $user['id'];

        $result = $this->userModel->updateUser($userId, $this->username, $newEmail);

        $this->assertTrue($result, "La mise à jour de l'utilisateur a échoué.");

        // Vérification en base
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        $this->assertEquals($newEmail, $user['email'], "L'email n'a pas été mis à jour correctement.");
    }

    // Test de la suppression de l'utilisateur
    public function testDeleteUser(): void
    {
        $this->userModel->createUser($this->username, $this->email, $this->password);

        // Récupération dynamique de l'ID de l'utilisateur
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$this->username]);
        $user = $stmt->fetch();
        $userId = $user['id'];

        // Suppression de l'utilisateur
        $result = $this->userModel->deleteUser($userId);
        $this->assertTrue($result, "L'utilisateur n'a pas été supprimé.");

        // Vérification que l'utilisateur n'est plus dans la base
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        $this->assertFalse($user, "L'utilisateur est toujours présent en base.");
    }

    // Test de l'existence de l'email
    public function testEmailExists(): void
    {
        $this->userModel->createUser($this->username, $this->email, $this->password);
        $result = $this->userModel->emailExists($this->email);
        $this->assertTrue($result, "L'email devrait exister en base.");
    }

    // Nettoyage après chaque test
    protected function tearDown(): void
    {
        // Supprimer l'utilisateur de la base
        $stmt = $this->db->prepare("DELETE FROM users WHERE username = ?");
        $stmt->execute([$this->username]);

        // Vérification que l'utilisateur est bien supprimé
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$this->username]);
        $user = $stmt->fetch();
        
        // Si l'utilisateur est encore présent, c'est que la suppression n'a pas fonctionné
        $this->assertFalse($user, "L'utilisateur n'a pas été supprimé correctement.");
    }
}

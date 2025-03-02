<?php
declare(strict_types=1);

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Core\Database;
use App\Controllers\UserController;

/**
 * Test de la fonctionnalité de connexion utilisateur.
 * 
 * Ce test vérifie que l'utilisateur peut se connecter avec les informations correctes.
 * Il utilise PHPUnit pour effectuer les assertions nécessaires sur le processus de connexion.
 * Le test crée un utilisateur dans la base de données, tente de se connecter, et vérifie que la session contient les informations correctes.
 * 
 * @package Tests
 * @covers \App\Controllers\UserController::login
 */
final class LoginTest extends TestCase {
    private $db;
    private string $username = "testUser";
    private string $password = "admin123";
    private string $hashedPassword;

    /**
     * Initialisation avant chaque test.
     * 
     * Cette méthode est exécutée avant chaque test pour préparer l'environnement de test,
     * y compris la connexion à la base de données, la suppression de l'utilisateur précédent s'il existe,
     * et la création d'un nouvel utilisateur avec des informations de connexion par défaut.
     * 
     * @return void
     */
    protected function setUp(): void {
        // Connexion à la base de données
        $this->db = Database::getConnection();

        // Nettoyage préalable pour éviter les doublons
        $this->db->prepare("DELETE FROM users WHERE username = ?")->execute([$this->username]);

        // Création de l'utilisateur de test avec un mot de passe haché
        $this->hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->username, 'test@example.com', $this->hashedPassword, 0]);

        // Vérification que l'utilisateur a bien été ajouté
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$this->username]);
        $user = $stmt->fetch();

        // Assertions pour vérifier que l'utilisateur a été ajouté avec succès
        $this->assertNotFalse($user, "L'utilisateur n'a pas été trouvé en base.");
        $this->assertArrayHasKey('id', $user, "L'utilisateur inséré doit avoir un ID.");
    }

    /**
     * Test de la connexion utilisateur.
     * 
     * Ce test vérifie que l'utilisateur peut se connecter en utilisant un formulaire avec
     * un nom d'utilisateur et un mot de passe corrects. Il vérifie également que les informations
     * de l'utilisateur sont stockées correctement dans la session après la connexion.
     * 
     * @return void
     * @covers \App\Controllers\UserController::login
     */
    public function testUserCanLogin(): void {
        // Simuler la soumission du formulaire de connexion
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = ['username' => $this->username, 'password' => $this->password];

        session_start();
        var_dump($_SESSION); // Affiche l'état de la session avant la connexion

        // Créer une instance du contrôleur UserController et appeler la méthode login
        $userController = new UserController();
        $result = $userController->login();

        // Assertions pour vérifier que la connexion a réussi
        $this->assertIsString($result, "Le retour de login() doit être une chaîne.");
        $this->assertEquals("Login success", $result, "L'utilisateur doit pouvoir se connecter.");
        
        // Vérification que la session contient l'ID de l'utilisateur
        $this->assertArrayHasKey('user_id', $_SESSION, "L'ID utilisateur doit être en session après connexion.");
        $this->assertEquals($_SESSION['user_id'], $this->getUserId(), "L'ID utilisateur en session doit correspondre.");
    }

    /**
     * Récupère l'ID de l'utilisateur de test depuis la base de données.
     * 
     * Cette méthode est utilisée pour récupérer l'ID de l'utilisateur de test créé dans la base de données.
     * Elle est utilisée pour valider que l'ID utilisateur dans la session correspond à l'ID récupéré.
     * 
     * @return int|null L'ID de l'utilisateur, ou null si l'utilisateur n'est pas trouvé.
     */
    private function getUserId(): ?int {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$this->username]);
        $user = $stmt->fetch();
        return $user['id'] ?? null;
    }

    /**
     * Nettoyage après chaque test.
     * 
     * Cette méthode est exécutée après chaque test pour nettoyer l'environnement de test, notamment
     * en supprimant l'utilisateur de test créé dans la base de données.
     * 
     * @return void
     */
    protected function tearDown(): void {
        // Nettoyage après le test
        $this->db->prepare("DELETE FROM users WHERE username = ?")->execute([$this->username]);
    }
}

<?php
namespace App\Database;

use PDO;
use PDOException;

class Database {
    private $host = 'localhost';
    private $db = 'ecommerce';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';
    private $pdo;

    public function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
            exit();
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}

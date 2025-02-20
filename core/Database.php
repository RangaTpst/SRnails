<?php
namespace Core;

class Database {
    public static function getConnection() {
        $configPath = realpath(__DIR__ . '/../config/config.php');

        if (!file_exists($configPath)) {
            die("Le fichier de configuration est introuvable : $configPath");
        }

        $config = include $configPath;

        try {
            $dsn = "mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']};charset=utf8mb4";
            $pdo = new \PDO($dsn, $config['db_user'], $config['db_password']);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
}

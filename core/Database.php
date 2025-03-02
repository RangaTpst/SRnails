<?php
namespace Core;

/**
 * Class Database
 *
 * Cette classe fournit une méthode statique pour établir une connexion à la base de données MySQL.
 * Elle récupère les paramètres de connexion depuis un fichier de configuration et utilise PDO
 * pour établir une connexion sécurisée et fiable avec la base de données.
 *
 * @package Core
 */
class Database {
    /**
     * Retourne une connexion à la base de données MySQL via PDO.
     *
     * Cette méthode se connecte à la base de données en utilisant les informations de connexion 
     * définies dans le fichier de configuration. Si la connexion échoue, une exception est lancée.
     *
     * @return \PDO La connexion à la base de données PDO.
     * @throws \PDOException Si une erreur survient lors de la connexion à la base de données.
     */
    public static function getConnection() {
        // Récupère le chemin du fichier de configuration
        $configPath = realpath(__DIR__ . '/../config/config.php');

        // Vérifie si le fichier de configuration existe
        if (!file_exists($configPath)) {
            die("Le fichier de configuration est introuvable : $configPath");
        }

        // Charge la configuration
        $config = include $configPath;

        try {
            // Crée le DSN (Data Source Name) pour la connexion MySQL
            $dsn = "mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']};charset=utf8mb4";
            // Crée la connexion PDO avec les informations de configuration
            $pdo = new \PDO($dsn, $config['db_user'], $config['db_password']);
            // Définit le mode d'erreur pour afficher les exceptions
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $e) {
            // En cas d'échec de connexion, affiche le message d'erreur
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
}

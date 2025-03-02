<?php
namespace App\Models;

use PDO;

/**
 * Class BaseModel
 * Classe de base pour les modèles, gère la connexion à la base de données.
 */
class BaseModel {
    /**
     * @var PDO La connexion à la base de données.
     */
    protected $db;

    /**
     * BaseModel constructor.
     * Initialise la connexion à la base de données en appelant la méthode statique de la classe Database.
     */
    public function __construct() {
        $this->db = \Core\Database::getConnection();
    }
}

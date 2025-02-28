<?php
namespace App\Models;

use PDO;

class BaseModel {
    protected $db;

    public function __construct() {
        $this->db = \Core\Database::getConnection();
    }
}

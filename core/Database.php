<?php
namespace Core;

use PDO;

class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../config/config.php';
            self::$connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']}",
                $config['user'],
                $config['password']
            );
        }
        return self::$connection;
    }
}

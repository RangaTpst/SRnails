<?php
namespace App\Models;

use App\Database\Database;
use PDO;

class User {
    private $id;
    private $username;
    private $email;
    private $password;
    private $registration_date;

    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    // CRUD Methods
    public function create($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, email, password, registration_date) VALUES (:username, :email, :password, NOW())";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        return $stmt->execute();
    }

    public function read($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function update($id, $username, $email, $password = null) {
        $sql = "UPDATE users SET username = :username, email = :email";
        
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $sql .= ", password = :password";
        }
        
        $sql .= " WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($password) {
            $stmt->bindParam(':password', $hashedPassword);
        }

        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

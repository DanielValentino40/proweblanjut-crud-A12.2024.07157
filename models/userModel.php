<?php
class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserByName($name) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE name = :name LIMIT 1");
        $stmt->execute([':name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registerUser($name, $email, $passw) {
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, passw) 
                                      VALUES (:name, :email, :passw)");
        return $stmt->execute([
            ':name'  => $name,
            ':email' => $email,
            ':passw' => $passw
        ]);
    }
}
?>
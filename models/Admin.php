<?php
class Admin {
    private $conn;
    private $table = "admins";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($username, $password) {
        $passwordHash = md5($password);

        $sql = "SELECT * FROM $this->table WHERE username=? AND password=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $username, $passwordHash);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}

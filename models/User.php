<?php
class User {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($data) {
        $passwordHash = md5($data['password']);

        $sql = "INSERT INTO $this->table (nik, nama, username, email, no_hp, password)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss",
            $data['nik'], $data['nama'], $data['username'],
            $data['email'], $data['no_hp'], $passwordHash
        );

        return $stmt->execute();
    }

    public function login($username, $password) {
        $passwordHash = md5($password);

        $sql = "SELECT * FROM $this->table WHERE username=? AND password=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $username, $passwordHash);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function countUsers() {
        $query = "SELECT COUNT(*) AS total FROM users";
        $result = $this->conn->query($query);
        return $result->fetch_assoc()['total'];
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

}

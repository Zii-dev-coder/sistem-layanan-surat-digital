<?php
class Permohonan {
    private $conn;
    private $table = "permohonan";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $sql = "INSERT INTO $this->table
                (penduduk_id, user_id, jenis_surat, keterangan, status, file_pdf, file_user, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "iisssss",
            $data['penduduk_id'],
            $data['user_id'],
            $data['jenis_surat'],
            $data['keterangan'],
            $data['status'],
            $data['file_pdf'],
            $data['file_user']
        );
        return $stmt->execute();
    }

    public function getById($id) {
        $sql = "SELECT p.*, u.nama, u.nik, u.alamat, u.email
                FROM $this->table p
                JOIN users u ON u.id = p.user_id
                WHERE p.id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getByUser($userId) {
        $sql = "SELECT * FROM $this->table
                WHERE user_id=?
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function updateStatus($id, $status, $filePdf = null) {
        if ($filePdf) {
            $sql = "UPDATE $this->table SET status=?, file_pdf=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $status, $filePdf, $id);
        } else {
            $sql = "UPDATE $this->table SET status=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $status, $id);
        }
        return $stmt->execute();
    }

    public function updatePDF($id, $filename) {
        $sql = "UPDATE $this->table SET file_pdf=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $filename, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getLatest($limit = 10) {
        $sql = "SELECT p.*, u.nama, u.nik
                FROM $this->table p
                JOIN users u ON u.id = p.user_id
                ORDER BY p.created_at DESC
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function countAll() {
        $sql = "SELECT COUNT(*) AS total FROM $this->table";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['total'];
    }

    public function countByStatus($status) {
        $sql = "SELECT COUNT(*) AS total FROM $this->table WHERE status=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }
}

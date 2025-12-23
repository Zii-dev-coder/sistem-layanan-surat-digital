<?php
session_start();
require_once "config.php";
require_once "models/Permohonan.php";

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak");
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID tidak valid");
}

$permohonan = new Permohonan($conn);
$data = $permohonan->getById($id);

if (!$data || empty($data['file_user'])) {
    die("File tidak ditemukan");
}

$filePath = __DIR__ . "/uploads/" . $data['file_user'];

if (!file_exists($filePath)) {
    die("File tidak ada di server");
}

header("Content-Type: application/octet-stream");
header("Content-Disposition: inline; filename=\"" . basename($filePath) . "\"");
header("Content-Length: " . filesize($filePath));

readfile($filePath);
exit;

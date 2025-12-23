<?php
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: index.php?page=login");
    exit;
}

require_once "config.php";
require_once "models/Permohonan.php";

$permohonanModel = new Permohonan($conn);
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID permohonan tidak valid.");
}

$permohonan = $permohonanModel->getById($id);
if (!$permohonan) {
    die("Permohonan tidak ditemukan.");
}

if (isset($_SESSION['user_id']) && $permohonan['user_id'] != $_SESSION['user_id']) {
    die("Akses ditolak. Ini bukan permohonan Anda.");
}

$file = $permohonan['file_user'];
$filepath = __DIR__ . '/uploads/' . $file;

if (!file_exists($filepath) || empty($file)) {
    die("File tidak tersedia.");
}

header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Content-Length: ' . filesize($filepath));
readfile($filepath);
exit;

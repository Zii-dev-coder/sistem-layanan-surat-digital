<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit;
}

require_once "config.php";
require_once "models/Permohonan.php";

$permohonanModel = new Permohonan($conn);
$userId = $_SESSION['user_id'];
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID permohonan tidak valid.");
}

$permohonan = $permohonanModel->getById($id);

if (!$permohonan) {
    die("Permohonan tidak ditemukan.");
}

if ($permohonan['user_id'] != $userId) {
    die("Akses ditolak. Ini bukan permohonan Anda.");
}

$file = $permohonan['file_pdf'];
$filepath = __DIR__ . '/uploads/' . $file;

if (!file_exists($filepath) || empty($file)) {
    die("File PDF tidak tersedia.");
}

header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Content-Length: ' . filesize($filepath));
readfile($filepath);
exit;

<?php
require_once "models/Permohonan.php";
require_once "models/User.php";

class PermohonanPdfController {
    private $conn;
    private $permohonan;
    private $user;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->permohonan = new Permohonan($conn);
        $this->user = new User($conn);
    }

    // Generate PDF
    public function generate($id) {
    $permohonan = $this->permohonan->getById($id);

    if (!$permohonan || empty($permohonan['file_user'])) {
        $_SESSION['error'] = "File dokumen user tidak ditemukan!";
        header("Location: index.php?page=admin_detail&id=$id");
        exit;
    }

    $inputPath = realpath(__DIR__ . '/../uploads/' . $permohonan['file_user']);
    if (!$inputPath) {
        $_SESSION['error'] = "Path file tidak valid!";
        header("Location: index.php?page=admin_detail&id=$id");
        exit;
    }

    $outputDir = realpath(__DIR__ . '/../uploads');

    $pdfName = pathinfo($permohonan['file_user'], PATHINFO_FILENAME) . '.pdf';
    $pdfPath = $outputDir . DIRECTORY_SEPARATOR . $pdfName;

    $soffice = '"C:\Program Files\LibreOffice\program\soffice.exe"';

    $command = $soffice . ' --headless --convert-to pdf ' .
               escapeshellarg($inputPath) .
               ' --outdir ' . escapeshellarg($outputDir);

    exec($command, $output, $returnVar);

    // DEBUG
    if ($returnVar === 0 && file_exists($pdfPath)) {
        $this->permohonan->updatePDF($id, $pdfName);
        $_SESSION['success'] = "PDF berhasil dibuat.";
    } else {
        $_SESSION['error'] = "Konversi gagal. File tidak dihasilkan LibreOffice.";
    }

    header("Location: index.php?page=admin_detail&id=$id");
    exit;
}

}

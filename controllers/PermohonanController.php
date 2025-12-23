<?php
require_once "models/Permohonan.php";
require_once "models/User.php";

class PermohonanController {
    private $conn;
    private $permohonan;
    private $user;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->permohonan = new Permohonan($conn);
        $this->user = new User($conn);
    }

    // Ambil detail
    public function detail($id) {
        $permohonan = $this->permohonan->getById($id);
        if (!$permohonan) {
            $_SESSION['error'] = "Permohonan tidak ditemukan!";
            header("Location: index.php?page=permohonan");
            exit;
        }

        $user = $this->user->getById($permohonan['user_id']);
        return ['permohonan' => $permohonan, 'user' => $user];
    }

    // Update status
    public function updateStatus($id, $status) {
        $status = in_array($status, ['approved', 'rejected']) ? $status : 'pending';
        $update = $this->permohonan->updateStatus($id, $status);

        if ($update) {
            $_SESSION['success'] = "Status permohonan berhasil diupdate ke '$status'.";
        } else {
            $_SESSION['error'] = "Gagal update status permohonan.";
        }

        header("Location: index.php?page=admin_detail&id=$id");
        exit;
    }

    // Generate PDF LibreOffice
    public function generatePDF($id) {
        $permohonan = $this->permohonan->getById($id);
        if (!$permohonan) {
            $_SESSION['error'] = "Permohonan tidak ditemukan!";
            header("Location: index.php?page=admin_detail&id=$id");
            exit;
        }

        $fileUser = $permohonan['file_user'];
        $filePath = realpath('uploads/' . $fileUser);

        if (empty($fileUser) || !file_exists($filePath)) {
            $_SESSION['error'] = "File dokumen user tidak ditemukan!";
            header("Location: index.php?page=admin_detail&id=$id");
            exit;
        }

        $allowedExtensions = ['doc','docx','odt','rtf','xls','xlsx','ods','ppt','pptx','txt'];
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions)) {
            $_SESSION['error'] = "File dokumen user tidak didukung untuk di-convert ke PDF. Gunakan: " . implode(", ", $allowedExtensions);
            header("Location: index.php?page=admin_detail&id=$id");
            exit;
        }

        $outputDir  = realpath('uploads/');
        $filename   = 'permohonan_' . $id . '.pdf';
        $outputFile = $outputDir . '/' . $filename;

        // Path LibreOffice
        $sofficePath = '"C:\Program Files\LibreOffice\program\soffice.exe"';

        $command = $sofficePath . ' --headless --convert-to pdf ' . escapeshellarg($filePath) . ' --outdir ' . escapeshellarg($outputDir);

        exec($command, $output, $returnVar);

        if ($returnVar === 0 && file_exists($outputFile)) {
            $this->permohonan->updatePDF($id, $filename);
            $_SESSION['success'] = "PDF permohonan berhasil dibuat dari dokumen user.";
        } else {
            $_SESSION['error'] = "Gagal membuat PDF dari dokumen user. Pastikan LibreOffice terinstall dan file didukung.";
        }

        header("Location: index.php?page=admin_detail&id=$id");
        exit;
    }

    public function create_action() {
        $userId = $_POST['user_id'] ?? null;
        $pendudukId = $_POST['penduduk_id'] ?? null;
        $jenisSurat = $_POST['jenis_surat'] ?? '';
        $keterangan = $_POST['keterangan'] ?? '';

        $fileUser = null;
        if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['lampiran']['name'], PATHINFO_EXTENSION);
            $fileUser = 'user_permohonan_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['lampiran']['tmp_name'], 'uploads/' . $fileUser);
        }

        $data = [
            'penduduk_id' => $pendudukId,
            'user_id' => $userId,
            'jenis_surat' => $jenisSurat,
            'keterangan' => $keterangan,
            'status' => 'pending',
            'file_pdf' => null,
            'file_user' => $fileUser
        ];

        if ($this->permohonan->create($data)) {
            $_SESSION['success'] = "Permohonan berhasil dikirim!";
        } else {
            $_SESSION['error'] = "Gagal mengirim permohonan.";
        }

        header("Location: index.php?page=user_permohonan");
        exit;
    }
}

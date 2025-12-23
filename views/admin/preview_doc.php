<?php
session_start();
require_once "models/Permohonan.php";
require_once "models/User.php";
require_once __DIR__ . '/../vendor/autoload.php'; // pastikan mpdf sudah terinstall

use Mpdf\Mpdf;

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?page=login");
    exit;
}

$permohonanModel = new Permohonan($conn);
$userModel       = new User($conn);

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php?page=permohonan");
    exit;
}

$permohonan = $permohonanModel->getById($id);
if (!$permohonan) {
    $_SESSION['error'] = "Permohonan tidak ditemukan.";
    header("Location: index.php?page=permohonan");
    exit;
}

$user = $userModel->getById($permohonan['user_id']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Permohonan - Admin</title>
    <link rel="stylesheet" href="assets/admin_detail.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="detail-page">
    <header class="detail-header">
        <div class="title-wrap">
            <button type="button" class="btn-back" onclick="history.back()" title="Kembali">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div>
                <h1>Detail Permohonan</h1>
                <p>ID #<?= htmlspecialchars($permohonan['id']) ?> • <?= htmlspecialchars($permohonan['jenis_surat']) ?></p>
            </div>
        </div>
        <span class="status-pill status-<?= htmlspecialchars($permohonan['status']) ?>">
            <i class="fas 
                <?php if ($permohonan['status'] === 'approved') echo 'fa-check-circle';
                      elseif ($permohonan['status'] === 'rejected') echo 'fa-times-circle';
                      else echo 'fa-clock'; ?>"></i>
            <?= ucfirst(htmlspecialchars($permohonan['status'])) ?>
        </span>
    </header>

    <main class="detail-grid">
        <section class="card">
            <h2><i class="fas fa-user"></i> Data Penduduk</h2>
            <div class="info-list">
                <div class="info-row">
                    <span class="label">Nama</span>
                    <span class="value"><?= htmlspecialchars($user['nama']) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">NIK</span>
                    <span class="value"><?= htmlspecialchars($user['nik']) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Alamat</span>
                    <span class="value"><?= htmlspecialchars($user['alamat']) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Email</span>
                    <span class="value"><?= htmlspecialchars($user['email']) ?></span>
                </div>
            </div>
        </section>

        <section class="card">
            <h2><i class="fas fa-file-alt"></i> Informasi Permohonan</h2>
            <div class="info-list">
                <div class="info-row">
                    <span class="label">Jenis Surat</span>
                    <span class="value"><?= htmlspecialchars($permohonan['jenis_surat']) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Dibuat</span>
                    <span class="value"><?= htmlspecialchars(date('d M Y H:i', strtotime($permohonan['created_at']))) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Keterangan</span>
                    <span class="value multiline"><?= nl2br(htmlspecialchars($permohonan['keterangan'] ?: '-')) ?></span>
                </div>

                <?php if (!empty($permohonan['file_user'])): ?>
                <div class="info-row">
                    <span class="label">Dokumen User</span>
                    <span class="value">
                        <a href="uploads/<?= htmlspecialchars($permohonan['file_user']) ?>" target="_blank" class="link-file">
                            <i class="fas fa-file-word"></i> Lihat / Download
                        </a>
                    </span>
                </div>
                <?php endif; ?>

                <?php if (!empty($permohonan['file_pdf'])): ?>
                <div class="info-row">
                    <span class="label">PDF</span>
                    <span class="value">
                        <a href="uploads/<?= htmlspecialchars($permohonan['file_pdf']) ?>" target="_blank" class="link-file">
                            <i class="fas fa-file-pdf"></i> Lihat / Download PDF
                        </a>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="card card-actions">
            <h2><i class="fas fa-tools"></i> Aksi Admin</h2>

            <form action="index.php?page=permohonan_update" method="POST" class="action-row">
                <input type="hidden" name="id" value="<?= $permohonan['id'] ?>">
                <button type="submit" name="action" value="approved" class="btn btn-success">
                    <i class="fas fa-check"></i> Setujui
                </button>
                <button type="submit" name="action" value="rejected" class="btn btn-danger">
                    <i class="fas fa-times"></i> Tolak
                </button>
            </form>

            <?php if (!empty($permohonan['file_user'])): ?>
            <form action="index.php?page=generate_pdf" method="POST" class="action-row single">
                <input type="hidden" name="id" value="<?= $permohonan['id'] ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i> Generate PDF
                </button>
            </form>
            <?php endif; ?>

            <p class="hint">Pastikan data sudah diperiksa sebelum menyetujui atau menolak permohonan.</p>
        </section>
    </main>
</div>
</body>
</html>

<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit;
}

require_once "models/Permohonan.php";
$permohonanModel = new Permohonan($conn);
$userId = $_SESSION['user_id'];
$data = $permohonanModel->getByUser($userId);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Permohonan</title>
    <link rel="stylesheet" href="assets/user_cek_status.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="page-container">
    <header class="page-header">
        <div class="header-left">
            <button type="button" class="btn-back" onclick="history.back()" title="Kembali">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div>
                <h1>Cek Status Permohonan</h1>
                <p>Lihat riwayat dan status permohonan surat Anda.</p>
            </div>
        </div>
    </header>

    <div class="card">
        <?php if ($data->num_rows == 0): ?>
            <p class="empty-text">Belum ada permohonan yang diajukan.</p>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="status-table">
                    <thead>
                        <tr>
                            <th>Jenis Surat</th>
                            <th>Tanggal Permohonan</th>
                            <th>Status</th>
                            <th>Dokumen User</th>
                            <th>PDF Kelurahan</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $data->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['jenis_surat']) ?></td>
                            <td><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
                            <td>
                                <span class="status-badge status-<?= htmlspecialchars($row['status']) ?>">
                                    <?= ucfirst(htmlspecialchars($row['status'])) ?>
                                </span>
                            </td>
                            <td class="cell-link">
                                <?php if (!empty($row['file_user'])): ?>
                                    <a href="download_file.php?id=<?= (int)$row['id'] ?>" target="_blank" class="link-icon">
                                        <i class="fas fa-file-word"></i>
                                        <span>Lihat</span>
                                    </a>
                                <?php else: ?>
                                    <span class="muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="cell-link">
                                <?php if (!empty($row['file_pdf'])): ?>
                                    <a href="download_pdf.php?id=<?= (int)$row['id'] ?>" target="_blank" class="link-icon">
                                        <i class="fas fa-file-pdf"></i>
                                        <span>Unduh</span>
                                    </a>
                                <?php else: ?>
                                    <span class="muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

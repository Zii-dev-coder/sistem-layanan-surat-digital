<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?page=login");
    exit;
}

require_once "models/User.php";
require_once "models/Permohonan.php";

$userModel = new User($conn);
$permohonanModel = new Permohonan($conn);

$totalUser       = $userModel->countUsers();
$totalPermohonan = $permohonanModel->countAll();
$pending         = $permohonanModel->countByStatus('pending');
$approved        = $permohonanModel->countByStatus('approved');
$rejected        = $permohonanModel->countByStatus('rejected');

$data = $permohonanModel->getLatest(10);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — Sistem Informasi Kelurahan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/admin.css">
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <div class="brand-icon">
                    <i class="fas fa-city"></i>
                </div>
                <div class="brand-text">
                    <h1>Dashboard Admin</h1>
                    <p>Sistem Informasi Kelurahan</p>
                </div>
            </div>
            <div class="navbar-actions">
                <span class="user-badge">
                    <i class="fas fa-user-circle"></i>
                    Admin
                </span>
                <a href="index.php?page=logout" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <!-- Hero Stats -->
        <section class="hero-stats">
            <div class="hero-left">
                <h2>
                    <i class="fas fa-chart-line"></i>
                    Ringkasan Sistem
                </h2>
                <p>Statistik terkini aktivitas sistem kelurahan</p>
                <canvas id="statsChart" class="stats-chart"></canvas>
            </div>
            <div class="hero-right">
                <div class="stat-card large">
                    <div class="stat-icon bg-blue">
                        <i class="fas fa-users"></i>
                    </div>
                    <p>Total Pengguna</p>
                    <div class="stat-number"><?= number_format($totalUser) ?></div>
                </div>
                <div class="stat-card large">
                    <div class="stat-icon bg-emerald">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <p>Disetujui</p>
                    <div class="stat-number"><?= number_format($approved) ?></div>
                </div>
            </div>
        </section>

        <!-- Stats Cards -->
        <section class="stats-grid">
            <?php
            $stats = [
                ['icon' => 'fa-file-alt', 'color' => 'indigo', 'label' => 'Total Permohonan', 'value' => $totalPermohonan],
                ['icon' => 'fa-clock', 'color' => 'amber', 'label' => 'Menunggu', 'value' => $pending],
                ['icon' => 'fa-exclamation-triangle', 'color' => 'rose', 'label' => 'Ditolak', 'value' => $rejected],
                ['icon' => 'fa-users-cog', 'color' => 'purple', 'label' => 'Aktif', 'value' => $totalUser],
                ['icon' => 'fa-chart-bar', 'color' => 'emerald', 'label' => 'Sukses', 'value' => $approved]
            ];
            
            foreach ($stats as $stat) {
            ?>
            <div class="stat-card">
                <div class="stat-icon bg-<?= $stat['color'] ?>">
                    <i class="fas <?= $stat['icon'] ?>"></i>
                </div>
                <p><?= $stat['label'] ?></p>
                <div class="stat-number"><?= number_format($stat['value']) ?></div>
            </div>
            <?php } ?>
        </section>

        <!-- Recent Requests -->
        <section class="recent-section">
            <div class="section-header">
                <div>
                    <h2>
                        <i class="fas fa-list-ul"></i>
                        Permohonan Terbaru
                    </h2>
                    <p>10 permohonan terakhir yang masuk ke sistem</p>
                </div>
                <a href="index.php?page=permohonan" class="btn-primary">
                    <i class="fas fa-arrow-right"></i>
                    Lihat Semua
                </a>
            </div>

            <div class="table-container">
                <table class="recent-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th class="hide-md">Jenis Permohonan</th>
                            <th class="hide-lg">Tanggal</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$data): ?>
                        <tr>
                            <td colspan="5" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-inbox"></i>
                                    <p>Belum ada permohonan</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($data as $p): 
                                $statusConfig = [
                                    'pending' => ['class' => 'yellow', 'icon' => 'fa-clock', 'text' => 'Pending'],
                                    'approved' => ['class' => 'emerald', 'icon' => 'fa-check-circle', 'text' => 'Disetujui'],
                                    'rejected' => ['class' => 'rose', 'icon' => 'fa-times-circle', 'text' => 'Ditolak']
                                ];
                                $status = $statusConfig[$p['status']] ?? $statusConfig['pending'];
                            ?>
                            <tr>
                                <td>
                                    <div class="user-avatar">
                                        <div class="avatar"><?= strtoupper(substr($p['nama'], 0, 2)) ?></div>
                                        <span><?= htmlspecialchars($p['nama']) ?></span>
                                    </div>
                                </td>
                                <td class="hide-md"><?= htmlspecialchars($p['jenis_surat']) ?></td>
                                <td class="hide-lg"><?= date('d M Y H:i', strtotime($p['created_at'])) ?></td>
                                <td>
                                    <span class="status-badge status-<?= $status['class'] ?>">
                                        <i class="fas <?= $status['icon'] ?>"></i>
                                        <?= $status['text'] ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <a href="index.php?page=admin_detail&id=<?= $p['id'] ?>" class="btn-detail">
                                        <i class="fas fa-eye"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        const chartData = {
            pending: <?= (int)($pending ?? 0) ?>,
            approved: <?= (int)($approved ?? 0) ?>,
            rejected: <?= (int)($rejected ?? 0) ?>
        };
    </script>
    <script src="assets/dashboard.js"></script>
</body>
</html>

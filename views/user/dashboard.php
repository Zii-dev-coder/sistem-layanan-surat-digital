<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit();
}

$userId = $_SESSION['user_id'];
$user = $_SESSION['user'] ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Warga | e-Roong</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/home.css">
    <link rel="stylesheet" href="assets/test.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

</head>
<body>

<body>
<section class="hero">
    <div class="hero-actions">
        <a href="index.php?page=logout" class="btn-logout-hero" title="Keluar">
            <span class="material-symbols-outlined">logout</span>
        </a>
    </div>

    <div class="logo-box">
        <img src="assets/logosistem.png" alt="Logo e-Roong">
    </div>

    <h1 class="title">SISTEM INFORMASI</h1>
    <h1 class="title-2">KELURAHAN E-ROONG</h1>

    <p class="subtitle">
        Layanan informasi, administrasi dan pengumuman resmi yang dapat diakses dengan cepat, mudah dan transparan.
    </p>

    <a href="#layanan-utama" class="btn-start">Mulai Layanan</a>
</section>

<section class="voting-rules-section" id="layanan-utama">
    <div class="voting-container">

        <h2 class="voting-title">Layanan Utama</h2>
        <p class="voting-desc">
            Akses berbagai layanan digital yang disediakan oleh Kelurahan
        </p>

        <div class="rules-grid">

            <a href="index.php?page=user_permohonan" class="rule-card" style="text-decoration:none; color:inherit;">
                <div class="rule-icon">
                    <span class="material-symbols-outlined" style="font-size:55px;">
                        forward_to_inbox
                    </span>
                </div>
                <p><strong>Permohonan Surat Online</strong><br>Ajukan berbagai surat kelurahan dengan mudah</p>
            </a>

            <a href="index.php?page=user_cek_status" class="rule-card" style="text-decoration:none; color:inherit;">
                <div class="rule-icon">
                    <span class="material-symbols-outlined" style="font-size:55px;">
                        search
                    </span>
                </div>
                <p><strong>Cek Status Permohonan</strong><br>Pantau proses surat yang sedang diajukan</p>   
</section>
</body>
</html>
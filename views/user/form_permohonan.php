<?php
if (!isset($_SESSION['user'])) {
    header("Location: index.php?page=login");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Surat - Sistem Informasi Kelurahan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/permohonan.css">
</head>
<style>
    .file-upload-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    .form-file {
        display: none; /* sembunyikan input file asli */
    }
    .file-upload-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        border: 2px dashed #ccc;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #f9f9f9;
    }
    .file-upload-label.active {
        border-color: #4ade80; /* hijau */
        background-color: #ecfdf5;
    }
    .file-upload-label i {
        margin-right: 10px;
    }
    .file-upload-text {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .btn-remove-file {
        background: #ef4444;
        color: #fff;
        border: none;
        padding: 2px 8px;
        margin-left: 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
    }
    .notif {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 8px;
    color: #fff;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    opacity: 0;
    animation: fadeInOut 4s forwards;
    z-index: 9999;
}

.notif.success { background-color: #4CAF50; }
.notif.error   { background-color: #F44336; }

@keyframes fadeInOut {
    0% { opacity: 0; transform: translateY(-20px); }
    10% { opacity: 1; transform: translateY(0); }
    90% { opacity: 1; transform: translateY(0); }
    100% { opacity: 0; transform: translateY(-20px); }
}

</style>
<body>
<div class="container">
    <!-- HEADER -->
    <header class="header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-file-signature"></i>
            </div>
            <div class="header-text">
                <h1>Permohonan Surat Kelurahan</h1>
                <p>Isi formulir dengan data yang lengkap dan benar</p>
            </div>
        </div>
    </header>
    
    <?php if (isset($_SESSION['success'])): ?>
<div id="notif" class="notif success">
    <i class="fas fa-check-circle"></i>
    <?= $_SESSION['success']; ?>
</div>
<?php unset($_SESSION['success']); endif; ?>

    <!-- FORM CARD -->
    <main class="form-card">
        <!-- Tombol kembali -->
        <div class="back-row">
            <button type="button" class="btn-back" onclick="history.back()">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </button>
        </div>
        <!-- Formulir Permohonan -->
        <form action="index.php?page=permohonan_action" method="POST" enctype="multipart/form-data" class="form">
            <!-- Hidden Field -->
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
            <input type="hidden" name="penduduk_id" value="<?= htmlspecialchars($user['nik']) ?>">

            <!-- Jenis Surat -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-file-alt"></i>
                    Jenis Surat <span class="required">*</span>
                </label>
                <select name="jenis_surat" class="form-select" required>
                    <option value="">-- Pilih Jenis Surat --</option>
                    <option value="Surat Keterangan Domisili">Surat Keterangan Domisili</option>
                    <option value="Surat Keterangan Usaha">Surat Keterangan Usaha</option>
                    <option value="Surat Keterangan Tidak Mampu">Surat Keterangan Tidak Mampu</option>
                    <option value="Surat Keterangan Kelahiran">Surat Keterangan Kelahiran</option>
                    <option value="Surat Keterangan Kematian">Surat Keterangan Kematian</option>
                    <option value="Surat Pengantar SKCK">Surat Pengantar SKCK</option>
                    <option value="Surat Pindah Domisili">Surat Pindah Domisili</option>
                    <option value="Surat Pengantar Nikah">Surat Pengantar Nikah</option>
                    <option value="Surat Keterangan Kehilangan">Surat Keterangan Kehilangan</option>
                </select>
            </div>

            <!-- Keterangan -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-align-left"></i>
                    Keterangan Tambahan
                </label>
                <textarea name="keterangan" class="form-textarea"
                          placeholder="Tuliskan keterangan tambahan jika diperlukan..."
                          rows="4"></textarea>
            </div>

            <!-- Lampiran -->
            <div class="form-group">
    <label class="form-label">
        <i class="fas fa-paperclip"></i>
        Lampiran Dokumen (Opsional, .docx, max 5MB)
    </label>
    <div class="file-upload-wrapper">
        <input type="file" name="lampiran" id="lampiran" class="form-file"
               accept=".docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
        <label for="lampiran" class="file-upload-label">
            <i class="fas fa-cloud-upload-alt"></i>
            <span class="file-upload-text">Pilih file</span>
            <button type="button" class="btn-remove-file" style="display:none;">Hapus</button>
        </label>
    </div>
    <p class="file-hint">
        * Opsional. Unggah dokumen pendukung seperti KTP, KK, atau dokumen lainnya bila diperlukan.
    </p>
        </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i>
                    <span>Kirim Permohonan</span>
                </button>
            </div>
        </form>
    </main>

    <aside class="info-card">
        <div class="info-content">
            <i class="fas fa-info-circle"></i>
            <h3>Informasi Penting</h3>
            <ul>
                <li><i class="fas fa-check-circle"></i><span>Pastikan data yang diisi sudah benar.</span></li>
                <li><i class="fas fa-clock"></i><span>Permohonan diproses dalam 1–3 hari kerja.</span></li>
                <li><i class="fas fa-file-pdf"></i><span>Status dapat dicek di menu “Riwayat Permohonan”.</span></li>
                <li><i class="fas fa-bell"></i><span>Anda akan mendapat notifikasi saat permohonan disetujui atau ditolak.</span></li>
            </ul>
        </div>
    </aside>
</div>
<script>
    const fileInput = document.getElementById('lampiran');
    const fileLabel = document.querySelector('.file-upload-label');
    const fileLabelText = document.querySelector('.file-upload-text');
    const btnRemove = document.querySelector('.btn-remove-file');

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            fileLabelText.textContent = fileInput.files[0].name;
            fileLabel.classList.add('active');
            btnRemove.style.display = 'inline-block';
        } else {
            fileLabelText.textContent = 'Pilih file';
            fileLabel.classList.remove('active');
            btnRemove.style.display = 'none';
        }
    });

    btnRemove.addEventListener('click', function(e) {
        e.preventDefault();
        fileInput.value = '';
        fileLabelText.textContent = 'Pilih file';
        fileLabel.classList.remove('active');
        btnRemove.style.display = 'none';
    });
</script>
</body>
</html>

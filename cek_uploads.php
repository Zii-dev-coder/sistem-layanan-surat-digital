<?php
$uploadDir = __DIR__ . '/uploads';

if (!is_dir($uploadDir)) {
    echo "Folder uploads tidak ditemukan!<br>";
} else {
    echo "Folder uploads ada.<br>";
}

if (is_writable($uploadDir)) {
    echo "Folder uploads bisa ditulis oleh PHP.<br>";
} else {
    echo "Folder uploads **tidak bisa ditulis** oleh PHP!<br>";
}

$tempFile = $uploadDir . '/test.txt';
if (file_put_contents($tempFile, "test") !== false) {
    echo "PHP berhasil menulis file di uploads.<br>";
    unlink($tempFile);
} else {
    echo "PHP gagal menulis file di uploads.<br>";
}
?>

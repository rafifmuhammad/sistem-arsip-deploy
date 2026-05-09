<?php

require_once __DIR__ . '/../../database/db.php';

if (!isset($_GET['id_versi'])) {
    die('File tidak ditemukan');
}

$id_versi = $_GET['id_versi'];
$type = $_GET['type'];

if ($type === 'surat_masuk') {
    $result = mysqli_query($conn, "SELECT file 
        FROM tb_surat_masuk_versi 
        WHERE id_versi = '$id_versi'
    ");

    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        die('Data tidak ada');
    }

    $file_path = __DIR__ . '/../../' . $data['file'];

    if (!file_exists($file_path)) {
        die('File tidak ditemukan di server');
    }
} else {
    $result = mysqli_query($conn, "SELECT file 
        FROM tb_surat_keluar_versi 
        WHERE id_versi = '$id_versi'
    ");

    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        die('Data tidak ada');
    }

    $file_path = __DIR__ . '/../../' . $data['file'];

    if (!file_exists($file_path)) {
        die('File tidak ditemukan di server');
    }
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
header('Content-Length: ' . filesize($file_path));

readfile($file_path);
exit;
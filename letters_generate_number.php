<?php
session_start();

include './functions/function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kode_surat'])) {
    $kode_surat = htmlspecialchars($_POST['kode_surat']);
    $type = $_POST['type'];

    $nomor = generateNomorSurat($kode_surat, $type);
    
    echo json_encode(['nomor_surat' => $nomor]);
}
<?php
session_start();

include '../../functions/function.php';

$id_surat_keluar = $_GET['id_surat_keluar'];

if (deleteOutgoingLetter($id_surat_keluar) > 0) {
    $_SESSION['flash'] = [
        "icon" => "success",
        "title" => "Berhasil",
        "text" => "Surat keluar berhasil dihapus"
    ];
} else {
    $_SESSION['flash'] = [
        "icon" => "alert",
        "title" => "Gagal",
        "text" => "Surat keluar gagal dihapus"
    ];
}

header("Location: /sistem-arsip/outgoing/");
exit;
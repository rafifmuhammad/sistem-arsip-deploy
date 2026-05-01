<?php
session_start();

include './functions/function.php';

$id_surat_masuk = $_GET['id_surat_masuk'];

if (deleteIncomingLetter($id_surat_masuk) > 0) {
    $_SESSION['flash'] = [
        "icon" => "success",
        "title" => "Berhasil",
        "text" => "Surat masuk berhasil dihapus"
    ];
} else {
    $_SESSION['flash'] = [
        "icon" => "alert",
        "title" => "Gagal",
        "text" => "Surat masuk gagal dihapus"
    ];
}

header("Location: letters_incoming.php");
exit;
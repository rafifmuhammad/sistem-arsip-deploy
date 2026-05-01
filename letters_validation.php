<?php
session_start();

include './functions/function.php';

$type = $_GET['type'];

if ($type === 'surat_masuk') {
    $id_surat = $_GET['id_surat_masuk'];

    if (validateLetter($id_surat, $type) > 0) {
        $_SESSION['flash'] = [
            "icon" => "success",
            "title" => "Berhasil Divalidasi",
            "text" => "Surat masuk berhasil divalidasi",
        ];

        header("Location: letters_incoming.php");
        exit;
    } else {
        $_SESSION['flash'] = [
            "icon" => "error",
            "title" => "Gagal Divalidasi",
            "text" => "Surat masuk gagal divalidasi",
        ];

        header("Location: letters_incoming.php");
        exit;
    }
} else {
    $id_surat = $_GET['id_surat_keluar'];

    if (validateLetter($id_surat, $type) > 0) {
        $_SESSION['flash'] = [
            "icon" => "success",
            "title" => "Berhasil Divalidasi",
            "text" => "Surat keluar berhasil divalidasi",
        ];

        header("Location: letters_outgoing.php");
        exit;
    } else {
        $_SESSION['flash'] = [
            "icon" => "error",
            "title" => "Gagal Divalidasi",
            "text" => "Surat keluar gagal divalidasi",
        ];

        header("Location: letters_outgoing.php");
        exit;
    }
}
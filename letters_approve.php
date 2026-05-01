<?php 
session_start();

include './functions/function.php';

$type = $_GET['type'];
$id_surat = '';

if ($type === 'surat_masuk') {
    $id_surat = $_GET['id_surat_masuk'];

    if (approveLetter($id_surat, $type) > 0) {
        $_SESSION["flash"] = [
            "icon" => "success",
            "title" => "Berhasil",
            "text" => "Surat berhasil diarsipkan",
        ];
    } else {
        $_SESSION["flash"] = [
            "icon" => "error",
            "title" => "Gagal",
            "text" => "Surat gagal diarsipkan",
        ];
    }

    header("Location: letters_incoming.php");
    exit;
} else {
    $id_surat = $_GET['id_surat_keluar'];

    if (approveLetter($id_surat, $type) > 0) {
        $_SESSION["flash"] = [
            "icon" => "success",
            "title" => "Berhasil",
            "text" => "Surat berhasil diarsipkan",
        ];
    } else {
        $_SESSION["flash"] = [
            "icon" => "error",
            "title" => "Gagal",
            "text" => "Surat gagal diarsipkan",
        ];
    }

    header("Location: letters_outgoing.php");
    exit;
}


?>
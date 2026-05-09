<?php
session_start();

include '../../functions/function.php';

$id_kategori = htmlspecialchars($_GET['id_kategori']);

if (deleteCategory($id_kategori) > 0) {
    $_SESSION['flash'] = [
        "icon" => "success",
        "title" => "Berhasil",
        "text" => "Kategori berhasil dihapus"
    ];
} else {
    $_SESSION['flash'] = [
        "icon" => "error",
        "title" => "Gagal",
        "text" => "Kategori gagal dihapus"
    ];
}

header("Location: /sistem-arsip/categories/");
exit;
?>
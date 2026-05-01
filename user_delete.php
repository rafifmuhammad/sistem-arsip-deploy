<?php
session_start();
require 'functions/function.php';

$id_user = htmlspecialchars($_GET["id_user"]);

if (deleteUser($id_user) > 0) {
    $_SESSION["flash"] = [
        "icon" => "success",
        "title" => "Berhasil",
        "text" => "Data pengguna berhasil dihapus."
    ];
} else {
    $_SESSION["flash"] = [
        "icon" => "error",
        "title" => "Gagal",
        "text" => "Data pengguna gagal dihapus."
    ];
}

header("Location: user_management.php");
exit;



<?php
session_start();

require '../../functions/function.php';

$type = $_GET['type'];

if ($type === 'surat_masuk') {
    if (!isset($_GET['id_surat_masuk']) || !isset($_GET['id_versi'])) {
        header("Location: /sistem-arsip/incoming/");
        exit;
    }

    $id_surat_masuk = $_GET['id_surat_masuk'];
    $id_versi = $_GET['id_versi'];

    $valid = query("
        SELECT id_versi 
        FROM tb_surat_masuk_versi
        WHERE id_versi = '$id_versi'
        AND id_surat_masuk = '$id_surat_masuk'
        LIMIT 1
    ");

    if (!$valid) {
        $_SESSION["flash"] = [
            "icon" => "error",
            "title" => "Tidak valid",
            "text" => "Versi tidak ditemukan."
        ];
        header("Location: /sistem-arsip/incoming/");
        exit;
    }

    // terapkan file
    $result = applyIncomingLetterVersion($id_surat_masuk, $id_versi);

    if ($result > 0) {
        $_SESSION["flash"] = [
            "icon" => "success",
            "title" => "Berhasil",
            "text" => "Versi berhasil diterapkan."
        ];
    } else {
        $_SESSION["flash"] = [
            "icon" => "error",
            "title" => "Gagal",
            "text" => "Gagal menerapkan versi."
        ];
    }

    header("Location: /sistem-arsip/incoming/$id_surat_masuk/edit/");
    exit;
} else {
    if (!isset($_GET['id_surat_keluar']) || !isset($_GET['id_versi'])) {
        header("Location: /sistem-arsip/outgoing/");
        exit;
    }

    $id_surat_keluar = $_GET['id_surat_keluar'];
    $id_versi = $_GET['id_versi'];

    $valid = query("
        SELECT id_versi 
        FROM tb_surat_keluar_versi
        WHERE id_versi = '$id_versi'
        AND id_surat_keluar = '$id_surat_keluar'
        LIMIT 1
    ");

    if (!$valid) {
        $_SESSION["flash"] = [
            "icon" => "error",
            "title" => "Tidak valid",
            "text" => "Versi tidak ditemukan."
        ];
        header("Location: /sistem-arsip/outgoing/");
        exit;
    }

    // terapkan file
    $result = applyOutgoingLetterVersion($id_surat_keluar, $id_versi);

    if ($result > 0) {
        $_SESSION["flash"] = [
            "icon" => "success",
            "title" => "Berhasil",
            "text" => "Versi berhasil diterapkan."
        ];
    } else {
        $_SESSION["flash"] = [
            "icon" => "error",
            "title" => "Gagal",
            "text" => "Gagal menerapkan versi."
        ];
    }

    header("Location: /sistem-arsip/outgoing/$id_surat_keluar/edit/");
    exit;
}


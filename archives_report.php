<?php
require 'vendor/autoload.php';
require './functions/function.php'; 

use Dompdf\Dompdf;

$tanggal_awal  = $_GET['tanggal_awal'] ?? date('Y-01-01');
$tanggal_akhir = $_GET['tanggal_akhir'] ?? date('Y-m-d');

// Surat masuk
$surat_masuk = query("SELECT * FROM tb_surat_masuk
    WHERE tanggal_terima BETWEEN '$tanggal_awal' AND '$tanggal_akhir' 
    AND status = 'arsip'
");

// Surat keluar
$surat_keluar = query("SELECT sk.*, k.kode_kategori 
    FROM tb_surat_keluar sk
    JOIN tb_kategori k ON sk.id_kategori = k.id_kategori
    WHERE sk.tanggal_keluar BETWEEN '$tanggal_awal' AND '$tanggal_akhir' 
    AND status = 'arsip'
");

ob_start();
include 'archives_view.php';
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("laporan_arsip.pdf", ["Attachment" => false]);
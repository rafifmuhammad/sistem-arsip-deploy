<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
  header("Location: /sistem-arsip/auth");
  exit;
}
?>
<style>
body {
    font-family: Arial, sans-serif;
    font-size: 12px;
}

h3, h4 {
    text-align: center;
    margin: 5px;
}

table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 10px;
}

th, td {
    border: 1px solid black;
    padding: 5px;
    text-align: center;
}

th {
    background-color: #eee;
}

.keterangan td {
    border: none;
    text-align: left;
    padding: 3px;
}

.header {
    width: 100%;
    border-bottom: 3px solid black;
    padding-bottom: 10px;
    margin-bottom: 10px;
}

.header-table {
    width: 100%;
}

.header-table td {
    border: none;
    vertical-align: middle;
}

.logo {
    width: 10px;
}

.header-text {
    text-align: center;
    line-height: 1.3;
}

.header-text .top {
    font-size: 14px;
    font-weight: bold;
}

.header-text .mid {
    font-size: 16px;
    font-weight: bold;
}

.header-text .bottom {
    font-size: 12px;
}
</style>

<?php
$tanggal_awal = $tanggal_awal ?? ($_GET['tanggal_awal'] ?? date('Y-01-01'));
$tanggal_akhir = $tanggal_akhir ?? ($_GET['tanggal_akhir'] ?? date('Y-m-d'));

$path = __DIR__ . '/../../assets/images/logo-kantor-preview.png';

$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>

<div class="header">
    <table class="header-table">
        <tr>
            <!-- LOGO -->
            <td style="width: 40px;">
                <img src="<?= $base64 ?>" width="140">
            </td>

            <!-- TEXT -->
            <td class="header-text">
                <div class="top">PEMERINTAH KABUPATEN KERINCI</div>
                <div class="top">KECAMATAN GUNUNG KERINCI</div>
                <div class="mid">KANTOR KEPALA DESA SUNGAI GELAMPEH</div>
                <div class="bottom">
                    RT. 02 Sungai Gelampeh, Kecamatan Gunung Kerinci<br>
                    Kabupaten Kerinci, Jambi (37162)
                </div>
            </td>
        </tr>
    </table>
</div>

<h3>BUKU ARSIP SURAT</h3>
<h4>
    Periode <?= date('d-m-Y', strtotime($tanggal_awal)) ?> s/d 
    <?= date('d-m-Y', strtotime($tanggal_akhir)) ?>
</h4>

<!-- surat masuk -->
<h4 style="text-align:left;">A. SURAT-SURAT MASUK</h4>

<table>
<tr>
    <th>No</th>
    <th>Nomor Agenda</th>
    <th>Sumber Surat</th>
    <th>Tanggal Surat</th>
    <th>Nomor Surat</th>
    <th>Perihal</th>
    <th>Keterangan</th>
</tr>

<?php if (!empty($surat_masuk)) : ?>
    <?php $no=1; foreach($surat_masuk as $row): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= sprintf('%03d', $row['nomor_agenda']) ?></td>
        <td><?= $row['sumber_surat'] ?></td>
        <td><?= $row['tanggal_terima'] ?></td>
        <td><?= $row['nomor_surat'] ?></td>
        <td><?= $row['perihal_surat'] ?></td>
        <td><?= $row['keterangan_surat'] ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7" style="text-align:center;">Tidak ada data</td>
    </tr>
<?php endif; ?>
</table>

<br>

<!-- surat keluar -->
<h4 style="text-align:left;">B. SURAT-SURAT KELUAR</h4>

<table>
<tr>
    <th>No</th>
    <th>Nomor Agenda</th>
    <th>Tanggal Surat</th>
    <th>Nomor Surat</th>
    <th>Perihal</th>
    <th>Keterangan</th>
    <th>Kode Arsip</th>
</tr>

<?php if (!empty($surat_keluar)) : ?>
    <?php $no=1; foreach($surat_keluar as $row): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= sprintf('%03d', $row['nomor_agenda']) ?></td>
        <td><?= $row['tanggal_keluar'] ?></td>
        <td><?= $row['nomor_surat'] ?></td>
        <td><?= $row['perihal_surat'] ?></td>
        <td><?= $row['keterangan_surat'] ?></td>
        <td><?= $row['kode_kategori'] ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7" style="text-align:center;">Tidak ada data</td>
    </tr>
<?php endif; ?>
</table>

<table>
<br>

<h4 style="text-align:left;">Keterangan Kolom:</h4>

<table class="keterangan">
<tr>
    <td style="border:none;">(1) No</td>
    <td style="border:none;">: Nomor urut data surat</td>
</tr>
<tr>
    <td style="border:none;">(2) Nomor Agenda</td>
    <td style="border:none;">: Nomor pencatatan surat dalam sistem</td>
</tr>
<tr>
    <td style="border:none;">(3) Sumber Surat / Tujuan</td>
    <td style="border:none;">: Asal surat (masuk) atau tujuan surat (keluar)</td>
</tr>
<tr>
    <td style="border:none;">(4) Tanggal Surat</td>
    <td style="border:none;">: Tanggal surat diterima atau dikirim</td>
</tr>
<tr>
    <td style="border:none;">(5) Nomor Surat</td>
    <td style="border:none;">: Nomor resmi surat</td>
</tr>
<tr>
    <td style="border:none;">(6) Perihal</td>
    <td style="border:none;">: Isi atau pokok pembahasan surat</td>
</tr>
<tr>
    <td style="border:none;">(7) Keterangan / Kode Arsip</td>
    <td style="border:none;">
        : Keterangan tambahan surat atau kode klasifikasi arsip 
        (contoh: ADM, UND, SK)
    </td>
</tr>
</table>

<?php
$conn = mysqli_connect('localhost', 'root', '', 'db_arsip');
mysqli_set_charset($conn, 'utf8mb4');

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

echo "=== STRUKTUR tb_surat_keluar ===\n";
$result = mysqli_query($conn, 'DESCRIBE tb_surat_keluar');
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['Field'] . ' | Type: ' . $row['Type'] . ' | Null: ' . $row['Null'] . ' | Key: ' . $row['Key'] . ' | Default: ' . $row['Default'] . "\n";
    }
} else {
    echo 'Error: ' . mysqli_error($conn) . "\n";
}

echo "\n=== STRUKTUR tb_surat_keluar_versi ===\n";
$result = mysqli_query($conn, 'DESCRIBE tb_surat_keluar_versi');
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['Field'] . ' | Type: ' . $row['Type'] . ' | Null: ' . $row['Null'] . ' | Key: ' . $row['Key'] . ' | Default: ' . $row['Default'] . "\n";
    }
} else {
    echo 'Error: ' . mysqli_error($conn) . "\n";
}

mysqli_close($conn);

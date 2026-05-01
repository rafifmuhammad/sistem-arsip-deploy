<?php

require_once __DIR__ . '/../database/db.php';

function query($query)
{
    global $conn;

    $result = mysqli_query($conn, $query);

    if ($result === false) {
        return [];
    }

    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// function untuk pengguna
function addUser($data) {
    global $conn;

    $id_user = uniqid();
    $username = htmlspecialchars($data['username']);
    $nama = htmlspecialchars($data['nama']);
    $password = htmlspecialchars($data['password']);
    $role = htmlspecialchars($data['role']);
    $created_at = date('Y-m-d');

    $query = "INSERT INTO 
        tb_user(id_user, username, nama, password, role, created_at) 
        VALUES ('$id_user', '$username', '$nama', '$password', '$role', '$created_at')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function deleteUser($id_user)
{
    global $conn;
    mysqli_query($conn, "DELETE FROM tb_user WHERE id_user = '$id_user'");
    
    return mysqli_affected_rows($conn);
}

// function untuk surat masuk
function addIncomingLetter($data) {
    global $conn;

    $id_surat_masuk = uniqid();
    $id_user = "69dc7e4c19f0e";
    $nomor_surat = htmlspecialchars($data['nomor_surat']);
    $nomor_agenda = query("SELECT COUNT(*) + 1 as nomor
                        FROM tb_surat_masuk
                        WHERE MONTH(tanggal_terima) = MONTH(CURDATE())
                        AND YEAR(tanggal_terima) = YEAR(CURDATE());")[0]['nomor'];
    $sumber_surat = htmlspecialchars($data['sumber_surat']);
    $perihal_surat = htmlspecialchars($data['perihal_surat']);;
    $keterangan = htmlspecialchars($data['keterangan']);
    $tanggal_terima = date('Y-m-d');
    $status = 'draft';
    $created_at = date('Y-m-d H:i:s');
    
    // Handle file upload
    $file_name = '';
    $file_hash = '';
    $file_path = '';
    
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_original_name = $_FILES['file']['name'];
        $file_extension = pathinfo($file_original_name, PATHINFO_EXTENSION);
        
        // Validasi format file
        $allowed_extensions = ['pdf', 'docx', 'doc'];
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            return false;
        }
        
        // Generate unique filename
        $file_name = $id_surat_masuk . '_v1_' . pathinfo($file_original_name, PATHINFO_FILENAME) . '_' . time() . '.' . $file_extension;
        
        // Generate SHA256 hash dari file
        $file_hash = hash_file('sha256', $file_tmp);

        // cek duplikasi global
        $exist = isFileExistByHash($file_hash, 'surat_masuk');

        if ($exist) {
            return -1;
        }
        
        // Upload file ke folder documents
        $upload_path = __DIR__ . '/../documents/incoming/';

        // Create directory jika belum ada
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        
        if (!move_uploaded_file($file_tmp, $upload_path . $file_name)) {
            return false;
        }

        $file_path = 'documents/incoming/' . $file_name;
    } else {
        // Jika tidak ada file, return false
        return false;
    }

    // Simpan data surat ke tabel utama (tb_surat_masuk)
    $query = "INSERT INTO 
        tb_surat_masuk(id_surat_masuk, id_user, nomor_surat, nomor_agenda, sumber_surat, perihal_surat, keterangan_surat, tanggal_terima, status, alasan) 
        VALUES ('$id_surat_masuk', '$id_user', '$nomor_surat', '$nomor_agenda', '$sumber_surat', '$perihal_surat', '$keterangan', '$tanggal_terima', '$status', null)";

    if (!mysqli_query($conn, $query)) {
        error_log("Error INSERT tb_surat_masuk: " . mysqli_error($conn));
        return false;
    }

    $id_versi = uniqid();
    $versi = query(" SELECT COALESCE(MAX(versi), 0) + 1 as versi
        FROM tb_surat_masuk_versi
        WHERE id_surat_masuk = '$id_surat_masuk'
    ")[0]['versi'];
    
    $stmt = mysqli_prepare($conn, "INSERT INTO tb_surat_masuk_versi(id_surat_masuk, id_versi, id_user, versi, file, file_hash, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Bind parameter tanpa binary data dulu
    mysqli_stmt_bind_param($stmt, "sssisss", $id_surat_masuk, $id_versi, $id_user, $versi, $file_path, $file_hash, $created_at);
    
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Error INSERT tb_surat_masuk_versi: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return false;
    }
    
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    // Buat versi file yang aktif terbaru
    mysqli_query($conn, "UPDATE tb_surat_masuk 
        SET id_versi_aktif = '$id_versi' 
        WHERE id_surat_masuk = '$id_surat_masuk'
    ");

    return $affected_rows;
}

function addNewIncomingLetterVersion($id_surat_masuk, $file, $id_user) {
    global $conn;

    if (!isset($file) || $file['error'] === 4) {
        return -1; 
    }

    if ($file['error'] !== 0) {
        return 0; 
    }

    $file_tmp = $file['tmp_name'];
    $file_original_name = $file['name'];
    $file_extension = pathinfo($file_original_name, PATHINFO_EXTENSION);

    // Ekstensi yang diperbolehkan
    $allowed = ['pdf', 'doc', 'docx'];
    if (!in_array(strtolower($file_extension), $allowed)) {
        return 0;
    }

    // generate hash
    $new_hash = hash_file('sha256', $file_tmp);

    // ambil hash versi yang aktif
    $active = query("SELECT v.file_hash
        FROM tb_surat_masuk s
        JOIN tb_surat_masuk_versi v 
            ON s.id_versi_aktif = v.id_versi
        WHERE s.id_surat_masuk = '$id_surat_masuk'
        LIMIT 1
    ");

    if ($active && $active[0]['file_hash'] === $new_hash) {
        return -1;
    }

    // ambil nomor versi berikutnya
    $versi = query("SELECT COALESCE(MAX(versi), 0) + 1 as versi
        FROM tb_surat_masuk_versi
        WHERE id_surat_masuk = '$id_surat_masuk'
    ")[0]['versi'];

    $id_versi = uniqid();

    // nama file
    $new_file_name = $id_surat_masuk . '_v' . $versi . '_' . pathinfo($file_original_name, PATHINFO_FILENAME) . '_' . time() . '.' . $file_extension;;

    $upload_path = __DIR__ . '/../documents/incoming/';

    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0755, true);
    }

    if (!move_uploaded_file($file_tmp, $upload_path . $new_file_name)) {
        return 0;
    }

    $file_path = 'documents/incoming/' . $new_file_name;
    $created_at = date('Y-m-d H:i:s');

    // insert versi baru
    mysqli_query($conn, " INSERT INTO tb_surat_masuk_versi
        (id_surat_masuk, id_versi, id_user, versi, file, file_hash, created_at)
        VALUES ('$id_surat_masuk', '$id_versi', '$id_user', '$versi', '$file_path', '$new_hash', '$created_at')
    ");

    // set versi aktif
    mysqli_query($conn, "
        UPDATE tb_surat_masuk
        SET id_versi_aktif = '$id_versi'
        WHERE id_surat_masuk = '$id_surat_masuk'
    ");

    return mysqli_affected_rows($conn);
}

function updateIncomingLetter($id_surat_masuk, $data) {
    global $conn;

    $nomor_surat   = htmlspecialchars($data['nomor_surat']);
    $sumber_surat  = htmlspecialchars($data['sumber_surat']);
    $perihal       = htmlspecialchars($data['perihal_surat']);
    $keterangan    = htmlspecialchars($data['keterangan']);

    mysqli_query($conn, "UPDATE tb_surat_masuk
        SET nomor_surat = '$nomor_surat',
            sumber_surat = '$sumber_surat',
            perihal_surat = '$perihal',
            keterangan_surat = '$keterangan'
        WHERE id_surat_masuk = '$id_surat_masuk'
    ");

    return mysqli_affected_rows($conn);
}

// update hanya versi yang tersedia di tabel / ganti versi
function applyIncomingLetterVersion($id_surat_masuk, $id_versi) {
    global $conn;

    mysqli_query($conn, "UPDATE tb_surat_masuk
        SET id_versi_aktif = '$id_versi'
        WHERE id_surat_masuk = '$id_surat_masuk'
    ");

    return mysqli_affected_rows($conn);
}

function deleteIncomingLetter($id_surat_masuk) {
    global $conn;

    mysqli_query($conn, "DELETE FROM tb_surat_masuk WHERE id_surat_masuk = '$id_surat_masuk'");

    return mysqli_affected_rows($conn);
}

// function surat keluar
function addOutgoingLetter($data) {
    global $conn;

    $id_surat_keluar = uniqid();
    $id_user = "69dc7e4c19f0e";
    $kode_kategori = htmlspecialchars($data['kode_surat']);
    $id_kategori = query("SELECT id_kategori 
        FROM tb_kategori 
        WHERE kode_kategori = '$kode_kategori'
        LIMIT 1
    ")[0]['id_kategori'];
    $nomor_surat = htmlspecialchars($data['nomor_surat']);
    $nomor_agenda = query("SELECT COUNT(*) + 1 as nomor
                        FROM tb_surat_keluar
                        WHERE MONTH(tanggal_keluar) = MONTH(CURDATE())
                        AND YEAR(tanggal_keluar) = YEAR(CURDATE());")[0]['nomor'];
    $perihal_surat = htmlspecialchars($data['perihal_surat']);;
    $keterangan = htmlspecialchars($data['keterangan']);
    $tanggal_keluar = date('Y-m-d');
    $status = 'draft';
    $created_at = date('Y-m-d H:i:s');
    
    // Handle file upload
    $file_name = '';
    $file_hash = '';
    $file_path = '';
    
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_original_name = $_FILES['file']['name'];
        $file_extension = pathinfo($file_original_name, PATHINFO_EXTENSION);
        
        // Validasi format file
        $allowed_extensions = ['pdf', 'docx', 'doc'];
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            return false;
        }
        
        // Generate unique filename
        $file_name = $id_surat_keluar . '_v1_' . pathinfo($file_original_name, PATHINFO_FILENAME) . '_' . time() . '.' . $file_extension;
        
        // Generate SHA256 hash dari file
        $file_hash = hash_file('sha256', $file_tmp);

        // cek duplikasi global
        $exist = isFileExistByHash($file_hash, 'surat_keluar');

        if ($exist) {
            return -1;
        }
        
        // Upload file ke folder documents
        $upload_path = __DIR__ . '/../documents/outgoing/';

        // Create directory jika belum ada
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        
        if (!move_uploaded_file($file_tmp, $upload_path . $file_name)) {
            return false;
        }

        $file_path = 'documents/outgoing/' . $file_name;
    } else {
        return false;
    }

    // Simpan data surat ke tb_surat_keluar
    $query = "INSERT INTO 
        tb_surat_keluar(id_surat_keluar, id_user, id_kategori, nomor_surat, nomor_agenda, perihal_surat, keterangan_surat, tanggal_keluar, status, alasan) 
        VALUES ('$id_surat_keluar', '$id_user', '$id_kategori', '$nomor_surat', '$nomor_agenda', '$perihal_surat', '$keterangan', '$tanggal_keluar', '$status', null)";

    if (!mysqli_query($conn, $query)) {
        error_log("Error INSERT tb_surat_keluar: " . mysqli_error($conn));
        return false;
    }

    $id_versi = uniqid();
    $versi = query(" SELECT COALESCE(MAX(versi), 0) + 1 as versi
        FROM tb_surat_keluar_versi
        WHERE id_surat_keluar = '$id_surat_keluar'
    ")[0]['versi'];
    
    $stmt = mysqli_prepare($conn, "INSERT INTO tb_surat_keluar_versi(id_surat_keluar, id_versi, id_user, versi, file, file_hash, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Bind parameter tanpa binary data dulu
    mysqli_stmt_bind_param($stmt, "sssisss", $id_surat_keluar, $id_versi, $id_user, $versi, $file_path, $file_hash, $created_at);
    
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Error INSERT tb_surat_keluar_versi: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return false;
    }
    
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    // Buat versi file yang aktif terbaru
    mysqli_query($conn, "UPDATE tb_surat_keluar 
        SET id_versi_aktif = '$id_versi' 
        WHERE id_surat_keluar = '$id_surat_keluar'
    ");

    return $affected_rows;
}

function addNewOutgoingLetterVersion($id_surat_keluar, $file, $id_user) {
    global $conn;

    if (!isset($file) || $file['error'] === 4) {
        return -1; 
    }

    if ($file['error'] !== 0) {
        return 0; 
    }

    $file_tmp = $file['tmp_name'];
    $file_original_name = $file['name'];
    $file_extension = pathinfo($file_original_name, PATHINFO_EXTENSION);

    // Ekstensi yang diperbolehkan
    $allowed = ['pdf', 'doc', 'docx'];
    if (!in_array(strtolower($file_extension), $allowed)) {
        return 0;
    }

    // generate hash
    $new_hash = hash_file('sha256', $file_tmp);

    // ambil hash versi yang aktif
    $active = query("SELECT v.file_hash
        FROM tb_surat_keluar s
        JOIN tb_surat_keluar_versi v 
            ON s.id_versi_aktif = v.id_versi
        WHERE s.id_surat_keluar = '$id_surat_keluar'
        LIMIT 1
    ");

    if ($active && $active[0]['file_hash'] === $new_hash) {
        return -1;
    }

    // ambil nomor versi berikutnya
    $versi = query("SELECT COALESCE(MAX(versi), 0) + 1 as versi
        FROM tb_surat_keluar_versi
        WHERE id_surat_keluar = '$id_surat_keluar'
    ")[0]['versi'];

    $id_versi = uniqid();

    // nama file
    $new_file_name = $id_surat_keluar . '_v' . $versi . '_' . pathinfo($file_original_name, PATHINFO_FILENAME) . '_' . time() . '.' . $file_extension;;

    $upload_path = __DIR__ . '/../documents/outgoing/';

    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0755, true);
    }

    if (!move_uploaded_file($file_tmp, $upload_path . $new_file_name)) {
        return 0;
    }

    $file_path = 'documents/outgoing/' . $new_file_name;
    $created_at = date('Y-m-d H:i:s');

    // insert versi baru
    mysqli_query($conn, " INSERT INTO tb_surat_keluar_versi
        (id_surat_keluar, id_versi, id_user, versi, file, file_hash, created_at)
        VALUES ('$id_surat_keluar', '$id_versi', '$id_user', '$versi', '$file_path', '$new_hash', '$created_at')
    ");

    // set versi aktif
    mysqli_query($conn, "
        UPDATE tb_surat_keluar
        SET id_versi_aktif = '$id_versi'
        WHERE id_surat_keluar = '$id_surat_keluar'
    ");

    return mysqli_affected_rows($conn);
}

function updateOutgoingLetter($id_surat_keluar, $data) {
    global $conn;

    $kode_kategori = htmlspecialchars($data['kode_surat']);
    $id_kategori = query("SELECT id_kategori 
        FROM tb_kategori 
        WHERE kode_kategori = '$kode_kategori'
        LIMIT 1
    ")[0]['id_kategori'];
    $nomor_surat   = htmlspecialchars($data['nomor_surat']);
    $perihal       = htmlspecialchars($data['perihal_surat']);
    $keterangan    = htmlspecialchars($data['keterangan_surat']);

    mysqli_query($conn, "UPDATE tb_surat_keluar
        SET nomor_surat = '$nomor_surat',
            id_kategori = '$id_kategori',
            perihal_surat = '$perihal',
            keterangan_surat = '$keterangan'
        WHERE id_surat_keluar = '$id_surat_keluar'
    ");

    return mysqli_affected_rows($conn);
}

// update hanya versi yang tersedia di tabel / ganti versi
function applyOutgoingLetterVersion($id_surat_keluar, $id_versi) {
    global $conn;

    mysqli_query($conn, "UPDATE tb_surat_keluar
        SET id_versi_aktif = '$id_versi'
        WHERE id_surat_keluar = '$id_surat_keluar'
    ");

    return mysqli_affected_rows($conn);
}

// delete surat keluar
function deleteOutgoingLetter($id) {
    global $conn;

    mysqli_query($conn, "DELETE FROM tb_surat_keluar
        WHERE id_surat_keluar = '$id'
    ");

    return mysqli_affected_rows($conn);
}

// Validasi surat
function validateLetter($id_surat, $type) {
    global $conn;

    if ($type === 'surat_masuk') {
        $query = "UPDATE tb_surat_masuk
        SET status = 'validasi', alasan = null
        WHERE id_surat_masuk = '$id_surat'";

        mysqli_query($conn, $query);
    } else {
        $query = "UPDATE tb_surat_keluar
        SET status = 'validasi', alasan = null
        WHERE id_surat_keluar = '$id_surat'";

        mysqli_query($conn, $query);
    }

    return mysqli_affected_rows($conn);
}

function rejectLetter($data) {
    global $conn;

    $id_surat = $data['id_surat'];
    $type = $data['type'];
    $alasan = $data['alasan_select'];

    if ($type === 'surat_masuk') {
        if ($alasan === 'lainnya') {
            $alasan = $_POST['alasan_lainnya'];

            $query = "UPDATE tb_surat_masuk
            SET status = 'draft', alasan = '$alasan'
            WHERE id_surat_masuk = '$id_surat'";
        } else {
            $query = "UPDATE tb_surat_masuk
            SET status = 'draft', alasan = '$alasan' 
            WHERE id_surat_masuk = '$id_surat'";
        }

        mysqli_query($conn, $query);
    } else {
        if ($alasan === 'lainnya') {
            $alasan = $_POST['alasan_lainnya'];

            $query = "UPDATE tb_surat_keluar
            SET status = 'draft', alasan = '$alasan'
            WHERE id_surat_keluar = '$id_surat'";
        } else {
            $query = "UPDATE tb_surat_keluar
            SET status = 'draft', alasan = '$alasan' 
            WHERE id_surat_keluar = '$id_surat'";
        }

        mysqli_query($conn, $query);
    }

    return mysqli_affected_rows($conn);
}

// Arsipkan surat
function approveLetter($id_surat, $type) {
    global $conn;

    if ($type === 'surat_masuk') {
        $query = "UPDATE tb_surat_masuk 
            SET status = 'arsip', alasan = null
            WHERE id_surat_masuk = '$id_surat'";
    } else {
        $query = "UPDATE tb_surat_keluar 
            SET status = 'arsip', alasan = null
            WHERE id_surat_keluar = '$id_surat'";
    }

    // query update arsip
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// Cek jika hash tersedia
function isFileExistByHash($file_hash, $type = 'surat_masuk') {
    if ($type === 'surat_masuk') {
        $result = query("SELECT id_surat_masuk 
            FROM tb_surat_masuk_versi
            WHERE file_hash = '$file_hash'
            LIMIT 1
        ");
    } else {
        $result = query("SELECT id_surat_keluar 
            FROM tb_surat_keluar_versi
            WHERE file_hash = '$file_hash'
            LIMIT 1
        ");
    }

    return !empty($result);
}

// function generate nomor surat
function generateNomorSurat($kode_surat, $type) {        
    $bulan_angka = date('m');
    $tahun = date('Y');

    $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    $bulan = $romawi[(int)$bulan_angka];

    // tentukan tabel berdasarkan type
    $table = ($type === 'surat_masuk') ? 'tb_surat_masuk' : 'tb_surat_keluar';

    $query = "SELECT COALESCE(MAX(nomor_agenda), 0) + 1 as nomor_urut FROM $table";
    $result = query($query);
    $nomor_urut = $result[0]['nomor_urut'];

    $nomor_urut_formatted = sprintf('%03d', $nomor_urut);

    return $nomor_urut_formatted . '/' . $kode_surat . '-DS/' . $bulan . '/' . $tahun;
}

// function handle kategori
function addCategory($data) {
    global $conn;

    $id_kategori = uniqid();
    $kode_kategori = htmlspecialchars($data['kode_kategori']);
    $nama_kategori = htmlspecialchars($data['nama_kategori']);
    $created_at = date('Y-m-d');

    $query = "INSERT INTO tb_kategori
        (id_kategori, kode_kategori, nama_kategori, created_at) VALUES
        ('$id_kategori', '$kode_kategori', '$nama_kategori', '$created_at')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function deleteCategory($id) {
    global $conn;

    mysqli_query($conn, "DELETE FROM tb_kategori WHERE id_kategori = '$id'");

    return mysqli_affected_rows($conn);
}

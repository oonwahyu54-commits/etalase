<?php

// =========================
// SESSION
// =========================
session_start();

// =========================
// ERROR REPORTING
// =========================
error_reporting(E_ALL);
ini_set('display_errors', 1);

// =========================
// KONEKSI DATABASE
// =========================
include_once __DIR__ . '/../koneksi.php';

// =========================
// VALIDASI LOGIN ADMIN
// =========================
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    strtolower($_SESSION['role']) !== 'admin'
) {

    header("Location: ../login.php");
    exit;
}

// =========================
// VALIDASI ID
// =========================
if (
    !isset($_POST['id']) ||
    !is_numeric($_POST['id'])
) {

    header("Location: produk-list.php");
    exit;
}

// =========================
// AMBIL DATA FORM
// =========================
$id = mysqli_real_escape_string(
    $koneksi,
    $_POST['id']
);

$nama = mysqli_real_escape_string(
    $koneksi,
    $_POST['nama']
);

$harga = intval($_POST['harga']);

$stok = intval($_POST['stok']);

$kategori = mysqli_real_escape_string(
    $koneksi,
    $_POST['kategori']
);

$deskripsi = mysqli_real_escape_string(
    $koneksi,
    $_POST['deskripsi']
);

$ukuran = mysqli_real_escape_string(
    $koneksi,
    $_POST['ukuran']
);

$link_wa = isset($_POST['link_wa'])
    ? mysqli_real_escape_string($koneksi, $_POST['link_wa'])
    : '';

$link_shopee = isset($_POST['link_shopee'])
    ? mysqli_real_escape_string($koneksi, $_POST['link_shopee'])
    : '';

// =========================
// UPDATE DATA PRODUK
// =========================
$queryUpdate = mysqli_query(
    $koneksi,
    "
    UPDATE produk SET
        nama='$nama',
        harga='$harga',
        stok='$stok',
        kategori='$kategori',
        deskripsi='$deskripsi',
        ukuran='$ukuran',
        link_wa='$link_wa',
        link_shopee='$link_shopee'
    WHERE id='$id'
    "
);

// =========================
// CEK QUERY UPDATE
// =========================
if (!$queryUpdate) {

    die(
        "Gagal Update Produk: " .
        mysqli_error($koneksi)
    );
}

// =========================
// KONFIGURASI UPLOAD
// =========================
$uploaded_files = [];

$validExtensions = [
    'jpg',
    'jpeg',
    'png',
    'webp'
];

// =========================
// FOLDER GAMBAR
// =========================
$folderGambar = IMAGES_PATH;

// Tambahkan slash otomatis
$folderGambar = rtrim(
    $folderGambar,
    '/\\'
) . DIRECTORY_SEPARATOR;

// =========================
// BUAT FOLDER JIKA BELUM ADA
// =========================
if (!is_dir($folderGambar)) {

    mkdir($folderGambar, 0755, true);
}

// =========================
// UPLOAD GAMBAR BARU
// =========================
if (
    isset($_FILES['gambar']) &&
    isset($_FILES['gambar']['name'][0]) &&
    !empty($_FILES['gambar']['name'][0])
) {

    $fileNames = $_FILES['gambar']['name'];
    $tmpNames  = $_FILES['gambar']['tmp_name'];
    $errors    = $_FILES['gambar']['error'];

    // =========================
    // AMBIL GAMBAR LAMA DULU
    // =========================
    $resultOld = mysqli_query(
        $koneksi,
        "SELECT gambar FROM produk WHERE id='$id'"
    );

    $oldRow = mysqli_fetch_assoc($resultOld);

    // =========================
    // LOOP FILE UPLOAD
    // =========================
    for ($i = 0; $i < count($fileNames); $i++) {

        if ($errors[$i] === UPLOAD_ERR_OK) {

            $originalName = $fileNames[$i];

            $tmpFile = $tmpNames[$i];

            $ext = strtolower(
                pathinfo(
                    $originalName,
                    PATHINFO_EXTENSION
                )
            );

            // =========================
            // VALIDASI EXTENSION
            // =========================
            if (!in_array($ext, $validExtensions)) {

                echo "
                <script>
                    alert('Format gambar harus JPG, JPEG, PNG, atau WEBP!');
                    window.history.back();
                </script>
                ";

                exit;
            }

            // =========================
            // NAMA FILE AMAN
            // =========================
            $cleanName = preg_replace(
                '/[^a-zA-Z0-9]/',
                '_',
                pathinfo(
                    $originalName,
                    PATHINFO_FILENAME
                )
            );

            $uniqueName =
                time() .
                '_' .
                $i .
                '_' .
                $cleanName .
                '.' .
                $ext;

            // =========================
            // PROSES UPLOAD
            // =========================
            if (
                move_uploaded_file(
                    $tmpFile,
                    $folderGambar . $uniqueName
                )
            ) {

                $uploaded_files[] = $uniqueName;
            }
        }
    }

    // =========================
    // JIKA ADA FILE BERHASIL
    // =========================
    if (!empty($uploaded_files)) {

        // =========================
        // HAPUS GAMBAR LAMA
        // =========================
        if (
            $oldRow &&
            !empty($oldRow['gambar'])
        ) {

            $oldImages = explode(
                ',',
                $oldRow['gambar']
            );

            foreach ($oldImages as $oldImage) {

                $oldImage = trim($oldImage);

                $oldPath = $folderGambar . $oldImage;

                if (
                    !empty($oldImage) &&
                    file_exists($oldPath)
                ) {

                    unlink($oldPath);
                }
            }
        }

        // =========================
        // SIMPAN GAMBAR BARU
        // =========================
        $gambar_list = implode(
            ',',
            $uploaded_files
        );

        $updateGambar = mysqli_query(
            $koneksi,
            "
            UPDATE produk
            SET gambar='$gambar_list'
            WHERE id='$id'
            "
        );

        // =========================
        // CEK UPDATE GAMBAR
        // =========================
        if (!$updateGambar) {

            die(
                "Gagal Update Gambar: " .
                mysqli_error($koneksi)
            );
        }
    }
}

// =========================
// REDIRECT
// =========================
header(
    "Location: produk-list.php?pesan=berhasil_update"
);

exit;

?>
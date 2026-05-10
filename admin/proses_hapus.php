<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include_once __DIR__ . '/../koneksi.php';

// =========================
// Cek Login Admin
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
// Validasi ID Produk
// =========================
if (
    !isset($_POST['id']) ||
    !is_numeric($_POST['id'])
) {

    header("Location: produk-list.php");
    exit;
}

// =========================
// Ambil ID Produk
// =========================
$id = mysqli_real_escape_string(
    $koneksi,
    $_POST['id']
);

// =========================
// Ambil Data Produk
// =========================
$queryProduk = mysqli_query(
    $koneksi,
    "SELECT * FROM produk WHERE id='$id'"
);

// Jika query gagal
if (!$queryProduk) {

    die(
        "Query Error: " .
        mysqli_error($koneksi)
    );
}

// Jika produk tidak ditemukan
if (mysqli_num_rows($queryProduk) < 1) {

    echo "
    <script>
        alert('Produk tidak ditemukan!');
        window.location='produk-list.php';
    </script>
    ";

    exit;
}

// Ambil data produk
$row = mysqli_fetch_assoc($queryProduk);

// =========================
// Hapus File Gambar
// =========================
if (!empty($row['gambar'])) {

    // Pecah nama gambar menjadi array
    $oldImages = array_filter(
        array_map(
            'trim',
            explode(',', $row['gambar'])
        )
    );

    // Hapus satu per satu
    foreach ($oldImages as $oldImage) {

        // Path gambar
        $pathGambar = IMAGES_PATH . $oldImage;

        // Jika file ada
        if (
            !empty($oldImage) &&
            file_exists($pathGambar)
        ) {

            unlink($pathGambar);
        }
    }
}

// =========================
// Hapus Produk Dari Database
// =========================
$queryDelete = mysqli_query(
    $koneksi,
    "DELETE FROM produk WHERE id='$id'"
);

// Jika gagal
if (!$queryDelete) {

    die(
        "Gagal Menghapus Produk: " .
        mysqli_error($koneksi)
    );
}

// =========================
// Redirect Berhasil
// =========================
header("Location: produk-list.php?pesan=berhasil_hapus");
exit;

?>
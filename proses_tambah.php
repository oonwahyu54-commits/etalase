<?php
session_start();
include_once __DIR__ . '/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$nama      = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
$harga     = mysqli_real_escape_string($koneksi, $_POST['harga']);
$stok      = mysqli_real_escape_string($koneksi, $_POST['stok']);
$kategori  = mysqli_real_escape_string($koneksi, $_POST['kategori']);
$deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
$ukuran    = mysqli_real_escape_string($koneksi, $_POST['ukuran']);
$link_wa   = isset($_POST['link_wa']) ? mysqli_real_escape_string($koneksi, $_POST['link_wa']) : '';
$link_shopee = isset($_POST['link_shopee']) ? mysqli_real_escape_string($koneksi, $_POST['link_shopee']) : '';

$uploaded_files = [];
$validExtensions = ['jpg', 'jpeg', 'png', 'webp'];

if (!is_dir('gambar')) {
    mkdir('gambar', 0755, true);
}

if (isset($_FILES['gambar']) && is_array($_FILES['gambar']['name'])) {
    for ($i = 0; $i < count($_FILES['gambar']['name']); $i++) {
        if ($_FILES['gambar']['error'][$i] === UPLOAD_ERR_OK) {
            $originalName = $_FILES['gambar']['name'][$i];
            $tmpFile = $_FILES['gambar']['tmp_name'][$i];
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            if (!in_array($ext, $validExtensions)) {
                echo "<script>alert('Ekstensi file tidak diperbolehkan. Gunakan jpg, jpeg, png, atau webp.'); window.location='tambah.php';</script>";
                exit;
            }

            $uniqueName = time() . '_' . $i . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $ext;
            if (move_uploaded_file($tmpFile, 'gambar/' . $uniqueName)) {
                $uploaded_files[] = $uniqueName;
            }
        }
    }
}

if (empty($uploaded_files)) {
    echo "<script>alert('Silakan unggah minimal satu gambar produk.'); window.location='tambah.php';</script>";
    exit;
}

$gambar_list = implode(',', $uploaded_files);
$query = "INSERT INTO produk (nama, harga, stok, kategori, deskripsi, ukuran, link_wa, link_shopee, gambar) 
          VALUES ('$nama', '$harga', '$stok', '$kategori', '$deskripsi', '$ukuran', '$link_wa', '$link_shopee', '$gambar_list')";

$result = mysqli_query($koneksi, $query);
if ($result) {
    header("Location: index.php?pesan=berhasil");
    exit;
} else {
    echo "Error Database: " . mysqli_error($koneksi);
}
?>
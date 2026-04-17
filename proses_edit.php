<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header("Location: index.php");
    exit;
}

$id        = mysqli_real_escape_string($koneksi, $_POST['id']);
$nama      = mysqli_real_escape_string($koneksi, $_POST['nama']);
$harga     = intval($_POST['harga']);
$stok      = intval($_POST['stok']);
$kategori  = mysqli_real_escape_string($koneksi, $_POST['kategori']);
$deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
$ukuran    = mysqli_real_escape_string($koneksi, $_POST['ukuran']);
$link_wa   = isset($_POST['link_wa']) ? mysqli_real_escape_string($koneksi, $_POST['link_wa']) : '';
$link_shopee = isset($_POST['link_shopee']) ? mysqli_real_escape_string($koneksi, $_POST['link_shopee']) : '';

$update_produk = "UPDATE produk SET nama='$nama', harga='$harga', stok='$stok', kategori='$kategori', deskripsi='$deskripsi', ukuran='$ukuran', link_wa='$link_wa', link_shopee='$link_shopee' WHERE id='$id'";
mysqli_query($koneksi, $update_produk);

$uploaded_files = [];
$validExtensions = ['jpg', 'jpeg', 'png', 'webp'];

if (!is_dir('gambar')) {
    mkdir('gambar', 0755, true);
}

if (isset($_FILES['gambar'])) {
    $fileNames = [];
    $tmpNames = [];
    $errors = [];

    if (is_array($_FILES['gambar']['name'])) {
        $fileNames = $_FILES['gambar']['name'];
        $tmpNames = $_FILES['gambar']['tmp_name'];
        $errors = $_FILES['gambar']['error'];
    } else {
        $fileNames = [$_FILES['gambar']['name']];
        $tmpNames = [$_FILES['gambar']['tmp_name']];
        $errors = [$_FILES['gambar']['error']];
    }

    for ($i = 0; $i < count($fileNames); $i++) {
        if ($errors[$i] === UPLOAD_ERR_OK && !empty($tmpNames[$i])) {
            $originalName = $fileNames[$i];
            $tmpFile = $tmpNames[$i];
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            if (!in_array($ext, $validExtensions)) {
                echo "<script>alert('Ekstensi file tidak diperbolehkan. Gunakan jpg, jpeg, png, atau webp.'); window.history.back();</script>";
                exit;
            }

            $uniqueName = time() . '_' . $i . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $ext;
            if (move_uploaded_file($tmpFile, 'gambar/' . $uniqueName)) {
                $uploaded_files[] = $uniqueName;
            }
        }
    }

    if (!empty($uploaded_files)) {
        $result_old = mysqli_query($koneksi, "SELECT gambar FROM produk WHERE id='$id'");
        $old_row = mysqli_fetch_assoc($result_old);
        if ($old_row && !empty($old_row['gambar'])) {
            $oldImages = array_filter(array_map('trim', explode(',', $old_row['gambar'])));
            foreach ($oldImages as $oldImage) {
                if (file_exists('gambar/' . $oldImage)) {
                    @unlink('gambar/' . $oldImage);
                }
            }
        }

        $gambar_list = implode(',', $uploaded_files);
        mysqli_query($koneksi, "UPDATE produk SET gambar='$gambar_list' WHERE id='$id'");
    }
}

header("Location: admin/produk-list.php?pesan=berhasil_update");
exit;
?>
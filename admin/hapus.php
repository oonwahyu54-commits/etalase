<?php
include_once __DIR__ . '/../koneksi.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: produk-list.php");
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

$result = mysqli_query($koneksi, "SELECT gambar FROM produk WHERE id='$id'");

if ($result && $row = mysqli_fetch_assoc($result)) {

    $oldImages = array_filter(array_map('trim', explode(',', $row['gambar'])));

    foreach ($oldImages as $oldImage) {

        $filePath = IMAGES_PATH . $oldImage;

        if ($oldImage && file_exists($filePath)) {
            @unlink($filePath);
        }
    }
}

mysqli_query($koneksi, "DELETE FROM produk WHERE id='$id'");

header("Location: produk-list.php");
?>
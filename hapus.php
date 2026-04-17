<?php
include 'koneksi.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

$result = mysqli_query($koneksi, "SELECT gambar FROM produk WHERE id='$id'");
if ($result && $row = mysqli_fetch_assoc($result)) {
    $oldImages = array_filter(array_map('trim', explode(',', $row['gambar'])));
    foreach ($oldImages as $oldImage) {
        if ($oldImage && file_exists('gambar/' . $oldImage)) {
            @unlink('gambar/' . $oldImage);
        }
    }
}

mysqli_query($koneksi, "DELETE FROM produk WHERE id='$id'");

header("Location: index.php");
exit;
?>

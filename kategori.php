<?php
include_once __DIR__ . '/koneksi.php';

// Tambah kolom kategori ke tabel produk jika belum ada
$checkKategori = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE 'kategori'");
if(mysqli_num_rows($checkKategori) == 0){
    mysqli_query($koneksi, "ALTER TABLE produk ADD COLUMN kategori VARCHAR(50) DEFAULT 'Umum' AFTER stok");
    echo "Kolom kategori berhasil ditambahkan!";
} else {
    echo "Kolom kategori sudah ada.";
}
?>
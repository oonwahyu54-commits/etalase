<?php
include 'header.php';
include_once __DIR__ . '/koneksi.php';

// Check session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect ke login jika belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk - Inda Gallery</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>

<div class="form-container">
    <h2>Tambah Produk Baru</h2>
    <p class="form-description">Silahkan isi detail produk di bawah ini.</p>
    
    <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">
        
        <label>Nama Produk</label>
        <input type="text" name="nama_produk" placeholder="Contoh: Gamis Silk Premium" required>

        <label>Harga (Rp)</label>
        <input type="number" name="harga" placeholder="Masukkan harga tanpa titik (Contoh: 150000)" required>

        <label>Stok Barang</label>
        <input type="number" name="stok" min="0" placeholder="Jumlah stok tersedia" required>

        <label>Pilih Kategori Produk</label>
        <div class="category-container">
            <?php
            // Query untuk mengambil nilai ENUM dari kolom kategori
            $query = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE 'kategori'");
            $row = mysqli_fetch_array($query);
            
            // Memproses string ENUM('val1','val2') menjadi array
            preg_match_all("/'([^']+)'/", $row['Type'], $matches);
            $enum_values = $matches[1];

            // Menampilkan setiap nilai sebagai tombol yang bisa diklik
            foreach ($enum_values as $value) {
                echo "
                <div class='category-item'>
                    <input type='radio' name='kategori' value='$value' id='cat_$value' required>
                    <label for='cat_$value' class='category-label'>$value</label>
                </div>";
            }
            ?>
        </div>

        <label>Deskripsi Produk</label>
        <textarea name="deskripsi" placeholder="Tuliskan detail bahan, ukuran, dll..." rows="5" required></textarea>

        <label>Ukuran Produk</label>
        <input type="text" name="ukuran" placeholder="Contoh: S, M, L, XL atau 100x200 cm" required>

        <label>Link WhatsApp (Opsional)</label>
        <input type="url" name="link_wa" placeholder="https://wa.me/628xxxxxxxxx?text=Halo%20saya%20mau%20pesan...">

        <label>Link Shopee (Opsional)</label>
        <input type="url" name="link_shopee" placeholder="https://shopee.co.id/product/...">

        <label>Gambar Produk (Bisa pilih lebih dari satu)</label>
            <input type="file" name="gambar[]" accept="image/*" multiple required>
            <p class="form-help">Tahan tombol Ctrl (Windows) atau Command (Mac) untuk memilih banyak gambar.</p>
            
        <button type="submit" class="primary-btn">
            Simpan ke Katalog
        </button>
    </form>
</div>

<div class="tmbh">
    <a href="index.php">← Kembali ke Dashboard</a>
</div>

</body>
</html>
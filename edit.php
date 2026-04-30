<?php
include_once __DIR__ . '/koneksi.php';
include 'header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id='$id'");
$row = mysqli_fetch_assoc($query);

if (!$row) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$currentImages = array_filter(array_map('trim', explode(',', $row['gambar'])));
?>

<div class="form-container">
    <h2>Edit Produk: <?php echo htmlspecialchars($row['nama']); ?></h2>
    
    <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        
        <label>Nama Produk</label>
        <input type="text" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>" required>

        <label>Harga (Rp)</label>
        <input type="number" name="harga" value="<?php echo htmlspecialchars($row['harga']); ?>" required>
        
        <label>Stok Barang</label>
        <input type="number" name="stok" value="<?php echo htmlspecialchars($row['stok']); ?>" min="0" required>

        <label>Kategori</label>
        <div class="radio-group">
            <?php
            $query_kat = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE 'kategori'");
            $res_kat = mysqli_fetch_array($query_kat);
            preg_match_all("/'([^']+)'/", $res_kat['Type'], $matches);
            foreach ($matches[1] as $value) {
                $checked = ($row['kategori'] == $value) ? "checked" : "";
                echo "<label>
                        <input type='radio' name='kategori' value='$value' $checked> $value
                      </label>";
            }
            ?>
        </div>

        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="5"><?php echo htmlspecialchars($row['deskripsi']); ?></textarea>

        <label>Link WhatsApp (Opsional)</label>
        <input type="url" name="link_wa" value="<?php echo htmlspecialchars(isset($row['link_wa']) ? $row['link_wa'] : ''); ?>" placeholder="https://wa.me/628xxxxxxxxx?text=Halo%20saya%20mau%20pesan...">

        <label>Link Shopee (Opsional)</label>
        <input type="url" name="link_shopee" value="<?php echo htmlspecialchars(isset($row['link_shopee']) ? $row['link_shopee'] : ''); ?>" placeholder="https://shopee.co.id/product...">

        <label></label>Gambar Saat Ini:</label>
        <div class="current-images">
            <?php if (!empty($currentImages)): ?>
                <?php foreach ($currentImages as $img): ?>
                    <?php if ($img && file_exists('gambar/' . $img)): ?>
                        <img src="gambar/<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($row['nama']); ?>">
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-images">Tidak ada gambar tersedia.</div>
            <?php endif; ?>
        </div>

        <label>Ganti Gambar:</label>
        <input type="file" name="gambar[]" accept="image/*" multiple>
        <p class="form-note">Unggah file baru untuk mengganti semua gambar produk. Biarkan kosong agar galeri lama tetap.</p>

        <button type="submit" class="primary-btn">
            Simpan Perubahan
        </button>
    </form>
    
    <div class="form-back-container">
        <a href="index.php" class="form-back">← Batal & Kembali</a>
    </div>
</div>
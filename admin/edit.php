<?php
session_start();
include_once __DIR__ . '/../koneksi.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: produk-list.php");
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id='$id'");
$row = mysqli_fetch_assoc($query);

if (!$row) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='produk-list.php';</script>";
    exit;
}

$currentImages = array_filter(array_map('trim', explode(',', $row['gambar'])));
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - Admin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="admin-container">
    <div class="admin-sidebar">
        <h2>🔐 Admin Panel</h2>
        <ul>
            <li><a href="../index.php">🏠 Home</a></li>
            <li><a href="dashboard.php">📊 Dashboard</a></li>
            <li><a href="../tambah.php">➕ Tambah Produk</a></li>
            <li><a href="produk-list.php" class="active">📦 Kelola Produk</a></li>
            <li><a href="logout.php" class="logout-btn">🚪 Logout</a></li>
        </ul>
    </div>

    <div class="admin-content">
        <div class="admin-header">
            <h1>Edit Produk</h1>
            <p class="welcome-message">Mengedit: <strong><?php echo htmlspecialchars($row['nama']); ?></strong></p>
        </div>

        <div class="content-section">
            <form action="../proses_edit.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                
                <label>Nama Produk</label>
                <input type="text" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>" required style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px;">

                <label>Harga (Rp)</label>
                <input type="number" name="harga" value="<?php echo htmlspecialchars($row['harga']); ?>" required style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px;">
                
                <label>Stok Barang</label>
                <input type="number" name="stok" value="<?php echo htmlspecialchars($row['stok']); ?>" min="0" required style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px;">

                <label>Kategori</label>
                <div style="display: flex; gap: 10px; margin: 10px 0; flex-wrap: wrap;">
                    <?php
                    $query_kat = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE 'kategori'");
                    $res_kat = mysqli_fetch_array($query_kat);
                    preg_match_all("/'([^']+)'/", $res_kat['Type'], $matches);
                    foreach ($matches[1] as $value) {
                        $checked = ($row['kategori'] == $value) ? "checked" : "";
                        echo "<label style='background: #f0f0f0; padding: 8px 16px; border-radius: 20px; cursor: pointer;'>
                                <input type='radio' name='kategori' value='$value' $checked> $value
                              </label>";
                    }
                    ?>
                </div>

                <label>Deskripsi</label>
                <textarea name="deskripsi" rows="5" style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px;"><?php echo htmlspecialchars($row['deskripsi']); ?></textarea>

                <label>Ukuran</label>
                <input type="text" name="ukuran" value="<?php echo htmlspecialchars(isset($row['ukuran']) ? $row['ukuran'] : ''); ?>" placeholder="Contoh: S, M, L, XL atau 100x200 cm" style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px;">

                <label>Link WhatsApp (Opsional)</label>
                <input type="url" name="link_wa" value="<?php echo htmlspecialchars(isset($row['link_wa']) ? $row['link_wa'] : ''); ?>" placeholder="https://wa.me/628xxxxxxxxx?text=Halo%20saya%20mau%20pesan..." style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px;">

                <label>Link Shopee (Opsional)</label>
                <input type="url" name="link_shopee" value="<?php echo htmlspecialchars(isset($row['link_shopee']) ? $row['link_shopee'] : ''); ?>" placeholder="https://shopee.co.id/product..." style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px;">

                <label>Gambar Saat Ini:</label>
                <div style="display: flex; gap: 10px; margin: 10px 0; flex-wrap: wrap;">
                    <?php if (!empty($currentImages)): ?>
                        <?php foreach ($currentImages as $img): ?>
                            <?php if ($img && file_exists('../gambar/' . $img)): ?>
                                <img src="../gambar/<?php echo htmlspecialchars($img); ?>" width="100" height="100" style="object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="padding: 20px; background: #f7f7f7; color: #666; border-radius: 8px;">Tidak ada gambar tersedia.</div>
                    <?php endif; ?>
                </div>

                <label>Ganti Gambar:</label>
                <input type="file" name="gambar[]" accept="image/*" multiple style="margin: 10px 0;">
                <p style="font-size: 11px; color: #666;">Unggah file baru untuk mengganti semua gambar produk. Biarkan kosong agar galeri lama tetap.</p>

                <button type="submit" style="background: #d609b4; color: white; border: none; padding: 15px; width: 100%; border-radius: 8px; cursor: pointer; font-weight: bold; margin-top: 20px;">
                    Simpan Perubahan
                </button>
            </form>

            <form action="../proses_hapus.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini? Tindakan tidak dapat dibatalkan.');" style="margin-top: 20px;">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit" style="background: #f44336; color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; cursor: pointer; font-weight: bold;">
                    Hapus Produk
                </button>
            </form>

            <div style="margin-top: 15px; text-align: center;">
                <a href="produk-list.php" style="color: #666; text-decoration: none;">← Kembali ke Daftar</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>

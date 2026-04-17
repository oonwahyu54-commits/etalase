<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header('Location: ../login.php');
    exit;
}

include '../koneksi.php';

$produk = mysqli_query($koneksi, 'SELECT * FROM produk ORDER BY id DESC');

$message = '';
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'berhasil_update') {
        $message = 'Produk berhasil diperbarui!';
    } elseif ($_GET['pesan'] == 'berhasil_hapus') {
        $message = 'Produk berhasil dihapus!';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk - Admin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-produk-body">
    <div class="admin-produk-container">
        <div class="admin-produk-box">
            <div class="topbar">
                <h1>Kelola Produk</h1>
                <div>
                    <a href="../tambah.php">➕ Tambah Produk</a>
                    <a href="dashboard.php">← Dashboard</a>
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <div style="background: #d4edda; color: #155724; padding: 10px; margin: 10px 0; border-radius: 4px; border: 1px solid #c3e6cb;">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if (mysqli_num_rows($produk) > 0): ?>
                <table class="admin-produk-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Kategori</th>
                            <th>Ukuran</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($produk)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td>Rp <?php echo number_format($row['harga']); ?></td>
                                <td><?php echo htmlspecialchars($row['stok']); ?></td>
                                <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                                <td><?php echo htmlspecialchars(isset($row['ukuran']) ? $row['ukuran'] : '-'); ?></td>
                                <td>
                                    <?php $gambarUtama = trim(explode(',', $row['gambar'])[0]); ?>
                                    <?php if (!empty($gambarUtama) && file_exists('../gambar/' . $gambarUtama)): ?>
                                        <img src="../gambar/<?php echo htmlspecialchars($gambarUtama); ?>" alt="<?php echo htmlspecialchars($row['nama']); ?>">
                                    <?php else: ?>
                                        <span class="text-muted">Tidak ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="actions">
                                    <a class="edit" href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                                    <form action="../proses_hapus.php" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="delete" onclick="return confirm('Yakin ingin menghapus produk ini?');">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty">Belum ada produk. Tambah produk baru untuk mulai menjual.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

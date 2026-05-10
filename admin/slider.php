<?php
session_start();
include_once __DIR__ . '/../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$data = mysqli_query($koneksi, "SELECT * FROM slider ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Slider</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="admin-produk-container">
        <div class="admin-produk-box">
            
        <div class="topbar">
        <h1>Kelola Slider</h1>

        <div class="topbar-actions">
            <a href="dashboard.php" class="btn-admin kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
        <table class="admin-produk-table">
            <tr>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>

            <?php while($row = mysqli_fetch_assoc($data)) { ?>
            <tr>
                <td>
                    <img src="<?php echo IMAGES_URL . htmlspecialchars($row['gambar']); ?>">
                </td>
                <td><?php echo htmlspecialchars($row['judul']); ?></td>
                <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                <td class="actions">
                    <a class="edit" href="edit_slider.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a class="delete" href="hapus_slider.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </table>

    </div>
</div>
</body>
</html>
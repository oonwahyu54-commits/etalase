<?php
// Dashboard Admin
session_start();

// Check apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Check apakah role admin (tambahan keamanan)
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    session_destroy();
    header("Location: ../login.php?error=not_admin");
    exit;
}

include '../koneksi.php';
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Inda Gallery</title>
    <link rel="stylesheet" href="../style.css">
    </head>
<body>
<a href="daftar_admin.php"></a>
<div class="admin-container">
    <div class="admin-sidebar">
        <h2>🔐 Admin Panel</h2>
        <ul>
            <li><a href="../index.php">🏠 Home</a></li>
            <li><a href="dashboard.php" class="active">📊 Dashboard</a></li>
            <li><a href="../tambah.php">➕ Tambah Produk</a></li>
            <li><a href="produk-list.php">📦 Kelola Produk</a></li>
            <li><a href="logout.php" class="logout-btn">🚪 Logout</a></li>
        </ul>
    </div>

    <div class="admin-content">
        <div class="admin-header">
            <h1>Dashboard Admin</h1>
            <p class="welcome-message">Selamat datang, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
        </div>

        <div class="dashboard-stats">
            <?php
            // Hitung total produk
            $produk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk");
            $produk_data = mysqli_fetch_assoc($produk);

            // Hitung total penjualan (jika ada tabel pesanan)

            ?>

            <div class="stat-card">
                <h3>Total Produk</h3>
                <p class="stat-number"><?php echo $produk_data['total']; ?></p>
            </div>

            

        <div class="content-section">
            <h2>Aksi Cepat</h2>
            <div class="quick-actions">
                <a href="../tambah.php" class="action-btn">➕ Tambah Produk</a>
                <a href="produk-list.php" class="action-btn">📦 Kelola Produk</a>
                <a href="../index.php" class="action-btn">🏠 Lihat Website</a>
                <a href="logout.php" class="action-btn">🚪 Logout</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>

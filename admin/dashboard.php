<?php
// =========================
// Dashboard Admin
// =========================

session_start();

// =========================
// Cek Login
// =========================
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");
    exit;
}

// =========================
// Cek Role Admin
// =========================
if (
    !isset($_SESSION['role']) ||
    strtolower($_SESSION['role']) !== 'admin'
) {

    session_destroy();

    header("Location: ../login.php?error=not_admin");
    exit;
}

// =========================
// Koneksi Database
// =========================
include_once __DIR__ . '/../koneksi.php';

// =========================
// Ambil Username
// =========================
$username = $_SESSION['username'];

// =========================
// Hitung Total Produk
// =========================
$queryProduk = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) as total FROM produk"
);

$produk_data = mysqli_fetch_assoc($queryProduk);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Dashboard Admin - Inda Gallery
    </title>

    <!-- CSS -->
    <link rel="stylesheet" href="../style.css">

</head>

<body>

<div class="admin-container">

    <!-- =========================
         SIDEBAR
    ========================== -->
    <div class="admin-sidebar">

        <h2>
            Halaman Admin
        </h2>

        <ul>

            <li>
                <a href="../index.php">
                    Home
                </a>
            </li>

            <li>
                <a
                    href="dashboard.php"
                    class="active"
                >
                    Dashboard
                </a>
            </li>

            <li>
                <a href="tambah.php">
                    Tambah Produk
                </a>
            </li>

            <li>
                <a href="produk-list.php">
                    Kelola Produk
                </a>
            </li>

            <li>
                <a href="slider.php">
                    Kelola Slider
                </a>
            </li>

            <li>
                <a
                    href="logout.php"
                    class="logout-btn"
                >
                    Logout
                </a>
            </li>

        </ul>

    </div>

    <!-- =========================
         CONTENT
    ========================== -->
    <div class="admin-content">

        <!-- Header -->
        <div class="admin-header">

            <h1>
                Dashboard Admin
            </h1>

            <p class="welcome-message">

                Selamat datang,

                <strong>
                    <?php echo htmlspecialchars($username); ?>
                </strong>

                !

            </p>

        </div>

        <!-- Statistik -->
        <div class="dashboard-stats">

            <div class="stat-card">

                <h3>
                    Total Produk
                </h3>

                <p class="stat-number">
                    <?php echo $produk_data['total']; ?>
                </p>

            </div>

        </div>

        <!-- Quick Action -->
        <div class="content-section">

            <h2>
                Aksi Cepat
            </h2>

            <div class="quick-actions">

                <a
                    href="tambah.php"
                    class="action-btn"
                >
                    Tambah Produk
                </a>

                <a
                    href="produk-list.php"
                    class="action-btn"
                >
                    Kelola Produk
                </a>

                <a
                    href="slider.php"
                    class="action-btn"
                >
                    Kelola Slider
                </a>

                <a
                    href="../index.php"
                    class="action-btn"
                >
                    Lihat Website
                </a>

                <a
                    href="logout.php"
                    class="action-btn"
                >
                    Logout
                </a>

            </div>

        </div>

    </div>

</div>

</body>
</html>
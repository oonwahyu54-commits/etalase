<?php

session_start();

// =========================
// Cek Login Admin
// =========================
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    strtolower($_SESSION['role']) !== 'admin'
) {

    header('Location: ../login.php');
    exit;
}

// =========================
// Koneksi Database
// =========================
include_once __DIR__ . '/../koneksi.php';

// =========================
// Ambil Data Produk
// =========================
$produk = mysqli_query(
    $koneksi,
    'SELECT * FROM produk ORDER BY id DESC'
);

// =========================
// Pesan Notifikasi
// =========================
$message = '';

if (isset($_GET['pesan'])) {

    if ($_GET['pesan'] == 'berhasil_update') {

        $message = 'Produk berhasil diperbarui!';

    } elseif ($_GET['pesan'] == 'berhasil_hapus') {

        $message = 'Produk berhasil dihapus!';

    } elseif ($_GET['pesan'] == 'berhasil') {

        $message = 'Produk berhasil ditambahkan!';
    }
}
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
        Kelola Produk - Admin
    </title>

    <!-- CSS -->
    <link
        rel="stylesheet"
        href="../style.css"
    >

</head>

<body class="admin-produk-body">

<div class="admin-produk-container">

    <div class="admin-produk-box">

        <!-- =========================
             TOPBAR
        ========================== -->
        <div class="topbar">

            <h1>
                Kelola Produk
            </h1>

            <div>

                <!-- BENAR -->
                <a href="tambah.php">
                    Tambah Produk
                </a>

                <a href="dashboard.php">
                    ← Dashboard
                </a>

            </div>

        </div>

        <!-- =========================
             NOTIFIKASI
        ========================== -->
        <?php if (!empty($message)): ?>

            <div
                style="
                    background: #d4edda;
                    color: #155724;
                    padding: 10px;
                    margin: 10px 0;
                    border-radius: 4px;
                    border: 1px solid #c3e6cb;
                "
            >

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>

        <!-- =========================
             TABEL PRODUK
        ========================== -->
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

                        <!-- ID -->
                        <td>
                            <?php echo $row['id']; ?>
                        </td>

                        <!-- Nama -->
                        <td>
                            <?php echo htmlspecialchars($row['nama']); ?>
                        </td>

                        <!-- Harga -->
                        <td>
                            Rp <?php echo number_format($row['harga']); ?>
                        </td>

                        <!-- Stok -->
                        <td>
                            <?php echo htmlspecialchars($row['stok']); ?>
                        </td>

                        <!-- Kategori -->
                        <td>
                            <?php echo htmlspecialchars($row['kategori']); ?>
                        </td>

                        <!-- Ukuran -->
                        <td>
                            <?php echo htmlspecialchars(
                                isset($row['ukuran'])
                                ? $row['ukuran']
                                : '-'
                            ); ?>
                        </td>

                        <!-- Gambar -->
                        <td>

                            <?php
                            $gambarUtama = trim(
                                explode(',', $row['gambar'])[0]
                            );
                            ?>

                            <?php if (
                                !empty($gambarUtama) &&
                                file_exists(IMAGES_PATH . $gambarUtama)
                            ): ?>

                                <img
                                    src="<?php echo IMAGES_URL . htmlspecialchars($gambarUtama); ?>"
                                    alt="<?php echo htmlspecialchars($row['nama']); ?>"
                                >

                            <?php else: ?>

                                <span class="text-muted">
                                    Tidak ada
                                </span>

                            <?php endif; ?>

                        </td>

                        <!-- AKSI -->
                        <td class="actions">

                            <!-- EDIT -->
                            <a
                                class="edit"
                                href="edit.php?id=<?php echo $row['id']; ?>"
                            >
                                Edit
                            </a>

                            <!-- HAPUS -->
                            <form
                                action="proses_hapus.php"
                                method="POST"
                            >

                                <br>

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?php echo $row['id']; ?>"
                                >

                                <button
                                    type="submit"
                                    class="delete"
                                    onclick="return confirm('Yakin ingin menghapus produk ini?');"
                                >
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        <?php else: ?>

            <div class="empty">

                Belum ada produk.
                Tambah produk baru untuk mulai menjual.

            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>
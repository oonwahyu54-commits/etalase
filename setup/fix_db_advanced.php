<?php
/**
 * Advanced Database Repair
 * Mengatasi error: Duplicate entry '0' for key 'PRIMARY'
 */

require_once __DIR__ . '/../koneksi.php';

$messages = [];

// ====== FIX 1: Rebuild Produk Table ======
$messages[] = ['type' => 'info', 'text' => '🔄 Memulai perbaikan table produk...'];

// Step 1: Check if produk table exists
$check_produk = mysqli_query($koneksi, "SHOW TABLES LIKE 'produk'");
$produk_exists = (mysqli_num_rows($check_produk) > 0);

if ($produk_exists) {
    // Get the data first
    $get_data = mysqli_query($koneksi, "SELECT * FROM produk");
    $data_backup = [];
    while ($row = mysqli_fetch_assoc($get_data)) {
        $data_backup[] = $row;
    }
    $messages[] = ['type' => 'info', 'text' => '📦 Data backup: ' . count($data_backup) . ' records'];

    // Drop the old table
    if (mysqli_query($koneksi, "DROP TABLE produk")) {
        $messages[] = ['type' => 'success', 'text' => '✓ Table lama dihapus'];
    }
}

// Step 2: Create fresh produk table with proper structure
$create_produk = "CREATE TABLE produk (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    harga INT NOT NULL,
    deskripsi LONGTEXT,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_id (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (mysqli_query($koneksi, $create_produk)) {
    $messages[] = ['type' => 'success', 'text' => '✓ Table produk berhasil dibuat dengan struktur baru'];
} else {
    $messages[] = ['type' => 'error', 'text' => '✗ Error: ' . mysqli_error($koneksi)];
}

// Step 3: Restore data if exists
if (!empty($data_backup)) {
    foreach ($data_backup as $row) {
        $id = intval($row['id']);
        $nama = mysqli_real_escape_string($koneksi, $row['nama']);
        $harga = intval($row['harga']);
        $deskripsi = mysqli_real_escape_string($koneksi, $row['deskripsi']);
        $gambar = mysqli_real_escape_string($koneksi, $row['gambar']);
        
        $restore = "INSERT INTO produk (id, nama, harga, deskripsi, gambar) VALUES ($id, '$nama', $harga, '$deskripsi', '$gambar')";
        if (mysqli_query($koneksi, $restore)) {
            $messages[] = ['type' => 'success', 'text' => '✓ Record ID ' . $id . ' restored'];
        } else {
            $messages[] = ['type' => 'error', 'text' => '✗ Error restore ID ' . $id . ': ' . mysqli_error($koneksi)];
        }
    }
}

// ====== FIX 2: Check Users Table ======
$check_users = mysqli_query($koneksi, "SHOW TABLES LIKE 'users'");
$users_exists = (mysqli_num_rows($check_users) > 0);

if (!$users_exists) {
    $create_users = "CREATE TABLE users (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100),
        role VARCHAR(50) DEFAULT 'admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (mysqli_query($koneksi, $create_users)) {
        $messages[] = ['type' => 'success', 'text' => '✓ Table users berhasil dibuat'];
    } else {
        $messages[] = ['type' => 'error', 'text' => '✗ Error: ' . mysqli_error($koneksi)];
    }
} else {
    $messages[] = ['type' => 'info', 'text' => '✓ Table users sudah ada'];
}

// ====== FIX 3: Check Pesanan Table ======
$check_pesanan = mysqli_query($koneksi, "SHOW TABLES LIKE 'pesanan'");
$pesanan_exists = (mysqli_num_rows($check_pesanan) > 0);

if (!$pesanan_exists) {
    $create_pesanan = "CREATE TABLE pesanan (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        id_produk INT NOT NULL,
        nama_pembeli VARCHAR(100) NOT NULL,
        email_pembeli VARCHAR(100),
        alamat VARCHAR(255),
        status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_produk) REFERENCES produk(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (mysqli_query($koneksi, $create_pesanan)) {
        $messages[] = ['type' => 'success', 'text' => '✓ Table pesanan berhasil dibuat'];
    } else {
        $messages[] = ['type' => 'error', 'text' => '✗ Error: ' . mysqli_error($koneksi)];
    }
} else {
    $messages[] = ['type' => 'info', 'text' => '✓ Table pesanan sudah ada'];
}

$messages[] = ['type' => 'success', 'text' => '✅ Perbaikan database selesai!'];

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Advanced Database Fix - Inda Gallery</title>
    <style>
        .status-box {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .status-item {
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
        }

        .status-item.good {
            background: #d4edda;
            color: #155724;
        }

        .status-item.bad {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="fix-container">
    <div class="fix-header">
        <h1>🔧 Advanced Database Repair</h1>
        <p>Memperbaiki PRIMARY KEY dan struktur table</p>
    </div>

    <div class="fix-section">
        <h2>⚙️ Status Perbaikan</h2>

        <?php foreach ($messages as $msg): ?>
            <div class="message <?php echo $msg['type']; ?>">
                <?php echo $msg['text']; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="fix-section">
        <h2>📊 Verifikasi Tabel</h2>

        <?php
        // Verify tables
        $tables_result = mysqli_query($koneksi, "SHOW TABLES");
        $table_list = [];
        while ($table = mysqli_fetch_assoc($tables_result)) {
            $table_name = reset($table);
            $table_list[] = $table_name;
        }

        echo "<div class='status-box'>";
        foreach (['produk', 'users', 'pesanan'] as $tbl) {
            $class = in_array($tbl, $table_list) ? 'good' : 'bad';
            $text = in_array($tbl, $table_list) ? '✓ ' . $tbl : '✗ ' . $tbl;
            echo "<div class='status-item $class'>$text</div>";
        }
        echo "</div>";

        // Show produk table structure
        echo "<h3 style='margin-top: 30px;'>Struktur Table Produk</h3>";
        $columns_result = mysqli_query($koneksi, "DESCRIBE produk");
        if (mysqli_num_rows($columns_result) > 0) {
            echo "<table>";
            echo "<tr><th>Kolom</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            while ($col = mysqli_fetch_assoc($columns_result)) {
                echo "<tr>";
                echo "<td>" . $col['Field'] . "</td>";
                echo "<td>" . $col['Type'] . "</td>";
                echo "<td>" . ($col['Null'] == 'YES' ? 'Ya' : 'Tidak') . "</td>";
                echo "<td>" . ($col['Key'] ?: '-') . "</td>";
                echo "<td>" . ($col['Default'] ?: '-') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }

        // Show record count
        echo "<h3 style='margin-top: 30px;'>Jumlah Record</h3>";
        echo "<table>";
        echo "<tr><th>Table</th><th>Records</th></tr>";
        foreach (['produk', 'users', 'pesanan'] as $tbl) {
            if (in_array($tbl, $table_list)) {
                $count = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM $tbl"))['cnt'];
                echo "<tr><td>" . $tbl . "</td><td>" . $count . "</td></tr>";
            }
        }
        echo "</table>";
        ?>
    </div>

    <div class="fix-section">
        <h2>✅ Langkah Selanjutnya</h2>

        <ol>
            <li><strong>Buat Admin Account</strong> - Klik tombol di bawah untuk membuat akun admin pertama</li>
            <li><strong>Check Status</strong> - Verifikasi sistem sudah berjalan dengan baik</li>
            <li><strong>Login</strong> - Masuk dengan akun admin yang baru dibuat</li>
            <li><strong>Tambah Produk</strong> - Mulai menambahkan produk ke catalog</li>
        </ol>

        <div class="action-buttons">
            <a href="setup_admin.php" class="btn btn-primary">👤 Buat Admin Account</a>
            <a href="debug_login.php" class="btn btn-primary">🔍 Check Status</a>
            <a href="../login.php" class="btn btn-secondary">🔐 Login</a>
            <a href="../index.php" class="btn btn-secondary">🏠 Home</a>
        </div>
    </div>
</div>

</body>
</html>

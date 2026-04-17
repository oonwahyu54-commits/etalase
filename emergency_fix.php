<?php
/**
 * Emergency Database Fix
 * Mengatasi: Table 'produk' already exists
 */

include 'koneksi.php';

$messages = [];

try {
    // Step 1: Check if backup exists and drop it
    $check_backup = mysqli_query($koneksi, "SHOW TABLES LIKE 'produk_backup'");
    if (mysqli_num_rows($check_backup) > 0) {
        if (mysqli_query($koneksi, "DROP TABLE IF EXISTS produk_backup")) {
            $messages[] = ['type' => 'success', 'text' => '✓ Old backup produk_backup dihapus'];
        }
    }

    // Step 2: Check if produk table exists
    $check_produk = mysqli_query($koneksi, "SHOW TABLES LIKE 'produk'");
    $produk_exists = (mysqli_num_rows($check_produk) > 0);

    if ($produk_exists) {
        // RENAME current table to backup
        if (mysqli_query($koneksi, "RENAME TABLE produk TO produk_backup")) {
            $messages[] = ['type' => 'success', 'text' => '✓ Table produk direname ke produk_backup'];

            // Get backup data
            $backup_result = mysqli_query($koneksi, "SELECT * FROM produk_backup");
            $backup_data = [];
            while ($row = mysqli_fetch_assoc($backup_result)) {
                $backup_data[] = $row;
            }
            $messages[] = ['type' => 'info', 'text' => '📦 Data backup: ' . count($backup_data) . ' records'];
        } else {
            throw new Exception('Gagal rename table: ' . mysqli_error($koneksi));
        }
    }

    // Step 3: Create fresh produk table
    $create_produk = "CREATE TABLE produk (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(255) NOT NULL,
        harga INT NOT NULL,
        deskripsi LONGTEXT,
        gambar VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (mysqli_query($koneksi, $create_produk)) {
        $messages[] = ['type' => 'success', 'text' => '✓ Table produk baru berhasil dibuat'];
    } else {
        throw new Exception('Gagal create table: ' . mysqli_error($koneksi));
    }

    // Step 4: Restore data from backup
    if (!empty($backup_data)) {
        $restored_count = 0;
        foreach ($backup_data as $row) {
            $id = intval($row['id']);
            $nama = mysqli_real_escape_string($koneksi, $row['nama'] ?? '');
            $harga = intval($row['harga'] ?? 0);
            $deskripsi = mysqli_real_escape_string($koneksi, $row['deskripsi'] ?? '');
            $gambar = mysqli_real_escape_string($koneksi, $row['gambar'] ?? '');
            
            $restore = "INSERT INTO produk (id, nama, harga, deskripsi, gambar) VALUES ($id, '$nama', $harga, '$deskripsi', '$gambar')";
            
            if (mysqli_query($koneksi, $restore)) {
                $restored_count++;
            } else {
                $messages[] = ['type' => 'warning', 'text' => '⚠ Error restore ID ' . $id . ': ' . mysqli_error($koneksi)];
            }
        }
        $messages[] = ['type' => 'success', 'text' => '✓ ' . $restored_count . ' records berhasil direstore'];
    }

    // Step 5: Drop backup table
    if (mysqli_query($koneksi, "DROP TABLE IF EXISTS produk_backup")) {
        $messages[] = ['type' => 'success', 'text' => '✓ Backup table dihapus'];
    }

    $messages[] = ['type' => 'success', 'text' => '✅ Database perbaikan BERHASIL!'];

} catch (Exception $e) {
    $messages[] = ['type' => 'error', 'text' => '❌ ERROR: ' . $e->getMessage()];
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Emergency Database Fix - Inda Gallery</title>
    <link rel="stylesheet" href="style.css">
            color: #721c24;
        }

        .buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 25px;
        }

        .btn {
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            display: inline-block;
            transition: 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #d609b4 0%, #e91e63 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(214, 9, 180, 0.3);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
            border: 2px solid #d609b4;
        }

        .btn-secondary:hover {
            background: #d609b4;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🚨 Emergency Database Fix</h1>
        <p>Memperbaiki kesalahan PRIMARY KEY</p>
    </div>

    <div class="section">
        <h2>⚙️ Status Perbaikan</h2>
        <?php foreach ($messages as $msg): ?>
            <div class="message <?php echo $msg['type']; ?>">
                <?php echo htmlspecialchars($msg['text']); ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="section">
        <h2>📋 Verifikasi Tabel</h2>

        <?php
        // Display table information
        $tables_result = mysqli_query($koneksi, "SHOW TABLES");
        $existing_tables = [];
        while ($table = mysqli_fetch_assoc($tables_result)) {
            $existing_tables[] = reset($table);
        }

        echo "<div class='status-grid'>";
        foreach (['produk', 'users', 'pesanan'] as $tbl) {
            if (in_array($tbl, $existing_tables)) {
                $count = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM $tbl"))['cnt'];
                echo "<div class='status-item ok'>✓ $tbl<br><small>$count records</small></div>";
            } else {
                echo "<div class='status-item error'>✗ $tbl<br><small>Missing</small></div>";
            }
        }
        echo "</div>";

        // Show produk table structure
        if (in_array('produk', $existing_tables)) {
            echo "<h3 style='margin-top: 30px; color: #d609b4;'>Struktur Table Produk</h3>";
            $columns = mysqli_query($koneksi, "DESCRIBE produk");
            if (mysqli_num_rows($columns) > 0) {
                echo "<table>";
                echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
                while ($col = mysqli_fetch_assoc($columns)) {
                    echo "<tr>";
                    echo "<td><strong>" . $col['Field'] . "</strong></td>";
                    echo "<td>" . $col['Type'] . "</td>";
                    echo "<td>" . ($col['Null'] == 'YES' ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . ($col['Key'] ?: '-') . "</td>";
                    echo "<td>" . ($col['Default'] ?: '-') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }

            // Count products
            $count = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM produk"))['cnt'];
            echo "<p style='margin-top: 15px; font-weight: bold;'>Total Produk: <span style='color: #d609b4;'>$count</span></p>";
        }
        ?>
    </div>

    <div class="section">
        <h2>✅ Langkah Berikutnya</h2>

        <p>Database sudah diperbaiki. Lanjutkan dengan:</p>

        <div class="buttons">
            <a href="setup_admin.php" class="btn btn-primary">👤 Buat Admin Account</a>
            <a href="debug_login.php" class="btn btn-primary">🔍 Check Status Sistem</a>
            <a href="login.php" class="btn btn-secondary">🔐 Login</a>
            <a href="index.php" class="btn btn-secondary">🏠 Home</a>
        </div>

        <div style="margin-top: 25px; padding: 15px; background: #f9f9f9; border-radius: 6px;">
            <h3 style="margin-top: 0; color: #d609b4;">Panduan Setup Lengkap:</h3>
            <ol>
                <li>Klik <strong>"Buat Admin Account"</strong> untuk membuat user admin pertama</li>
                <li>Isikan username, password, dan email</li>
                <li>Klik <strong>"Check Status Sistem"</strong> untuk verifikasi</li>
                <li>Klik <strong>"Login"</strong> dengan credentials admin</li>
                <li>Masuk dashboard → Tambah Produk</li>
            </ol>
        </div>
    </div>
</div>

</body>
</html>

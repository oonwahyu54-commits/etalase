<?php
/**
 * Fix Database Table Structure
 * Memperbaiki struktur table produk
 */

include 'koneksi.php';

$messages = [];

// Check 1: Produk table exists
$check_produk = mysqli_query($koneksi, "SHOW TABLES LIKE 'produk'");
$produk_exists = (mysqli_num_rows($check_produk) > 0);

if (!$produk_exists) {
    // Create produk table
    $create_produk = "CREATE TABLE produk (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nama VARCHAR(255) NOT NULL,
        harga INT NOT NULL,
        deskripsi LONGTEXT,
        gambar VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if (mysqli_query($koneksi, $create_produk)) {
        $messages[] = ['type' => 'success', 'text' => '✓ Tabel produk berhasil dibuat dengan struktur lengkap!'];
    } else {
        $messages[] = ['type' => 'error', 'text' => '✗ Error membuat tabel produk: ' . mysqli_error($koneksi)];
    }
} else {
    $messages[] = ['type' => 'info', 'text' => '✓ Tabel produk sudah ada'];
    
    // Check columns
    $columns_result = mysqli_query($koneksi, "DESCRIBE produk");
    $columns = [];
    while ($col = mysqli_fetch_assoc($columns_result)) {
        $columns[$col['Field']] = $col;
    }
    
    $required_columns = ['id', 'nama', 'harga', 'deskripsi', 'gambar'];
    
    foreach ($required_columns as $col) {
        if (!isset($columns[$col])) {
            $messages[] = ['type' => 'warning', 'text' => "⚠ Kolom '$col' tidak ditemukan - akan ditambahkan"];
            
            // Add missing column
            switch ($col) {
                case 'nama':
                    $add_col = "ALTER TABLE produk ADD COLUMN nama VARCHAR(255) NOT NULL";
                    break;
                case 'harga':
                    $add_col = "ALTER TABLE produk ADD COLUMN harga INT NOT NULL";
                    break;
                case 'deskripsi':
                    $add_col = "ALTER TABLE produk ADD COLUMN deskripsi LONGTEXT";
                    break;
                case 'gambar':
                    $add_col = "ALTER TABLE produk ADD COLUMN gambar VARCHAR(255)";
                    break;
            }
            
            if (isset($add_col)) {
                if (mysqli_query($koneksi, $add_col)) {
                    $messages[] = ['type' => 'success', 'text' => "✓ Kolom '$col' berhasil ditambahkan"];
                } else {
                    $messages[] = ['type' => 'error', 'text' => "✗ Error menambah kolom '$col': " . mysqli_error($koneksi)];
                }
            }
        } else {
            $messages[] = ['type' => 'success', 'text' => "✓ Kolom '$col' ada"];
        }
    }
}

// Check 2: Users table
$check_users = mysqli_query($koneksi, "SHOW TABLES LIKE 'users'");
$users_exists = (mysqli_num_rows($check_users) > 0);

if (!$users_exists) {
    $create_users = "CREATE TABLE users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100),
        role VARCHAR(50) DEFAULT 'admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (mysqli_query($koneksi, $create_users)) {
        $messages[] = ['type' => 'success', 'text' => '✓ Tabel users berhasil dibuat!'];
    } else {
        $messages[] = ['type' => 'error', 'text' => '✗ Error membuat tabel users: ' . mysqli_error($koneksi)];
    }
} else {
    $messages[] = ['type' => 'info', 'text' => '✓ Tabel users sudah ada'];
}

// Check 3: Pesanan table
$check_pesanan = mysqli_query($koneksi, "SHOW TABLES LIKE 'pesanan'");
$pesanan_exists = (mysqli_num_rows($check_pesanan) > 0);

if (!$pesanan_exists) {
    $create_pesanan = "CREATE TABLE pesanan (
        id INT PRIMARY KEY AUTO_INCREMENT,
        id_produk INT NOT NULL,
        nama_pembeli VARCHAR(100) NOT NULL,
        email_pembeli VARCHAR(100),
        alamat VARCHAR(255),
        status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_produk) REFERENCES produk(id)
    )";
    
    if (mysqli_query($koneksi, $create_pesanan)) {
        $messages[] = ['type' => 'success', 'text' => '✓ Tabel pesanan berhasil dibuat!'];
    } else {
        $messages[] = ['type' => 'error', 'text' => '✗ Error membuat tabel pesanan: ' . mysqli_error($koneksi)];
    }
} else {
    $messages[] = ['type' => 'info', 'text' => '✓ Tabel pesanan sudah ada'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fix Database - Inda Gallery</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .btn-secondary:hover {
            background: #d609b4;
            color: white;
        }
    </style>
</head>
<body>

<div class="fix-container">
    <div class="fix-header">
        <h1>🔧 Database Fix & Setup</h1>
        <p>Memperbaiki struktur table dan kolom</p>
    </div>

    <div class="fix-section">
        <h2>📊 Status Perbaikan</h2>

        <?php foreach ($messages as $msg): ?>
            <div class="message <?php echo $msg['type']; ?>">
                <?php echo $msg['text']; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="fix-section">
        <h2>📋 Struktur Table Produk</h2>

        <?php
        $columns_result = mysqli_query($koneksi, "DESCRIBE produk");
        if (mysqli_num_rows($columns_result) > 0) {
            echo "<table>";
            echo "<tr><th>Kolom</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            while ($col = mysqli_fetch_assoc($columns_result)) {
                echo "<tr>";
                echo "<td><strong>" . $col['Field'] . "</strong></td>";
                echo "<td>" . $col['Type'] . "</td>";
                echo "<td>" . ($col['Null'] == 'YES' ? 'Ya' : 'Tidak') . "</td>";
                echo "<td>" . ($col['Key'] ?: '-') . "</td>";
                echo "<td>" . ($col['Default'] ?: '-') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        ?>
    </div>

    <div class="fix-section">
        <h2>✅ Pengecekan Lanjutan</h2>

        <?php
        // Get all tables
        $tables_result = mysqli_query($koneksi, "SHOW TABLES");
        echo "<h3>Tabel yang Ada:</h3>";
        echo "<table>";
        echo "<tr><th>Nama Tabel</th><th>Jumlah Record</th></tr>";
        while ($table = mysqli_fetch_assoc($tables_result)) {
            $table_name = reset($table);
            $count = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM $table_name"))['cnt'];
            echo "<tr>";
            echo "<td><strong>$table_name</strong></td>";
            echo "<td>$count records</td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>
    </div>

    <div class="fix-section">
        <h2>🚀 Langkah Selanjutnya</h2>

        <p>Database telah diperbaiki! Langkah berikutnya:</p>

        <div class="action-buttons">
            <a href="setup_admin.php" class="btn btn-primary">👤 Buat Admin Account</a>
            <a href="debug_login.php" class="btn btn-primary">🔍 Check Status</a>
            <a href="login.php" class="btn btn-secondary">🔐 Login</a>
            <a href="index.php" class="btn btn-secondary">🏠 Home</a>
        </div>
    </div>
</div>

</body>
</html>

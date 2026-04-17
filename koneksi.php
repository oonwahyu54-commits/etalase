<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbName = "etalase_db";

$koneksi = mysqli_connect($host, $user, $pass);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Buat database jika belum ada
if (!mysqli_select_db($koneksi, $dbName)) {
    $createDB = mysqli_query($koneksi, "CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    if (!$createDB) {
        die("Gagal membuat database $dbName: " . mysqli_error($koneksi));
    }
    mysqli_select_db($koneksi, $dbName);
}

function ensureProdukTable($koneksi) {
    $checkProduk = mysqli_query($koneksi, "SHOW TABLES LIKE 'produk'");
    if (!$checkProduk) {
        return;
    }

    if (mysqli_num_rows($checkProduk) == 0) {
        $createProduk = "CREATE TABLE IF NOT EXISTS produk (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(255) NOT NULL,
            harga DECIMAL(12,2) NOT NULL DEFAULT 0,
            stok INT NOT NULL DEFAULT 0,
            kategori ENUM('Umum','Gamis','Hijab','Aksesori','Fashion','Lainnya') NOT NULL DEFAULT 'Umum',
            deskripsi LONGTEXT,
            ukuran VARCHAR(255),
            link_wa VARCHAR(500),
            link_shopee VARCHAR(500),
            gambar VARCHAR(1000),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        mysqli_query($koneksi, $createProduk);
    } else {
        $requiredColumns = [
            'stok' => 'INT NOT NULL DEFAULT 0',
            'kategori' => "ENUM('Umum','Gamis','Hijab','Aksesori','Fashion','Lainnya') NOT NULL DEFAULT 'Umum'",
            'deskripsi' => 'LONGTEXT',
            'ukuran' => 'VARCHAR(255)',
            'link_wa' => 'VARCHAR(500)',
            'link_shopee' => 'VARCHAR(500)',
            'gambar' => 'VARCHAR(1000)',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ];

        foreach ($requiredColumns as $column => $definition) {
            $checkColumn = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE '$column'");
            if ($checkColumn && mysqli_num_rows($checkColumn) == 0) {
                mysqli_query($koneksi, "ALTER TABLE produk ADD COLUMN $column $definition");
            }
        }
    }
}

function ensureUsersTable($koneksi) {
    $checkUsers = mysqli_query($koneksi, "SHOW TABLES LIKE 'users'");
    if (!$checkUsers) {
        return;
    }

    if (mysqli_num_rows($checkUsers) == 0) {
        $createUsers = "CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100),
            role VARCHAR(50) DEFAULT 'admin',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        mysqli_query($koneksi, $createUsers);
    }

    $roleColumnCheck = mysqli_query($koneksi, "SHOW COLUMNS FROM users LIKE 'role'");
    if ($roleColumnCheck && mysqli_num_rows($roleColumnCheck) == 0) {
        mysqli_query($koneksi, "ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT 'admin'");
    }

    $adminCheck = mysqli_query($koneksi, "SELECT id FROM users WHERE role = 'admin' LIMIT 1");
    if ($adminCheck && mysqli_num_rows($adminCheck) == 0) {
        $defaultAdmin = 'admin';
        $defaultPass = password_hash('admin123', PASSWORD_BCRYPT);
        $defaultEmail = 'admin@example.com';
        $insertAdmin = mysqli_prepare($koneksi, "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'admin')");
        if ($insertAdmin) {
            mysqli_stmt_bind_param($insertAdmin, 'sss', $defaultAdmin, $defaultPass, $defaultEmail);
            mysqli_stmt_execute($insertAdmin);
            mysqli_stmt_close($insertAdmin);
        }
    }
}

ensureProdukTable($koneksi);
ensureUsersTable($koneksi);

mysqli_set_charset($koneksi, 'utf8mb4');
?>

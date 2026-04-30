<?php
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'etalase_db');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$koneksi = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
if (!$koneksi) {
    die("Koneksi MySQL gagal: " . mysqli_connect_error());
}

if (!mysqli_select_db($koneksi, DB_NAME)) {
    $createDB = mysqli_query($koneksi, "CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    if (!$createDB) {
        die("Gagal membuat database " . DB_NAME . ": " . mysqli_error($koneksi));
    }
    mysqli_select_db($koneksi, DB_NAME);
}

if (!mysqli_set_charset($koneksi, 'utf8mb4')) {
    die("Gagal set charset: " . mysqli_error($koneksi));
}

function safeQuery($koneksi, $query) {
    try {
        return mysqli_query($koneksi, $query);
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function createUsersTable($koneksi) {
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

function repairUsersTable($koneksi) {
    mysqli_query($koneksi, "DROP TABLE IF EXISTS users");
    createUsersTable($koneksi);
}

function ensureProdukTable($koneksi) {
    $checkProduk = safeQuery($koneksi, "SHOW TABLES LIKE 'produk'");
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
            $checkColumn = safeQuery($koneksi, "SHOW COLUMNS FROM produk LIKE '$column'");
            if ($checkColumn && mysqli_num_rows($checkColumn) == 0) {
                mysqli_query($koneksi, "ALTER TABLE produk ADD COLUMN $column $definition");
            }
        }
    }
}

function ensureUsersTable($koneksi) {
    $checkUsers = safeQuery($koneksi, "SHOW TABLES LIKE 'users'");
    if (!$checkUsers) {
        repairUsersTable($koneksi);
        return;
    }

    if (mysqli_num_rows($checkUsers) == 0) {
        createUsersTable($koneksi);
    }

    $roleColumnCheck = safeQuery($koneksi, "SHOW COLUMNS FROM users LIKE 'role'");
    if ($roleColumnCheck === false) {
        repairUsersTable($koneksi);
        return;
    }

    if (mysqli_num_rows($roleColumnCheck) == 0) {
        mysqli_query($koneksi, "ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT 'admin'");
    }

    $adminCheck = safeQuery($koneksi, "SELECT id FROM users WHERE role = 'admin' LIMIT 1");
    if ($adminCheck === false) {
        repairUsersTable($koneksi);
        return;
    }

    if (mysqli_num_rows($adminCheck) == 0) {
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
?>

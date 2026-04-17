<?php
// Setup database untuk table users
session_start();
require_once __DIR__ . '/../koneksi.php';

?> 

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Setup Database - Inda Gallery</title>
</head>
<body>

<div class="setup-container">
    <div class="setup-card">
        <h2>🔧 Setup Database</h2>

        <?php
        // Cek dan buat table users jika belum ada
        $checkUsers = mysqli_query($koneksi, "SHOW TABLES LIKE 'users'");

        if (!$checkUsers) {
            echo "<div class='status-message status-error'>✗ Error saat memeriksa tabel users: " . mysqli_error($koneksi) . "</div>";
        }

        if (!$checkUsers || mysqli_num_rows($checkUsers) == 0) {
            // Buat table users
            $createUsers = "CREATE TABLE users (
                id INT PRIMARY KEY AUTO_INCREMENT,
                username VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(100),
                role VARCHAR(50) DEFAULT 'admin',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

            if (mysqli_query($koneksi, $createUsers)) {
                echo "<div class='status-message status-success'>✓ Tabel 'users' berhasil dibuat!</div>";
            } else {
                echo "<div class='status-message status-error'>✗ Error: " . mysqli_error($koneksi) . "</div>";
            }
        } else {
            echo "<div class='status-message status-info'>√ Tabel 'users' sudah ada!</div>";
        }

        // Pastikan kolom role ada di tabel users (untuk kompatibilitas versi lama)
        $roleColumnCheck = mysqli_query($koneksi, "SHOW COLUMNS FROM users LIKE 'role'");
        if ($roleColumnCheck && mysqli_num_rows($roleColumnCheck) == 0) {
            mysqli_query($koneksi, "ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT 'admin'");
            echo "<div class='status-message status-success'>✓ Kolom 'role' ditambahkan ke tabel 'users'.</div>";
        }

        // Buat akun admin default jika belum ada
        $adminCheck = mysqli_query($koneksi, "SELECT id FROM users WHERE role = 'admin' LIMIT 1");
        if ($adminCheck && mysqli_num_rows($adminCheck) == 0) {
            $defaultAdmin = 'admin';
            $defaultPass = password_hash('admin123', PASSWORD_BCRYPT);
            $defaultEmail = 'admin@example.com';
            $insertAdmin = mysqli_prepare($koneksi, "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'admin')");
            mysqli_stmt_bind_param($insertAdmin, 'sss', $defaultAdmin, $defaultPass, $defaultEmail);
            if (mysqli_stmt_execute($insertAdmin)) {
                echo "<div class='status-message status-success'>✓ Akun admin default berhasil dibuat! Username: admin, Password: admin123</div>";
            } else {
                echo "<div class='status-message status-error'>✗ Gagal membuat akun admin default: " . mysqli_error($koneksi) . "</div>";
            }
            mysqli_stmt_close($insertAdmin);
        }

        // Cek table produk
        $checkProduk = mysqli_query($koneksi, "SHOW TABLES LIKE 'produk'");
        if (!$checkProduk || mysqli_num_rows($checkProduk) == 0) {
            $createProduk = "CREATE TABLE produk (
                id INT PRIMARY KEY AUTO_INCREMENT,
                nama VARCHAR(255) NOT NULL,
                deskripsi TEXT,
                harga DECIMAL(12,2) NOT NULL DEFAULT 0,
                stok INT NOT NULL DEFAULT 0,
                gambar VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

            if (mysqli_query($koneksi, $createProduk)) {
                echo "<div class='status-message status-success'>✓ Tabel 'produk' berhasil dibuat!</div>";
            } else {
                echo "<div class='status-message status-error'>✗ Error saat membuat tabel produk: " . mysqli_error($koneksi) . "</div>";
            }
        } else {
            echo "<div class='status-message status-info'>√ Tabel 'produk' sudah ada!</div>";
        }

        // Cek table pesanan
        $checkOrder = mysqli_query($koneksi, "SHOW TABLES LIKE 'pesanan'");
        
        if (!$checkOrder || mysqli_num_rows($checkOrder) == 0) {
            // Buat table pesanan
            $createOrder = "CREATE TABLE pesanan (
                id INT PRIMARY KEY AUTO_INCREMENT,
                id_produk INT NOT NULL,
                nama_pembeli VARCHAR(100) NOT NULL,
                email_pembeli VARCHAR(100),
                alamat VARCHAR(255),
                status VARCHAR(50) DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (id_produk) REFERENCES produk(id) ON DELETE CASCADE ON UPDATE CASCADE
            )";
            
            if (mysqli_query($koneksi, $createOrder)) {
                echo "<div class='status-message status-success'>✓ Tabel 'pesanan' berhasil dibuat!</div>";
            } else {
                echo "<div class='status-message status-error'>✗ Error: " . mysqli_error($koneksi) . "</div>";
            }
        } else {
            echo "<div class='status-message status-info'>√ Tabel 'pesanan' sudah ada!</div>";
        }

        // Perbaikan otomatis: migrasi password plain-text ke hash jika ditemukan
        $plainPasswordCheck = mysqli_query($koneksi, 'SELECT id, password FROM users WHERE password NOT LIKE "$2y$%" AND password NOT LIKE "$argon2id$%" AND password NOT LIKE "$argon2i$%"');
        if ($plainPasswordCheck && mysqli_num_rows($plainPasswordCheck) > 0) {
            echo "<div class='status-message status-warning'>⚠️ Ditemukan password non-hash, akan dimigrasi otomatis.</div>";
            while ($row = mysqli_fetch_assoc($plainPasswordCheck)) {
                $hashed = password_hash($row['password'], PASSWORD_BCRYPT);
                $update = mysqli_prepare($koneksi, "UPDATE users SET password = ? WHERE id = ?");
                mysqli_stmt_bind_param($update, 'si', $hashed, $row['id']);
                mysqli_stmt_execute($update);
                mysqli_stmt_close($update);
            }
            echo "<div class='status-message status-success'>✓ Password non-hash berhasil dimigrasi ke hash.</div>";
        }
        ?>

        <h3>📝 Buat Akun Admin</h3>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $email = trim($_POST['email']);

            if (empty($username) || empty($password) || empty($email)) {
                echo "<div class='status-message status-error'>✗ Semua field harus diisi.</div>";
            } else {
                $checkStmt = mysqli_prepare($koneksi, "SELECT id FROM users WHERE username = ?");
                mysqli_stmt_bind_param($checkStmt, 's', $username);
                mysqli_stmt_execute($checkStmt);
                mysqli_stmt_store_result($checkStmt);

                if (mysqli_stmt_num_rows($checkStmt) > 0) {
                    echo "<div class='status-message status-error'>✗ Username sudah terdaftar. Pilih username lain.</div>";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    // Role tidak lagi diwajibkan; simpan role default kosong
                    $insertStmt = mysqli_prepare($koneksi, "INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
                    mysqli_stmt_bind_param($insertStmt, 'sss', $username, $hashed_password, $email);

                    if (mysqli_stmt_execute($insertStmt)) {
                        echo "<div class='status-message status-success'>✓ Akun berhasil dibuat! (role tidak dicek)</div>";
                    } else {
                        echo "<div class='status-message status-error'>✗ Error: " . mysqli_error($koneksi) . "</div>";
                    }
                }

                mysqli_stmt_close($checkStmt);
                if (isset($insertStmt)) {
                    mysqli_stmt_close($insertStmt);
                }
            }
        }
        ?>

        <div class="setup-form">
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" required>
                </div>

                <div class="form-group">
                    <button type="submit">Buat Akun Admin</button>
                </div>
            </form>
        </div>

        <h3>👥 Daftar Admin</h3>

        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Dibuat</th>
            </tr>
            <?php
            $users = mysqli_query($koneksi, "SELECT * FROM users");
            while ($user = mysqli_fetch_assoc($users)) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                echo "<td>" . $user['role'] . "</td>";
                echo "<td>" . (isset($user['role']) ? htmlspecialchars($user['role']) : '') . "</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <hr style="margin: 30px 0;">

        <p>
            <a href="../index.php" style="color: #d609b4; text-decoration: none; font-weight: bold;">← Kembali ke Beranda</a>
        </p>
    </div>
</div>

</body>
</html>

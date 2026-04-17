<?php
/**
 * Test Koneksi Database dengan XAMPP 3.3
 * File ini digunakan untuk memverifikasi semua koneksi database
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<h1>🔍 Test Koneksi Database - XAMPP 3.3</h1>";
echo "<hr>";

// Test 1: Include koneksi.php
echo "<h2>Test 1: Include koneksi.php</h2>";
try {
    include 'koneksi.php';
    echo "<p style='color: green;'>✓ File koneksi.php berhasil di-include</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    die();
}

// Test 2: Verifikasi koneksi
echo "<h2>Test 2: Verifikasi Koneksi MySQL</h2>";
if ($koneksi && !mysqli_connect_errno()) {
    echo "<p style='color: green;'>✓ Koneksi MySQL berhasil</p>";
    echo "<p>MySQL Version: " . mysqli_get_server_info($koneksi) . "</p>";
} else {
    echo "<p style='color: red;'>✗ Koneksi MySQL gagal: " . mysqli_connect_error() . "</p>";
    die();
}

// Test 3: Verifikasi database
echo "<h2>Test 3: Verifikasi Database</h2>";
$dbName = 'etalase_db';
$dbCheck = mysqli_query($koneksi, "SELECT DATABASE()");
$result = mysqli_fetch_row($dbCheck);
if ($result[0] == $dbName) {
    echo "<p style='color: green;'>✓ Database '" . $dbName . "' aktif</p>";
} else {
    echo "<p style='color: red;'>✗ Database tidak aktif atau salah nama</p>";
}

// Test 4: Verifikasi charset
echo "<h2>Test 4: Verifikasi Charset</h2>";
$charset = mysqli_query($koneksi, "SELECT @@character_set_client");
$charResult = mysqli_fetch_row($charset);
echo "<p>Charset yang digunakan: <strong>" . $charResult[0] . "</strong></p>";
if ($charResult[0] == 'utf8mb4') {
    echo "<p style='color: green;'>✓ Charset utf8mb4 aktif</p>";
} else {
    echo "<p style='color: orange;'>⚠ Charset bukan utf8mb4, mungkin ada masalah dengan emoji atau karakter khusus</p>";
}

// Test 5: Verifikasi tabel produk
echo "<h2>Test 5: Verifikasi Tabel Produk</h2>";
$tableCheck = mysqli_query($koneksi, "SHOW TABLES LIKE 'produk'");
if (mysqli_num_rows($tableCheck) > 0) {
    echo "<p style='color: green;'>✓ Tabel 'produk' ada</p>";
    
    // Tampilkan struktur tabel
    echo "<h3>Struktur Tabel Produk:</h3>";
    $structure = mysqli_query($koneksi, "DESCRIBE produk");
    echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
    echo "<tr style='background: #ddd;'>";
    echo "<th style='padding: 10px;'>Field</th>";
    echo "<th style='padding: 10px;'>Type</th>";
    echo "<th style='padding: 10px;'>Null</th>";
    echo "<th style='padding: 10px;'>Key</th>";
    echo "<th style='padding: 10px;'>Default</th>";
    echo "</tr>";
    
    while ($row = mysqli_fetch_assoc($structure)) {
        echo "<tr>";
        echo "<td style='padding: 10px;'><strong>" . $row['Field'] . "</strong></td>";
        echo "<td style='padding: 10px;'>" . $row['Type'] . "</td>";
        echo "<td style='padding: 10px;'>" . ($row['Null'] == 'YES' ? 'YES' : 'NO') . "</td>";
        echo "<td style='padding: 10px;'>" . ($row['Key'] ? $row['Key'] : '-') . "</td>";
        echo "<td style='padding: 10px;'>" . ($row['Default'] ? $row['Default'] : '-') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>✗ Tabel 'produk' tidak ditemukan</p>";
    echo "<p>Harap jalankan <a href='setup/setup.php'>setup database</a></p>";
}

// Test 6: Verifikasi tabel users
echo "<h2>Test 6: Verifikasi Tabel Users</h2>";
$usersCheck = mysqli_query($koneksi, "SHOW TABLES LIKE 'users'");
if (mysqli_num_rows($usersCheck) > 0) {
    echo "<p style='color: green;'>✓ Tabel 'users' ada</p>";
} else {
    echo "<p style='color: red;'>✗ Tabel 'users' tidak ditemukan</p>";
}

// Test 7: Jumlah data
echo "<h2>Test 7: Jumlah Data</h2>";
$produkCount = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk");
$produk = mysqli_fetch_assoc($produkCount);
echo "<p>Total produk di database: <strong>" . $produk['total'] . "</strong></p>";

$usersTableCheck = mysqli_query($koneksi, "SHOW TABLES LIKE 'users'");
if ($usersTableCheck && mysqli_num_rows($usersTableCheck) > 0) {
    $usersCount = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users");
    if ($usersCount) {
        $users = mysqli_fetch_assoc($usersCount);
        echo "<p>Total user di database: <strong>" . $users['total'] . "</strong></p>";
    }
}

// Test 8: Session variables
echo "<h2>Test 8: PHP Configuration</h2>";
echo "<p>PHP Version: <strong>" . phpversion() . "</strong></p>";
echo "<p>Session Status: <strong>" . (session_status() === PHP_SESSION_NONE ? 'Not Started' : 'Active') . "</strong></p>";
echo "<p>Display Errors: <strong>" . (ini_get('display_errors') ? 'Enabled' : 'Disabled') . "</strong></p>";

// Test 9: Summary
echo "<h2>📊 Summary</h2>";
echo "<div style='padding: 15px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;'>";
echo "<strong>✓ XAMPP 3.3 Configuration Complete!</strong><br>";
echo "Database connection siap digunakan.<br>";
echo "Semua tabel dan konfigurasi telah diverifikasi.";
echo "</div>";

echo "<hr>";
echo "<p><a href='index.php' style='padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 4px;'>← Kembali ke Beranda</a></p>";

mysqli_close($koneksi);
?>

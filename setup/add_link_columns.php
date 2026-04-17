<?php
require_once __DIR__ . '/../koneksi.php';

echo "<h2>Menambahkan Kolom Link WA dan Shopee ke Tabel Produk</h2>";

// Tambah kolom link_wa
$check_wa = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE 'link_wa'");
if(mysqli_num_rows($check_wa) == 0){
    $alter_wa = "ALTER TABLE produk ADD COLUMN link_wa VARCHAR(500) AFTER gambar";
    if(mysqli_query($koneksi, $alter_wa)){
        echo "<p style='color: green;'>✓ Kolom 'link_wa' berhasil ditambahkan</p>";
    } else {
        echo "<p style='color: red;'>✗ Error menambah kolom link_wa: " . mysqli_error($koneksi) . "</p>";
    }
} else {
    echo "<p style='color: blue;'>√ Kolom 'link_wa' sudah ada</p>";
}

// Tambah kolom link_shopee
$check_shopee = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE 'link_shopee'");
if(mysqli_num_rows($check_shopee) == 0){
    $alter_shopee = "ALTER TABLE produk ADD COLUMN link_shopee VARCHAR(500) AFTER link_wa";
    if(mysqli_query($koneksi, $alter_shopee)){
        echo "<p style='color: green;'>✓ Kolom 'link_shopee' berhasil ditambahkan</p>";
    } else {
        echo "<p style='color: red;'>✗ Error menambah kolom link_shopee: " . mysqli_error($koneksi) . "</p>";
    }
} else {
    echo "<p style='color: blue;'>√ Kolom 'link_shopee' sudah ada</p>";
}

echo "<h3>Struktur Tabel Produk Saat Ini:</h3>";
$structure = mysqli_query($koneksi, "DESCRIBE produk");
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
while($row = mysqli_fetch_assoc($structure)){
    echo "<tr>";
    echo "<td><strong>" . $row['Field'] . "</strong></td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . ($row['Null'] == 'YES' ? 'YES' : 'NO') . "</td>";
    echo "<td>" . ($row['Key'] ? $row['Key'] : '-') . "</td>";
    echo "<td>" . ($row['Default'] ? $row['Default'] : '-') . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
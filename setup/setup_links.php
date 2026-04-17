<?php
include '../koneksi.php';

echo "<!DOCTYPE html><html><head><title>Setup Link WA & Shopee</title></head><body>";
echo "<div class='form-container'>";
echo "<h2>Menambahkan Kolom Link WA & Shopee</h2>";

// Tambah kolom link_wa
$check_wa = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE 'link_wa'");
if(mysqli_num_rows($check_wa) == 0){
    $alter_wa = "ALTER TABLE produk ADD COLUMN link_wa VARCHAR(500) AFTER gambar";
    if(mysqli_query($koneksi, $alter_wa)){
        echo "<div style='color: green; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb; background: #d4edda; border-radius: 4px;'>✓ Kolom 'link_wa' berhasil ditambahkan</div>";
    } else {
        echo "<div style='color: red; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb; background: #f8d7da; border-radius: 4px;'>✗ Error menambah kolom link_wa: " . mysqli_error($koneksi) . "</div>";
    }
} else {
    echo "<div style='color: blue; padding: 10px; margin: 10px 0; border: 1px solid #bee5eb; background: #d1ecf1; border-radius: 4px;'>√ Kolom 'link_wa' sudah ada</div>";
}

// Tambah kolom link_shopee
$check_shopee = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE 'link_shopee'");
if(mysqli_num_rows($check_shopee) == 0){
    $alter_shopee = "ALTER TABLE produk ADD COLUMN link_shopee VARCHAR(500) AFTER link_wa";
    if(mysqli_query($koneksi, $alter_shopee)){
        echo "<div style='color: green; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb; background: #d4edda; border-radius: 4px;'>✓ Kolom 'link_shopee' berhasil ditambahkan</div>";
    } else {
        echo "<div style='color: red; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb; background: #f8d7da; border-radius: 4px;'>✗ Error menambah kolom link_shopee: " . mysqli_error($koneksi) . "</div>";
    }
} else {
    echo "<div style='color: blue; padding: 10px; margin: 10px 0; border: 1px solid #bee5eb; background: #d1ecf1; border-radius: 4px;'>√ Kolom 'link_shopee' sudah ada</div>";
}

echo "<h3>Struktur Tabel Produk Saat Ini:</h3>";
$structure = mysqli_query($koneksi, "DESCRIBE produk");
echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-top: 20px;'>";
echo "<tr style='background: #f8f2f8;'><th style='padding: 10px;'>Field</th><th style='padding: 10px;'>Type</th><th style='padding: 10px;'>Null</th><th style='padding: 10px;'>Key</th><th style='padding: 10px;'>Default</th></tr>";
while($row = mysqli_fetch_assoc($structure)){
    echo "<tr>";
    echo "<td style='padding: 10px;'><strong>" . $row['Field'] . "</strong></td>";
    echo "<td style='padding: 10px;'>" . $row['Type'] . "</td>";
    echo "<td style='padding: 10px;'>" . ($row['Null'] == 'YES' ? 'YES' : 'NO') . "</td>";
    echo "<td style='padding: 10px;'>" . ($row['Key'] ? $row['Key'] : '-') . "</td>";
    echo "<td style='padding: 10px;'>" . ($row['Default'] ? $row['Default'] : '-') . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<div style='margin-top: 30px; text-align: center;'>";
echo "<a href='../index.php' style='padding: 12px 24px; background: #d609b4; color: white; text-decoration: none; border-radius: 6px;'>← Kembali ke Beranda</a>";
echo "</div>";

echo "</div></body></html>";
?>
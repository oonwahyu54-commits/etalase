<?php
require_once __DIR__ . '/../koneksi.php';
include 'header.php';

?> 

<div style="max-width: 900px; margin: 30px auto; padding: 20px;">
    <h2>Setup Database - Tambah Kolom Deskripsi</h2>
    
    <?php
    
    // Cek apakah kolom deskripsi sudah ada
    $checkColumn = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE 'deskripsi'");
    
    if(mysqli_num_rows($checkColumn) == 0){
        // Kolom deskripsi belum ada, tambahkan
        $alterTable = "ALTER TABLE produk ADD COLUMN deskripsi LONGTEXT AFTER harga";
        
        if(mysqli_query($koneksi, $alterTable)){
            echo "<div style='padding: 15px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;'>";
            echo "<strong>✓ Berhasil!</strong> Kolom 'deskripsi' telah ditambahkan ke tabel produk.";
            echo "</div>";
        } else {
            echo "<div style='padding: 15px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;'>";
            echo "<strong>✗ Error:</strong> " . mysqli_error($koneksi);
            echo "</div>";
        }
    } else {
        echo "<div style='padding: 15px; background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; border-radius: 4px;'>";
        echo "<strong>√ Informasi:</strong> Kolom 'deskripsi' sudah ada di tabel produk.";
        echo "</div>";
    }
    
    ?>
    
    <hr style="margin: 30px 0;">
    
    <h3>Struktur Tabel Produk</h3>
    
    <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr style="background: #ddd;">
            <th style="padding: 10px;">Field</th>
            <th style="padding: 10px;">Type</th>
            <th style="padding: 10px;">Null</th>
            <th style="padding: 10px;">Key</th>
            <th style="padding: 10px;">Default</th>
        </tr>
        <?php
        $structure = mysqli_query($koneksi, "DESCRIBE produk");
        while($row = mysqli_fetch_assoc($structure)){
            echo "<tr>";
            echo "<td style='padding: 10px;'><strong>" . $row['Field'] . "</strong></td>";
            echo "<td style='padding: 10px;'>" . $row['Type'] . "</td>";
            echo "<td style='padding: 10px;'>" . ($row['Null'] == 'YES' ? 'YES' : 'NO') . "</td>";
            echo "<td style='padding: 10px;'>" . ($row['Key'] ? $row['Key'] : '-') . "</td>";
            echo "<td style='padding: 10px;'>" . ($row['Default'] ? $row['Default'] : '-') . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    
    <hr style="margin: 30px 0;">
    
    <h3>Semua Data Produk</h3>
    
    <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr style="background: #ddd;">
            <th style="padding: 10px;">ID</th>
            <th style="padding: 10px;">Nama</th>
            <th style="padding: 10px;">Harga</th>
            <th style="padding: 10px;">Deskripsi</th>
            <th style="padding: 10px;">Gambar</th>
        </tr>
        <?php
        $data = mysqli_query($koneksi, "SELECT * FROM produk");
        if(mysqli_num_rows($data) > 0){
            while($row = mysqli_fetch_assoc($data)){
                echo "<tr>";
                echo "<td style='padding: 10px;'>" . $row['id'] . "</td>";
                echo "<td style='padding: 10px;'>" . htmlspecialchars($row['nama']) . "</td>";
                echo "<td style='padding: 10px;'>Rp " . number_format($row['harga']) . "</td>";
                echo "<td style='padding: 10px;'>" . (isset($row['deskripsi']) && !empty($row['deskripsi']) ? substr(htmlspecialchars($row['deskripsi']), 0, 50) . "..." : "<em>Kosong</em>") . "</td>";
                echo "<td style='padding: 10px;'>" . (isset($row['gambar']) ? htmlspecialchars($row['gambar']) : "Kosong") . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' style='padding: 10px; text-align: center;'><em>Belum ada produk</em></td></tr>";
        }
        ?>
    </table>
    
    <div style="margin-top: 30px; text-align: center;">
        <a href="../index.php" style="padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 4px;">← Kembali ke Beranda</a>
    </div>
</div>

</body>
</html>

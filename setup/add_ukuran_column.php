<?php
require_once __DIR__ . '/../koneksi.php';
include 'header.php';

?> 

<div style="max-width: 900px; margin: 30px auto; padding: 20px;">
    <h2>Setup Database - Tambah Kolom Ukuran</h2>

    <?php

    // Cek apakah kolom ukuran sudah ada
    $checkColumn = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE 'ukuran'");

    if(mysqli_num_rows($checkColumn) == 0){
        // Kolom ukuran belum ada, tambahkan
        $alterTable = "ALTER TABLE produk ADD COLUMN ukuran VARCHAR(255) AFTER deskripsi";

        if(mysqli_query($koneksi, $alterTable)){
            echo "<div style='padding: 15px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;'>";
            echo "<strong>✓ Berhasil!</strong> Kolom 'ukuran' telah ditambahkan ke tabel produk.";
            echo "</div>";
        } else {
            echo "<div style='padding: 15px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;'>";
            echo "<strong>✗ Error:</strong> " . mysqli_error($koneksi);
            echo "</div>";
        }
    } else {
        echo "<div style='padding: 15px; background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; border-radius: 4px;'>";
        echo "<strong>√ Informasi:</strong> Kolom 'ukuran' sudah ada di tabel produk.";
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
        ?>
            <tr>
                <td style="padding: 10px;"><?php echo $row['Field']; ?></td>
                <td style="padding: 10px;"><?php echo $row['Type']; ?></td>
                <td style="padding: 10px;"><?php echo $row['Null']; ?></td>
                <td style="padding: 10px;"><?php echo $row['Key']; ?></td>
                <td style="padding: 10px;"><?php echo $row['Default']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>

    <div style="margin-top: 30px;">
        <a href="setup.php" style="padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">← Kembali ke Setup</a>
    </div>
</div>
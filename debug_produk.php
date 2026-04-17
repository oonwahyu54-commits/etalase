<?php
include 'koneksi.php';
include 'header.php';

?>

<div style="max-width: 900px; margin: 30px auto; padding: 20px;">
    <h2>Debug - Semua Produk</h2>
    
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <tr style="background: #ddd;">
            <th style="padding: 10px;">ID</th>
            <th style="padding: 10px;">Nama</th>
            <th style="padding: 10px;">Harga</th>
            <th style="padding: 10px;">Deskripsi</th>
            <th style="padding: 10px;">Gambar</th>
        </tr>
        <?php
        $data = mysqli_query($koneksi, "SELECT * FROM produk");
        while($row = mysqli_fetch_assoc($data)){
            echo "<tr>";
            echo "<td style='padding: 10px;'>" . $row['id'] . "</td>";
            echo "<td style='padding: 10px;'>" . $row['nama'] . "</td>";
            echo "<td style='padding: 10px;'>" . $row['harga'] . "</td>";
            echo "<td style='padding: 10px;'>" . (isset($row['deskripsi']) ? substr($row['deskripsi'], 0, 50) . "..." : "KOSONG") . "</td>";
            echo "<td style='padding: 10px;'>" . (isset($row['gambar']) ? $row['gambar'] : "KOSONG") . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

</body>
</html>

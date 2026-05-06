<?php
session_start();
include_once __DIR__ . '/../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = intval($_GET['id']);
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM slider WHERE id=$id"));

if(isset($_POST['submit'])){
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    $gambar = $data['gambar']; // default gambar lama

// cek apakah upload gambar baru
if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0){
    $namaFile = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    $namaBaru = time() . '_' . $namaFile;
    $path = "../gambar/" . $namaBaru;

    if(move_uploaded_file($tmp, $path)){
        // hapus gambar lama (opsional tapi bagus)
        if(file_exists("../gambar/" . $data['gambar'])){
            unlink("../gambar/" . $data['gambar']);
        }

        $gambar = $namaBaru;
    }
}

mysqli_query($koneksi, "UPDATE slider SET 
    judul='$judul',
    deskripsi='$deskripsi',
    gambar='$gambar'
    WHERE id=$id
");
    header("Location: slider.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Slider</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="form-container">
    <h2>Edit Slider</h2>

    <form method="POST" enctype="multipart/form-data">

    <form method="POST">
        <label>Judul</label>
        <input type="text" name="judul" value="<?php echo htmlspecialchars($data['judul']); ?>" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi" required><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>

        <label>Gambar Saat Ini</label><br>
        <img src="../gambar/<?php echo $data['gambar']; ?>" width="150"><br><br>

        <label>Ganti Gambar</label>
        <input type="file" name="gambar" accept="image/*">

        <button type="submit" name="submit">Update</button>
    </form>

    <div class="form-back-container">
        <a href="slider.php" class="form-back">← Kembali</a>
    </div>
</div>
</body>
</html>
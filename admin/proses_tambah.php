<?php
session_start();

include_once __DIR__ . '/../koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Cek koneksi
if (!isset($koneksi)) {
    die("Koneksi database tidak ditemukan!");
}

// Ambil data form
$nama      = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
$harga     = mysqli_real_escape_string($koneksi, $_POST['harga']);
$stok      = mysqli_real_escape_string($koneksi, $_POST['stok']);
$kategori  = mysqli_real_escape_string($koneksi, $_POST['kategori']);
$deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
$ukuran    = mysqli_real_escape_string($koneksi, $_POST['ukuran']);

$link_wa = isset($_POST['link_wa'])
    ? mysqli_real_escape_string($koneksi, $_POST['link_wa'])
    : '';

$link_shopee = isset($_POST['link_shopee'])
    ? mysqli_real_escape_string($koneksi, $_POST['link_shopee'])
    : '';

// Folder gambar
$folderGambar = __DIR__ . '/../gambar/';

// Buat folder jika belum ada
if (!is_dir($folderGambar)) {
    mkdir($folderGambar, 0755, true);
}

$uploaded_files = [];
$validExtensions = ['jpg', 'jpeg', 'png', 'webp'];

// Upload gambar
if (isset($_FILES['gambar']) && is_array($_FILES['gambar']['name'])) {

    for ($i = 0; $i < count($_FILES['gambar']['name']); $i++) {

        if ($_FILES['gambar']['error'][$i] === UPLOAD_ERR_OK) {

            $originalName = $_FILES['gambar']['name'][$i];
            $tmpFile      = $_FILES['gambar']['tmp_name'][$i];

            // Ambil extension
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            // Validasi extension
            if (!in_array($ext, $validExtensions)) {

                echo "
                <script>
                    alert('Format gambar harus JPG, JPEG, PNG, atau WEBP!');
                    window.location='tambah.php';
                </script>
                ";

                exit;
            }

            // Bersihkan nama file
            $cleanName = preg_replace('/[^A-Za-z0-9\-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));

            // Nama file baru
            $newFileName = time() . '_' . $i . '_' . $cleanName . '.' . $ext;

            // Path upload
            $uploadPath = $folderGambar . $newFileName;

            // Upload file
            if (move_uploaded_file($tmpFile, $uploadPath)) {
                $uploaded_files[] = $newFileName;
            }
        }
    }
}

// Gabungkan nama file
$gambar = implode(',', $uploaded_files);

// Simpan ke database
$query = "INSERT INTO produk (
    nama,
    harga,
    stok,
    kategori,
    deskripsi,
    ukuran,
    gambar,
    link_wa,
    link_shopee
) VALUES (
    '$nama',
    '$harga',
    '$stok',
    '$kategori',
    '$deskripsi',
    '$ukuran',
    '$gambar',
    '$link_wa',
    '$link_shopee'
)";

$result = mysqli_query($koneksi, $query);

// Cek hasil insert
if ($result) {

    echo "
    <script>
        alert('Produk berhasil ditambahkan!');
        window.location='produk-list.php';
    </script>
    ";

} else {

    echo "
    <script>
        alert('Gagal menambahkan produk!');
        window.location='tambah.php';
    </script>
    ";

    echo mysqli_error($koneksi);
}
?>
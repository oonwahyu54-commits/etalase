<?php 
include_once __DIR__ . '/koneksi.php';
include 'header.php';

// Ambil kategori dari URL jika ada (Filter)
$kategori_filter = isset($_GET['kategori']) ? mysqli_real_escape_string($koneksi, $_GET['kategori']) : '';

$table_check = mysqli_query($koneksi, "SHOW TABLES LIKE 'produk'");
if (!$table_check || mysqli_num_rows($table_check) == 0) {
    die("<p>Database belum siap. Jalankan <a href='setup/setup.php'>setup database</a> atau refresh halaman setelah setup selesai.</p>");
}

if($kategori_filter != "") {
    // Jika kategori dipilih, tampilkan produk sesuai kategori
    $data = mysqli_query($koneksi, "SELECT * FROM produk WHERE kategori = '$kategori_filter'");
} else {
    // Jika tidak ada filter, tampilkan semua produk
    $data = mysqli_query($koneksi, "SELECT * FROM produk");
}

if (!$data) {
    die("<p>Error database: " . mysqli_error($koneksi) . "</p>");
}
?>

<!-- Carousel Slide -->
<?php
$slider = mysqli_query($koneksi, "SELECT * FROM slider ORDER BY id DESC");
?>

<div class="carousel-container">
    <div class="carousel">

        <div class="carousel-slides" id="carouselSlides">

            <?php while($row = mysqli_fetch_assoc($slider)) { ?>
                <div class="slide">
                    <img src="<?php echo IMAGES_URL . htmlspecialchars($row['gambar']); ?>">

                    <div class="slide-content">
                        <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                        <p><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                    </div>
                </div>
            <?php } ?>

        </div>

        <button class="carousel-arrow prev" onclick="moveSlide(-1)">❮</button>
        <button class="carousel-arrow next" onclick="moveSlide(1)">❯</button>

        <div class="carousel-controls" id="carouselDots"></div>

    </div>
</div>

</div>
<div class="category-section">
    <h2 class="category-title">Pilih Kategori</h2>
    <div class="category-buttons">
        
        <a href="index.php" class="cat-btn <?php echo ($kategori_filter == '') ? 'active' : ''; ?>">
            Semua Produk
        </a>

        <?php
        // Ambil daftar kategori otomatis dari ENUM database
        $query_kat = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE 'kategori'");
        $row_kat = mysqli_fetch_array($query_kat);
        preg_match_all("/'([^']+)'/", $row_kat['Type'], $matches);
        $categories = $matches[1];

        // Tampilkan tombol untuk setiap kategori
        foreach ($categories as $cat) {
            $is_active = ($kategori_filter == $cat) ? 'active' : '';
            echo "<a href='index.php?kategori=$cat' class='cat-btn $is_active'>$cat</a>";
        }
        ?>
    </div>
</div>

<div class="product-container">
    <?php 
    if(mysqli_num_rows($data) > 0) {
        while($row = mysqli_fetch_assoc($data)) { 
    ?>
        <div class="card">
            <div class="card-image">
                <?php $gambarUtama = trim(explode(',', $row['gambar'])[0]); ?>
                <?php if ($gambarUtama && file_exists(IMAGES_PATH . $gambarUtama)): ?>
                    <img src="<?php echo IMAGES_URL . htmlspecialchars($gambarUtama); ?>" alt="<?php echo htmlspecialchars($row['nama']); ?>">
                <?php else: ?>
                    <div class="no-image-box">Tidak ada gambar</div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <h3 class="product-title"><?php echo htmlspecialchars($row['nama']); ?></h3>
                <p class="product-price">Rp <?php echo number_format($row['harga']); ?></p>
                <div class="card-action">
                    <a href="deskripsi.php?id=<?php echo $row['id']; ?>" class="btn-detail">Lihat Produk</a>
                </div>
            </div>
        </div>
    <?php 
        } 
    } else {
        echo "<p class='empty-msg'>Produk tidak tersedia untuk kategori ini.</p>";
    }
    ?>
</div>

<script>
let currentSlide = 0;
const slides = document.getElementById('carouselSlides');
const totalSlides = slides.children.length;

function initDots() {
    const dotsContainer = document.getElementById('carouselDots');
    dotsContainer.innerHTML = '';

    for(let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('div');
        dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
        dot.onclick = () => goToSlide(i);
        dotsContainer.appendChild(dot);
    }
}

function showSlide(n) {
    slides.style.transform = `translateX(-${n * 100}%)`;

    document.querySelectorAll('.carousel-dot').forEach((dot, index) => {
        dot.classList.toggle('active', index === n);
    });
}

function moveSlide(n) {
    currentSlide = (currentSlide + n + totalSlides) % totalSlides;
    showSlide(currentSlide);
}

function goToSlide(n) {
    currentSlide = n;
    showSlide(currentSlide);
}

setInterval(() => {
    moveSlide(1);
}, 4000);

initDots();
showSlide(0);
</script>
<?php include 'footer.php'; ?>
</body>
</html>

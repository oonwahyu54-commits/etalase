<?php
include_once __DIR__ . '/koneksi.php';
include 'header.php';

if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id='$id'");
    $data = mysqli_fetch_assoc($query);

    if(!$data){
        echo "Produk tidak ditemukan!";
        exit;
    }
} else {
    echo "ID tidak ditemukan!";
    exit;
}

// Memecah string gambar menjadi array
$foto_produk = array_filter(array_map('trim', explode(',', $data['gambar'])));
?>

<div class="detail-container">
    <div class="detail-card">
        
        <div class="detail-slider-container">
            <div class="detail-slider" id="mainSlider">
                <?php foreach($foto_produk as $img) : ?>
                    <div class="detail-slide">
                        <img src="gambar/<?php echo trim($img); ?>" alt="<?php echo $data['nama']; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if(count($foto_produk) > 1) : ?>
                <button class="slide-arrow prev" onclick="changeSlide(-1)">❮</button>
                <button class="slide-arrow next" onclick="changeSlide(1)">❯</button>
                
                <div class="slider-dots">
                    <?php foreach($foto_produk as $index => $img) : ?>
                        <span class="dot <?php echo ($index == 0) ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $index; ?>)"></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="detail-info">
            <h2><?php echo $data['nama']; ?></h2>
            <h3 class="price-tag">Rp <?php echo number_format($data['harga']); ?></h3>

            <p><b>Ukuran:</b> <?php echo isset($data['ukuran']) ? htmlspecialchars($data['ukuran']) : '-'; ?></p>
            <p><b>Stok:</b> <?php echo $data['stok']; ?></p>
            
            <div class="description-box">
                <b>Deskripsi:</b>
                <p>
                    <?php 
                    echo isset($data['deskripsi']) && !empty($data['deskripsi']) 
                    ? nl2br(htmlspecialchars($data['deskripsi'])) 
                    : 'Tidak ada deskripsi'; 
                    ?>
                </p>
            </div>

            <div class="purchase-options">
                <h3>Pilih Metode Pembelian</h3>
                <div class="btn-group">
                    <?php if (!empty($data['link_wa'])): ?>
                        <a href="<?php echo htmlspecialchars($data['link_wa']); ?>" target="_blank" class="btn btn-wa">💬 WhatsApp</a>
                    <?php endif; ?>
                    <?php if (!empty($data['link_shopee'])): ?>
                        <a href="<?php echo htmlspecialchars($data['link_shopee']); ?>" target="_blank" class="btn btn-shopee">🛒 Shopee</a>
                    <?php endif; ?>
                    <?php if (empty($data['link_wa']) && empty($data['link_shopee'])): ?>
                        <p class="no-links">Link pembelian belum tersedia. Silakan hubungi admin.</p>
                    <?php endif; ?>
                </div>
            </div>

            <br>
            <a href="index.php" class="btn-back">← Kembali</a>
        </div>
    </div>
</div>

<script>
let slideIndex = 0;
const slides = document.getElementById('mainSlider');
const totalSlides = slides.children.length;
const dots = document.querySelectorAll('.dot');

function updateSlider() {
    slides.style.transform = `translateX(-${slideIndex * 100}%)`;
    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === slideIndex);
    });
}

function changeSlide(n) {
    slideIndex = (slideIndex + n + totalSlides) % totalSlides;
    updateSlider();
}

function currentSlide(n) {
    slideIndex = n;
    updateSlider();
}
</script>
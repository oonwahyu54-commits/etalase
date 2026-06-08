-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Jun 2026 pada 17.26
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `etalase_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', '$2y$10$.jew6SpZJ7ynpdrb7Oz1uuXa.sFX4ooYA5JwAT9drp1DIQMpji/om', 'admin@example.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gambar_produk1`
--

CREATE TABLE `gambar_produk1` (
  `id_gambar` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `nama_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stok` int(11) NOT NULL DEFAULT 0,
  `kategori` enum('Gamis','Hijab Segi Empat','Aksesoris','Fashion','Lainnya') NOT NULL DEFAULT 'Gamis',
  `deskripsi` longtext DEFAULT NULL,
  `ukuran` varchar(255) DEFAULT NULL,
  `link_wa` varchar(500) DEFAULT NULL,
  `link_shopee` varchar(500) DEFAULT NULL,
  `gambar` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama`, `harga`, `stok`, `kategori`, `deskripsi`, `ukuran`, `link_wa`, `link_shopee`, `gambar`, `created_at`, `updated_at`, `id_admin`) VALUES
(3, 'DEENAY HIKARU, FAYE, KEVIA, LAICA, MISAKI, CHARLOTTE', 275000.00, 4, 'Hijab Segi Empat', 'nyaman di pakai', '-', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/DEENAY-HIKARU-FAYE-KEVIA-LAICA-MISAKI-CHARLOTTE-i.86314699.17420091608?extraParams=%7B%22display_model_id%22%3A29699209616%2C%22model_selection_logic%22%3A3%7D', '1778674235_0_id-11134207-7r98s-lnwbhtpf4wi176_resize_w900_nl.webp', '2026-05-13 12:10:35', '2026-05-19 09:34:38', 1),
(4, 'DICHA MAGNET PIN', 95000.00, 6, 'Aksesoris', 'ready \r\n\r\n*Dicha Magnetic Pin*\r\n- Black\r\n- Brown\r\n\r\nIdr 95k\r\n\r\nHappy shopping❤️❤️', '-', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/DICHA-MAGNET-PIN-i.86314699.25526475327?extraParams=%7B%22display_model_id%22%3A178171368012%2C%22model_selection_logic%22%3A3%7D', '1778676819_0_dichamagnet.webp,1778676819_1_dichamagnet2.webp', '2026-05-13 12:53:39', '2026-05-19 09:34:38', 1),
(5, 'BROSS DEENAY LOGO BARU MEDIUM', 170000.00, 5, 'Aksesoris', 'Bahan: Alloy High Quality \r\nWarna: Gold\r\nBentuk: Logo \"DNY\"\r\nKemasan box brooch & pouch brooch ', '-', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/BROSS-DEENAY-LOGO-BARU-MEDIUM-i.86314699.24476480285?extraParams=%7B%22display_model_id%22%3A250709871129%2C%22model_selection_logic%22%3A3%7D', '1778676924_0_brosdenay.webp,1778676924_1_brosdenay1.webp,1778676924_2_brosdenay2.webp', '2026-05-13 12:55:24', '2026-05-19 09:34:38', 1),
(6, 'DEENAY LIZA MATTE GODL LOGO BARU', 170000.00, 10, 'Hijab Segi Empat', 'Liza matte series :\r\nHitam\r\nPutih\r\nnavi\r\nyellow cream\r\nmustard\r\nclasic blue\r\nMaterial: Ultrafine \r\nFinishing : sewing+ matte\r\nLimited edition🤩🥳\r\n\r\nHappy shopping❤️❤️', '-', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/DEENAY-LIZA-MATTE-GODL-LOGO-BARU-i.86314699.21231962563?extraParams=%7B%22display_model_id%22%3A118043572956%2C%22model_selection_logic%22%3A3%7D', '1778677133_0_lizagold.webp', '2026-05-13 12:58:53', '2026-05-19 09:34:38', 1),
(7, 'DEENAY LIZA MATTE GOLD NEW LOGO BLACK', 170000.00, 5, 'Hijab Segi Empat', 'Deenay Liza Voal Plain Jahit Tepi\r\nBahan : voal ultrafine\r\nTampil cantik setiap hari dengan kerudung bahan lembut, ringan, dan mudah dibentuk.\r\nTidak panas, nyaman dipakai seharian, dan cocok untuk semua aktivitas', '-', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/DEENAY-LIZA-MATTE-GOLD-NEW-LOGO-BLACK-i.86314699.27110737895?extraParams=%7B%22display_model_id%22%3A29517465961%2C%22model_selection_logic%22%3A3%7D', '1778677210_0_lizablack.webp', '2026-05-13 13:00:10', '2026-05-19 09:34:38', 1),
(8, 'DEENAY LIZA MATTE GOLD NEW LOGO TOFU', 170000.00, 2, 'Hijab Segi Empat', 'Deenay Liza Voal Plain Jahit Tepi\r\nBahan : voal ultrafine\r\nTampil cantik setiap hari dengan kerudung bahan lembut, ringan, dan mudah dibentuk.\r\nTidak panas, nyaman dipakai seharian, dan cocok untuk semua aktivitas', '-', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/DEENAY-LIZA-MATTE-GOLD-NEW-LOGO-TOFU-i.86314699.29610725081?extraParams=%7B%22display_model_id%22%3A168408622121%2C%22model_selection_logic%22%3A3%7D', '1778677287_0_lizatofu.webp', '2026-05-13 13:01:27', '2026-05-19 09:34:38', 1),
(9, 'DEENAY LIZA MATTE GOLD GREEN SERIES', 170000.00, 7, 'Hijab Segi Empat', 'LIZA MATTE GOLD\r\n\r\n- Bahan: Deenay Voile / Voal Ultrafine\r\n- Finishing: Sewing\r\n- Logo: Matte Gold + Logam Pin \"d\" Gold \r\n\r\nKesamaan warna dengan yang asli 98% (karena efek cahaya dan layar masing-masing perangkat).\r\nTampil cantik setiap hari dengan kerudung bahan lembut, ringan, dan mudah dibentuk.\r\nTidak panas, nyaman dipakai seharian, dan cocok untuk semua aktivitas', '-', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/DEENAY-LIZA-MATTE-GOLD-GREEN-SERIES-i.86314699.24000603781?extraParams=%7B%22display_model_id%22%3A232259197402%2C%22model_selection_logic%22%3A3%7D', '1778686991_0_lizagreen.webp', '2026-05-13 15:43:11', '2026-05-19 09:34:38', 1),
(10, 'MANDJHA IVAN GUNAWAN RAYA SERIES', 260000.00, 12, 'Hijab Segi Empat', 'Mandjha raya series\r\n\r\n- Siap siap tampil makin cantik deh pakai Mandjha motif terbaru.\r\n- Pastinya bikin bingung mau milih motif apa, warna apa nihh.\r\n- yuu buruan biar terpancar pesonanya plus menjadi pelengkap untuk penampilan mu dihari raya.\r\n\r\nTampil cantik setiap hari dengan kerudung bahan lembut, ringan, dan mudah dibentuk.\r\nTidak panas, nyaman dipakai seharian, dan cocok untuk semua aktivitas\r\n', '-', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/MANDJHA-IVAN-GUNAWAN-RAYA-SERIES-i.86314699.15458599813?extraParams=%7B%22display_model_id%22%3A205885010271%2C%22model_selection_logic%22%3A3%7D', '1778687523_0_ivan1.webp,1778687523_1_ivan2.webp,1778687523_2_ivan3.webp,1778687523_3_ivan4.webp,1778687523_4_ivan5.webp,1778687523_5_ivan6.webp', '2026-05-13 15:52:03', '2026-05-19 09:34:38', 1),
(11, 'MUKENAH PAULA SERIES BY. KAMEA', 230000.00, 5, 'Lainnya', 'Mukenah Paula series mukenah by Kamea\r\nBahan cradenza, Kombinasi plisket bahan ceruty baby doll, Resleting depan.\r\n\r\nIbadah jadi lebih khusyuk dengan mukenah berbahan adem, halus, dan ringan.\r\nDesain simple, mudah dilipat, dan cocok dibawa bepergian ✈️\r\n\r\n💖 Bahan lembut & tidak panas\r\n💖 Ringan & travel friendly\r\n💖 Ukuran nyaman dipakai', '-', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/MUKENAH-PAULA-SERIES-BY.-KAMEA-i.86314699.24866405270?extraParams=%7B%22display_model_id%22%3A350614402184%2C%22model_selection_logic%22%3A3%7D', '1778688049_0_mukenah.webp', '2026-05-13 16:00:49', '2026-05-19 09:34:38', 1),
(12, 'Mukenah', 290000.00, 3, 'Lainnya', 'Hallo Bunda Kemiso!🤗\r\nKemiso merupakan merupakan brand fashion muslim yang menghadirkan mukena terbaik serta motif yang ekslusif\r\n\r\nKeunggulan Mukena Kemiso :\r\n\r\n1. Motif yang esklusif anti pasaran\r\n2. Menggunakan katun rayon premium (terbaik dikain jenisnya) \r\n\r\nNew Series!✨\r\n\r\nMukena Afiyah Series ini merupakan rangkaian mukena set terbaru dari Kemiso. Mukena ini di desain untuk wanita remaja sampai dewasa untuk pemakaian di rumah atu bepergian. Modelnya elegan membuat kesan mewah tapi tetap kualitasnya juara, Dipercantik dengan aksen dedaunan pada bagian atasan mukenanya sehingga semakin cantik ketika sedang dipakai. Kualitas bahan adem dan lembut. \r\n\r\nAtasan Mukena :\r\nPanjang Depan: 124 cm\r\nPanjang Belakang: 144 cm\r\nLingkar Wajah: 49 cm\r\nDetail Kepala: Tali\r\n\r\nRok Mukena :\r\nPanjang Rok: 117 cm\r\nLingkar Rok: 60 cm \r\nAll Size, Fit to XL \r\n\r\nSajadah :\r\nPremium Maxmara\r\nPanjang: 75 cm\r\nLebar: 45 cm\r\n\r\nMukena Kemiso sudah termasuk tas mukena berbentuk pouch dan satu set dengan sajadah sehingga lebih ringkas dan travelling friendly ', '-', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/MUKENAH-i.86314699.12584407186?extraParams=%7B%22display_model_id%22%3A194335630460%2C%22model_selection_logic%22%3A3%7D', '1778688673_0_Mukenah4.webp,1778688673_1_Mukenah3.webp,1778688673_2_mukenah_2.webp', '2026-05-13 16:11:13', '2026-05-19 09:34:38', 1),
(13, 'MANDJHA By IVAN GUNAWAN', 229000.00, 4, 'Hijab Segi Empat', 'KARAKTERISTIK MATERIAL :\r\nVoal Premium\r\nRingan\r\nMudah dibentuk\r\nTidak mudah kusut\r\nPerawatannya mudah\r\n\r\nTampil cantik setiap hari dengan kerudung bahan lembut, ringan, dan mudah dibentuk.\r\nTidak panas, nyaman dipakai seharian, dan cocok untuk semua aktivitas 💕', '-', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/MANDJHA-By-IVAN-GUNAWAN-i.86314699.13280385453?extraParams=%7B%22display_model_id%22%3A184335492137%2C%22model_selection_logic%22%3A3%7D', '1778688819_0_ivang.webp', '2026-05-13 16:13:39', '2026-05-19 09:34:38', 1),
(14, 'HIJAB MANDJHA IVAN GUNAWAN TERBARU 2', 260000.00, 20, 'Hijab Segi Empat', 'Mandjha raya series\r\n- Siap siap tampil makin cantik deh pakai Mandjha motif terbaru\r\n- Pastinya bikin bingung mau milih motif apa, warna apa nihh\r\n- yuu buruan biar terpancar pesonanya plus menjadi pelengkap untuk penampilan mu dihari raya ', '-', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/HIJAB-MANDJHA-IVAN-GUNAWAN-TERBARU-2-i.86314699.44152055132?extraParams=%7B%22display_model_id%22%3A260169187178%2C%22model_selection_logic%22%3A3%7D', '1778923546_0_gunawanbaru.webp', '2026-05-16 09:25:46', '2026-05-19 09:34:38', 1),
(15, 'MUKENAH BY INJI', 320000.00, 3, 'Lainnya', 'Mukena by INJI \r\nBahan Rayon Valencia\r\n- Bagian Kepala ikat tali\r\n- Bagian Kening kerut pakai karet\r\n- Panjang Rok = 119cm\r\n- Lingkar Rok = 66cm sebelum ditarik (full karet)\r\n- Panjang Atasan : Depan = 114cm\r\n                               Belakang = 135cm\r\n- Ukuran Tas = 27 x 6 x 23', '-', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/MUKENAH-BY-INJI-i.86314699.25820612403?extraParams=%7B%22display_model_id%22%3A188194181257%2C%22model_selection_logic%22%3A3%7D', '1778923669_0_inji1.webp,1778923669_1_inji2.webp,1778923669_2_inji3.webp', '2026-05-16 09:27:49', '2026-05-19 09:34:38', 1),
(16, 'MANDJHA IVAN GUNAEAN ZOY SERIES', 229000.00, 0, 'Hijab Segi Empat', 'Zoey Series By mandjha Ivan Gunawan\r\n* Mint \r\n* Grey\r\n* Black', '-', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/MANDJHA-IVAN-GUNAEAN-ZOY-SERIES-i.86314699.24640281761?extraParams=%7B%22display_model_id%22%3A128181140482%2C%22model_selection_logic%22%3A3%7D', '1778923795_0_zoy1.webp,1778923795_1_zoy2.webp,1778923795_2_zoy3.webp', '2026-05-16 09:29:55', '2026-05-19 09:34:38', 1),
(17, 'MANDJHA IVAN GUNAWAN TERBARU', 260000.00, 4, 'Hijab Segi Empat', 'MATERIAL\r\nVoal Premium \r\n\r\nKARAKTERISTIK MATERIAL\r\nRingan\r\nMudah dibentuk\r\nTidak mudah kusut\r\nPerawatannya mudah', '115x115', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/MANDJHA-IVAN-GUNAWAN-TERBARU-i.86314699.24316837948?extraParams=%7B%22display_model_id%22%3A253713291590%2C%22model_selection_logic%22%3A3%7D', '1778923916_0_barugunawan1.webp,1778923916_1_barugunawan2.webp,1778923916_2_barugunawan3.webp', '2026-05-16 09:31:56', '2026-05-19 09:34:38', 1),
(18, 'MANDJHA IVAN GUNAWAN TERBARU', 228997.00, 6, 'Hijab Segi Empat', 'MATERIAL\r\nVoal Premium \r\nKARAKTERISTIK MATERIAL\r\nRingan\r\nMudah dibentuk\r\nTidak mudah kusut\r\nPerawatannya mudah', '115x115', 'https://api.whatsapp.com/qr/T774FZXG3XZUA1?autoload=1&app_absent=0', 'https://shopee.co.id/MANDJHA-IVAN-GUNAWAN-TERBARU-i.86314699.25611766237?extraParams=%7B%22display_model_id%22%3A236031212278%2C%22model_selection_logic%22%3A3%7D', '1778924035_0_gunawanbaru0.webp', '2026-05-16 09:33:55', '2026-05-19 09:34:38', 1),
(19, 'Dress sinta & Leopard', 170000.00, 13, 'Fashion', 'Elegan dan anggun, cocok untuk pesta.', 'L', '', 'https://shopee.co.id/DRESS-SINTA-LEOPARD-i.86314699.2107195832?extraParams=%7B%22display_model_id%22%3A181376079218%2C%22model_selection_logic%22%3A3%7D', '1779182287_0_c98faef04b82267c7d001b7f1018a443.webp', '2026-05-19 09:18:07', '2026-05-19 09:34:38', 1),
(20, 'MANDJHA IVAN GUNAWAN CROWMATIC', 200000.00, 7, 'Gamis', 'Desainnya kokoh dan rapi serta tidak mudah bergeser meski aktif bergerak', '100 x 200 cm', 'https://wa.me/087718868172', 'https://shopee.co.id/MANDJHA-IVAN-GUNAWAN-CROWMATIC-i.86314699.25106134340?extraParams=%7B%22display_model_id%22%3A217249033334%2C%22model_selection_logic%22%3A3%7D', '1779182615_0_id-11134207-7r98r-lq05zvti67eu89_resize_w900_nl.webp', '2026-05-19 09:23:35', '2026-05-28 15:53:46', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `slider`
--

INSERT INTO `slider` (`id`, `gambar`, `judul`, `deskripsi`, `id_admin`) VALUES
(1, '1778673766_id-11134207-7rbka-m6zey2irmc6s38.webp', 'Koleksi Gamis Terbaru', 'Kualitas Premium dengan Harga Terjangkau', NULL),
(2, '1778673754_id-11134207-7rbk4-m6l2l9omgu3l04@resize_w450_nl.webp', 'Fashion Muslim Modern', 'Desain Eksklusif untuk Anda', NULL),
(3, '1778673738_id-11134201-7rbkc-m6e2i8y54dh082.webp', 'Koleksi Hijab Cantik', 'Berbagai Warna dan Model yang cantik', NULL),
(4, '1778673720_id-11134207-7r98s-lnwbhtpf4wi176@resize_w900_nl.webp', 'Tren Fashion Terkini', 'Bergabunglah dengan Ribuan Pelanggan Kami', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `gambar_produk1`
--
ALTER TABLE `gambar_produk1`
  ADD PRIMARY KEY (`id_gambar`),
  ADD KEY `fk_gambar_produk` (`id_produk`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produk_admin` (`id_admin`);

--
-- Indeks untuk tabel `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_slider_admin` (`id_admin`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `gambar_produk1`
--
ALTER TABLE `gambar_produk1`
  MODIFY `id_gambar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `gambar_produk1`
--
ALTER TABLE `gambar_produk1`
  ADD CONSTRAINT `fk_gambar_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_produk_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `slider`
--
ALTER TABLE `slider`
  ADD CONSTRAINT `fk_slider_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

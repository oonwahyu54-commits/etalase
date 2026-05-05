-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Bulan Mei 2026 pada 12.00
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama`, `harga`, `stok`, `kategori`, `deskripsi`, `ukuran`, `link_wa`, `link_shopee`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Mandjha Ivan Gunawan Scarf Raya Collection', 260000.00, 10, 'Hijab Segi Empat', 'Bismillah\r\n\r\nAssalamualaikum...\r\n*READY*\r\nMotif terbaru\r\nMandjha Ivan Gunawan\r\nScarf Raya Collection', '115x115', 'https://wa.me/qr/AKMPMWPRJWHZG1', 'https://shopee.co.id/Mandjha-Ivan-Gunawan-Scarf-Raya-Collection-i.86314699.25920608940?extraParams=%7B%22display_model_id%22%3A215812398593%2C%22model_selection_logic%22%3A3%7D', '1777251420_0_id_11134207_7rbk1_m6l2l9lkii0799.webp', '2026-04-27 00:57:00', '2026-04-27 00:57:40'),
(2, 'SCRAFT BY MONEL TERBARU', 220000.00, 13, 'Hijab Segi Empat', 'New Arrival!\r\nALL SCARF MONEL\r\nBahan: Voal Premium', '115x115', 'https://wa.me/qr/AKMPMWPRJWHZG1', 'https://shopee.co.id/SCRAFT-BY-MONEL-TERBARU-i.86314699.25631890620?extraParams=%7B%22display_model_id%22%3A147335145323%2C%22model_selection_logic%22%3A3%7D', '1777274185_0_id_11134207_7ra0i_mbw4p4ol8bx305_resize_w900_nl.webp', '2026-04-27 07:16:25', '2026-04-27 07:16:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `slider`
--

INSERT INTO `slider` (`id`, `gambar`, `judul`, `deskripsi`) VALUES
(1, 'gamis1.jpg', 'Koleksi Gamis Terbaru', 'Kualitas Premium dengan Harga Terjangkau'),
(2, 'gamis2.jpg', 'Fashion Muslim Modern', 'Desain Eksklusif untuk Anda'),
(3, 'hijab1.jpg', 'Koleksi Hijab Cantik', 'Berbagai Warna dan Model'),
(4, 'gamis3.jpg', 'Tren Fashion Terkini', 'Bergabunglah dengan Ribuan Pelanggan Kami');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$.jew6SpZJ7ynpdrb7Oz1uuXa.sFX4ooYA5JwAT9drp1DIQMpji/om', 'admin@example.com', 'admin', '2026-04-26 18:33:02');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `gambar_produk1`
--
ALTER TABLE `gambar_produk1`
  ADD PRIMARY KEY (`id_gambar`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `gambar_produk1`
--
ALTER TABLE `gambar_produk1`
  MODIFY `id_gambar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

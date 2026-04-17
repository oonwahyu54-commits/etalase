# Dokumentasi Koneksi Database XAMPP 3.3 - Etalase

## 📋 Ringkasan Perubahan

Semua file PHP di project **Etalase** telah diperbarui untuk kompatibilitas optimal dengan **XAMPP 3.3**.

---

## 🔧 Konfigurasi Database

### Database Connection Details
```
Host:     127.0.0.1 (localhost)
User:     root
Password: (kosong/none)
Database: etalase_db
Charset:  utf8mb4
Collation: utf8mb4_unicode_ci
```

### File Konfigurasi Utama
📄 **koneksi.php** - File utama yang berisi semua konfigurasi database

---

## ✅ File-File yang Sudah Terkoneksi

### Root Level Files (include 'koneksi.php')
- ✅ index.php
- ✅ tambah.php
- ✅ proses_tambah.php
- ✅ proses_hapus.php
- ✅ proses_edit.php
- ✅ login.php
- ✅ kategori.php
- ✅ edit.php
- ✅ deskripsi.php
- ✅ debug_produk.php
- ✅ fix_db.php
- ✅ aboutme.php

### Admin Directory Files (include '../koneksi.php')
- ✅ admin/dashboard.php
- ✅ admin/edit.php
- ✅ admin/produk-list.php
- ✅ admin/logout.php

### Setup Files (include 'koneksi.php')
- ✅ setup/setup.php
- ✅ setup/setup_db.php
- ✅ setup/setup_admin.php
- ✅ setup/setup_links.php

---

## 📊 Struktur Database

### Tabel: produk
| Field | Type | Keterangan |
|-------|------|-----------|
| id | INT (AUTO_INCREMENT) | Primary Key |
| nama | VARCHAR(255) | Nama produk |
| harga | DECIMAL(12,2) | Harga produk |
| stok | INT | Jumlah stok |
| kategori | ENUM | Kategori produk (Umum, Gamis, Hijab, Aksesori, Fashion, Lainnya) |
| deskripsi | LONGTEXT | Deskripsi lengkap |
| ukuran | VARCHAR(255) | Ukuran produk |
| link_wa | VARCHAR(500) | Link WhatsApp |
| link_shopee | VARCHAR(500) | Link Shopee |
| gambar | VARCHAR(1000) | Path gambar produk |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu update terakhir |

### Tabel: users
Tabel untuk menyimpan data login admin

---

## 🧪 Testing Koneksi

### Akses File Test
Buka di browser: `http://localhost/etalase/test_koneksi.php`

File ini akan memverifikasi:
- ✓ Koneksi MySQL
- ✓ Status database
- ✓ Charset utf8mb4
- ✓ Tabel produk
- ✓ Tabel users
- ✓ Jumlah data
- ✓ Konfigurasi PHP

---

## 🔄 Fitur Otomatis di koneksi.php

### 1. **Auto-Create Database**
Jika database `etalase_db` belum ada, sistem akan membuat secara otomatis.

### 2. **Character Set UTF8MB4**
Mendukung emoji dan karakter khusus lainnya.

### 3. **Error Handling**
- Exception handling dengan try-catch
- Error reporting yang detail

### 4. **Session SQL Mode**
Mengaktifkan `STRICT_TRANS_TABLES` untuk integritas data yang lebih baik.

---

## 📝 Perubahan pada koneksi.php

### Perbaikan yang dilakukan:
1. ✅ Menggunakan `define()` untuk konstanta database
2. ✅ Menambahkan error handling dengan exception
3. ✅ Mengaktifkan STRICT error mode untuk mysqli
4. ✅ Konfigurasi SQL mode untuk XAMPP 3.3
5. ✅ Setting charset di awal koneksi

### Sebelum:
```php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$dbName = "etalase_db";

$koneksi = mysqli_connect($host, $user, $pass, $dbName);
// ... error checking sederhana
```

### Sesudah:
```php
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'etalase_db');

try {
    $koneksi = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
    // ... error handling dengan exception dan session configuration
}
```

---

## 🚀 Cara Menggunakan

### Pertama Kali Setup
1. Akses: `http://localhost/etalase/setup/setup.php`
2. Sistem akan membuat database dan tabel secara otomatis

### Testing Koneksi
1. Akses: `http://localhost/etalase/test_koneksi.php`
2. Verifikasi semua status ✓

### Akses Aplikasi
1. Login di: `http://localhost/etalase/login.php`
2. Gunakan aplikasi normally

---

## ⚙️ Troubleshooting

### Jika error "Koneksi gagal"
1. Pastikan XAMPP sudah berjalan (Apache + MySQL)
2. Buka `http://localhost/phpmyadmin`
3. Pastikan bisa login dengan root (password kosong)

### Jika tabel tidak ada
1. Akses: `http://localhost/etalase/setup/setup.php`
2. Sistem akan membuat tabel secara otomatis
3. Atau akses: `http://localhost/etalase/test_koneksi.php` untuk verify

### Jika charset tidak utf8mb4
1. Buka phpmyadmin
2. Buka database `etalase_db`
3. Jalankan: `ALTER DATABASE etalase_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`

---

## 📞 Support

Untuk semua file database-related, pastikan sudah include:
- **Root level**: `include 'koneksi.php';`
- **Admin folder**: `include '../koneksi.php';`
- **Setup folder**: `include 'koneksi.php';` (dalam file setup di folder setup)

---

## ✨ Kesimpulan

Semua file PHP telah terkoneksi dengan database baru di XAMPP 3.3.
Sistem siap digunakan dengan:
- ✅ Error handling yang baik
- ✅ Charset UTF8MB4 untuk support karakter khusus
- ✅ Auto-create database dan tabel
- ✅ Session configuration yang tepat
- ✅ Kompatibilitas XAMPP 3.3

**Status: SIAP DIGUNAKAN** 🎉

---

*Last Updated: April 17, 2026*

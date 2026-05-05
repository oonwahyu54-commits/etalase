<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inda Gallery</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header>
    <div class="header-content">
        
        <div class="header-left">
            <h1 class="textjudul">INDA GALLERY</h1>

            <nav class="navbar">
                <a href="index.php">Beranda</a>
                <a href="aboutme.php">About Me</a>
                <a href="kontak.php">Kontak</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="admin/logout.php">Logout</a>
                <?php endif; ?>
            </nav>
        </div>

        <!-- LOGO JADI TOMBOL LOGIN -->
        <div class="logo">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="admin/dashboard.php" class="logo-btn" title="Dashboard Admin">
                    <img src="gambar/mitra.png" alt="Admin">
                </a>
            <?php else: ?>
                <a href="login.php" class="logo-btn" title="Login Admin">
                    <img src="gambar/mitra.png" alt="Login Admin">
                </a>
            <?php endif; ?>
        </div>

    </div>
</header>
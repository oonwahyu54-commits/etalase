<?php
session_start();
include_once __DIR__ . '/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username == '' || $password == '') {
        $error = 'Username dan password harus diisi';
    } else {

        $safeUsername = mysqli_real_escape_string($koneksi, $username);
        $query = mysqli_query($koneksi, "SELECT id, username, password, role FROM users WHERE username = '$safeUsername' LIMIT 1");

        if ($query && mysqli_num_rows($query) > 0) {
            $user = mysqli_fetch_assoc($query);
            $userId = $user['id'];
            $userName = $user['username'];
            $userPassword = $user['password'];
            $userRole = isset($user['role']) ? strtolower(trim($user['role'])) : '';

            if (password_verify($password, $userPassword) || $password === $userPassword) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $userName;
                $_SESSION['role'] = !empty($userRole) ? $userRole : 'admin';

                header('Location: admin/dashboard.php');
                exit;
            }

            $error = 'Password salah';
        } elseif ($query) {
            $error = 'Username tidak ditemukan';
        } else {
            $error = 'Database error: ' . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Inda Gallery</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <h2>Login Admin</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Masukkan username Anda"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Masukkan password Anda"
                    required
                >
            </div>

            <button type="submit" class="login-btn"> Login</button>
        </form>

        <div class="back-link">
            <a href="index.php">← Kembali ke Beranda</a>
        </div>
    </div>
</div>

</body>
</html>

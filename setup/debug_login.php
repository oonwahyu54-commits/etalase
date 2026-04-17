<?php
/**
 * Login Troubleshooting & Debug
 */

require_once __DIR__ . '/../koneksi.php';

$debug_info = [];

// Check 1: Database Connection
$debug_info['db_connected'] = ($koneksi && !mysqli_connect_errno());

// Check 2: Users Table Exists
$check_table = mysqli_query($koneksi, "SHOW TABLES LIKE 'users'");
$debug_info['users_table_exists'] = (mysqli_num_rows($check_table) > 0);

// Check 3: Users Count
if ($debug_info['users_table_exists']) {
    $count_result = mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM users");
    $count_row = mysqli_fetch_assoc($count_result);
    $debug_info['users_count'] = $count_row['cnt'];
}

// Check 4: List all users
if ($debug_info['users_table_exists']) {
    $users_result = mysqli_query($koneksi, "SELECT * FROM users");
    $debug_info['users_list'] = mysqli_fetch_all($users_result, MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login Debug - Inda Gallery</title>
</head>
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .form-group button:hover {
            background: #c007a1;
        }
    </style>
</head>
<body>

<div class="debug-container">
    <div class="debug-header">
        <h1>🔍 Login Troubleshoot & Debug</h1>
        <p>Diagnosis masalah login dan database</p>
    </div>

    <!-- Database Status -->
    <div class="debug-section">
        <h2>✅ Database Status</h2>

        <?php if ($debug_info['db_connected']): ?>
            <div class="status-ok">
                <strong>✓ Database Connected</strong><br>
                Server: localhost | Database: etalase_db | User: root
            </div>
        <?php else: ?>
            <div class="status-error">
                <strong>✗ Database Connection Failed</strong><br>
                Error: <?php echo mysqli_connect_error(); ?>
            </div>
        <?php endif; ?>

        <?php if ($debug_info['users_table_exists']): ?>
            <div class="status-ok">
                <strong>✓ Users Table Exists</strong><br>
                Total users: <?php echo $debug_info['users_count']; ?>
            </div>
        <?php else: ?>
            <div class="status-error">
                <strong>✗ Users Table NOT Found</strong><br>
                Jalankan setup untuk membuat table.
            </div>
        <?php endif; ?>
    </div>

    <!-- Users List -->
    <div class="debug-section">
        <h2>👥 Registered Users</h2>

        <?php if ($debug_info['users_count'] > 0): ?>
            <div class="status-ok">
                <strong>✓ Ada <?php echo $debug_info['users_count']; ?> user terdaftar</strong>
            </div>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                </tr>
                <?php foreach ($debug_info['users_list'] as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($user['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="status-warning">
                <strong>⚠ Tidak ada user terdaftar</strong><br>
                Anda perlu membuat akun admin terlebih dahulu.
            </div>
        <?php endif; ?>
    </div>

    <!-- Troubleshooting Steps -->
    <div class="debug-section">
        <h2>🔧 Troubleshooting Steps</h2>

        <?php if (!$debug_info['db_connected']): ?>
            <div class="step-box">
                <strong>Problem: Database connection failed</strong><br>
                <strong>Solution:</strong>
                <ul>
                    <li>Pastikan XAMPP/MySQL sudah running</li>
                    <li>Check file koneksi.php di root folder</li>
                    <li>Verifikasi credentials: host=localhost, user=root, password=(kosong)</li>
                    <li>Pastikan database "etalase_db" sudah ada di MySQL</li>
                </ul>
            </div>
        <?php elseif (!$debug_info['users_table_exists']): ?>
            <div class="step-box">
                <strong>Problem: Users table tidak ditemukan</strong><br>
                <strong>Solution:</strong>
                <ol>
                    <li>Buka <a href="setup.php">setup.php</a></li>
                    <li>Klik button untuk membuat tables</li>
                    <li>Tunggu hingga table users dan pesanan terbuat</li>
                </ol>
            </div>
        <?php elseif ($debug_info['users_count'] == 0): ?>
            <div class="step-box">
                <strong>Problem: Tidak ada admin account</strong><br>
                <strong>Solution:</strong>
                <ol>
                    <li>Buka <a href="setup_admin.php">setup_admin.php</a></li>
                    <li>Isi form dengan data admin Anda</li>
                    <li>Klik "Buat Akun Admin"</li>
                    <li>Setelah berhasil, baru bisa login</li>
                </ol>
            </div>
        <?php else: ?>
            <div class="status-ok">
                <strong>✓ Semua setup sudah selesai</strong><br>
                Database terhubung, users table ada, dan admin account sudah terdaftar.
                <br><br>
                <a href="../login.php" class="btn btn-primary" style="display: inline-block; margin-top: 10px;">Login Sekarang →</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Test Login -->
    <div class="debug-section">
        <h2>🧪 Test Login</h2>

        <?php if ($debug_info['users_count'] > 0): ?>
            <p>Gunakan salah satu username di bawah untuk test login:</p>
            <div class="test-form">
                <h3>Test dengan User yang Terdaftar:</h3>
                <?php foreach ($debug_info['users_list'] as $user): ?>
                    <div class="step-box">
                        <strong>Username:</strong> <code><?php echo htmlspecialchars($user['username']); ?></code><br>
                        <strong>Note:</strong> Gunakan password yang Anda buat saat membuat account ini.<br>
                        <a href="../login.php" class="btn btn-primary" style="margin-top: 10px;">Login →</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick Setup Guide -->
    <div class="debug-section">
        <h2>📋 Quick Setup Guide</h2>

        <strong>Jika belum selesai setup, ikuti langkah di bawah:</strong>

        <div class="action-buttons" style="margin-top: 20px;">
            <?php if (!$debug_info['users_table_exists']): ?>
                <a href="setup.php" class="btn btn-primary">1️⃣ Buat Database Tables</a>
            <?php endif; ?>

            <?php if ($debug_info['users_table_exists'] && $debug_info['users_count'] == 0): ?>
                <a href="setup_admin.php" class="btn btn-primary">2️⃣ Buat Admin Account</a>
            <?php endif; ?>

            <?php if ($debug_info['users_count'] > 0): ?>
                <a href="../login.php" class="btn btn-primary">🔐 Login</a>
            <?php endif; ?>

            <a href="test_db.php" class="btn btn-secondary">🧪 Test Database</a>
            <a href="../index.php" class="btn btn-secondary">🏠 Home</a>
        </div>
    </div>

    <!-- Common Issues -->
    <div class="debug-section">
        <h2>❓ Common Issues & Solutions</h2>

        <div class="step-box">
            <strong>Q: Lupa username/password</strong><br>
            A: Hubungi admin atau reset melalui database langsung.
        </div>

        <div class="step-box">
            <strong>Q: Login gagal tapi username dan password benar</strong><br>
            A: Cek apakah password sudah di-hash dengan bcrypt. Jika tidak, buat admin baru.
        </div>

        <div class="step-box">
            <strong>Q: "Users table not found" error</strong><br>
            A: Buka setup.php dan jalankan database setup.
        </div>

        <div class="step-box">
            <strong>Q: Database connection error</strong><br>
            A: Pastikan MySQL sudah running dan credentials di koneksi.php benar.
        </div>
    </div>
</div>

</body>
</html>

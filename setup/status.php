<?php
/**
 * Complete System Status & Connection Checker
 * Verifikasi semua koneksi dan setup
 */

require_once __DIR__ . '/../koneksi.php';

$system_status = [
    'database' => false,
    'users_table' => false,
    'produk_table' => false,
    'admin_exists' => false,
    'all_ok' => false
];

// Check 1: Database Connection
if ($koneksi && !mysqli_connect_errno()) {
    $system_status['database'] = true;
}

// Check 2: Users Table
if ($system_status['database']) {
    $check_users = mysqli_query($koneksi, "SHOW TABLES LIKE 'users'");
    $system_status['users_table'] = (mysqli_num_rows($check_users) > 0);
}

// Check 3: Produk Table
if ($system_status['database']) {
    $check_produk = mysqli_query($koneksi, "SHOW TABLES LIKE 'produk'");
    $system_status['produk_table'] = (mysqli_num_rows($check_produk) > 0);
}

// Check 4: Admin User
if ($system_status['database'] && $system_status['users_table']) {
    $check_admin = mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM users");
    $admin_count = mysqli_fetch_assoc($check_admin);
    $system_status['admin_exists'] = ($admin_count['cnt'] > 0);
}

// Check 5: Overall Status
$system_status['all_ok'] = (
    $system_status['database'] && 
    $system_status['users_table'] && 
    $system_status['produk_table'] && 
    $system_status['admin_exists']
);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>System Status - Inda Gallery</title>
</head>
<body>

<div class="status-container">
    <div class="status-header">
        <h1>🔌 System Status & Connection</h1>
        <p>Verifikasi koneksi dan setup lengkap sistem</p>
    </div>

    <!-- Overall Status -->
    <div class="status-grid">
        <div class="status-card <?php echo $system_status['all_ok'] ? 'ok' : 'error'; ?>">
            <h3>System Status</h3>
            <div class="status-text">
                <span class="status-indicator <?php echo $system_status['all_ok'] ? 'ok' : 'error'; ?>"></span>
                <?php echo $system_status['all_ok'] ? '✓ Ready' : '✗ Not Ready'; ?>
            </div>
        </div>

        <div class="status-card <?php echo $system_status['database'] ? 'ok' : 'error'; ?>">
            <h3>Database</h3>
            <div class="status-text">
                <span class="status-indicator <?php echo $system_status['database'] ? 'ok' : 'error'; ?>"></span>
                <?php echo $system_status['database'] ? '✓ Connected' : '✗ Failed'; ?>
            </div>
        </div>

        <div class="status-card <?php echo $system_status['users_table'] ? 'ok' : 'error'; ?>">
            <h3>Users Table</h3>
            <div class="status-text">
                <span class="status-indicator <?php echo $system_status['users_table'] ? 'ok' : 'error'; ?>"></span>
                <?php echo $system_status['users_table'] ? '✓ Exists' : '✗ Missing'; ?>
            </div>
        </div>

        <div class="status-card <?php echo $system_status['produk_table'] ? 'ok' : 'error'; ?>">
            <h3>Produk Table</h3>
            <div class="status-text">
                <span class="status-indicator <?php echo $system_status['produk_table'] ? 'ok' : 'error'; ?>"></span>
                <?php echo $system_status['produk_table'] ? '✓ Exists' : '✗ Missing'; ?>
            </div>
        </div>

        <div class="status-card <?php echo $system_status['admin_exists'] ? 'ok' : 'error'; ?>">
            <h3>Admin Account</h3>
            <div class="status-text">
                <span class="status-indicator <?php echo $system_status['admin_exists'] ? 'ok' : 'error'; ?>"></span>
                <?php echo $system_status['admin_exists'] ? '✓ Created' : '✗ Missing'; ?>
            </div>
        </div>
    </div>

    <!-- Recommended Actions -->
    <div class="actions-section">
        <h2>📋 Next Steps</h2>

        <?php if ($system_status['all_ok']): ?>
            <div class="success-box">
                ✓ Sistem Anda sudah siap digunakan! Lanjutkan ke login.
            </div>
            <div class="action-buttons">
                <a href="../login.php" class="action-btn primary">🔐 Login</a>
                <a href="../index.php" class="action-btn primary">🏠 Home</a>
                <a href="../admin/dashboard.php" class="action-btn secondary">📊 Dashboard</a>
            </div>
        <?php elseif (!$system_status['users_table']): ?>
            <div class="error-box">
                ⚠ Table users belum dibuat. Jalankan setup terlebih dahulu.
            </div>
            <div class="action-buttons">
                <a href="setup.php" class="action-btn primary">⚙️ Run Setup</a>
            </div>
        <?php elseif (!$system_status['admin_exists']): ?>
            <div class="error-box">
                ⚠ Admin account belum dibuat. Buat admin terlebih dahulu.
            </div>
            <div class="action-buttons">
                <a href="setup_admin.php" class="action-btn primary">👤 Create Admin</a>
                <a href="setup.php" class="action-btn secondary">⚙️ Setup</a>
            </div>
        <?php else: ?>
            <div class="error-box">
                ⚠ Terdapat masalah dengan koneksi database. Periksa kembali.
            </div>
            <div class="action-buttons">
                <a href="test_db.php" class="action-btn primary">🧪 Test DB</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Detailed Information -->
    <div class="details-section">
        <h2>📊 Detailed Information</h2>

        <h3>Connection Details</h3>
        <table>
            <tr>
                <th>Property</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Host</td>
                <td><code>localhost</code></td>
            </tr>
            <tr>
                <td>Database</td>
                <td><code>etalase_db</code></td>
            </tr>
            <tr>
                <td>User</td>
                <td><code>root</code></td>
            </tr>
            <tr>
                <td>Port</td>
                <td><code>3306 (default)</code></td>
            </tr>
        </table>

        <h3 style="margin-top: 30px;">Database Tables</h3>
        <?php
        if ($system_status['database']) {
            $tables = mysqli_query($koneksi, "SHOW TABLES");
            if (mysqli_num_rows($tables) > 0) {
                echo "<table>";
                echo "<tr><th>Table</th><th>Records</th><th>Status</th></tr>";
                while ($row = mysqli_fetch_assoc($tables)) {
                    $table = reset($row);
                    $count = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM $table"))['c'];
                    $status = ($count > 0) ? "✓ OK" : "⚠ Empty";
                    echo "<tr><td><strong>$table</strong></td><td>$count</td><td>$status</td></tr>";
                }
                echo "</table>";
            }
        }
        ?>

        <h3 style="margin-top: 30px;">Admin Accounts</h3>
        <?php
        if ($system_status['users_table']) {
            $users = mysqli_query($koneksi, "SELECT id, username, email, role, created_at FROM users");
            if (mysqli_num_rows($users) > 0) {
                echo "<table>";
                echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th></tr>";
                while ($user = mysqli_fetch_assoc($users)) {
                    echo "<tr>";
                    echo "<td>" . $user['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                    echo "<td>" . $user['role'] . "</td>";
                    echo "<td>" . date('d-m-Y H:i', strtotime($user['created_at'])) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='color: #d609b4; font-weight: bold;'>Belum ada admin. <a href='setup_admin.php'>Buat admin →</a></p>";
            }
        }
        ?>
    </div>
</div>

</body>
</html>

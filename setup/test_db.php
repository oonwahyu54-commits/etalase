<?php
/**
 * Database Connection Test & Setup
 */

require_once __DIR__ . '/../koneksi.php';
?> 

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Database Connection Test - Inda Gallery</title>
</head>
<body>

<div class="test-container">
<div class="test-card">
<h2>🔌 Database Connection Test</h2>

<?php
// Test 1: Connection Status
if ($koneksi) {
    echo "<div class='status-box status-success'>✓ Database Connected Successfully!</div>";
} else {
    echo "<div class='status-box status-error'>✗ Database Connection Failed!</div>";
}
?>

<div class="test-section">
<h3>📊 Connection Details</h3>

<table>
<tr>
<th>Property</th>
<th>Value</th>
</tr>

<tr>
<td>Database Host</td>
<td><code>localhost</code></td>
</tr>

<tr>
<td>Database User</td>
<td><code>root</code></td>
</tr>

<tr>
<td>Database Name</td>
<td><code>etalase_db</code></td>
</tr>

<tr>
<td>Connection Status</td>
<td>
<?php
if ($koneksi) {
echo "<span style='color: green; font-weight: bold;'>✓ Connected</span>";
} else {
echo "<span style='color: red; font-weight: bold;'>✗ Disconnected</span>";
}
?>
</td>
</tr>

</table>
</div>

<div class="test-section">
<h3>📋 Database Tables</h3>

<?php

$tables_result = mysqli_query($koneksi, "SHOW TABLES");

if ($tables_result && mysqli_num_rows($tables_result) > 0) {

echo "<table>";
echo "<tr><th>Table Name</th><th>Record Count</th></tr>";

while ($table = mysqli_fetch_row($tables_result)) {

$table_name = $table[0];

$count_query = mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM `$table_name`");

$count = 0;

if ($count_query) {
$row = mysqli_fetch_assoc($count_query);
$count = $row['cnt'];
}

echo "<tr>";
echo "<td><strong>$table_name</strong></td>";
echo "<td>$count records</td>";
echo "</tr>";
}

echo "</table>";

} else {

echo "<div class='status-box status-info'>No tables found or query error.</div>";

}

?>

</div>

<div class="test-section">
<h3>👥 Users Table Status</h3>

<?php

$users_check = mysqli_query($koneksi, "SHOW TABLES LIKE 'users'");

if ($users_check && mysqli_num_rows($users_check) > 0) {

echo "<div class='status-box status-success'>✓ Users table exists!</div>";

$users = mysqli_query($koneksi, "SELECT id, username, email, role FROM users");

if ($users && mysqli_num_rows($users) > 0) {

echo "<table>";
echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th></tr>";

while ($user = mysqli_fetch_assoc($users)) {

echo "<tr>";
echo "<td>".$user['id']."</td>";
echo "<td><strong>".htmlspecialchars($user['username'])."</strong></td>";
echo "<td>".htmlspecialchars($user['email'])."</td>";
echo "<td>".$user['role']."</td>";
echo "</tr>";

}

echo "</table>";

} else {

echo "<div class='status-box status-info'>No admin users found. 
<a href='setup_admin.php' class='action-btn'>Create Admin</a></div>";

}

} else {

echo "<div class='status-box status-error'>✗ Users table not found!</div>";
echo "<a href='setup.php' class='action-btn'>Run Setup</a>";

}

?>

</div>

<div class="test-section">

<h3>🧪 Test Query</h3>

<p>Sample query to test database functionality:</p>

<div class="code">
SELECT * FROM produk LIMIT 1;
</div>

<?php

$test = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk");

if ($test) {

$result = mysqli_fetch_assoc($test);

echo "<div class='status-box status-success'>✓ Query successful! Total products: ".$result['total']."</div>";

} else {

echo "<div class='status-box status-error'>✗ Query failed: ".mysqli_error($koneksi)."</div>";

}

?>

</div>

<div style="text-align:center;margin-top:30px;">
<a href="../index.php" class="action-btn">← Back to Home</a>
<a href="setup.php" class="action-btn">Setup Database</a>
<a href="../login.php" class="action-btn">Go to Login</a>
</div>

</div>
</div>

</body>
</html>
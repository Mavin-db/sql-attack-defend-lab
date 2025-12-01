<?php
// login.php – vulnerable login with debug output

// Show all PHP errors (development only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database config
$host = 'localhost';
$db   = 'shop_lab';
$user = 'shop_vuln';
$pass = 'ShopVulnPass!';

// Connect to DB
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If this file is accessed directly via GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<h1>Login handler</h1>";
    echo "<p>Submit the login form from <a href='index.php'>index.php</a>.</p>";
    exit;
}

// Get input directly (no sanitisation)
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Debug output
echo "<p>DEBUG: username='" . htmlspecialchars($username) . "'</p>";
echo "<p>DEBUG: password='" . htmlspecialchars($password) . "'</p>";

// Vulnerable SQL query
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

// Debug SQL output
echo "<p>DEBUG SQL: " . htmlspecialchars($sql) . "</p>";

$result = $conn->query($sql);

if (!$result) {
    die("Query error: " . $conn->error);
}

if ($result->num_rows === 1) {
    $userRow = $result->fetch_assoc();
    echo "<h1>Welcome, " . htmlspecialchars($userRow['username']) . "!</h1>";
    echo "<p>Role: " . htmlspecialchars($userRow['role']) . "</p>";
    echo "<p>(This login is vulnerable to SQL injection – lab only.)</p>";
} else {
    echo "<h1>Login failed</h1>";
    echo "<p>Invalid username or password.</p>";
    echo "<p><a href='index.php'>Back to login</a></p>";
}

$conn->close();


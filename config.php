<?php
// ✅ ONLY START SESSION ONCE HERE
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', '/butch-expense-web/');
define('BASE_URL', 'http://localhost/butch-expense-web/');

$host = 'localhost';
$dbname = 'dynamic_web';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
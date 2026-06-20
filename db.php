<?php
$host = '127.0.0.1';
$dbname = 'expense_tracker';
$username = 'root';
$password = ''; // XAMPP default password is blank

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // Log exception safely to server error logs for security audits
    error_log("Database Connection Failed: " . $e->getMessage());
    die("An internal processing error occurred. Please try again later.");
}
?>
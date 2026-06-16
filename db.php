<?php
$host = 'localhost';
$db   = 'expense_tracker';
$user = 'root';
$pass = ''; // Default XAMPP password is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    // Log this to your Error Logs in a real environment
    die("Database connection failed.");
}
?>
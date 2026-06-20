<?php
session_start();
$host = 'localhost';
$dbname = 'dynamic_web';
$username = 'root';
$password = ''; // default XAMPP password is empty

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
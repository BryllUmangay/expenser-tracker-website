<?php
include 'config.php'; // ✅
if (!isset($_SESSION['user_id'])) { 
    header("Location: ".BASE_PATH."login.php"); 
    exit; 
}

$user_id = $_SESSION['user_id'];
$expense_date = $_POST['expense_date'];
$description = $_POST['description'];
$amount = $_POST['amount'];
$category = $_POST['category'];

$stmt = $conn->prepare("INSERT INTO expenses (user_id, expense_date, description, amount, category) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issds", $user_id, $expense_date, $description, $amount, $category);

if ($stmt->execute()) {
    header("Location: ".BASE_PATH."dashboard.php?msg=expense_added"); // ✅
} else {
    echo "Error: " . $conn->error;
}
?>
<?php
require 'db.php';
session_start();

// Ensure the user is authenticated before logging an expense
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $invoice_path = null;

    // Secure File Upload Logic
    if (isset($_FILES['invoice']) && $_FILES['invoice']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        $fileType = mime_content_type($_FILES['invoice']['tmp_name']);
        
        if (in_array($fileType, $allowedTypes)) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir);
            
            // Create unique filename to prevent overwriting
            $fileName = uniqid() . '-' . basename($_FILES['invoice']['name']);
            $destination = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['invoice']['tmp_name'], $destination)) {
                $invoice_path = $destination;
            }
        } else {
            die("Invalid file type. Only JPG, PNG, and PDF allowed.");
        }
    }

    // Insert into Database securely using Prepared Statements
    $stmt = $pdo->prepare("INSERT INTO expenses (user_id, amount, description, invoice_path) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $amount, $description, $invoice_path]);

    echo "Expense logged successfully!";
}
?>
<?php
require 'db.php';
session_start();

// Ensure the user is authenticated before logging an expense
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $expense_date = $_POST['expense_date'] ?? '';
    $invoice_filename = null;

    // Validate required fields
    if (empty($description) || empty($amount) || empty($expense_date)) {
        die("All fields are required.");
    }

    if (!is_numeric($amount) || $amount <= 0) {
        die("Amount must be a positive number.");
    }

    // Secure File Upload Logic — matches index.php validation
    if (isset($_FILES['invoice']) && $_FILES['invoice']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['invoice']['tmp_name'];
        $originalName = $_FILES['invoice']['name'];
        $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        if (in_array($fileExtension, $allowedExtensions)) {
            // Cryptographically randomize filename
            $invoice_filename = bin2hex(random_bytes(16)) . '.' . $fileExtension;
            $uploadDir = __DIR__ . '/uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destPath = $uploadDir . $invoice_filename;
            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                die("Failed to store uploaded invoice.");
            }
        } else {
            die("Invalid file type. Only JPG, PNG, and PDF allowed.");
        }
    }

    // Insert into Database using Prepared Statements — matching index.php schema
    $stmt = $pdo->prepare("INSERT INTO expenses (user_id, description, amount, expense_date, invoice_filename) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $description, $amount, $expense_date, $invoice_filename]);

    echo "Expense logged successfully!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Expense - Expense Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-box">
        <h2>Upload Expense</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="description" placeholder="Description" required>
            <input type="number" step="0.01" name="amount" placeholder="Amount ($)" required>
            <input type="date" name="expense_date" required>
            <label style="display:block; margin: 10px 5px 5px 5px;">Upload Invoice Receipt (PDF/Image):</label>
            <input type="file" name="invoice">
            <button type="submit">Submit Expense</button>
        </form>
        <p><a href="index.php">Back to Dashboard</a></p>
    </div>
</body>
</html>

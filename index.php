<?php
session_start();
// If the session variable doesn't exist, redirect them to login page immediately
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Expense Tracker</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input, button, textarea { width: 100%; margin-bottom: 15px; padding: 10px; box-sizing: border-box; }
        button { background-color: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>

<div class="container">
    <h2>Log Daily Expense</h2>
    <form action="upload_expense.php" method="POST" enctype="multipart/form-data">
        <label for="amount">Amount ($):</label>
        <input type="number" id="amount" name="amount" step="0.01" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="3" required></textarea>

        <label for="invoice">Upload Invoice (PDF/Image):</label>
        <input type="file" id="invoice" name="invoice" accept=".pdf, .jpg, .jpeg, .png">

        <button type="submit">Submit Expense</button>
    </form>
</div>

</body>
</html>
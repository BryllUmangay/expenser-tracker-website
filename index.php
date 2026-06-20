<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $desc = trim($_POST['description']);
    $amount = floatval($_POST['amount']);
    $date = $_POST['expense_date'];
    $filename = null;

    // Handle Multipart Invoice Upload Execution
    if (isset($_FILES['invoice']) && $_FILES['invoice']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['invoice']['tmp_name'];
        $originalName = $_FILES['invoice']['name'];
        $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        if (in_array($fileExtension, $allowedExtensions)) {
            // Cryptographically randomize filename for Windows server storage safety
            $filename = bin2hex(random_bytes(16)) . '.' . $fileExtension;
            $uploadDir = __DIR__ . '/uploads/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $destPath = $uploadDir . $filename;
            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                $message = "Failed to store uploaded invoice artifact.";
                $filename = null;
            }
        } else {
            $message = "Insecure file extension blocked.";
        }
    }

    if ($amount > 0 && !empty($desc) && !empty($date) && $message === '') {
        try {
            $stmt = $pdo->prepare("INSERT INTO expenses (user_id, description, amount, expense_date, invoice_filename) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $desc, $amount, $date, $filename]);
            $message = "Expense logged successfully.";
        } catch (PDOException $e) {
            error_log("Insertion Error: " . $e->getMessage());
            $message = "Failed to record expense telemetry.";
        }
    }
}

// Fetch only entries matching current user context
$stmt = $pdo->prepare("SELECT * FROM expenses WHERE user_id = ? ORDER BY expense_date DESC");
$stmt->execute([$userId]);
$expenses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Expense Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="dash-header">
            <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
            <a href="logout.php" class="logout-btn">Terminate Session</a>
        </header>

        <?php if($message): ?><p class="info-msg"><?= htmlspecialchars($message) ?></p><?php endif; ?>

        <section class="form-section">
            <h3>Log a New Daily Expense</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="description" placeholder="Description (e.g., Office Supplies)" required>
                <input type="number" step="0.01" name="amount" placeholder="Amount ($)" required>
                <input type="date" name="expense_date" required>
                <label style="display:block; margin: 10px 5px 5px 5px;">Upload Invoice Receipt (PDF/Image):</label>
                <input type="file" name="invoice">
                <button type="submit">Submit Entry</button>
            </form>
        </section>

        <section class="table-section">
            <h3>Historical Expense Records</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Invoice Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($expenses)): ?>
                        <tr><td colspan="4">No historical records discovered.</td></tr>
                    <?php else: ?>
                        <?php foreach($expenses as $exp): ?>
                            <tr>
                                <td><?= htmlspecialchars($exp['expense_date']) ?></td>
                                <td><?= htmlspecialchars($exp['description']) ?></td>
                                <td>$<?= htmlspecialchars(number_format($exp['amount'], 2)) ?></td>
                                <td>
                                    <?php if($exp['invoice_filename']): ?>
                                        <a href="uploads/<?= htmlspecialchars($exp['invoice_filename']) ?>" target="_blank">View Invoice</a>
                                    <?php else: ?>
                                        None
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
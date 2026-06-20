<?php 
include 'config.php'; 

if (!isset($_SESSION['user_id'])) { 
    header("Location: ".BASE_PATH."login.php"); 
    exit; 
}

$user_id = $_SESSION['user_id'];

// Fetch user expenses
$expenses = $conn->query("SELECT * FROM expenses WHERE user_id = $user_id ORDER BY expense_date DESC");

// Calculate TOTAL EXPENSE
$total_result = $conn->query("SELECT SUM(amount) AS total FROM expenses WHERE user_id = $user_id");
$total_row = $total_result->fetch_assoc();
$total_expense = $total_row['total'] ?? 0;

// Fetch UPLOADED INVOICES history
$invoices = $conn->query("
    SELECT i.*, e.description, e.amount 
    FROM invoices i 
    LEFT JOIN expenses e ON i.expense_id = e.id 
    WHERE i.user_id = $user_id 
    ORDER BY i.upload_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Expense Tracker - Dashboard</title>
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>style.css">
</head>
<body>
    <!-- ✅ NO SIDEBAR ANYMORE -->
    <div class="main-container">
        <!-- HEADER -->
        <div class="dash-header">
            <div class="welcome-text">Welcome back, <strong><?= $_SESSION['fullname'] ?></strong></div>
            <a href="<?php echo BASE_PATH; ?>logout.php" class="logout-btn">Logout</a>
        </div>

        <!-- TOTAL EXPENSE CARD -->
        <div class="total-box">
            Total Expenses: ₱ <?= number_format($total_expense, 2) ?>
        </div>

        <!-- LOG EXPENSE CARD -->
        <div class="card">
            <div class="card-header">LOG DAILY EXPENSE</div>
            <div class="form-section">
                <form method="POST" action="<?php echo BASE_PATH; ?>add_expense.php">
                    <input type="date" name="expense_date" required>
                    <input type="text" name="description" placeholder="Description (e.g. Grocery, Transport)" required>
                    <input type="number" step="0.01" name="amount" placeholder="Amount (PHP)" required>
                    <select name="category" required>
                        <option value="">Select Category</option>
                        <option>Food</option>
                        <option>Transport</option>
                        <option>Utilities</option>
                        <option>Shopping</option>
                        <option>Others</option>
                    </select>
                    <button type="submit">Save Expense</button>
                </form>
            </div>
        </div>

        <!-- UPLOAD INVOICE CARD -->
        <div class="card">
            <div class="card-header">UPLOAD INVOICE</div>
            <div class="form-section">
                <form method="POST" action="<?php echo BASE_PATH; ?>upload_invoice.php" enctype="multipart/form-data">
                    <input type="file" name="invoice_file" accept=".pdf,.jpg,.jpeg,.png" required>
                    <select name="expense_id">
                        <option value="">Link to Expense (optional)</option>
                        <?php 
                        $expenses_dropdown = $conn->query("SELECT * FROM expenses WHERE user_id = $user_id ORDER BY expense_date DESC");
                        while ($row = $expenses_dropdown->fetch_assoc()): 
                        ?>
                            <option value="<?= $row['id'] ?>"><?= $row['expense_date'] ?> - <?= $row['description'] ?> (₱<?= $row['amount'] ?>)</option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit">Upload Invoice</button>
                </form>
            </div>
        </div>

        <!-- EXPENSE HISTORY -->
        <h2>Expense History</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Category</th>
                <th>Amount (₱)</th>
            </tr>
            <?php
            $expenses->data_seek(0);
            while ($row = $expenses->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['expense_date'] ?></td>
                <td><?= $row['description'] ?></td>
                <td><?= $row['category'] ?></td>
                <td><?= number_format($row['amount'], 2) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- INVOICE HISTORY -->
        <div class="section-gap">
            <h2>Invoice Upload History</h2>
            <table>
                <tr>
                    <th>Upload Date</th>
                    <th>Linked Expense</th>
                    <th>File Name</th>
                    <th>Action</th>
                </tr>
                <?php if ($invoices->num_rows == 0): ?>
                <tr>
                    <td colspan="4" style="text-align:center; color:#888;">No invoices uploaded yet.</td>
                </tr>
                <?php else: ?>
                <?php while ($inv = $invoices->fetch_assoc()): ?>
                <tr>
                    <td><?= date('Y-m-d H:i', strtotime($inv['upload_date'])) ?></td>
                    <td>
                        <?php 
                        if ($inv['description']) {
                            echo $inv['description'] . " (₱" . number_format($inv['amount'], 2) . ")";
                        } else {
                            echo "Not linked";
                        }
                        ?>
                    </td>
                    <td><?= $inv['invoice_file'] ?></td>
                    <td>
                        <a href="<?php echo BASE_PATH; ?>uploads/<?= $inv['invoice_file'] ?>" target="_blank">View / Open</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </table>
        </div>

    </div>
</body>
</html>
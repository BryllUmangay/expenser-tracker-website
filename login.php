<?php
require 'db.php';
session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    if (!empty($user) && !empty($pass)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$user]);
        $account = $stmt->fetch();

        if ($account && password_verify($pass, $account['password_hash'])) {
            // Mitigate Session Fixation attacks by regenerating session IDs
            session_regenerate_id(true);
            $_SESSION['user_id'] = $account['id'];
            $_SESSION['username'] = $account['username'];
            header("Location: index.php");
            exit;
        } else {
            $message = "Invalid authentication credentials.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense Tracker - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-box">
        <h2>System Login</h2>
        <?php if($message): ?><p class="error"><?= htmlspecialchars($message) ?></p><?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>New user? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>
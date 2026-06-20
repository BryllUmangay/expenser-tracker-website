<?php
require 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    if (!empty($user) && !empty($pass)) {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
            $stmt->execute([$user, $hash]);
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { 
                $message = "Error: Username already exists.";
            } else {
                error_log("Registration Exception: " . $e->getMessage());
                $message = "An unhandled exception occurred.";
            }
        }
    } else {
        $message = "Please populate all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense Tracker - Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-box">
        <h2>Create Account</h2>
        <?php if($message): ?><p class="error"><?= htmlspecialchars($message) ?></p><?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Register</button>
        </form>
        <p>Existing account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>
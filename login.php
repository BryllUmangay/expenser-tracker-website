<?php
require 'db.php';
session_start();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("INSERT INTO log_events (event_type, description) VALUES ('LOGIN_ATTEMPT', ?)");
        $stmt->execute(["Username: " . $username]);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Verify the input password against the hashed database value
        if ($user && password_verify($password, $user['password_hash'])) {
            // Prevent Session Fixation attacks by regenerating session ID
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            header("Location: index.php");
            exit;
        } else {
            $message = "Invalid username or password.";
        }
    } else {
        $message = "Please enter both username and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Expense Tracker</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; margin-bottom: 15px; padding: 10px; box-sizing: border-box; }
        button { background-color: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #218838; }
        .msg { margin-bottom: 15px; color: red; }
    </style>
</head>
<body>
<div class="container">
    <h2>User Login</h2>
    <?php if (!empty($message)) echo "<div class='msg'>$message</div>"; ?>
    <form action="login.php" method="POST">
        <label>Username</label>
        <input type="text" name="username" required>
        
        <label>Password</label>
        <input type="password" name="password" required>
        
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>
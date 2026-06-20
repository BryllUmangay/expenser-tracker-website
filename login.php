<?php
// ✅ FIRST LINE ALWAYS
session_start();
include 'config.php';

// ✅ IF ALREADY LOGGED IN, GO TO DASHBOARD
if (isset($_SESSION['user_id'])) {
    header("Location: ".BASE_PATH."dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT id, fullname, password FROM users WHERE email = '$email'");
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            header("Location: ".BASE_PATH."dashboard.php");
            exit;
        } else {
            $error = "❌ Incorrect password!";
        }
    } else {
        $error = "❌ Email not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | Expensio</title>
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>style.css">
</head>
<body>
    <div class="auth-box">
        <h2>🔐 Welcome Back</h2>
        <p style="color:#888; margin-bottom:20px;">Sign in to access your expense tracker</p>

        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        <?php if (isset($success)) echo "<div class='success'>$success</div>"; ?>
        
        <form method="POST" action="">
            <input type="email" name="email" placeholder="📧 Email Address" required>
            <input type="password" name="password" placeholder="🔑 Password" required>
            <button type="submit">Sign In</button>
        </form>

        <p style="margin-top:25px; color:#888;">Don't have an account? <a href="<?php echo BASE_PATH; ?>register.php">Create Account</a></p>
        <div class="logo" style="margin-top:30px; font-size:1.3rem;">LOGIN</div>
    </div>
</body>
</html>
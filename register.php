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
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $error = "❌ Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fullname, $email, $password);
        if ($stmt->execute()) {
            $success = "✅ Registration successful! You can now login.";
        } else {
            $error = "❌ Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | Expensio</title>
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>style.css">
</head>
<body>
    <div class="auth-box">
        <h2>📝 Create Account</h2>
        <p style="color:#888; margin-bottom:20px;">Fill in details to start tracking expenses</p>

        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        <?php if (isset($success)) echo "<div class='success'>$success</div>"; ?>
        
        <form method="POST" action="">
            <input type="text" name="fullname" placeholder="👤 Full Name" required>
            <input type="email" name="email" placeholder="📧 Email Address" required>
            <input type="password" name="password" placeholder="🔑 Create Password" required>
            <button type="submit">Register Now</button>
        </form>

        <p style="margin-top:25px; color:#888;">Already have an account? <a href="<?php echo BASE_PATH; ?>login.php">Sign In</a></p>
        <div class="logo" style="margin-top:30px; font-size:1.3rem;">REGISTER</div>
    </div>
</body>
</html>
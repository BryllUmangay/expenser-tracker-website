<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];
$expense_id = !empty($_POST['expense_id']) ? $_POST['expense_id'] : NULL;

// FIXED: Absolute path to uploads folder
$target_dir = __DIR__ . "/uploads/";
$file_name = time() . "_" . basename($_FILES["invoice_file"]["name"]);
$target_file = $target_dir . $file_name;
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Allow only certain file formats
if($fileType != "pdf" && $fileType != "jpg" && $fileType != "jpeg" && $fileType != "png" ) {
    echo "Only PDF, JPG, JPEG & PNG files are allowed.";
    $uploadOk = 0;
}

if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["invoice_file"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO invoices (user_id, expense_id, invoice_file) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $expense_id, $file_name);
        $stmt->execute();
        header("Location: dashboard.php?msg=invoice_uploaded");
    } else {
        echo "Sorry, there was an error uploading your file.";
        // Optional: Show exact error
        echo "<br>Error: " . error_get_last()['message'];
    }
}
?>
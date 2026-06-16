<?php
session_start();
$_SESSION = array(); // Clear all session data
session_destroy();   // Kill the session state
header("Location: login.php");
exit;
?>
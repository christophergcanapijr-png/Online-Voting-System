<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('../dbcon.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    if (basename($_SERVER['PHP_SELF']) !== 'index.php') {
        header("Location: index.php");
        exit();
    }
    return;
}

// **Load active academic year**
$year_query = mysqli_query($conn, "SELECT academic_year FROM settings WHERE is_current = 1 LIMIT 1");
if ($year_query && mysqli_num_rows($year_query) > 0) {
    $year_row = mysqli_fetch_assoc($year_query);
    $_SESSION['academic_year'] = $year_row['academic_year'];
} else {
    // Fallback default
    $_SESSION['academic_year'] = '2024-2025';
}

// Verify admin exists
$admin_query = mysqli_query($conn, 
    "SELECT * FROM users 
     WHERE User_id = '".$_SESSION['admin_id']."' 
       AND User_Type = 'Admin'")
    or die(mysqli_error($conn));

if (mysqli_num_rows($admin_query) == 0) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

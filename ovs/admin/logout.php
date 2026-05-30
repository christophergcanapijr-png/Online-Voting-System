<?php
session_start();
include('dbcon.php');

if (isset($_SESSION['admin_id'])) {
    $user_id = $_SESSION['admin_id'];
    $username = $_SESSION['username'] ?? 'Unknown';
    $academic_year = $_SESSION['academic_year'] ?? '2024-2025';

    // Log the logout
    $stmt = mysqli_prepare($conn, 
        "INSERT INTO history (action, data, user_id, academic_year) 
         VALUES (?, ?, ?, ?)");
    $action = "Logout";
    $data = "Admin logged out";
    mysqli_stmt_bind_param($stmt, "ssis", $action, $data, $user_id, $academic_year);
    mysqli_stmt_execute($stmt);
}

session_destroy();
header("Location: index.php");
exit();
?>
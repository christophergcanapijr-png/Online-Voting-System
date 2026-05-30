<?php
session_start();
require_once('dbcon.php');

if (isset($_POST['academic_year'])) {
    $academic_year = mysqli_real_escape_string($conn, $_POST['academic_year']);
    
    // Update the current academic year in settings table
    $stmt = mysqli_prepare($conn, "UPDATE settings SET is_current = 0 WHERE is_current = 1");
    mysqli_stmt_execute($stmt);
    
    $stmt = mysqli_prepare($conn, "UPDATE settings SET is_current = 1 WHERE academic_year = ?");
    mysqli_stmt_bind_param($stmt, "s", $academic_year);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['academic_year'] = $academic_year;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>

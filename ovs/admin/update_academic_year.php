<?php
session_start();
require_once('../dbcon.php');

if(isset($_POST['update_year'])) {
    $new_year = mysqli_real_escape_string($conn, $_POST['academic_year']);
    
    // First, set all years to not current
    mysqli_query($conn, "UPDATE settings SET is_current = 0");
    
    // Then set the selected year as current
    $update_query = mysqli_query($conn, 
        "UPDATE settings SET is_current = 1 
         WHERE academic_year = '$new_year'");
    
    if($update_query) {
        $_SESSION['success'] = "Academic year updated successfully";
    } else {
        $_SESSION['error'] = "Failed to update academic year";
    }
    
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
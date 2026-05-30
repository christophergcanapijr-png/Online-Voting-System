<?php
include('dbcon.php');
session_start();

if (!isset($_SESSION['voter_id'])) {
    echo json_encode(['reload' => true, 'message' => 'Session expired']);
    exit();
}

// Get current academic year from settings
$settings_query = mysqli_query($conn, "SELECT academic_year FROM settings WHERE is_current = 1");
$settings_row = mysqli_fetch_array($settings_query);
$current_academic_year = $settings_row['academic_year'];

// Check if voter exists in current academic year
$voter_check = mysqli_query($conn, 
    "SELECT COUNT(*) as count FROM voters 
     WHERE StudentID = '{$_SESSION['voter_id']}' 
     AND academic_year = '$current_academic_year'"
);
$voter_exists = mysqli_fetch_array($voter_check)['count'] > 0;

if (!$voter_exists) {
    echo json_encode([
        'reload' => true, 
        'message' => "Access denied: You are not registered for the current academic year ($current_academic_year)"
    ]);
    exit();
}

echo json_encode(['reload' => false]);
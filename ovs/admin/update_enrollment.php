<?php
header('Content-Type: application/json');
session_start();
require_once('dbcon.php');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voter_id = mysqli_real_escape_string($conn, $_POST['voter_id'] ?? '');
    $current_status = mysqli_real_escape_string($conn, $_POST['status'] ?? '');
    $admin_id = (int)$_SESSION['admin_id'];  // Make sure this is valid
    $academic_year = $_SESSION['academic_year'] ?? '2024-2025';

    if (empty($voter_id)) {
        echo json_encode(['success' => false, 'message' => 'Voter ID is required']);
        exit();
    }

    // Verify admin exists in users table
    $admin_check = mysqli_query($conn, "SELECT User_id FROM users WHERE User_id = $admin_id");
    if (!$admin_check || mysqli_num_rows($admin_check) == 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid admin user']);
        exit();
    }

    // Toggle enrollment status
    $new_status = ($current_status == 'enrolled') ? 'unenrolled' : 'enrolled';

    // Update the voter
    $query = "UPDATE voters SET enrollment = '$new_status' WHERE StudentID = '$voter_id'";
    
    if (mysqli_query($conn, $query)) {
        // Record in history (only if admin exists)
        $history_query = "INSERT INTO history (action, data, user_id, academic_year, `date`) 
                         VALUES ('Updated enrollment', '$voter_id - $new_status', $admin_id, '$academic_year', NOW())";
        
        if (!mysqli_query($conn, $history_query)) {
            // Log the error but don't fail the update
            error_log("History insert failed: " . mysqli_error($conn));
        }

        echo json_encode([
            'success' => true,
            'message' => 'Enrollment status updated',
            'new_status' => $new_status
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . mysqli_error($conn)
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
exit();
?>
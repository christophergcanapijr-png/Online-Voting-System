<?php
session_start();
require_once('dbcon.php');

// Check authorization
if (!isset($_SESSION['admin_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit();
}

header('Content-Type: application/json');

// FIXED: Changed from 'new_academic_year' to 'academic_year'
if(isset($_POST['academic_year']) && !empty($_POST['academic_year'])) {
    $year = mysqli_real_escape_string($conn, trim($_POST['academic_year']));
    
    // Validate format (YYYY-YYYY)
    if(!preg_match('/^\d{4}-\d{4}$/', $year)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid format. Use YYYY-YYYY (e.g., 2024-2025)'
        ]);
        exit();
    }
    
    // Begin transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Check if academic year already exists
        $stmt = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM settings WHERE academic_year = ?");
        mysqli_stmt_bind_param($stmt, "s", $year);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $duplicate = mysqli_fetch_assoc($result);
        
        if($duplicate['count'] > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Academic year ' . htmlspecialchars($year) . ' already exists'
            ]);
            exit();
        }
        
        // Check if this is the first academic year
        $check = mysqli_query($conn, "SELECT COUNT(*) as count FROM settings");
        $row = mysqli_fetch_assoc($check);
        $is_first = ($row['count'] == 0);
        
        // Add new academic year using prepared statement
        $stmt = mysqli_prepare($conn, "INSERT INTO settings (academic_year, is_current) VALUES (?, ?)");
        $is_current = $is_first ? 1 : 0;
        mysqli_stmt_bind_param($stmt, "si", $year, $is_current);
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_error($conn));
        }
        
        // If it's the first year, set it as current in session
        if($is_first) {
            $_SESSION['academic_year'] = $year;
        }
        
        mysqli_commit($conn);
        
        // Log the action to history table
        if(isset($_SESSION['admin_id'])) {
            $user_id = intval($_SESSION['admin_id']);
            $action = mysqli_real_escape_string($conn, "Added Academic Year");
            $data = mysqli_real_escape_string($conn, "Academic year: $year");
            
            $log_query = "INSERT INTO history (user_id, action, data, date) 
                          VALUES ($user_id, '$action', '$data', NOW())";
            @mysqli_query($conn, $log_query); // @ suppresses minor errors
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Academic year ' . htmlspecialchars($year) . ' added successfully'
        ]);
        
    } catch(Exception $e) {
        mysqli_rollback($conn);
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required field: academic_year'
    ]);
}
?>
<?php
session_start();
require_once('dbcon.php');

$response = array('success' => false, 'message' => '');

if (isset($_POST['academic_year'])) {
    $new_year = mysqli_real_escape_string($conn, $_POST['academic_year']);
    $old_year = $_SESSION['academic_year'] ?? 'None';

    mysqli_begin_transaction($conn);

    try {
        // Reset all years to not current
        $reset = mysqli_query($conn, "UPDATE settings SET is_current = 0");
        if (!$reset) {
            throw new Exception("Failed to reset academic years");
        }

        // Set the new year as current
        $update = mysqli_query($conn, "UPDATE settings SET is_current = 1 
                                     WHERE academic_year = '$new_year'");
        if (!$update) {
            throw new Exception("Failed to set new academic year");
        }

        // Update session
        $_SESSION['academic_year'] = $new_year;
        
        mysqli_commit($conn);
        
        $response['success'] = true;
        $response['message'] = "Successfully changed to academic year $new_year";

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $response['message'] = $e->getMessage();
    }
} else {
    $response['message'] = 'No academic year selected';
}

header('Content-Type: application/json');
echo json_encode($response);
exit;

<?php
header('Content-Type: application/json');
include('session.php');
include('dbcon.php');

$response = ['success' => false, 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['voter_id'])) {
    $voter_id = mysqli_real_escape_string($conn, $_POST['voter_id'] ?? '');

    // Check if voter exists
    $check_sql = "SELECT StudentID FROM voters WHERE StudentID = '$voter_id'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) === 0) {
        $response['message'] = 'Voter not found';
        echo json_encode($response);
        exit;
    }

    // Delete the voter
    $delete_sql = "DELETE FROM voters WHERE StudentID = '$voter_id'";

    if (mysqli_query($conn, $delete_sql)) {
        // Record in history
        $admin_id = $_SESSION['admin_id'] ?? 'Unknown';
        $academic_year = $_SESSION['academic_year'] ?? 'N/A';

        mysqli_query($conn, "
            INSERT INTO history (action, data, user_id, academic_year, date)
            VALUES ('Deleted voter', '$voter_id', '$admin_id', '$academic_year', NOW())
        ");

        $response['success'] = true;
        $response['message'] = 'Voter deleted successfully';
    } else {
        $response['message'] = 'Database error: ' . mysqli_error($conn);
    }
} else {
    $response['message'] = 'Invalid request';
}

echo json_encode($response);
?>

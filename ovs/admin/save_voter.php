<?php
session_start();
include('dbcon.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get current academic year
    $current_year = $_SESSION['academic_year'];
    
    // Sanitize inputs
    $firstname = mysqli_real_escape_string($conn, $_POST['FirstName']);
    $lastname = mysqli_real_escape_string($conn, $_POST['LastName']);
    $middlename = mysqli_real_escape_string($conn, $_POST['Section']); // Section is used as MiddleName
    $year_level = mysqli_real_escape_string($conn, $_POST['Year']);
    $enrollment = mysqli_real_escape_string($conn, $_POST['enrollment']);
    $username = mysqli_real_escape_string($conn, $_POST['UserName']);
    $password = mysqli_real_escape_string($conn, $_POST['Password']);
    
    try {
        // Start transaction
        mysqli_begin_transaction($conn);
        
        // Check if username already exists
        $check_query = mysqli_query($conn, "SELECT * FROM voters WHERE Username='$username' AND academic_year='$current_year'");
        if(mysqli_num_rows($check_query) > 0) {
            throw new Exception("Username already exists");
        }
        
        // Insert new voter
        $query = mysqli_query($conn, "INSERT INTO voters (FirstName, LastName, MiddleName, 
                                                        Username, Password, Year, Status, 
                                                        enrollment, academic_year) 
                                     VALUES ('$firstname', '$lastname', '$middlename', 
                                             '$username', '$password', '$year_level', 
                                             'Unvoted', '$enrollment', '$current_year')");
        
        if(!$query) {
            throw new Exception(mysqli_error($conn));
        }
        
        // Log the action
        $user_id = $_SESSION['id'];
        $voter_name = "$firstname $lastname";
        $date = date('Y-m-d H:i:s');
        
        $log_query = mysqli_query($conn, "INSERT INTO history (data, action, date, user, academic_year) 
                                        VALUES ('$voter_name', 'New Voter Added', '$date', 
                                                '$user_id', '$current_year')");
        
        if(!$log_query) {
            throw new Exception(mysqli_error($conn));
        }
        
        mysqli_commit($conn);
        echo json_encode(['success' => true, 'message' => 'Voter added successfully']);
        
    } catch(Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>

<?php
include('session.php');
include('dbcon.php');

if (isset($_POST['id']) || isset($_GET['id'])) {
    // support both POST and GET for id
    $id = isset($_POST['id']) ? $_POST['id'] : $_GET['id'];

    // 1) Fetch candidate info before deletion
    $q  = mysqli_query($conn, "SELECT FirstName, LastName, Position FROM candidate WHERE CandidateID='$id'") 
          or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($q);
    $candidate_name = $row['FirstName'] . ' ' . $row['LastName'];
    $position       = $row['Position'];

    // 2) Delete the candidate
    $result = mysqli_query($conn, "DELETE FROM candidate WHERE CandidateID='$id'") 
              or die(mysqli_error($conn));

    if ($result) {
        // 3) Log deletion to history
        if (isset($_SESSION['admin_id'])) {
            $admin_id      = $_SESSION['admin_id'];
            $academic_year = $_SESSION['academic_year'] ?? '';
            $action        = 'Deleted candidate';
            $data          = "$candidate_name - $position";
            mysqli_query($conn,
                "INSERT INTO history (action, data, user_id, academic_year, date)
                 VALUES ('$action', '$data', '$admin_id', '$academic_year', NOW())"
            ) or die(mysqli_error($conn));
        }
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    // no ID provided
    echo 'error';
}
?>

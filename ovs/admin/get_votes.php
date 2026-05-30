<?php
session_start();
include('dbcon.php');
header('Content-Type: application/json');

if(isset($_POST['academic_year'])) {
    $current_year = mysqli_real_escape_string($conn, $_POST['academic_year']);
    
    $query = mysqli_query($conn, "SELECT c.CandidateID, 
        COUNT(v.ID) as vote_count 
        FROM candidate c 
        LEFT JOIN votes v ON c.CandidateID = v.CandidateID 
        AND v.academic_year = '$current_year'
        WHERE c.academic_year = '$current_year'
        GROUP BY c.CandidateID");
    
    $votes = array();
    while($row = mysqli_fetch_assoc($query)) {
        $votes[$row['CandidateID']] = $row['vote_count'];
    }
    
    echo json_encode($votes);
}
?>
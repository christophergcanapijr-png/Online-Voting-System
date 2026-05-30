<?php
session_start();
include('dbcon.php');
header('Content-Type: application/json');

$current_year = $_SESSION['academic_year'];

try {
    // Get voter statistics
    $voter_stats = mysqli_query($conn, "SELECT 
        COUNT(*) as total_voters,
        SUM(CASE WHEN Status = 'Voted' THEN 1 ELSE 0 END) as voted,
        SUM(CASE WHEN Status = 'Unvoted' THEN 1 ELSE 0 END) as not_voted
        FROM voters 
        WHERE academic_year = '$current_year'");
    
    // Get position statistics
    $position_stats = mysqli_query($conn, "SELECT 
        Position,
        COUNT(*) as candidate_count,
        (SELECT COUNT(*) FROM votes v 
         WHERE v.CandidateID IN (
             SELECT CandidateID FROM candidate c2 
             WHERE c2.Position = c.Position 
             AND c2.academic_year = '$current_year'
         ) AND v.academic_year = '$current_year') as vote_count
        FROM candidate c
        WHERE academic_year = '$current_year'
        GROUP BY Position");
    
    $data = [
        'voter_stats' => mysqli_fetch_assoc($voter_stats),
        'position_stats' => []
    ];
    
    while($row = mysqli_fetch_assoc($position_stats)) {
        $data['position_stats'][] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
    
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
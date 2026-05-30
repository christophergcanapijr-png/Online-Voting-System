<?php
session_start();
include('dbcon.php');
header('Content-Type: application/json');

$current_year = $_SESSION['academic_year'];
$positions = ['Governor', 'Vice-Governor', '1st Year Representative', '2nd Year Representative', '3rd Year Representative', '4th Year Representative'];
$results = [];

foreach($positions as $position) {
    $query = mysqli_query($conn, "SELECT 
        c.CandidateID,
        CONCAT(c.FirstName, ' ', c.LastName) as name,
        c.Position,
        c.Party,
        COUNT(v.ID) as vote_count,
        (SELECT COUNT(*) FROM votes 
         WHERE academic_year = '$current_year' 
         AND CandidateID IN (
             SELECT CandidateID FROM candidate 
             WHERE Position = c.Position 
             AND academic_year = '$current_year'
         )) as total_position_votes
        FROM candidate c
        LEFT JOIN votes v ON c.CandidateID = v.CandidateID 
        AND v.academic_year = '$current_year'
        WHERE c.Position = '$position' 
        AND c.academic_year = '$current_year'
        GROUP BY c.CandidateID
        ORDER BY vote_count DESC");

    $position_results = [];
    while($row = mysqli_fetch_assoc($query)) {
        $percentage = $row['total_position_votes'] > 0 
            ? round(($row['vote_count'] / $row['total_position_votes']) * 100, 1) 
            : 0;
        
        $position_results[] = [
            'name' => $row['name'],
            'party' => $row['party'],
            'votes' => $row['vote_count'],
            'percentage' => $percentage
        ];
    }
    
    $results[$position] = $position_results;
}

echo json_encode([
    'success' => true,
    'timestamp' => date('Y-m-d H:i:s'),
    'results' => $results
]);
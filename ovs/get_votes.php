<?php
include('dbcon.php');
session_start();

// Debug old values
error_log('AJAX Payload - Voter Year: ' . $_POST['voter_year']);
error_log('AJAX Payload - Academic Year: ' . $_POST['academic_year']);

// Get current running academic year from settings
$current_year_query = mysqli_query($conn, "SELECT academic_year FROM settings WHERE is_current = 1") 
    or die(mysqli_error($conn));
$current_year_row = mysqli_fetch_array($current_year_query);
$current_academic_year = $current_year_row['academic_year'];

error_log('Database Settings - Current Academic Year: ' . $current_academic_year);

// Use the academic year from settings, not from POST
$voter_year = mysqli_real_escape_string($conn, $_POST['voter_year']);

function getVotes($conn, $position, $academic_year) {
    // Debug: Log query parameters
    error_log("Getting votes for Position: $position, Academic Year: $academic_year");
    
    $query = "SELECT 
        c.CandidateID,
        c.FirstName, 
        c.LastName,
        c.MiddleName,
        c.Position,
        c.Party,
        c.Year,
        c.Platform,
        c.Photo,
        COALESCE(COUNT(v.CandidateID), 0) as vote_count
    FROM candidate c
    LEFT JOIN votes v ON c.CandidateID = v.CandidateID 
        AND v.academic_year = ?
    WHERE c.Position = ? 
    AND c.academic_year = ?
    GROUP BY c.CandidateID, c.FirstName, c.LastName, c.MiddleName, 
             c.Position, c.Party, c.Year, c.Platform, c.Photo
    ORDER BY vote_count DESC";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $academic_year, $position, $academic_year);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $votes = [];
    while($row = mysqli_fetch_assoc($result)) {
        $votes[] = [
            'id' => $row['CandidateID'],
            'name' => $row['FirstName'] . ' ' . $row['LastName'],
            'firstName' => $row['FirstName'],
            'lastName' => $row['LastName'],
            'middleName' => $row['MiddleName'],
            'position' => $row['Position'],
            'party' => $row['Party'],
            'year' => $row['Year'],
            'platform' => $row['Platform'],
            'votes' => (int)$row['vote_count'],
            'photo' => 'admin/' . $row['Photo']
        ];
    }
    
    // Debug: Log results
    error_log("Found " . count($votes) . " candidates for $position");
    return $votes;
}

// Get votes using current academic year from settings
$governor_votes = getVotes($conn, 'Governor', $current_academic_year);
$vice_votes = getVotes($conn, 'Vice-Governor', $current_academic_year);
$rep_votes = getVotes($conn, $voter_year . ' Representative', $current_academic_year);

$response = [
    'governor' => $governor_votes,
    'vice_governor' => $vice_votes,
    'representatives' => $rep_votes,
    'academic_year' => $current_academic_year,
    'payload_year' => $_POST['academic_year'] // Add this for debugging
];

// Debug: Log final response
error_log("Final Response: " . json_encode($response));

header('Content-Type: application/json');
echo json_encode($response);
?>

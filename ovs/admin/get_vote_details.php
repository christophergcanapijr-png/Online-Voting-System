<?php
include('session.php');
include('dbcon.php');

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    die(json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]));
}

try {
    $candidate_id = isset($_POST['candidate_id']) ? (int)$_POST['candidate_id'] : 0;
    $academic_year = $_POST['academic_year'] ?? '';

    if (!$candidate_id || !$academic_year) {
        throw new Exception('Missing required parameters');
    }

    // Get candidate info
    $stmt = mysqli_prepare($conn, 
        "SELECT CONCAT(FirstName, ' ', LastName) as full_name, Position 
         FROM candidate 
         WHERE CandidateID = ?"
    );
    mysqli_stmt_bind_param($stmt, "i", $candidate_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $candidate = mysqli_fetch_assoc($result);

    if (!$candidate) {
        throw new Exception('Candidate not found');
    }

    // Get votes by year level with correct year filter
    $vote_query = mysqli_prepare($conn, 
        "SELECT 
            v.Year as year_level,
            COUNT(DISTINCT votes.voter_id) as votes,
            (SELECT COUNT(*) FROM voters 
             WHERE Year = v.Year 
             AND academic_year = ?) as total_voters
         FROM voters v
         LEFT JOIN votes ON votes.voter_id = v.StudentID 
             AND votes.CandidateID = ?
             AND votes.academic_year = ?
         WHERE v.academic_year = ?
         GROUP BY v.Year
         ORDER BY v.Year"
    );

    mysqli_stmt_bind_param($vote_query, "siss", 
        $academic_year, 
        $candidate_id, 
        $academic_year,
        $academic_year
    );

    if (!mysqli_stmt_execute($vote_query)) {
        throw new Exception("Database error: " . mysqli_error($conn));
    }

    $votes_result = mysqli_stmt_get_result($vote_query);
    $votes = [];

    while ($row = mysqli_fetch_assoc($votes_result)) {
        $votes[] = [
            'year_level' => $row['year_level'] . ' Year',
            'votes' => (int)$row['votes'],
            'total_voters' => (int)$row['total_voters']
        ];
    }

    echo json_encode([
        'success' => true,
        'candidate_name' => $candidate['full_name'],
        'position' => $candidate['Position'],
        'votes' => $votes
    ]);

} catch (Exception $e) {
    error_log("Vote details error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
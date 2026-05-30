<?php
include('session.php');
include('../dbcon.php');

// Ensure the active academic year is set in the session
if (!isset($_SESSION['active_academic_year'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Active academic year is not set.'
    ]);
    exit;
}

$active_year = $_SESSION['active_academic_year'];

// Fetch candidates for the active academic year
$query = "SELECT * FROM candidates WHERE academic_year = '$active_year'";
$result = mysqli_query($conn, $query);

$candidates = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $candidates[] = $row;
    }
}

echo json_encode([
    'success' => true,
    'candidates' => $candidates
]);
?>

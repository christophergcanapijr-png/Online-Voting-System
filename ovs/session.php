<?php
session_start();
require_once('dbcon.php');

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Verify voter exists using prepared statement
$voter_id = $_SESSION['id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM voters WHERE StudentID = ?");
mysqli_stmt_bind_param($stmt, "s", $voter_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    session_destroy();
    header('location:index.php');
    exit();
}

// Load active academic year using prepared statement
$stmt = mysqli_prepare($conn, "SELECT academic_year FROM settings WHERE is_current = 1 LIMIT 1");
mysqli_stmt_execute($stmt);
$year_result = mysqli_stmt_get_result($stmt);
if ($yr = mysqli_fetch_assoc($year_result)) {
    $_SESSION['academic_year'] = $yr['academic_year'];
}
$current_year = $_SESSION['academic_year'];

// Check if voter has already voted this academic year
$stmt = mysqli_prepare($conn, 
    "SELECT COUNT(*) AS vote_count 
     FROM votes 
     WHERE voter_id = ? 
     AND academic_year = ?"
);
mysqli_stmt_bind_param($stmt, "ss", $voter_id, $current_year);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($row['vote_count'] > 0) {
    header("Location: thankyou.php");
    exit();
}
?>

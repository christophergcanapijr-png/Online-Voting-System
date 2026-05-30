<?php
$conn = mysqli_connect('localhost', 'root', '', 'ovs');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set default academic year if not set in session
if (!isset($_SESSION['academic_year'])) {
    $year_query = mysqli_query($conn, "SELECT academic_year FROM settings WHERE is_current = 1");
    if ($year_query && mysqli_num_rows($year_query) > 0) {
        $_SESSION['academic_year'] = mysqli_fetch_assoc($year_query)['academic_year'];
    } else {
        $_SESSION['academic_year'] = '2024-2025';
    }
}
?>
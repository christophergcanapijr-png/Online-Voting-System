<?php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="voter_template.csv"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

// CSV Header - MUST match import_voters.php exactly
fputcsv($output, ['Student ID', 'First Name', 'Last Name', 'Middle Name', 'Year', 'Enrollment', 'User Name', 'Password']);

// Sample rows for reference - Use "enrolled" or "unenrolled"
fputcsv($output, ['2024001', 'John', 'Doe', 'M', '1st Year', 'enrolled', '2024001', 'Doe']);
fputcsv($output, ['2024002', 'Jane', 'Smith', 'A', '2nd Year', 'enrolled', '2024002', 'Smith']);
fputcsv($output, ['2024003', 'Robert', 'Johnson', 'B', '3rd Year', 'unenrolled', '2024003', 'Johnson']);

fclose($output);
exit;
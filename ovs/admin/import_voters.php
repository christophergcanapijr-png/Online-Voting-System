<?php
ob_start();
include('session.php');

if (!@include_once('../dbcon.php')) {
    header('Content-Type: application/json', true, 200);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection missing',
        'errors'  => []
    ]);
    exit;
}

header('Content-Type: application/json', true, 200);
@ini_set('display_errors', 0);
error_reporting(0);

$response = ['success' => false, 'message' => '', 'errors' => []];
$errors   = [];

try {
    if (!isset($_FILES['voter_file']) || $_FILES['voter_file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Please upload a valid CSV file');
    }

    $file = $_FILES['voter_file'];
    $allowed = ['text/csv', 'application/vnd.ms-excel', 'application/octet-stream'];
    if (!in_array($file['type'], $allowed, true)) {
        throw new Exception('Invalid file type. Please upload CSV.');
    }

    $handle = fopen($file['tmp_name'], 'r');
    if (!$handle) {
        throw new Exception('Failed to open uploaded file');
    }

    $header = fgetcsv($handle);
    if (!$header) throw new Exception('Empty or invalid CSV file');

    $expected = [
        'Student ID', 'First Name', 'Last Name', 'Middle Name',
        'Year', 'Enrollment', 'User Name', 'Password'
    ];
    $normalized = array_map(fn($h) => trim(str_replace(["\r", "\n"], '', $h)), $header);
    if ($normalized !== $expected) {
        throw new Exception('Invalid CSV header. Must be: ' . implode(', ', $expected));
    }

    mysqli_begin_transaction($conn);
    $successful = 0;
    $rowNum = 1;

    $insert = mysqli_prepare($conn, "
        INSERT INTO voters (
            StudentID, FirstName, LastName, MiddleName,
            Year, Status, enrollment, Username,
            Password, academic_year
        ) VALUES (?, ?, ?, ?, ?, 'UnVoted', ?, ?, ?, ?)
    ");

    if (!$insert) {
        throw new Exception('DB prepare error: ' . mysqli_error($conn));
    }

    while (($row = fgetcsv($handle)) !== false) {
        $rowNum++;
        try {
            if (count($row) !== 8) throw new Exception('Invalid column count');

            [$sid, $fn, $ln, $mn, $yr, $enr, $un, $pw] = array_map('trim', $row);
            if (empty($sid) || empty($fn) || empty($ln)) throw new Exception('Missing required fields');

            $ay = $_SESSION['academic_year'] ?? date('Y') . '-' . (date('Y') + 1);

            // Check for duplicate
            $dup_check = mysqli_prepare($conn, "SELECT COUNT(*) FROM voters WHERE StudentID = ? AND academic_year = ?");
            mysqli_stmt_bind_param($dup_check, 'ss', $sid, $ay);
            mysqli_stmt_execute($dup_check);
            mysqli_stmt_bind_result($dup_check, $dup_count);
            mysqli_stmt_fetch($dup_check);
            mysqli_stmt_close($dup_check);

            if ($dup_count > 0) {
                throw new Exception("Duplicate: Student ID $sid already exists for $ay");
            }

            $username = $sid;
            $password = $fn;

            if (!mysqli_stmt_bind_param($insert, "sssssssss", $sid, $fn, $ln, $mn, $yr, $enr, $username, $password, $ay)) {
                throw new Exception('Param bind failed: ' . mysqli_stmt_error($insert));
            }

            if (!mysqli_stmt_execute($insert)) {
                throw new Exception('Execute failed: ' . mysqli_stmt_error($insert));
            }

            $successful++;

            $log = mysqli_prepare($conn, "
                INSERT INTO history (action, data, user_id, academic_year, `date`)
                VALUES ('Imported voter', ?, ?, ?, NOW())
            ");
            if ($log) {
                $detail = "$sid - $fn $ln";
                mysqli_stmt_bind_param($log, 'sss', $detail, $_SESSION['admin_id'], $ay);
                mysqli_stmt_execute($log);
                mysqli_stmt_close($log);
            }

        } catch (Exception $e) {
            $errors[] = "Row $rowNum: " . $e->getMessage();
            continue;
        }
    }

    if ($successful > 0) {
        mysqli_commit($conn);
        $response['success'] = true;
        $response['message'] = "$successful imported successfully";
        if ($errors) {
            $response['message'] .= " (" . count($errors) . " errors)";
            $response['errors'] = $errors;
        }
    } else {
        throw new Exception('No rows imported successfully');
    }

} catch (Exception $e) {
    if (isset($conn)) {
        mysqli_rollback($conn);
    }
    $response['message'] = $e->getMessage();
    $response['errors'] = $errors;
} finally {
    if (!empty($handle)) fclose($handle);
    if (!empty($insert)) mysqli_stmt_close($insert);
    ob_clean();
    echo json_encode($response);
    exit;
}
?>

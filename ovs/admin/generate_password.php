<?php
include('session.php');
include('dbcon.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$target = $_POST['voter_id'] ?? 'single';
$generateAll = ($target === 'all');
$generateSelected = ($target === 'selected'); // NEW: Handle selected mode
$current_year = $_SESSION['academic_year'] ?? null;
$admin_id = intval($_SESSION['admin_id'] ?? 0);

function gen_pass($len = 8) {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz0123456789!@#$%^&*()-_=+[]{};:,.<>?';
    $bytes = random_bytes($len);
    $out = '';
    $hasNum = false; $hasSym = false;
    for ($i=0;$i<$len;$i++){
        $idx = ord($bytes[$i]) % strlen($chars);
        $ch = $chars[$idx];
        if (preg_match('/\d/',$ch)) $hasNum = true;
        if (preg_match('/[!@#$%^&*()\-\_\=\+\[\]\{\}\;\:\,\.<>?]/',$ch)) $hasSym = true;
        $out .= $ch;
    }
    if (!$hasNum) $out[0] = '7';
    if (!$hasSym) $out[1] = '!';
    return $out;
}

// Store PLAINTEXT in Password column only
$updateSql = "UPDATE voters SET Password = ? WHERE StudentID = ?";
if ($current_year) $updateSql .= " AND academic_year = ?";

$updateStmt = mysqli_prepare($conn, $updateSql);
if (!$updateStmt) {
    echo json_encode(['success'=>false, 'message'=>'DB prepare failed']);
    exit;
}

$targets = [];

// Mode 1: Generate for ALL students
if ($generateAll) {
    $selectSql = "SELECT StudentID FROM voters WHERE academic_year = ?";
    $selectStmt = mysqli_prepare($conn, $selectSql);
    if (!$selectStmt) {
        echo json_encode(['success'=>false, 'message'=>'DB prepare failed']);
        exit;
    }
    mysqli_stmt_bind_param($selectStmt, "s", $current_year);
    mysqli_stmt_execute($selectStmt);
    $res = mysqli_stmt_get_result($selectStmt);
    while ($r = mysqli_fetch_assoc($res)) $targets[] = $r['StudentID'];
    mysqli_stmt_close($selectStmt);
}
// Mode 2: Generate for SELECTED students (NEW)
elseif ($generateSelected) {
    if (!isset($_POST['selected_ids']) || !is_array($_POST['selected_ids'])) {
        echo json_encode(['success' => false, 'message' => 'No students selected']);
        exit;
    }
    $targets = array_map('trim', $_POST['selected_ids']);
    if (empty($targets)) {
        echo json_encode(['success' => false, 'message' => 'Empty selection']);
        exit;
    }
}
// Mode 3: Generate for SINGLE student
else {
    $voter_id = trim($_POST['voter_id'] ?? '');
    if ($voter_id === '') {
        echo json_encode(['success' => false, 'message' => 'Missing voter_id']);
        exit;
    }
    $targets[] = $voter_id;
}

$results = [];
$successCount = 0;

foreach ($targets as $sid) {
    $plain = gen_pass(8);

    if ($current_year) {
        mysqli_stmt_bind_param($updateStmt, "sss", $plain, $sid, $current_year);
    } else {
        mysqli_stmt_bind_param($updateStmt, "ss", $plain, $sid);
    }

    if (!mysqli_stmt_execute($updateStmt)) {
        $results[$sid] = ['ok'=>false, 'message'=>mysqli_error($conn)];
        continue;
    }

    // Check if any row was actually updated
    if (mysqli_stmt_affected_rows($updateStmt) > 0) {
        $successCount++;
        
        // Log history
        $action = "Generated password";
        $details = "Generated password for StudentID: {$sid}";
        if ($hist = mysqli_prepare($conn, "INSERT INTO history (action, data, user_id, academic_year, date) VALUES (?, ?, ?, ?, NOW())")) {
            mysqli_stmt_bind_param($hist, "ssis", $action, $details, $admin_id, $current_year);
            mysqli_stmt_execute($hist);
            mysqli_stmt_close($hist);
        }

        $results[$sid] = ['ok'=>true, 'password'=>$plain];
    } else {
        $results[$sid] = ['ok'=>false, 'message'=>'Student not found or no changes made'];
    }
}

mysqli_stmt_close($updateStmt);

// Response handling
if (!$generateAll && !$generateSelected) {
    // Single student mode
    $sid = $targets[0];
    if (isset($results[$sid]) && $results[$sid]['ok']) {
        echo json_encode(['success'=>true, 'password'=>$results[$sid]['password']]);
    } else {
        echo json_encode(['success'=>false, 'message'=>$results[$sid]['message'] ?? 'Failed']);
    }
    exit;
}

// All or Selected mode
echo json_encode([
    'success' => true, 
    'results' => $results,
    'total_processed' => count($targets),
    'successful' => $successCount
]);
exit;
?>
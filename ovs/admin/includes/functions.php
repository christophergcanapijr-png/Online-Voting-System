<?php
function logHistory($conn, $action, $description, $admin_id) {
    $action = mysqli_real_escape_string($conn, $action);
    $description = mysqli_real_escape_string($conn, $description);
    $timestamp = date('Y-m-d H:i:s');
    
    $sql = "INSERT INTO history_log (action, description, admin_id, timestamp) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssis", $action, $description, $admin_id, $timestamp);
    
    return mysqli_stmt_execute($stmt);
}
?>

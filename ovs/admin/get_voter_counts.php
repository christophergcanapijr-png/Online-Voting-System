<?php
require_once('../dbcon.php');

header('Content-Type: application/json');

$counts = array(
    'total' => 0,
    'voted' => 0,
    'unvoted' => 0
);

$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM voters");
$counts['total'] = mysqli_fetch_assoc($result)['count'];

$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM voters WHERE Status = 'Voted'");
$counts['voted'] = mysqli_fetch_assoc($result)['count'];

$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM voters WHERE Status = 'Not Voted'");
$counts['unvoted'] = mysqli_fetch_assoc($result)['count'];

echo json_encode($counts);
exit;
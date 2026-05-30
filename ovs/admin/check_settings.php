<?php
include('dbcon.php');

echo "<h2>Settings Table Check</h2>";

// Check if settings table exists
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'settings'");
if(mysqli_num_rows($table_check) == 0) {
    echo "Settings table does not exist!";
    exit;
}

// Check settings table structure
$structure = mysqli_query($conn, "DESCRIBE settings");
echo "<h3>Table Structure:</h3>";
while($row = mysqli_fetch_assoc($structure)) {
    print_r($row);
    echo "<br>";
}

// Check current settings data
$settings = mysqli_query($conn, "SELECT * FROM settings");
echo "<h3>Current Settings Data:</h3>";
if(mysqli_num_rows($settings) == 0) {
    echo "No settings data found!";
} else {
    while($row = mysqli_fetch_assoc($settings)) {
        echo "Academic Year: " . $row['academic_year'] . 
             " | Is Current: " . $row['is_current'] . "<br>";
    }
}
?>
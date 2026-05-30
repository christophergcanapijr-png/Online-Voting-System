<?php
include('dbcon.php');
if (isset($_POST['save'])){

$rfirstname = $_POST['rfirstname'];
$rlastname = $_POST['rlastname'];
$rgender = $_POST['rgender'];
$ryear = $_POST['ryear'];
$rposition = $_POST['rposition'];
$rmname = $_POST['rmname'];
$party = $_POST['party'];
$user_name = $_POST['user_name'];

// Check if file was uploaded
if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    $image_name = addslashes($_FILES['image']['name']);
    $image_size = getimagesize($_FILES['image']['tmp_name']);
    
    // Upload directory
    $upload_dir = "upload/";
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Generate unique filename
    $location = $upload_dir . time() . '_' . $_FILES['image']['name'];
    
    // Move uploaded file
    if(move_uploaded_file($_FILES['image']['tmp_name'], $location)) {
        // Insert into database based on position
        $abc = '';
        switch($rposition) {
            case 'Governor': $abc = 'a'; break;
            case 'Vice-Governor': $abc = 'b'; break;
            case '1st Year Representative': $abc = 'c'; break;
            case '2nd Year Representative': $abc = 'd'; break;
            case '3rd Year Representative': $abc = 'e'; break;
            case '4th Year Representative': $abc = 'f'; break;
        }
        
        // Insert candidate
        mysqli_query($conn, "INSERT INTO candidate (FirstName, LastName, Year, Position, Gender, MiddleName, Photo, Party, abc)
            VALUES ('$rfirstname', '$rlastname', '$ryear', '$rposition', '$rgender', '$rmname', '$location', '$party', '$abc')") 
            or die(mysqli_error($conn));
        
        // Log the action
        mysqli_query($conn, "INSERT INTO history (data, action, date, user)
            VALUES ('$rfirstname $rlastname', 'Added Candidate', NOW(), '$user_name')")
            or die(mysqli_error($conn));
            
        header('location:candidate_list.php');
    } else {
        die("Error uploading file");
    }
} else {
    die("No file uploaded or upload error occurred");
}

}
?>
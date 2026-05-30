<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_error.log');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College of Information Technology Voting System</title>
    <link rel="stylesheet" type="text/css" href="admin/css/style.css" />
    <style>
        /* Responsive styles */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .navbar-inner {
            padding: 10px;
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 5px;
        }

        .brand img {
            max-width: 60px;
            height: auto;
        }

        .brand h2 {
            font-size: clamp(16px, 4vw, 24px);
            margin: 5px 0;
        }

        .chmsc_nav {
            font-size: clamp(14px, 3vw, 16px);
        }

        .wrapper_admin {
            padding: 10px;
            width: 100%;
            max-width: 100vw;
        }

        .hero-body-index {
            width: 90%;
            max-width: 500px;
            margin: 20px auto;
            padding: 15px;
        }

        .hero-body-index h2 {
            font-size: clamp(20px, 5vw, 28px);
            text-align: center;
        }

        .hero-body-index form {
            width: 100%;
        }

        .hero-body-index table {
            width: 100%;
        }

        .hero-body-index table td {
            padding: 5px;
        }

        @media screen and (max-width: 768px) {
            .hero-body-index table td {
                display: block;
                width: 100%;
                text-align: left;
            }

            .hero-body-index input[type="text"],
            .hero-body-index input[type="password"] {
                width: 100%;
                padding: 10px;
                margin: 5px 0;
                border-radius: 4px;
            }

            .hero-body-index input[type="submit"] {
                width: 100%;
                padding: 12px;
                margin-top: 10px;
            }

            .alert {
                width: 100%;
                margin: 10px 0;
                padding: 10px;
            }
        }

        /* Fix iOS input appearance */
        input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px;
            width: 100%;
        }

        /* Improve touch targets */
        .btn {
            min-height: 44px;
            padding: 10px 15px;
            width: 100%;
            font-size: 16px;
        }

        /* Fix alert messages */
        .alert {
            border-radius: 4px;
            padding: 12px;
            margin: 10px 0;
            word-wrap: break-word;
        }

        /* Fix spacing */
        br {
            display: none;
        }

        /* Add proper spacing */
        .spacing {
            margin: 15px 0;
        }
    </style>
</head>
<?php include('dbcon.php');
if (isset($_POST['Login'])) {
    if (empty($_POST['UserName']) || empty($_POST['Password'])) {
        $_SESSION['error'] = "Please enter both username and password";
        header('Location: index.php');
        exit();
    }

    $UserName = trim(mysqli_real_escape_string($conn, $_POST['UserName']));
    $Password = trim(mysqli_real_escape_string($conn, $_POST['Password']));

    // Enhanced debug logging
    error_log("===============================");
    error_log("Login attempt details:");
    error_log("Username: " . $UserName);
    error_log("Password length: " . strlen($Password));
    error_log("Raw password (for debugging): " . $Password);

    // Check database connection
    if (mysqli_connect_errno()) {
        error_log("Database connection failed: " . mysqli_connect_error());
        $_SESSION['error'] = "Database connection error";
        header('Location: index.php');
        exit();
    }

    // Use case-insensitive username comparison
    $query = "SELECT * FROM voters WHERE LOWER(Username) = LOWER(?)";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn));
        $_SESSION['error'] = "Database error";
        header('Location: index.php');
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $UserName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        error_log("Found user in database:");
        error_log("Stored username: " . $row['Username']);
        error_log("Stored password length: " . strlen($row['Password']));
        error_log("Stored password (for debugging): " . $row['Password']);
        error_log("Password match result: " . ($Password === $row['Password'] ? 'true' : 'false'));

        if ($Password === $row['Password']) {
            // Get current active academic year first
            $year_query = mysqli_query($conn, "SELECT academic_year FROM settings WHERE is_current = 1");
            $current_academic_year = mysqli_fetch_assoc($year_query)['academic_year'];
            
            // Check if student is enrolled in current academic year
           $enrollment_check = mysqli_query($conn, 
    "SELECT * FROM voters 
     WHERE StudentID = '{$row['StudentID']}' 
     AND academic_year = '$current_academic_year'
     AND Enrollment = 'Enrolled'"
);

if (mysqli_num_rows($enrollment_check) == 0) {
    $_SESSION['error'] = "Access denied: You are not enrolled for the current academic year ($current_academic_year)";
    header('Location: index.php');
    exit();
}

            // If enrolled, continue with session creation
            session_regenerate_id(true);
            $_SESSION['id'] = $row['StudentID'];
            $_SESSION['Year'] = $row['Year'];
            $_SESSION['voter_id'] = $row['StudentID'];
            $_SESSION['academic_year'] = $current_academic_year;

            // Check if already voted
            // Check if voter has already voted in the current academic year
$year_query = mysqli_query($conn, "SELECT academic_year FROM settings WHERE is_current = 1");
$active_year = mysqli_fetch_assoc($year_query)['academic_year'];

$check_vote = mysqli_query($conn, "SELECT * FROM votes WHERE voter_id = '{$row['StudentID']}' AND academic_year = '$active_year'");
if (mysqli_num_rows($check_vote) > 0) {
    header('Location: thankyou.php');
    exit();
}

            // Redirect to single voting page (auto-detects year level)
            error_log("Login successful - Redirecting to: voting.php");
            echo "<script>window.location.href = 'voting.php';</script>";
            exit();

        } else {
            error_log("Invalid password attempt for username: " . $UserName);
            $_SESSION['error'] = "Invalid password";
        }
    } else {
        error_log("Username not found: " . $UserName);
        $_SESSION['error'] = "Username not found";
    }

    header('Location: index.php');
    exit();
}

include('header.php');
?>
</head>
<body>

    <div class="navbar navbar-fixed-top">
    <div class="navbar-inner" style="background-color: #041d52ff !important; background-image: none;">
    <div class="container">

        <a class="brand">
        <img src="admin/images/cit.png" width="60" height="60">
    </a>
    <a class="brand">
     <h2 style="color: white">College of Information Technology</h2>
    </a>

    <?php include('head.php'); ?>

    </div>
    </div>
    </div>
<div class="wrapper_admin">
</br>
</br>
</br>
    <div class="hero-body-index" style="margin-top: 150px;">
    <div class="spacing">
        <h2><font color="white">Voter Login</font></h2>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php">
        <div class="spacing">
            <label><font color="white">Username</font></label>
            <input type="text" name="UserName" required autocomplete="username">
        </div>
        
        <div class="spacing">
            <label><font color="white">Password</font></label>
            <input type="password" name="Password" required autocomplete="current-password">
        </div>
        
        <div class="spacing">
            <input type="submit" name="Login" value="Login" class="btn btn-primary">
        </div>
    </form>
</div>
</br>
</br>
</br>
</br>
</br>

</div>
    </body>

</html>

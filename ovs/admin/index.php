<?php
session_start();
require_once('../dbcon.php');

// If already logged in, redirect to home
if (isset($_SESSION['admin_id'])) {
    header("Location: home.php");
    exit();
}

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['Password'];
    
    // Debug: Check input values
    error_log("Login attempt - Username: " . $username);
    
    $check_user = mysqli_prepare($conn, "SELECT User_id, UserName, Password, User_Type FROM users WHERE UserName = ? AND User_Type = 'Admin'");
    mysqli_stmt_bind_param($check_user, "s", $username);
    mysqli_stmt_execute($check_user);
    $result = mysqli_stmt_get_result($check_user);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (($password) === $user['Password']) {
            $_SESSION['admin_id'] = $user['User_id'];
            $_SESSION['username'] = $user['UserName'];
            $_SESSION['User_Type'] = $user['User_Type'];
            header("Location: home.php");
            exit();
        }
    }
    
    // If we get here, login failed
    $_SESSION['error'] = "Invalid username or password";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <?php include('dbcon.php');
    include('header.php');
    ?>
</head>
<body>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner" style="background-color: #041d52 !important; background-image: none;">
            <div class="container">
                <a class="brand">
                    <img src="images/ucv.png" width="60" height="60">
                </a>
                <a class="brand" style="color: white;">
                    <h2>College of Information Technology</h2>
                    <div class="chmsc_nav"><font size="4" color="white">University of Cagayan Valley</font></div>
                </a>
            </div>
        </div>
    </div>
    <div class="wrapper_admin">
        </br>
        </br>
        </br>
        <div id="element" class="hero-body-index">
            <p><font color="white"><h2>Admin Login</h2></font></p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <table>
                    <tr>
                        <td><font color="white">Username:</font>&nbsp;&nbsp;</td>
                        <td><input type="text" name="username" required autocomplete="username" /></td>
                    </tr>
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr>
                        <td><font color="white">Password:</font>&nbsp;&nbsp;</td>
                        <td><input type="password" name="Password" required autocomplete="current-password" /></td>
                    </tr>
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="login" value="Login" class="btn btn-primary"></td>
                    </tr>
                </table>
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




<?php
session_start();
require_once('../dbcon.php');

// Log POST for debugging
file_put_contents('log.txt', print_r($_POST, true));

// Action logger
function logAction($conn, $action, $data) {
    if (!isset($_SESSION['admin_id'])) return;
    $user_id = $_SESSION['admin_id'];
    $academic_year = $_SESSION['academic_year'] ?? '2024-2025';

    $check_stmt = mysqli_prepare($conn,
        "SELECT COUNT(*) as count FROM history 
         WHERE user_id = ? AND action = ? AND data = ? 
         AND date >= NOW() - INTERVAL 5 SECOND"
    );
    mysqli_stmt_bind_param($check_stmt, "iss", $user_id, $action, $data);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] == 0) {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO history (action, data, user_id, academic_year, date) 
             VALUES (?, ?, ?, ?, NOW())"
        );
        mysqli_stmt_bind_param($stmt, "ssis", $action, $data, $user_id, $academic_year);
        mysqli_stmt_execute($stmt);
    }
}

$response = ['success' => false, 'message' => ''];

if (isset($_POST['action'])) {
    mysqli_begin_transaction($conn);

    try {
        switch ($_POST['action']) {
            case 'add':
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';
                if (!$username || !$password) throw new Exception("Username and password are required");

                $check = mysqli_prepare($conn, "SELECT COUNT(*) FROM users WHERE LOWER(UserName) = LOWER(?)");
                mysqli_stmt_bind_param($check, "s", $username);
                mysqli_stmt_execute($check);
                $result = mysqli_stmt_get_result($check);
                $row = mysqli_fetch_assoc($result);
if ($row && intval($row['COUNT(*)']) > 0) {
    mysqli_rollback($conn);
    $response['success'] = false;
    $response['message'] = "Username already exists";
    echo json_encode($response);
    exit;
}



                $insert = mysqli_prepare($conn, "INSERT INTO users (UserName, Password, User_Type) VALUES (?, ?, 'Admin')");
                mysqli_stmt_bind_param($insert, "ss", $username, $password);
                if (!mysqli_stmt_execute($insert)) throw new Exception("Failed to add user");

                logAction($conn, 'Add User', "Added new admin: $username");
                $response['success'] = true;
                $response['message'] = "User added successfully!";
                break;
case 'edit':
    $user_id = $_POST['user_id'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $new_password = $_POST['new_password'] ?? ($_POST['password'] ?? '');


    if (!$user_id || !$username) {
        throw new Exception("User ID and username are required");
    }

    // Check for username conflicts
    $check = mysqli_prepare($conn, "SELECT COUNT(*) FROM users WHERE LOWER(UserName) = LOWER(?) AND User_id != ?");
    mysqli_stmt_bind_param($check, "si", $username, $user_id);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);
    if (mysqli_fetch_assoc($result)['COUNT(*)'] > 0) {
        throw new Exception("Username already exists");
    }

    if (!empty($new_password)) {
        $stmt = mysqli_prepare($conn, "UPDATE users SET UserName = ?, Password = ? WHERE User_id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $username, $new_password, $user_id);
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET UserName = ? WHERE User_id = ?");
        mysqli_stmt_bind_param($stmt, "si", $username, $user_id);
    }

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Failed to update user: " . mysqli_error($conn));
    }

    // If editing self, update session username
    if ($_SESSION['admin_id'] == $user_id) {
        $_SESSION['username'] = $username;
    }

    logAction($conn, 'Edit User', "Updated user: $username");
    $response['success'] = true;
    $response['message'] = "User updated successfully";
    break;


            case 'update_profile':
                $admin_id = $_SESSION['admin_id'] ?? 0;
                $username = trim($_POST['username'] ?? '');
                $new_password = $_POST['new_password'] ?? '';


                if (!$admin_id || !$username) {
                    throw new Exception("Admin ID and username are required");
                }

                // Check username conflicts
                $check = mysqli_prepare($conn, 
                    "SELECT COUNT(*) FROM users 
                     WHERE LOWER(UserName) = LOWER(?) 
                     AND User_id != ?"
                );
                mysqli_stmt_bind_param($check, "si", $username, $admin_id);
                mysqli_stmt_execute($check);
                $result = mysqli_stmt_get_result($check);
                if (mysqli_fetch_assoc($result)['COUNT(*)'] > 0) {
                    throw new Exception("Username already exists");
                }

                // Update query based on whether password is provided
                if (!empty($new_password)) {
                    $stmt = mysqli_prepare($conn, 
                        "UPDATE users 
                         SET UserName = ?, Password = ? 
                         WHERE User_id = ?"
                    );
                    mysqli_stmt_bind_param($stmt, "ssi", $username, $new_password, $admin_id);
                } else {
                    $stmt = mysqli_prepare($conn, 
                        "UPDATE users 
                         SET UserName = ? 
                         WHERE User_id = ?"
                    );
                    mysqli_stmt_bind_param($stmt, "si", $username, $admin_id);
                }

                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Failed to update profile: " . mysqli_error($conn));
                }

                // Update session
                $_SESSION['username'] = $username;

                logAction($conn, 'Update Profile', "Updated own profile");
                $response['success'] = true;
                $response['message'] = "Profile updated successfully";
                break;

            case 'delete':
                $user_id = intval($_POST['id'] ?? 0);
                if ($user_id == $_SESSION['admin_id']) throw new Exception("You cannot delete your own account");

                $stmt = mysqli_prepare($conn, "SELECT UserName FROM users WHERE User_id = ?");
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['UserName'] ?? 'Unknown';

                $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE User_id = ?");
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                if (!mysqli_stmt_execute($stmt)) throw new Exception("Failed to delete user");

                logAction($conn, 'Delete User', "Deleted user: $user");
                $response['success'] = true;
                $response['message'] = "User deleted successfully";
                break;

            default:
                throw new Exception("Invalid action");
        }

        mysqli_commit($conn);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $response['success'] = false;
        $response['message'] = $e->getMessage();
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit;

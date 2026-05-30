<?php
// ===============================
// PROFILE UPDATE (WORKING VERSION)
// ===============================
function logHistory($conn, $action, $details, $admin_id) {
    $academic_year = $_SESSION['academic_year'] ?? '2024-2025';

    $stmt = mysqli_prepare($conn, 
        "INSERT INTO history (action, data, user_id, academic_year, date)
         VALUES (?, ?, ?, ?, NOW())"
    );
    mysqli_stmt_bind_param($stmt, "ssis", $action, $details, $admin_id, $academic_year);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

if(!isset($conn)) {
    die('Database connection not found');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    session_start(); // ensure session
    $username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');
    $admin_id = intval($_SESSION['admin_id'] ?? 0);

    if ($admin_id === 0) {
        $_SESSION['profile_error'] = 'Invalid session.';
        $_SESSION['show_error_popup'] = true;
        header('Location: home.php');
        exit();
    }

    if (!empty($new_password) && $new_password !== $confirm) {
        $_SESSION['profile_error'] = "Passwords do not match!";
        $_SESSION['show_error_popup'] = true;
        header('Location: home.php');
        exit();
    }

    if (!empty($new_password)) {
        $stmt = mysqli_prepare($conn, "UPDATE users SET UserName = ?, Password = ? WHERE User_id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $username, $new_password, $admin_id);
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET UserName = ? WHERE User_id = ?");
        mysqli_stmt_bind_param($stmt, "si", $username, $admin_id);
    }

  if ($stmt && mysqli_stmt_execute($stmt)) {

    // LOG HISTORY
    $old_username = $_SESSION['username'] ?? 'Unknown';
    $new_username = $username;
    $changed_pw = !empty($new_password) ? "Yes" : "No";

    $details = "Updated profile | Old Username: $old_username | New Username: $new_username | Password Changed: $changed_pw";
    logHistory($conn, "Edit Profile", $details, $admin_id);

    // UPDATE SESSION USERNAME IF CHANGED
    $_SESSION['username'] = $new_username;

    $_SESSION['profile_success'] = "Profile updated successfully!";
    $_SESSION['show_success_popup'] = true;
}    else {
        $_SESSION['profile_error'] = "Failed to update profile!";
        $_SESSION['show_error_popup'] = true;
    }
    if ($stmt) mysqli_stmt_close($stmt);

    // PRG: redirect to avoid re-submission and ensure the success flag is available on the GET page
    header('Location: home.php');
    exit();
}

// On GET: capture flags then unset so modal shows once
$show_success = isset($_SESSION['show_success_popup']) && $_SESSION['show_success_popup'];
$show_error   = isset($_SESSION['show_error_popup']) && $_SESSION['show_error_popup'];
$error_text   = isset($_SESSION['profile_error']) ? $_SESSION['profile_error'] : '';

if ($show_success) unset($_SESSION['show_success_popup'], $_SESSION['profile_success']);
if ($show_error)   { unset($_SESSION['show_error_popup'], $_SESSION['profile_error']); }
?>
<!-- SUCCESS POPUP -->
<div id="successPopup" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" style="max-width:420px;margin-top:120px;">
    <div class="modal-content" style="padding:30px;text-align:center;border-radius:8px;box-shadow:0 6px 24px rgba(0,0,0,.18);">
      <div style="font-size:48px;color:#28a745;margin-bottom:8px;"><i class="icon-ok-circle"></i></div>
      <h4 style="margin-bottom:5px;">Profile Updated Successfully!</h4>
      <p style="color:#666;margin-bottom:20px;">Your profile has been changed and saved.</p>
      <button type="button" class="btn btn-success" data-dismiss="modal" id="successClose">Close</button>
    </div>
  </div>
</div>

<!-- ERROR POPUP -->
<div id="errorPopup" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" style="max-width:420px;margin-top:120px;">
    <div class="modal-content" style="padding:30px;text-align:center;border-radius:8px;box-shadow:0 6px 24px rgba(0,0,0,.18);">
      <div style="font-size:48px;color:#dc3545;margin-bottom:8px;"><i class="icon-remove-circle"></i></div>
      <h4 style="margin-bottom:5px;">Error</h4>
      <p id="errorMessage" style="color:#666;margin-bottom:20px;"></p>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>

<!-- PROFILE MODAL -->
<div id="profileModal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-header" style="background:maroon;color:#fff;padding:12px;border-radius:8px 8px 0 0;">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:#fff;">&times;</button>
    <h3 style="margin:0;"><i class="icon-pencil"></i> Edit Profile</h3>
  </div>
  <form method="POST" action="home.php" id="profileForm">
    <div class="modal-body" style="padding:20px;">
      <table class="table table-bordered" style="margin-bottom:0;">
        <tbody>
          <tr>
            <td style="width:30%;background:#f7f7f7;padding:10px;">Username:</td>
            <td style="padding:10px;"><input type="text" name="username" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;"></td>
          </tr>
          <tr>
            <td style="background:#f7f7f7;padding:10px;">New Password:</td>
            <td style="padding:10px;"><input type="password" name="new_password" placeholder="Leave blank to keep current" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;"><small style="color:#666;"> (Leave blank to keep current)</small></td>
          </tr>
          <tr>
            <td style="background:#f7f7f7;padding:10px;">Confirm Password:</td>
            <td style="padding:10px;"><input type="password" name="confirm_password" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;"></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="modal-footer" style="padding:12px;background:#f6f6f6;border-radius:0 0 8px 8px;">
      <button type="button" class="btn" data-dismiss="modal">Cancel</button>
      <button type="submit" name="update_profile" class="btn" style="background:maroon;color:#fff;border:none;padding:8px 18px;">Update Profile</button>
    </div>
  </form>
</div>

<script>
$(function(){
  // fill username when opening
  $(document).on('click','a[href="#profileModal"]',function(e){ 
    e.preventDefault();
    $('input[name="username"]').val("<?=(addslashes($_SESSION['username'] ?? ''))?>");
    $('input[name="new_password"], input[name="confirm_password"]').val('');
    $('#profileModal').modal('show');
  });

  <?php if ($show_success): ?>
    // show once after PRG
    $('#successPopup').modal('show');
    setTimeout(function(){ $('#successPopup').modal('hide'); }, 2200);
  <?php endif; ?>

  <?php if ($show_error): ?>
    $('#errorMessage').text(<?= json_encode($error_text) ?>);
    $('#errorPopup').modal('show');
  <?php endif; ?>
});
</script>

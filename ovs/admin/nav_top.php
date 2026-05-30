<?php
// Remove session_start() from here since it's already called in included files
require_once('../dbcon.php');

$id_session = $_SESSION['admin_id'] ?? null;
// Get current academic year
$year_query = mysqli_query($conn, "SELECT * FROM settings WHERE is_current = 1");
$current_year = ($year_query && mysqli_num_rows($year_query) > 0) ? mysqli_fetch_assoc($year_query) : null;

// Get admin user details if logged in
$user = null;
if (isset($_SESSION['admin_id'])) {
    $user_query = mysqli_query($conn, "SELECT * FROM users WHERE User_id = '".$_SESSION['admin_id']."'");
    $user = ($user_query && mysqli_num_rows($user_query) > 0) ? mysqli_fetch_assoc($user_query) : null;
}
?>

<!-- Add CSS and JS files -->
<link rel="stylesheet" href="css/admin-styles.css">

<div class="navbar navbar-fixed-top">
    <div style="background-color: #041d52ff !important; background-image: none;">
        <div class="container">
            <a class="brand">
                <img src="images/cit.png" width="80" height="80">
            </a>
            <a class="brand" style="margin-top: 20px">
                <h1>College of Information Technology</h1>
            </a>

            <!-- Display current info only -->
            <div class="current-info pull-right">
                <span class="current-year">
                    <i class="icon-calendar"></i> 
                    Academic Year: <?php echo htmlspecialchars($current_year['academic_year'] ?? 'N/A'); ?>
                </span>
                <span class="current-admin">
                    <i class="icon-user"></i> 
                    Admin: <?php echo htmlspecialchars($user['UserName'] ?? 'Guest'); ?>
                </span>
                <span class="current-datetime">
                    <i class="icon-time"></i> 
                    <span id="currentDateTime"></span>
                </span>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function updateDateTime() {
    var now = new Date();
    var options = { 
        weekday: 'short', 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit', 
        minute: '2-digit'
    };
    document.getElementById('currentDateTime').textContent = now.toLocaleDateString('en-US', options);
}

updateDateTime();
setInterval(updateDateTime, 1000);
</script>


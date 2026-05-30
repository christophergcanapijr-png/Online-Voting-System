<?php
include('session.php');
include('header.php');
include('dbcon.php');

if(isset($_POST['update_profile'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_password = $_POST['new_password'];
    
    if(!empty($new_password)) {
        if($new_password != $_POST['confirm_password']) {
            echo "<script>alert('Passwords do not match!');</script>";
        } else {
                mysqli_query($conn, "UPDATE users SET 
                UserName='$username', 
                Password='$new_password' 
                WHERE User_id='".$_SESSION['admin_id']."'");
            echo "<script>alert('Profile updated successfully!');window.location='home.php';</script>";
        }
    } else {
        mysqli_query($conn, "UPDATE users SET UserName='$username' WHERE User_id='".$_SESSION['admin_id']."'");
        echo "<script>alert('Profile updated successfully!');window.location='home.php';</script>";
    }
}

if(isset($_POST['add_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['new_username']);
    $password = $_POST['password'];
    
    if($password != $_POST['confirm_password']) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        mysqli_query($conn, "INSERT INTO users (UserName, Password, User_Type) 
            VALUES ('$username', '$password', 'Admin')");
        echo "<script>alert('New admin added successfully!');window.location='home.php';</script>";
    }
}

?>


</head>
<head>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/modern.css">
    <style>
/* Fix modal styling */
.modal.hide.fade {
    display: none;
}

.modal.hide.fade.in {
    display: block;
}

.modal-body {
    max-height: 600px;
    overflow-y: auto;
    padding: 20px;
}

.modal-header {
    background: #991b1b;
    color: white;
    padding: 15px 20px;
    border-bottom: 1px solid #ddd;
}

.modal-header h1 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
}

.modal-header .close {
    color: white;
    font-size: 20px;
    opacity: 0.8;
}

.modal-header .close:hover {
    opacity: 1;
}

.modal-body img {
    max-width: 200px;
    height: auto;
    margin-bottom: 15px;
    border-radius: 8px;
}

.pull-right-modal {
    width: 100%;
}

.pull-right-modal p {
    margin: 12px 0;
    font-size: 14px;
    color: #333;
    line-height: 1.6;
}

.pull-right-modal p strong {
    font-weight: 700;
    color: #1f2937;
}

.modal-footer {
    background: #f5f5f5;
    padding: 15px 20px;
    text-align: right;
    border-top: 1px solid #ddd;
}

.modal-footer .btn {
    margin-left: 8px;
}

/* Better layout for modal content */
.candidate-info-wrapper {
    display: flex;
    gap: 20px;
}

.candidate-photo {
    flex-shrink: 0;
    text-align: center;
}

.candidate-details {
    flex: 1;
}

.candidate-details p {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.candidate-details label {
    font-weight: 700;
    color: #2c3e50;
    min-width: 120px;
}

.candidate-details span {
    color: #555;
}
</style>
</head>
<body>
<?php include('nav_top.php'); ?>
<div class="wrapper">
 
<div class="home_body">
<div class="navbar">
	<div class="navbar-inner">
	<div class="container">	
	<ul class="nav nav-pills">
	  <li><a href="home.php"><i class="icon-home icon-large"></i>Home</a></li>
	  <li  class="active"><a  href="candidate_list.php"><i class="icon-align-justify icon-large"></i>Candidates List</a></li>  

	  <li class=""><a  href="voter_list.php"><i class="icon-align-justify icon-large"></i>Student List</a></li>  
		 <li><a  href="canvassing_report.php"><i class="icon-book icon-large"></i>Votes Report</a></li>
		    <li><a  href="History.php"><i class="icon-table icon-large"></i>History Log</a>
	 </ul>
	</div>
	</div>
	</div>
	
	<div id="element" class="hero-body">
	    <?php
    if(isset($_SESSION['success_msg'])) {
        echo '<div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                '.$_SESSION['success_msg'].'
              </div>';
        unset($_SESSION['success_msg']);
    }
    if(isset($_SESSION['error_msg'])) {
        echo '<div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                '.$_SESSION['error_msg'].'
              </div>';
        unset($_SESSION['error_msg']);
    }
    ?>

<!-- CATEGORY NAV -->
<div class="modern-tab-bar">
    <a href="candidate_list.php" class="tab-btn active">All</a>
    <a href="candidate_for_governor.php" class="tab-btn">Governor</a>
    <a href="candidate_for_vice-governor.php" class="tab-btn">Vice-Governor</a>
    <a href="1st_year.php" class="tab-btn">1st Year Representative</a>
    <a href="2nd_year.php" class="tab-btn">2nd Year Representative</a>
    <a href="3rd_year.php" class="tab-btn">3rd Year Representative</a>
    <a href="4th_year.php" class="tab-btn">4th Year Representative</a>
</div>

<!-- ADD CANDIDATE BUTTON -->
<div class="modern-tab-bar" style="margin-top:10px;">
    <a href="new_candidate.php" class="tab-btn add-btn">
        <i class="icon-plus"></i> Add Candidates
    </a>
</div>


	<form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
		<div class="demo_jui">
			<!-- EXACT VOTER LIST TABLE STRUCTURE -->
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Position</th>
						<th>FirstName</th>
						<th>LastName</th>
						<th>MiddleName</th>
						<th>Year</th>
						<th>Photo</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>

<?php 
$current_year = $_SESSION['academic_year'];
$query = "SELECT * FROM candidate WHERE academic_year = '$current_year' ORDER BY FirstName ASC";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
while($candidate_rows = mysqli_fetch_array($result)) {
    $id = $candidate_rows['CandidateID'];
    
?>

	<tr class="del<?php echo $id ?>">
		<td align="center"><?php echo $candidate_rows['Position']; ?></td>
		<td><?php echo $candidate_rows['FirstName']; ?></td>
		<td><?php echo $candidate_rows['LastName']; ?></td>
        <td><?php echo $candidate_rows['MiddleName']; ?></td>
		<td align="center"><?php echo $candidate_rows['Year']; ?></td>
		<td align="center">
		<img class="pic" 
			 width="40" 
			 height="30" 
			 src="<?php echo $candidate_rows['Photo'];?>" 
			 style="cursor: pointer;" 
			 onclick="$('#<?php echo $id; ?>').modal('show')" 
			 border="0">
	</td>
			<td align="center" style="white-space: nowrap;">
		<a class="btn btn-Success" href="edit_candidate.php<?php echo '?id='.$id; ?>"><i class="icon-edit icon-large"></i>&nbsp;Edit</a>
		<a class="btn btn-info" data-toggle="modal" href="#<?php echo $id; ?>"><i class="icon-list icon-large"></i>&nbsp;View</a>
		<a class="btn btn-danger1" id="<?php echo $id; ?>"><i class="icon-trash icon-large"></i>&nbsp;Delete</a>
		</td>

	<div class="modal hide fade" id="<?php echo $id ?>">
		<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h1>Candidate Information</h1>
	</div>	
		  <div class="modal-body">
		  
		  <div class="candidate-info-wrapper">
                <div class="candidate-photo">
                    <img src="<?php echo $candidate_rows['Photo'];?>" width="180" height="180" style="border-radius: 8px;">
                </div>
                <div class="candidate-details">
                    <p>
                        <label>First Name:</label>
                        <span><?php echo htmlspecialchars($candidate_rows['FirstName']);  ?></span>
                    </p>
                    <p>
                        <label>Last Name:</label>
                        <span><?php echo htmlspecialchars($candidate_rows['LastName']);  ?></span>
                    </p>
                    <p>
                        <label>Middle Name:</label>
                        <span><?php echo htmlspecialchars($candidate_rows['MiddleName']);  ?></span>
                    </p>
                    <p>
                        <label>Position:</label>
                        <span><?php echo htmlspecialchars($candidate_rows['Position']);  ?></span>
                    </p>
                    <p>
                        <label>Party:</label>
                        <span><?php echo htmlspecialchars($candidate_rows['Party']);  ?></span>
                    </p>
                    <p>
                        <label>Year:</label>
                        <span><?php echo htmlspecialchars($candidate_rows['Year']);  ?></span>
                    </p>
                    <p>
                        <label>Platform:</label>
                        <span><?php echo htmlspecialchars($candidate_rows['Platform']);  ?></span>
                    </p>
                </div>
            </div>
		  </div>
		  <div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Close</a>
		  
			</div>
			</div>	
			</div>

		
		
		
	<input type="hidden" name="data_name" class="data_name<?php echo $id ?>" value="<?php echo $candidate_rows['FirstName']." ".$candidate_rows['LastName']; ?>"/>
		<input type="hidden" name="user_name" class="user_name" value="<?php echo $_SESSION['User_Type']; ?>"/>
		
		</tr>
	<?php } ?>

				</tbody>
			</table>
		</div>	
	</form>
	</div>	
	
</div>
<input type="hidden" class="pc_date" name="pc_date"/>
<input type="hidden" class="pc_time" name="pc_time"/>

<!-- Add Edit User Modal -->
<div class="modal hide fade" id="editUserModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>Edit User</h3>
    </div>
    <form method="post" id="editUserForm">
        <div class="modal-body">
            <input type="hidden" name="edit_user_id" id="edit_user_id">
            <div class="control-group">
                <label>Username:</label>
                <input type="text" name="edit_username" id="edit_username" required>
            </div>
            <div class="control-group">
                <label>New Password:</label>
                <input type="password" name="edit_password">
                <p class="help-block">Leave blank to keep current password</p>
            </div>
            <div class="control-group">
                <label>Confirm Password:</label>
                <input type="password" name="edit_confirm_password">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>

<!-- Profile Edit Modal -->
<div class="modal hide fade" id="profileModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>Edit Profile</h3>
    </div>
    <form method="post">
        <div class="modal-body">
            <?php 
            $current_user = mysqli_query($conn, "SELECT * FROM users WHERE User_id='".$_SESSION['admin_id']."'");
            $user_data = mysqli_fetch_array($current_user);
            ?>
            <div class="control-group">
                <label>Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user_data['UserName']); ?>" required>
            </div>
            <div class="control-group">
                <label>New Password:</label>
                <input type="password" name="new_password">
                <p class="help-block">Leave blank to keep current password</p>
            </div>
            <div class="control-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">Close</button>
            <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>

<!-- User Management Modal -->
<div class="modal hide fade" id="userManageModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>User Management</h3>
        <button class="btn btn-primary btn-small" onclick="$('#addUserModal').modal('show');$('#userManageModal').modal('hide');">
            <i class="icon-plus"></i> Add New Admin
        </button>
    </div>
    <div class="modal-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>User Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users = mysqli_query($conn, "SELECT * FROM users ORDER BY User_Type");
                while($user = mysqli_fetch_array($users)):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['UserName']); ?></td>
                    <td><?php echo htmlspecialchars($user['User_Type']); ?></td>
                    <td>
                        <?php if($user['User_id'] != $id_session): ?>
                        <button class="btn btn-warning btn-small" 
                                onclick="editUser(<?php echo $user['User_id']; ?>, '<?php echo htmlspecialchars($user['UserName']); ?>')">
                            <i class="icon-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-small" 
                                onclick="deleteUser(<?php echo $user['User_id']; ?>)">
                            <i class="icon-trash"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal">Close</button>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal hide fade" id="addUserModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>Add New Admin</h3>
    </div>
    <form id="addUserForm">
        <div class="modal-body">
            <div class="control-group">
                <label>Username:</label>
                <input type="text" name="username" required>
                <input type="hidden" name="action" value="add">
            </div>
            <div class="control-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="control-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add User</button>
        </div>
    </form>
</div>

<!-- Add JavaScript for user management -->
<script type="text/javascript">
function deleteUser(userId) {
    if(confirm('Are you sure you want to delete this user?')) {
        $.ajax({
            type: 'POST',
            url: 'user_actions.php',
            data: {
                action: 'delete',
                id: userId
            },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if(response.success) {
                    $('#userManageModal').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Error processing request');
            }
        });
    }
}

$('#addUserModal form').on('submit', function(e) {
    e.preventDefault();
    const formData = $(this).serialize();
    
    $.ajax({
        type: 'POST',
        url: 'user_actions.php',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if(response.success) {
                alert('Admin added successfully!');
                $('#addUserModal').modal('hide');
                $('#userManageModal').modal('hide');
                location.reload();
            } else {
                alert(response.message || 'Error adding user');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            alert('Error processing request');
        }
    });
});

function editUser(userId, username) {
    $('#edit_user_id').val(userId);
    $('#edit_username').val(username);
    $('#editUserModal').modal('show');
    $('#userManageModal').modal('hide');
}

$('#editUserForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: 'user_actions.php',
        data: {
            action: 'edit',
            user_id: $('#edit_user_id').val(),
            username: $('#edit_username').val(),
            password: $('input[name="edit_password"]').val(),
            confirm_password: $('input[name="edit_confirm_password"]').val()
        },
        dataType: 'json',
        success: function(response) {
            alert(response.message);
            if(response.success) {
                $('#editUserModal').modal('hide');
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            alert('Error processing request');
        }
    });
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    // Delete candidate
    $('.btn-danger1').click(function() {
        var id = $(this).attr("id");
        var dataString = 'id=' + id;
        
        if(confirm("Are you sure you want to delete this candidate?")) {
            $.ajax({
                type: "POST",
                url: "delete_candidate.php",
                data: dataString,
                success: function(response) {
                    if(response === 'success') {
                        // Remove the row from table
                        $('.del' + id).fadeOut('slow');
                        alert("Candidate deleted successfully!");
                    } else {
                        alert("Error deleting candidate!");
                    }
                }
            });
        }
        return false;
    });
});
</script>
</body>
</html>
<?php
include('session.php');
include('header.php');
include('dbcon.php');

if (mysqli_connect_errno()) {
    die("Database connection failed: " . mysqli_connect_error());
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<body>
<?php include('nav_top.php'); ?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="span12">
                <div class="content">
                        

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">User Management</h3>
                            <div class="actions">
                                <button class="btn btn-primary" data-toggle="modal" href="#addUserModal">
                                    <i class="icon-plus"></i> Add New Admin
                                </button>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>User Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $users = mysqli_query($conn, "SELECT * FROM users ORDER BY User_Type");
                                    if (!$users) {
                                        die("Query failed: " . mysqli_error($conn));
                                    }

                                    while($user = mysqli_fetch_array($users)):
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['FirstName'] . ' ' . $user['LastName']); ?></td>
                                        <td><?php echo htmlspecialchars($user['UserName']); ?></td>
                                        <td><?php echo htmlspecialchars($user['User_Type']); ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-small edit-user" 
                                                    data-id="<?php echo htmlspecialchars($user['User_id']); ?>"
                                                    data-firstname="<?php echo htmlspecialchars($user['FirstName']); ?>"
                                                    data-lastname="<?php echo htmlspecialchars($user['LastName']); ?>"
                                                    data-username="<?php echo htmlspecialchars($user['UserName']); ?>"
                                                    data-toggle="modal" href="#editUserModal">
                                                <i class="icon-pencil"></i>
                                            </button>
                                            <?php if($user['User_id'] != $_SESSION['admin_id']): ?>
                                            <button class="btn btn-danger btn-small delete-user" 
                                                    data-id="<?php echo htmlspecialchars($user['User_id']); ?>">
                                                <i class="icon-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal hide fade" id="addUserModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>Add New Admin</h3>
    </div>
    <form id="addUserForm" method="post">
        <div class="modal-body">
            <div class="control-group">
                <label>Username:</label>
                <input type="text" name="username" required 
                       pattern="[a-zA-Z0-9_]+" title="Only letters, numbers, and underscore allowed">
            </div>
            <div class="control-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add User</button>
        </div>
    </form>
</div>

<!-- Edit User Modal -->
<div class="modal hide fade" id="editUserModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>Edit User</h3>
    </div>
    <form id="editUserForm" method="post">
        <input type="hidden" name="user_id" id="edit_user_id">
        <div class="modal-body">
            <div class="control-group">
                <label>Username:</label>
                <input type="text" name="username" id="edit_username" required>
            </div>
            <div class="control-group">
                <label>New Password:</label>
                <input type="password" name="new_password">
                <span class="help-block">Leave blank to keep current password</span>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Reset form and remove old alerts when modal opens
    $('#addUserModal').on('shown.bs.modal', function () {
        $('#addUserForm')[0].reset();
        $('.alert', this).remove(); // Remove all types of alerts (danger/success)
    });

    // Add User Submit
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();
        $('#addUserModal .alert').remove(); // Remove old alerts

        const $form = $(this);
        const username = $('[name="username"]', $form).val().trim();
        const password = $('[name="password"]', $form).val();

        if (!username || !password) {
            $('#addUserModal .modal-body').prepend(
                '<div class="alert alert-danger">Username and password are required</div>'
            );
            return false;
        }

        $form.find('button[type="submit"]').prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: 'user_actions.php',
            data: $form.serialize() + '&action=add',
            dataType: 'json',
            success: function(response) {
                $form.find('button[type="submit"]').prop('disabled', false);
                $('#addUserModal .alert').remove(); // Clear any leftover alerts

                if (response.success) {
                    alert(response.message);
                    $('#addUserModal').modal('hide');
                    $form[0].reset(); // Clear form fields
                } else {
                    $('#addUserModal .modal-body').prepend(
                        '<div class="alert alert-danger">' + response.message + '</div>'
                    );
                }
            },
            error: function(xhr) {
                $form.find('button[type="submit"]').prop('disabled', false);
                alert('Unexpected error occurred.');
            }
        });
    });

    // Edit User Submit
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();
        $('#editUserModal .alert').remove();

        const $form = $(this);
        const formData = $form.serialize() + '&action=edit';
        $form.find('button[type="submit"]').prop('disabled', true);

        $.ajax({
            url: 'user_actions.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $form.find('button[type="submit"]').prop('disabled', false);
                if (response.success) {
                    alert(response.message);
                    $('#editUserModal').modal('hide');
                    location.reload();
                } else {
                    $('#editUserModal .modal-body').prepend(
                        '<div class="alert alert-danger">' + response.message + '</div>'
                    );
                }
            },
            error: function(xhr) {
                $form.find('button[type="submit"]').prop('disabled', false);
                alert('Error updating user');
            }
        });
    });

    // Delete User
    $('.delete-user').click(function() {
        if (confirm('Are you sure you want to delete this user?')) {
            const userId = $(this).data('id');
            $.post('user_actions.php', { action: 'delete', id: userId }, function(response) {
                alert(response.message);
                if (response.success) location.reload();
            }, 'json');
        }
    });

    // Prefill edit modal
    $('.edit-user').click(function() {
        $('#edit_user_id').val($(this).data('id'));
        $('#edit_username').val($(this).data('username'));
    });
});
</script>

</body>
</html>

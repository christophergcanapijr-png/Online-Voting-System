$(document).ready(function() {
    // Profile Update
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serializeArray();
        formData.push({name: 'action', value: 'update_profile'});
        
        $.ajax({
            type: 'POST',
            url: 'user_actions.php',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#profileModal').modal('hide');
                    alert(response.message);
                    location.reload();
                }
            }
        });
    });

    // User Management
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'user_actions.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if(response.success) {
                    $('#addUserModal').modal('hide');
                    location.reload();
                }
            }
        });
    });

    // Delete User
    window.deleteUser = function(userId) {
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
                        location.reload();
                    }
                }
            });
        }
    };

    // Edit User
    window.editUser = function(userId, username) {
        $('#edit_user_id').val(userId);
        $('#edit_username').val(username);
        $('#editUserModal').modal('show');
        $('#userManageModal').modal('hide');
    };

    // Handle edit user form submission
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serializeArray();
        formData.push({name: 'action', value: 'edit_user'});
        
        $.ajax({
            type: 'POST',
            url: 'user_actions.php',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#editUserModal').modal('hide');
                    alert(response.message);
                    location.reload();
                }
            }
        });
    });

    // Academic Year functions
    window.changeAcademicYear = function(year) {
        if(confirm('Change academic year to ' + year + '?')) {
            $.ajax({
                type: 'POST',
                url: 'process_change_academic_year.php',
                data: { 
                    academic_year: year,
                    action: 'change_year'
                },
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        alert('Academic year changed to ' + year);
                        window.location.reload();
                    } else {
                        alert('Failed to change academic year: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Error changing academic year. Please try again.');
                }
            });
        }
    };

    // Add Academic Year
    $('#addAcademicYearForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'add_academic_year.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if(response.success) {
                    $('#addAcademicYearModal').modal('hide');
                    location.reload();
                }
            }
        });
    });
});

<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="icon-cog icon-large"></i> Admin Control <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
        <li>
            <a href="#profileModal" data-toggle="modal">
                <i class="icon-pencil"></i> Edit Profile
            </a>
        </li>
        <li>
            <a href="#addUserModal" data-toggle="modal">
                <i class="icon-user"></i> Add Admin User
            </a>
        </li>
        <li>
            <a href="manage_users.php">
                <i class="icon-trash"></i> Delete Admin User
            </a>
        </li>
        <li class="dropdown-submenu">
            <a href="#"><i class="icon-calendar"></i> Academic Year</a>
            <ul class="dropdown-menu">
                <?php
                $result = mysqli_query($conn, "SELECT academic_year, is_current FROM settings ORDER BY academic_year DESC");
                if($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $current = $row['is_current'] ? ' <i class=\"icon-ok\"></i>' : '';
                        echo "<li><a href='javascript:void(0)' onclick='changeAcademicYear(\"{$row['academic_year']}\")'>".
                             htmlspecialchars($row['academic_year']) . $current ."</a></li>";
                    }
                }
                ?>
                <li class="divider"></li>
                <li>
                    <a href="#addAcademicYearModal" data-toggle="modal">
                        <i class="icon-plus"></i> Add New Academic Year
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</li>

<!-- Add Admin User Modal -->
<div id="addUserModal" class="modal hide fade" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3><i class="icon-user"></i> Add Admin User</h3>
    </div>
    <form method="POST" action="home.php">
        <div class="modal-body">
            <div class="control-group">
                <label class="control-label">Username:</label>
                <div class="controls">
                    <input type="text" name="new_username" class="input-xlarge" placeholder="Enter username" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Password:</label>
                <div class="controls">
                    <input type="password" name="password" class="input-xlarge" placeholder="Enter password" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Confirm Password:</label>
                <div class="controls">
                    <input type="password" name="confirm_password" class="input-xlarge" placeholder="Confirm password" required>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" data-dismiss="modal">Cancel</button>
            <button type="submit" name="add_user" class="btn btn-primary">Add Admin</button>
        </div>
    </form>
</div>
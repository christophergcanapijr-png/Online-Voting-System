<?php ?>
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="icon-cog icon-large"></i> Admin <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
        <li><a href="#profileModal" data-toggle="modal"><i class="icon-pencil"></i> Edit Profile</a></li>
        <li><a href="manage_users.php"><i class="icon-user"></i> Manage Users</a></li>
        <li class="dropdown-submenu">
            <a href="#"><i class="icon-calendar"></i> Academic Year</a>
            <ul class="dropdown-menu">
                <?php
                $result = mysqli_query($conn, "SELECT academic_year, is_current FROM settings ORDER BY academic_year DESC");
                if($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $current = $row['is_current'] ? ' <i class="icon-ok"></i>' : '';
                        echo "<li><a href='javascript:void(0)' onclick='changeAcademicYear(\"{$row['academic_year']}\")'>".
                             htmlspecialchars($row['academic_year']) . $current ."</a></li>";
                    }
                }
                ?>
                <li class="divider"></li>
                <li><a href="#addAcademicYearModal" data-toggle="modal">
                    <i class="icon-plus"></i> Add New Academic Year
                </a></li>
            </ul>
        </li>
        <li class="divider"></li>
        <li><a href="#myModal" data-toggle="modal"><i class="icon-off"></i> Logout</a></li>
    </ul>
</li>
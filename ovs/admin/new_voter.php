<?php
include('session.php');
include('header.php');
include('dbcon.php');

// Messages for manual entry
$error_msg   = '';
$success_msg = '';

// Handle manual voter addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manual_save'])) {
    $stud_id       = mysqli_real_escape_string($conn, $_POST['stud_id']    ?? '');
    $fname         = mysqli_real_escape_string($conn, $_POST['fname']      ?? '');
    $lname         = mysqli_real_escape_string($conn, $_POST['lname']      ?? '');
    $year_manual   = mysqli_real_escape_string($conn, $_POST['Year']       ?? '');

    // Enrollment ALWAYS "enrolled"
    $enroll_status = "enrolled";

    if (empty($stud_id) || empty($fname) || empty($lname) || empty($year_manual)) {
        $error_msg = "All fields are required: Student ID, First Name, Last Name & Year.";
    } else {
        // Check if student ID already exists
        $check_sql = "SELECT StudentID FROM voters WHERE StudentID = '$stud_id'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $error_msg = "Student ID already exists in the system.";
        } else {

            // Username = Student ID, Password = Last Name
            $username = $stud_id;
            $password_plain = $lname;

            $sql = "INSERT INTO voters 
                    (StudentID, FirstName, LastName, Username, `Password`, `Year`, `Status`, `enrollment`, academic_year)
                    VALUES 
                    ('$stud_id', '$fname', '$lname', '$username', '$password_plain', '$year_manual', 'Unvoted', '$enroll_status', '{$_SESSION['academic_year']}')";

            if (mysqli_query($conn, $sql)) {

                // Record history
                mysqli_query($conn, "
                    INSERT INTO history (action, data, user_id, academic_year, `date`)
                    VALUES (
                        'Added voter',
                        '$stud_id - $fname $lname',
                        '{$_SESSION['admin_id']}',
                        '{$_SESSION['academic_year']}',
                        NOW()
                    )
                ") or die(mysqli_error($conn));

                $success_msg = "New voter added successfully!";
            } else {
                $error_msg = "Error adding voter: " . mysqli_error($conn);
            }
        }
    }
}
?>
<head>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/modern.css">
</head>

<body>
<?php include('nav_top.php'); ?>
<div class="wrapper">
  <div class="home_body">

    <div class="navbar">
      <div class="navbar-inner">
        <div class="container">    
          <ul class="nav nav-pills">
            <li><a href="home.php"><i class="icon-home icon-large"></i> Home</a></li>
            <li><a href="candidate_list.php"><i class="icon-align-justify icon-large"></i> Candidates List</a></li>  
            <li class="active"><a href="voter_list.php"><i class="icon-align-justify icon-large"></i> Student List</a></li>  
            <li><a href="canvassing_report.php"><i class="icon-book icon-large"></i> Votes Report</a></li>
            <li><a href="History.php"><i class="icon-table icon-large"></i> History Log</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div id="element" class="hero-body">

      <div class="pagination">
        <ul>
          <li><a href="voter_list.php"><font color="white">All</font></a></li>
          <li><a href="Voted_voters.php"><font color="white">Voted Student</font></a></li>
          <li><a href="Unvoted_voters.php"><font color="white">UnVoted Student</font></a></li>
          <li class="active"><a href="new_voter.php"><font color="white"><i class="icon-plus icon-large"></i> Import Student</font></a></li>
        </ul>
      </div>

      <div class="row-fluid">

        <!-- CSV IMPORT -->
        <div class="span6">
          <div class="well" style="padding:15px;">
            <h4>Import Voters from CSV</h4>

            <form id="import_voters" class="form-horizontal" method="POST" enctype="multipart/form-data">
              <div class="control-group">
                <label class="control-label">Upload CSV File:</label>
                <div class="controls">
                  <input type="file" name="voter_file" accept=".csv" required>
                  <p class="help-block">Upload CSV file with voter details</p>
                </div>
              </div>

              <div class="control-group">
                <div class="controls">
                  <button type="submit" class="btn btn-success">
                    <i class="icon-upload icon-large"></i> Import Voters
                  </button>
                  <button type="button" id="downloadTemplate" class="btn btn-info" style="margin-left: 10px;">
                    <i class="icon-download icon-large"></i> Download Template
                  </button>
                </div>
              </div>
            </form>
            <div id="importResult"></div>
          </div>
        </div>

        <!-- MANUAL ENTRY -->
        <div class="span6">
          <div class="well" style="padding:15px;">
            <h4>Manually Add Student</h4>

            <?php if ($error_msg): ?>
              <div class="alert alert-danger"><?php echo $error_msg; ?></div>
            <?php elseif ($success_msg): ?>
              <div class="alert alert-success"><?php echo $success_msg; ?></div>
            <?php endif; ?>

            <form method="POST" class="form-horizontal">

              <div class="control-group">
                <label class="control-label">Student ID:</label>
                <div class="controls">
                  <input type="text" name="stud_id" required>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">First Name:</label>
                <div class="controls">
                  <input type="text" name="fname" required>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Last Name:</label>
                <div class="controls">
                  <input type="text" name="lname" required>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Year:</label>
                <div class="controls">
                  <select name="Year" required>
                    <option value="">Select Year</option>
                    <option value="1st Year">1st Year</option>
                    <option value="2nd Year">2nd Year</option>
                    <option value="3rd Year">3rd Year</option>
                    <option value="4th Year">4th Year</option>
                  </select>
                </div>
              </div>

              <!-- REMOVED ENROLLMENT FIELD (ALWAYS ENROLLED) -->

              <div class="control-group">
                <div class="controls">
                  <button type="submit" name="manual_save" class="btn btn-primary">
                    <i class="icon-plus"></i> Add Student
                  </button>
                </div>
              </div>

              <p class="help-block" style="margin-left:10px;">
                <small><b>Note:</b> Username = Student ID. Password = Student's last name.</small>
              </p>

            </form>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>

<script>
$(document).ready(function() {

  $('#downloadTemplate').click(function() {
    window.location.href = 'download_template.php?fields=StudentID,FirstName,LastName,Year';
  });

  // CSV import
  $('#import_voters').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
      url: 'import_voters.php',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function(response) {

        if (response.success) {
          alert(response.message);
          window.location.href = 'voter_list.php';
        } else {
          let msg = response.message + "\n\n";
          if (response.errors)
            msg += "Errors:\n" + response.errors.join("\n");
          alert(msg);
        }
      },
      error: function() {
        alert('Error importing voters.');
      }
    });

  });

});
</script>

</body>
</html>

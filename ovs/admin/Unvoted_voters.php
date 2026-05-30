<?php
include('session.php');
include('header.php');
include('dbcon.php');
?>
</head>
<head>
	<link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/modern.css">
</head>
<body>
<?php include('nav_top.php'); ?>
<div class="wrapper">
  <div class="home_body">

    <!-- Navigation Bar -->
    <div class="navbar">
      <div class="navbar-inner">
        <div class="container">	
          <ul class="nav nav-pills">
            <li><a href="home.php"><i class="icon-home icon-large"></i>Home</a></li>
            <li><a href="candidate_list.php"><i class="icon-align-justify icon-large"></i>Candidates List</a></li>  
            <li class="active"><a href="voter_list.php"><i class="icon-align-justify icon-large"></i>Student List</a></li>  
            <li><a href="canvassing_report.php"><i class="icon-book icon-large"></i>Votes Report</a></li>
            <li><a href="History.php"><i class="icon-table icon-large"></i>History Log</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div id="element" class="hero-body">
      <div class="pagination">
        <ul>
          <li><a href="voter_list.php"><font color="white">All</font></a></li>
          <li><a href="Voted_voters.php"><font color="white">Voted Student</font></a></li>
          <li class="active"><a href="Unvoted_voters.php"><font color="white">UnVoted Student</font></a></li>
          <li><a href="new_voter.php"><font color="white"><i class="icon-plus icon-large"></i>Add Student</font></a></li>
        </ul>
      </div>
<div class="excel_button">
        <form method="POST" action="excel_unvoted_voter.php">
          <button id="excel" class="btn btn-success" name="save">
            <i class="icon-download icon-large"></i>Download Excel File
          </button>
        </form>
      </div>

      <div class="search-form" style="margin: 20px 0;">
        <form method="get" action="Unvoted_voters.php" class="form-inline">
          <input type="text" 
                 id="search_id" 
                 name="id" 
                 class="form-control" 
                 placeholder="Search by Student ID e.g. 225711426" 
                 value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
          <button type="submit" class="btn btn-primary">Search</button>
        </form>
      </div>

      
      <div class="demo_jui">
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Student ID</th>
              <th>Name</th>
              <th>Year</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $current_year = $_SESSION['academic_year'];
            $search_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

            $sql = "
              SELECT * FROM voters
              WHERE StudentID NOT IN (
                SELECT voter_id FROM votes WHERE academic_year = '$current_year'
              )
              AND academic_year = '$current_year'
            ";

            if ($search_id) {
              $sql .= " AND StudentID LIKE '%$search_id%'";
            }

            $sql .= " ORDER BY StudentID";

            $voter_query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
            while ($row = mysqli_fetch_array($voter_query)):
              $voterID = $row['StudentID'];
            ?>
            <tr class="del<?php echo $voterID; ?>">
              <td><?php echo htmlspecialchars($row['StudentID']); ?></td>
              <td><?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['MiddleName'] . ' ' . $row['LastName']); ?></td>

              <td align="center"><?php echo htmlspecialchars($row['Year']); ?></td>
              <td align="center">
                <?php 
                  $status_check = mysqli_query($conn, "SELECT 1 FROM votes WHERE voter_id = '$voterID' AND academic_year = '$current_year' LIMIT 1");
                  $real_status = (mysqli_num_rows($status_check) > 0) ? 'Voted' : 'Not Voted';
                  echo $real_status;
                ?>
              </td>
              <td align="center">
                <button class="btn btn-danger delete-voter" data-voter-id="<?php echo $voterID; ?>">
                    <i class="icon-trash icon-large"></i> Delete
                </button>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <input type="hidden" class="pc_date" name="pc_date"/>
      <input type="hidden" class="pc_time" name="pc_time"/>
    </div> <!-- /.hero-body -->
  </div> <!-- /.home_body -->
</div>   <!-- /.wrapper -->

<script type="text/javascript">
$(document).ready(function() {
  var now = new Date(),
      pc_date = (now.getMonth()+1) + '/' + now.getDate() + '/' + now.getFullYear(),
      pc_time = now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
  $('.pc_date').val(pc_date);
  $('.pc_time').val(pc_time);

  $('.btn-danger').click(function(e) {
    e.preventDefault();
    var voterId = $(this).attr('id');
    if (confirm('Are you sure you want to delete this Voter?')) {
      $.ajax({
        type: 'POST',
        url: 'delete_voter.php',
        data: { voter_id: voterId },
        dataType: 'json',
        success: function(resp) {
          if (resp.success) {
            $('.del'+voterId).fadeOut('slow');
          } else {
            alert('Error: ' + resp.message);
          }
        },
        error: function(xhr, status, err) {
          console.error(err);
          alert('Error deleting voter.');
        }
      });
    }
  });
});
</script>
</body>
</html>

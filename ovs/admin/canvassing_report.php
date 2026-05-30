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
<div class="navbar">
    <div class="navbar-inner">
    <div class="container">	
    <ul class="nav nav-pills">
      <li><a href="home.php"><i class="icon-home icon-large"></i>Home</a></li>
      <li><a href="candidate_list.php"><i class="icon-align-justify icon-large"></i>Candidates List</a></li>  
      <li><a href="voter_list.php"><i class="icon-align-justify icon-large"></i>Student List</a></li>  
      <li class="active"><a href="canvassing_report.php"><i class="icon-book icon-large"></i>Votes Report</a></li>
      <li><a href="History.php"><i class="icon-table icon-large"></i>History Log</a>
     </ul>
    </div>
    </div>
</div>

<div id="element" class="hero-body">
    <!-- MODERN VOTE REPORT TABS -->
<div class="modern-tab-bar">
    <a href="Canvassing_report.php" class="tab-btn <?php echo basename($_SERVER['PHP_SELF'])=='Canvassing_report.php'?'active':''; ?>">All</a>
    <a href="C_governor.php" class="tab-btn <?php echo basename($_SERVER['PHP_SELF'])=='C_governor.php'?'active':''; ?>">Governor</a>
    <a href="C_vice-governor.php" class="tab-btn <?php echo basename($_SERVER['PHP_SELF'])=='C_vice-governor.php'?'active':''; ?>">Vice-Governor</a>
    <a href="C_1st_year.php" class="tab-btn <?php echo basename($_SERVER['PHP_SELF'])=='C_1st_year.php'?'active':''; ?>">1st Year Representative</a>
    <a href="C_2nd_year.php" class="tab-btn <?php echo basename($_SERVER['PHP_SELF'])=='C_2nd_year.php'?'active':''; ?>">2nd Year Representative</a>
    <a href="C_3rd_year.php" class="tab-btn <?php echo basename($_SERVER['PHP_SELF'])=='C_3rd_year.php'?'active':''; ?>">3rd Year Representative</a>
    <a href="C_4th_year.php" class="tab-btn <?php echo basename($_SERVER['PHP_SELF'])=='C_4th_year.php'?'active':''; ?>">4th Year Representative</a>
</div>

<?php
$current_page = basename($_SERVER['PHP_SELF'], '.php');

$position_filter = "";
switch($current_page) {
    case 'C_governor':
        $position_filter = "AND c.Position='Governor'";
        break;
    case 'C_vice-governor':
        $position_filter = "AND c.Position='Vice-Governor'";
        break;
    case 'C_1st_year':
        $position_filter = "AND c.Position='1st Year Representative'";
        break;
    case 'C_2nd_year':
        $position_filter = "AND c.Position='2nd Year Representative'";
        break;
    case 'C_3rd_year':
        $position_filter = "AND c.Position='3rd Year Representative'";
        break;
    case 'C_4th_year':
        $position_filter = "AND c.Position='4th Year Representative'";
        break;
    default:
        $position_filter = ""; // show all positions
}

$current_year = $_SESSION['academic_year'];

$query = "SELECT c.*, COUNT(v.ID) as vote_count 
          FROM candidate c 
          LEFT JOIN votes v ON c.CandidateID = v.CandidateID 
          WHERE c.academic_year = '$current_year' $position_filter
          GROUP BY c.CandidateID 
          ORDER BY c.Position, vote_count DESC";

$votes_query = mysqli_query($conn, $query) or die(mysqli_error($conn));
?>

<form method="POST" action="canvassing_excel.php">
    <input type="hidden" name="position_filter" value="<?php echo $current_page; ?>">
    <button id="save_voter" class="btn btn-success" name="save" <?php if(mysqli_num_rows($votes_query) == 0) echo 'disabled'; ?>>
        <i class="icon-download icon-large"></i>Download Reports
    </button>
</form>

<div class="demo_jui">
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Position</th>
            <th>FirstName</th>
            <th>MiddleName</th>
            <th>LastName</th>
            <th>Year</th>
            <th>Photo</th>
            <th>No. of Votes</th>
        </tr>
    </thead>
    <tbody>

<?php 
while($candidate_rows = mysqli_fetch_array($votes_query)) {
    $id = $candidate_rows['CandidateID'];
?>
<tr class="del<?php echo $id ?>">
    <td align="center"><?php echo $candidate_rows['Position']; ?></td>
    <td><?php echo $candidate_rows['FirstName']; ?></td>
    <td><?php echo $candidate_rows['MiddleName']; ?></td>
    <td><?php echo $candidate_rows['LastName']; ?></td>
    <td align="center"><?php echo $candidate_rows['Year']; ?></td>
    <td align="center">
        <img class="pic" width="40" height="30" src="<?php echo $candidate_rows['Photo'];?>" border="0" 
        onmouseover="showtrail('<?php echo $candidate_rows['Photo'];?>','<?php echo $candidate_rows['FirstName']." ".$candidate_rows['LastName'];?>',200,5)" 
        onmouseout="hidetrail()">
    </td>
    <td align="center"><?php echo $candidate_rows['vote_count']; ?></td>
</tr>
<?php } ?>

    </tbody>
</table>
</div>

</div>
</div>
</div>
</body>
</html>

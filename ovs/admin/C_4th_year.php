<?php
include('session.php');
include('header.php');
include('dbcon.php');

$current_year = $_SESSION['academic_year'];
$position = '4th Year Representative'; // Change for each position file

$candidate_query = mysqli_query($conn, "SELECT c.*, 
    (SELECT COUNT(*) FROM votes v 
     WHERE v.CandidateID = c.CandidateID 
     AND v.academic_year = '$current_year') as vote_count
    FROM candidate c 
    WHERE c.Position = '$position' 
    AND c.academic_year = '$current_year'
    ORDER BY vote_count DESC") 
    or die(mysqli_error($conn));
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
	  <li><a  href="candidate_list.php"><i class="icon-align-justify icon-large"></i>Candidates List</a></li>  

	  <li class=""><a  href="voter_list.php"><i class="icon-align-justify icon-large"></i>Student List</a></li>  
		 <li class="active"><a  href="canvassing_report.php"><i class="icon-book icon-large"></i>Votes Report</a></li>
		    <li><a  href="History.php"><i class="icon-table icon-large"></i>History Log</a>
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
    // Get 4th Year Representative candidates for Excel download
    $query = mysqli_query($conn, "SELECT * FROM candidate WHERE Position='4th Year Representative' LIMIT 1");
    $row = mysqli_fetch_array($query);
    $id_excel = isset($row['CandidateID']) ? $row['CandidateID'] : '';
    ?>
    
    <form method="POST" action="canvassing_excel.php">
        <input type="hidden" name="position_filter" value="<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?>">
        <button id="save_voter" class="btn btn-success" name="save" <?php if(empty($id_excel)) echo 'disabled'; ?>>
            <i class="icon-download icon-large"></i>Download Reports
        </button>
    </form>

    <table class="users-table">

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

<?php $year_q = mysqli_query($conn, "SELECT academic_year FROM settings WHERE is_current = 1");
$current_year = mysqli_fetch_assoc($year_q)['academic_year'];
		while($candidate_rows=mysqli_fetch_array($candidate_query)){ $id=$candidate_rows['CandidateID'];
		$fl=$candidate_rows['FirstName'];
	
		?>

<tr class="del<?php echo $id ?>">
	<td align="center"><?php echo $candidate_rows['Position']; ?></td>
	<td><?php echo $candidate_rows['FirstName']; ?></td>
	<td><?php echo $candidate_rows['LastName']; ?></td>
	<td><?php echo $candidate_rows['MiddleName']; ?></td>
	<td align="center"><?php echo $candidate_rows['Year']; ?></td>
	
	<td align="center"><img class="pic" width="40" height="30" src="<?php echo $candidate_rows['Photo'];?>" border="0" onmouseover="showtrail('<?php echo $candidate_rows['Photo'];?>','<?php echo $candidate_rows['FirstName']." ".$candidate_rows['LastName'];?> ',200,5)" onmouseout="hidetrail()"></a></td>
		<td align="center">
	<?php $votes_query = mysqli_query($conn, "SELECT * FROM votes WHERE CandidateID = '$id' AND academic_year = '$current_year'");
$vote_count = mysqli_num_rows($votes_query);
echo $vote_count;
	?>
</td>	




	
	
	
<input type="hidden" name="data_name" class="data_name<?php echo $id ?>" value="<?php echo $candidate_rows['FirstName']." ".$candidate_rows['LastName']; ?>"/>
	<input type="hidden" name="user_name" class="user_name" value="<?php echo $_SESSION['User_Type']; ?>"/>
	
	</tr>
<?php } ?>

			</tbody>
		</table>
	</div>	
	</div>	
	
	
</div>
<input type="hidden" class="pc_date" name="pc_date"/>
<input type="hidden" class="pc_time" name="pc_time"/>
</body>
</html>

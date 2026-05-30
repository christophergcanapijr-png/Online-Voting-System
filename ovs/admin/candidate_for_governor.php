<?php
include('session.php');
include('header.php');
include('dbcon.php');
?>
</head>
<head>
	<link rel="stylesheet" href="css/navbar.css">
	<link rel="stylesheet" href="css/modern.css">
	<link rel="stylesheet" href="css/table-style.css"> <!-- NEW: Link to reusable table CSS -->
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
    <a href="candidate_list.php" class="tab-btn">All</a>
    <a href="candidate_for_governor.php" class="tab-btn active">Governor</a>
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
			<!-- UPDATED: Clean table structure matching voter list -->
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
$candidate_query = mysqli_query($conn, "SELECT * FROM candidate 
                                      WHERE Position='Governor' 
                                      AND academic_year='$current_year'") 
or die(mysqli_error($conn));
		while($candidate_rows=mysqli_fetch_array($candidate_query)){ $id=$candidate_rows['CandidateID'];
		$fl=$candidate_rows['FirstName'];
	
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
	<td align="center">
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
				<?php if(!empty($candidate_rows['Platform'])): ?>
				<p>
					<label>Platform:</label>
					<span><?php echo htmlspecialchars($candidate_rows['Platform']);  ?></span>
				</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>
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
</body>
</html>
<script type="text/javascript">
	$(document).ready( function() {
	
	var myDate = new Date();
var pc_date = (myDate.getMonth()+1) + '/' + (myDate.getDate()) + '/' + myDate.getFullYear();
var pc_time = myDate.getHours()+':'+myDate.getMinutes()+':'+myDate.getSeconds();
jQuery(".pc_date").val(pc_date);
jQuery(".pc_time").val(pc_time);
	
	
	$('.btn-danger1').click( function() {
		
		var id = $(this).attr("id");
		var pc_date = $('.pc_date').val();
		var pc_time = $('.pc_time').val();
		var data_name = $('.data_name'+id).val();
		var user_name = $('.user_name').val();
		
		if(confirm("Are you sure you want to delete this Candidate?")){
			
		
			$.ajax({
			type: "POST",
			url: "delete_candidate.php",
			data: ({id: id,pc_time:pc_time,pc_date:pc_date,data_name:data_name,user_name:user_name}),
			cache: false,
			success: function(html){
			$(".del"+id).fadeOut('slow'); 
			} 
			}); 
			}else{
			return false;}
		});				
	});

</script>

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
	  <li  class="active"><a  href="candidate_list.php"><i class="icon-align-justify icon-large"></i>Candidates List</a></li>  

	  <li class=""><a  href="voter_list.php"><i class="icon-align-justify icon-large"></i>Student List</a></li>  
		 <li><a  href="canvassing_report.php"><i class="icon-book icon-large"></i>Votes Report</a></li>
		    <li><a  href="History.php"><i class="icon-table icon-large"></i>History Log</a>
	 </ul>
	</div>
	</div>
	</div>
	
	<div id="element" class="hero-body">
	         <!-- CATEGORY NAV -->
<div class="modern-tab-bar">
    <a href="candidate_list.php" class="tab-btn">All</a>
    <a href="candidate_for_governor.php" class="tab-btn">Governor</a>
    <a href="candidate_for_vice-governor.php" class="tab-btn active">Vice-Governor</a>
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
				<th>Actions</th>
				
				</tr>
			</thead>
			<tbody>

<?php 
$current_year = $_SESSION['academic_year'];
$candidate_query = mysqli_query($conn, "SELECT * FROM candidate 
                                      WHERE Position='Vice-Governor' 
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
	<td align="center"><img class="pic" width="40" height="30" src="<?php echo $candidate_rows['Photo'];?>" border="0" onmouseover="showtrail('<?php echo $candidate_rows['Photo'];?>','<?php echo $candidate_rows['FirstName']." ".$candidate_rows['LastName'];?> ',200,5)" onmouseout="hidetrail()"></a></td>
	<td align="center" style="white-space: nowrap;">
	<a class="btn btn-Success" href="edit_candidate.php<?php echo '?id='.$id; ?>"><i class="icon-edit icon-large"></i>&nbsp;Edit</a>&nbsp;
	<a class="btn btn-info"  data-toggle="modal" href="#<?php echo $id; ?>" ><i class="icon-list icon-large"></i>&nbsp;View</a>
	<a class="btn btn-danger1" id="<?php echo $id; ?>"><i class="icon-trash icon-large"></i>&nbsp;Delete</a>&nbsp;
	</td>

<div class="modal hide fade" id="<?php echo $id ?>">
	<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">�</button>
<h1>Candidate Information</h1>
</div>	
	  <div class="modal-body">
	  
	  <p><img src="<?php echo $candidate_rows['Photo'];?>" width="200" height="200"></p>
	  <div class="pull-right-modal">
	  <p>
	  FirstName:&nbsp;<?php echo $candidate_rows['FirstName'];  ?>
	  </p>
	   <p>
	  LastName:&nbsp;<?php echo $candidate_rows['LastName'];  ?>
	  </p>
	  <p>
	  MiddleName:&nbsp;<?php echo $candidate_rows['MiddleName'];  ?>
	  </p>
	  <p>
	  Gender:&nbsp;<?php echo $candidate_rows['Gender'];  ?>
	  </p>
	
	   <p>
	  Position:&nbsp;<?php echo $candidate_rows['Position'];  ?>
	  </p>
	   <p>
	  Party:&nbsp;<?php echo $candidate_rows['Party'];  ?>
	  </p>
	  <p>
	  Year:&nbsp;<?php echo $candidate_rows['Year'];  ?>
	  </p>
	  </div>
	  </div>
	  <div class="modal-footer">
	    <a href="#" class="btn" data-dismiss="modal">Close</a>
	  
		</div>
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


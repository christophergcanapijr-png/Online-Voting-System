<?php
include('session.php');
include('header.php');
include('dbcon.php');

$get_id = $_GET['id'];
$success_message = '';

// PROCESS FORM SUBMISSION FIRST (before any HTML output)
if (isset($_POST['save'])) {
    $rfirstname = mysqli_real_escape_string($conn, $_POST['rfirstname']);
    $rlastname = mysqli_real_escape_string($conn, $_POST['rlastname']);
    $ryear = mysqli_real_escape_string($conn, $_POST['ryear']);
    $rposition = mysqli_real_escape_string($conn, $_POST['rposition']);
    $rname = mysqli_real_escape_string($conn, $_POST['rname']);
    $party = mysqli_real_escape_string($conn, $_POST['party']);
    $platform = mysqli_real_escape_string($conn, $_POST['platform']);
    $academic_year = $_SESSION['academic_year'] ?? '2024-2025';
    $location = '';

    // Handle image upload
    if(!empty($_FILES['image']['tmp_name'])) {
        $image_name = $_FILES['image']['name'];
        $upload_path = "upload/" . $image_name;
        
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $upload_path)) {
            $location = ", Photo = '$upload_path'";
        }
    }

    // Update candidate WITH academic_year
    $update_query = "UPDATE candidate SET 
        FirstName='$rfirstname',
        LastName='$rlastname',
        Year='$ryear',
        Position='$rposition',
        MiddleName='$rname',
        Party='$party', 
        Platform='$platform',
        academic_year='$academic_year'
        $location 
        WHERE CandidateID='$get_id'";
    
    $update = mysqli_query($conn, $update_query);
    
    if(!$update) {
        die("Update Error: " . mysqli_error($conn));
    }

    if($update) {
        // Try multiple possible session variable names
        $admin_id = null;
        if(isset($_SESSION['admin_id'])) {
            $admin_id = $_SESSION['admin_id'];
        } elseif(isset($_SESSION['id'])) {
            $admin_id = $_SESSION['id'];
        } elseif(isset($_SESSION['user_id'])) {
            $admin_id = $_SESSION['user_id'];
        } elseif(isset($_SESSION['User_id'])) {
            $admin_id = $_SESSION['User_id'];
        }
        
        // Debug output
        $debug_info = "Admin ID: " . ($admin_id ? $admin_id : 'NULL') . " | Academic Year: $academic_year";
        
        if($admin_id) {
            $details = mysqli_real_escape_string($conn, "Edited candidate: $rfirstname $rlastname ($rposition) for $academic_year");
            $action = "Edit Candidate";
            
            // Insert with academic_year column
            $history_query = "INSERT INTO history (data, action, date, user_id, academic_year) 
                             VALUES ('$details', '$action', NOW(), $admin_id, '$academic_year')";
            
            $history_result = mysqli_query($conn, $history_query);
            
            if($history_result) {
                $success_message = 'Candidate updated and logged! [' . $debug_info . ']';
            } else {
                $error = mysqli_error($conn);
                $success_message = 'Candidate updated but logging failed: ' . $error . ' [Query: ' . $history_query . ']';
            }
        } else {
            $success_message = 'Candidate updated but no admin_id found in session. Available: ' . implode(', ', array_keys($_SESSION));
        }
    }
}

// Get candidate data
$result = mysqli_query($conn, "SELECT * FROM candidate WHERE CandidateID='$get_id'") or die(mysqli_error($conn));
$row = mysqli_fetch_array($result);
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
    <li><a href="home.php"><i class="icon-home icon-large"></i>Home</a></li>
    <li class="active"><a href="candidate_list.php"><i class="icon-align-justify icon-large"></i>Candidates List</a></li>  
    <li><a href="voter_list.php"><i class="icon-align-justify icon-large"></i>Voters List</a></li>  
    <li><a href="canvassing_report.php"><i class="icon-book icon-large"></i>Votes Report</a></li>
    <li><a href="History.php"><i class="icon-table icon-large"></i>History Log</a></li>
    </ul>
    </div>
    </div>
</div>

<div id="element" class="hero-body">
<form method="POST" class="form-horizontal" enctype="multipart/form-data">
<input type="hidden" name="user_name" class="user_name" value="<?php echo $_SESSION['User_Type']; ?>"/>
<fieldset>
<legend><font color="white">Edit Candidate</font></legend>
<br/>
<div class="candidate_margin">
<ul class="thumbnails_new_voter">
<li class="span3">
<div class="thumbnail_new_voter">

    <div class="control-group">
        <label class="control-label" for="input01">FirstName:</label>
        <div class="controls">
            <input type="text" name="rfirstname" class="rfirstname" value="<?php echo $row['FirstName']; ?>" required>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="input01">LastName:</label>
        <div class="controls">
            <input type="text" name="rlastname" class="rlastname" value="<?php echo $row['LastName']; ?>" required>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="input01">MiddleName:</label>
        <div class="controls">
            <input type="text" name="rname" class="rname" value="<?php echo $row['MiddleName']; ?>">
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="input01">Year Level:</label>
        <div class="controls">
            <select name="ryear" class="ryear" required>
                <option value="<?php echo $row['Year']; ?>"><?php echo $row['Year']; ?></option>
                <option value="1st year">1st year</option>
                <option value="2nd year">2nd year</option>
                <option value="3rd year">3rd year</option>
                <option value="4th year">4th year</option>
            </select>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="input01">Position:</label>
        <div class="controls">
            <select name="rposition" class="rposition" required>
                <option value="<?php echo $row['Position']; ?>"><?php echo $row['Position']; ?></option>
                <option value="Governor">Governor</option>
                <option value="Vice-Governor">Vice-Governor</option>
                <option value="1st Year Representative">1st Year Representative</option>
                <option value="2nd Year Representative">2nd Year Representative</option>
                <option value="3rd Year Representative">3rd Year Representative</option>
                <option value="4th Year Representative">4th Year Representative</option>
            </select>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="input01">Party:</label>
        <div class="controls">
            <input type="text" name="party" class="party" value="<?php echo $row['Party']; ?>">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="input01">Image:</label>
        <div class="controls">
            <input type="file" name="image" class="font"> 
            <?php if(!empty($row['Photo'])): ?>
                <small>Current: <?php echo basename($row['Photo']); ?></small>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="platform">Platform:</label>
        <div class="controls">
            <textarea name="platform" id="platform" class="span2" rows="4"><?php echo $row['Platform']; ?></textarea>
        </div>
    </div>
    
    <div class="control-group">
        <div class="controls">
            <button class="btn btn-primary" name="save"><i class="icon-save icon-large"></i> Save</button>
            <a href="candidate_list.php" class="btn">Cancel</a>
        </div>
    </div>
    
</div>
</li>
</ul>
</div>
</fieldset>
</form>

</div>
</div>
</div>

<style>
.popup-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
}

.popup-overlay.show {
    display: flex !important;
    align-items: center;
    justify-content: center;
}

.popup-box {
    background: #ffffff;
    border-radius: 12px;
    padding: 30px;
    max-width: 400px;
    width: 90%;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    position: relative;
    animation: popupSlideIn 0.3s ease-out;
}

@keyframes popupSlideIn {
    from { opacity: 0; transform: translateY(-30px) scale(0.9); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.popup-close {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #f3f4f6;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
    color: #6b7280;
    transition: all 0.2s;
}

.popup-close:hover {
    background: #e5e7eb;
    color: #991b1b;
    transform: rotate(90deg);
}

.popup-icon { margin-bottom: 20px; }
.popup-title { font-size: 22px; font-weight: 700; margin: 0 0 10px 0; color: #1f2937; }
.popup-message { font-size: 14px; color: #6b7280; margin: 0 0 25px 0; }
.popup-btn {
    background: #991b1b;
    color: #ffffff;
    border: none;
    padding: 12px 40px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.popup-btn:hover { background: #7a1414; }
</style>

<script>
function showPopup(type, title, message) {
    $('.popup-overlay').remove();
    
    var icon = type === 'success' 
        ? '<i class="icon-ok-circle" style="color: #10b981; font-size: 48px;"></i>'
        : '<i class="icon-remove-circle" style="color: #ef4444; font-size: 48px;"></i>';
    
    var popup = `
        <div class="popup-overlay show">
            <div class="popup-box ${type}">
                <button class="popup-close">&times;</button>
                <div class="popup-icon">${icon}</div>
                <h3 class="popup-title">${title}</h3>
                <p class="popup-message">${message}</p>
                <button class="popup-btn" onclick="$('.popup-overlay').fadeOut(300, function(){ $(this).remove(); })">
                    OK
                </button>
            </div>
        </div>
    `;
    
    $('body').append(popup);
    $('.popup-overlay').hide().fadeIn(300);
}

$(document).ready(function() {
    $(document).on('click', '.popup-close, .popup-overlay', function(e) {
        if($(e.target).hasClass('popup-overlay') || $(e.target).hasClass('popup-close')) {
            $('.popup-overlay').fadeOut(300, function() {
                $(this).remove();
            });
        }
    });
    
    <?php if(!empty($success_message)): ?>
    showPopup('success', 'Success!', '<?php echo $success_message; ?>');
    setTimeout(function() {
        // Force page reload with cache bypass
        window.location.href = 'candidate_list.php?t=' + new Date().getTime();
    }, 1500);
    <?php endif; ?>
});
</script>

</body>
</html>
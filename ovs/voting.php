<?php
// filepath: c:\xampp\htdocs\ovs\ovs\ovs\voting.php
include('session.php');
include('dbcon.php');

// Get student's year level
$student_query = mysqli_query($conn, "SELECT Year FROM voters WHERE StudentID = '$_SESSION[id]'") 
    or die(mysqli_error($conn));
$student = mysqli_fetch_array($student_query);
$student_year = $student['Year'];

// Map year level to position and vote action
$year_config = [
    '1st Year' => ['position' => '1st Year Representative', 'action' => 'vote.php'],
    '2nd Year' => ['position' => '2nd Year Representative', 'action' => 'vote2.php'],
    '3rd Year' => ['position' => '3rd Year Representative', 'action' => 'vote3.php'],
    '4th Year' => ['position' => '4th Year Representative', 'action' => 'vote4.php']
];

$config = $year_config[$student_year] ?? null;

if (!$config) {
    $_SESSION['error'] = "Invalid year level detected.";
    header("Location: index.php");
    exit();
}

$rep_position = $config['position'];
$vote_action = 'vote.php';

// Load active academic year
$year_q = mysqli_query($conn, "SELECT academic_year FROM settings WHERE is_current = 1");
if ($yr = mysqli_fetch_assoc($year_q)) {
    $_SESSION['academic_year'] = $yr['academic_year'];
}
$current_year = $_SESSION['academic_year'];

$user_query = mysqli_query($conn, "SELECT FirstName, MiddleName, LastName FROM voters WHERE StudentID = '$_SESSION[id]'");
$user_row = mysqli_fetch_assoc($user_query);

include('header.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/voting.css" />
 </head>  
<body>

<div class="navbar">
  <div class="container">
    <a class="brand">
      <img src="admin/images/cit.png" width="60" height="60" alt="UCV Logo">
      <div class="brand-text-container">
        <div class="chmsc_nav"><h2>College of Information Technology</h2></div>
      </div>
    </a>
    <?php include('head.php'); ?>
  </div>
</div>

<div class="wrapper">
  <form id="voteForm" method="post" action="<?php echo $vote_action; ?>">
    
    <!-- Welcome Banner -->
    <div class="welcome-banner">
      <h2>Welcome, <?php echo $user_row['FirstName'] . ' ' . $user_row['LastName']; ?>!</h2>
      <p>You are voting as a <strong><?php echo $student_year; ?></strong> student. Please select one candidate for each position below.</p>
    </div>

    <!-- Hidden inputs for form submission -->
    <input type="hidden" name="governor" id="governor_input">
    <input type="hidden" name="vice" id="vice_input">
    <input type="hidden" name="representative" id="representative_input">

    <!-- Governor Section -->
    <div class="position-section">
      <div class="position-header">
        <h3 class="position-title">Governor</h3>
        <span class="selected-indicator" id="gov-selected">✓ Candidate Selected</span>
      </div>
      <div class="candidates-grid" id="governor-grid">
        <?php
        $governor = mysqli_query($conn,
          "SELECT * FROM candidate 
          WHERE Position='Governor' 
          AND academic_year='$current_year'")
          or die(mysqli_error());

        while ($row = mysqli_fetch_array($governor)) {
          $photo = 'admin/' . $row['Photo'];
          $fullName = $row['FirstName'] . ' ' . ($row['MiddleName'] ? $row['MiddleName'] . ' ' : '') . $row['LastName'];
          
          echo "<div class='candidate-card' data-position='governor' data-id='{$row['CandidateID']}' 
                data-firstname='{$row['FirstName']}' 
                data-middlename='{$row['MiddleName']}' 
                data-lastname='{$row['LastName']}'
                data-photo='{$photo}'
                data-party='" . ($row['Party'] ?? 'No Party') . "'
                data-year='" . ($row['Year'] ?? 'N/A') . "'
                data-platform='" . htmlspecialchars($row['Platform'] ?? 'No Platform Available', ENT_QUOTES) . "'>
                <img src='{$photo}' alt='{$fullName}' class='candidate-image'>
                <h4 class='candidate-name'>{$fullName}</h4>
                <p class='candidate-party'>" . ($row['Party'] ?? 'No Party') . "</p>
                <button type='button' class='view-details-btn' onclick='event.stopPropagation(); showDetails(this.parentElement)'>View Details</button>
              </div>";
        }
        ?>
      </div>
    </div>

    <!-- Vice-Governor Section -->
    <div class="position-section">
      <div class="position-header">
        <h3 class="position-title">Vice-Governor</h3>
        <span class="selected-indicator" id="vice-selected">✓ Candidate Selected</span>
      </div>
      <div class="candidates-grid" id="vice-grid">
        <?php
        $vice = mysqli_query($conn,
          "SELECT * FROM candidate 
          WHERE Position='Vice-Governor' 
          AND academic_year='$current_year'")
          or die(mysqli_error());

        while ($row = mysqli_fetch_array($vice)) {
          $photo = 'admin/' . $row['Photo'];
          $fullName = $row['FirstName'] . ' ' . ($row['MiddleName'] ? $row['MiddleName'] . ' ' : '') . $row['LastName'];
          
          echo "<div class='candidate-card' data-position='vice' data-id='{$row['CandidateID']}'
                data-firstname='{$row['FirstName']}' 
                data-middlename='{$row['MiddleName']}' 
                data-lastname='{$row['LastName']}'
                data-photo='{$photo}'
                data-party='" . ($row['Party'] ?? 'No Party') . "'
                data-year='" . ($row['Year'] ?? 'N/A') . "'
                data-platform='" . htmlspecialchars($row['Platform'] ?? 'No Platform Available', ENT_QUOTES) . "'>
                <img src='{$photo}' alt='{$fullName}' class='candidate-image'>
                <h4 class='candidate-name'>{$fullName}</h4>
                <p class='candidate-party'>" . ($row['Party'] ?? 'No Party') . "</p>
                <button type='button' class='view-details-btn' onclick='event.stopPropagation(); showDetails(this.parentElement)'>View Details</button>
              </div>";
        }
        ?>
      </div>
    </div>

    <!-- Representative Section -->
    <div class="position-section">
      <div class="position-header">
        <h3 class="position-title"><?php echo $rep_position; ?></h3>
        <span class="selected-indicator" id="rep-selected">✓ Candidate Selected</span>
      </div>
      <div class="candidates-grid" id="rep-grid">
        <?php
        $rep = mysqli_query($conn,
          "SELECT * FROM candidate 
          WHERE Position='$rep_position' 
          AND academic_year='$current_year' 
          ORDER BY FirstName ASC")
          or die(mysqli_error());

        while ($row = mysqli_fetch_array($rep)) {
          $photo = 'admin/' . $row['Photo'];
          $fullName = $row['FirstName'] . ' ' . ($row['MiddleName'] ? $row['MiddleName'] . ' ' : '') . $row['LastName'];
          
          echo "<div class='candidate-card' data-position='representative' data-id='{$row['CandidateID']}'
                data-firstname='{$row['FirstName']}' 
                data-middlename='{$row['MiddleName']}' 
                data-lastname='{$row['LastName']}'
                data-photo='{$photo}'
                data-party='" . ($row['Party'] ?? 'No Party') . "'
                data-year='" . ($row['Year'] ?? 'N/A') . "'
                data-platform='" . htmlspecialchars($row['Platform'] ?? 'No Platform Available', ENT_QUOTES) . "'>
                <img src='{$photo}' alt='{$fullName}' class='candidate-image'>
                <h4 class='candidate-name'>{$fullName}</h4>
                <p class='candidate-party'>" . ($row['Party'] ?? 'No Party') . "</p>
                <button type='button' class='view-details-btn' onclick='event.stopPropagation(); showDetails(this.parentElement)'>View Details</button>
              </div>";
        }
        ?>
      </div>
    </div>

    <!-- Submit Section -->
    <div class="submit-section">
      <button type="submit" class="submit-btn" name="save">
        <i class="icon-thumbs-up"></i> Submit Vote
      </button>
      <a href="#" class="later-btn" data-toggle="modal" onclick="return confirm('Are you sure you want to vote later?') ? (window.location.href='logout.php') : false;">
        Vote Later
      </a>
    </div>

  </form>
</div>

<!-- Slide Panel for Candidate Details -->
<div class="panel-overlay" id="panelOverlay" onclick="closePanel()"></div>
<div class="slide-panel" id="slidePanel">
  <div class="panel-header">
    <button class="close-panel" onclick="closePanel()">&times;</button>
    <img id="panelImage" class="panel-image" src="" alt="">
    <h3 class="panel-name" id="panelName"></h3>
    <p class="panel-position" id="panelPosition"></p>
  </div>
  <div class="panel-body">
    <div class="info-row">
      <span class="info-label">Party:</span>
      <span class="info-value" id="panelParty"></span>
    </div>
    <div class="info-row">
      <span class="info-label">Year:</span>
      <span class="info-value" id="panelYear"></span>
    </div>
    <div class="platform-section">
      <h4>Platform</h4>
      <p id="panelPlatform"></p>
    </div>
    <button type="button" class="select-candidate-btn" id="selectFromPanel">Select This Candidate</button>
  </div>
</div>

<script>
let currentPanelCard = null;

// Handle card clicks
document.querySelectorAll('.candidate-card').forEach(card => {
  card.addEventListener('click', function() {
    const position = this.dataset.position;
    const id = this.dataset.id;
    
    // Remove selected from all cards in this position
    document.querySelectorAll(`[data-position="${position}"]`).forEach(c => {
      c.classList.remove('selected');
    });
    
    // Add selected to this card
    this.classList.add('selected');
    
    // Update hidden input
    document.getElementById(`${position}_input`).value = id;
    
    // Show selected indicator
    const indicator = position === 'governor' ? 'gov-selected' : 
                     position === 'vice' ? 'vice-selected' : 'rep-selected';
    document.getElementById(indicator).classList.add('active');
  });
});

function showDetails(card) {
  currentPanelCard = card;
  
  const firstName = card.dataset.firstname;
  const middleName = card.dataset.middlename;
  const lastName = card.dataset.lastname;
  const fullName = `${firstName} ${middleName ? middleName + ' ' : ''}${lastName}`;
  
  document.getElementById('panelImage').src = card.dataset.photo;
  document.getElementById('panelName').textContent = fullName;
  document.getElementById('panelPosition').textContent = card.dataset.position.replace('representative', '<?php echo $rep_position; ?>');
  document.getElementById('panelParty').textContent = card.dataset.party;
  document.getElementById('panelYear').textContent = card.dataset.year;
  document.getElementById('panelPlatform').textContent = card.dataset.platform;
  
  document.getElementById('slidePanel').classList.add('active');
  document.getElementById('panelOverlay').classList.add('active');
}

function closePanel() {
  document.getElementById('slidePanel').classList.remove('active');
  document.getElementById('panelOverlay').classList.remove('active');
}

// Select candidate from panel
document.getElementById('selectFromPanel').addEventListener('click', function() {
  if (currentPanelCard) {
    currentPanelCard.click();
    closePanel();
  }
});

// Form validation
document.getElementById('voteForm').addEventListener('submit', function(e) {
  const governor = document.getElementById('governor_input').value;
  const vice = document.getElementById('vice_input').value;
  const rep = document.getElementById('representative_input').value;
  
  let missing = [];
  if (!governor) missing.push('Governor');
  if (!vice) missing.push('Vice-Governor');
  if (!rep) missing.push('Representative');
  
  if (missing.length > 0) {
    e.preventDefault();
    alert('Please select a candidate for: ' + missing.join(', '));
  }
});
</script>

<?php include('hover.php'); ?>
</body>
</html>
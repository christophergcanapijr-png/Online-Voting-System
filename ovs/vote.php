<?php
// Start the session and include required files
require_once('session.php');
require_once('dbcon.php');

// Get student's year level and info
$user_query = mysqli_query($conn, "SELECT FirstName, MiddleName, LastName, Year FROM voters WHERE StudentID = '$_SESSION[id]'");
$user_row = mysqli_fetch_assoc($user_query);
$student_year = $user_row['Year'];

// Load active academic year
$year_q = mysqli_query($conn, "SELECT academic_year FROM settings WHERE is_current = 1");
if ($yr = mysqli_fetch_assoc($year_q)) {
    $_SESSION['academic_year'] = $yr['academic_year'];
}
$current_year = $_SESSION['academic_year'];

// Map year level to representative position
$year_positions = [
    '1st Year' => '1st Year Representative',
    '2nd Year' => '2nd Year Representative',
    '3rd Year' => '3rd Year Representative',
    '4th Year' => '4th Year Representative'
];

$rep_position = $year_positions[$student_year] ?? '1st Year Representative';

// Define session_id variable from session
$session_id = $_SESSION['id'] ?? null;

// Process vote submission
if (isset($_POST['vote']) && $session_id) {
    try {
        mysqli_begin_transaction($conn);
        
        $academic_year = $_SESSION['academic_year'];
        $voter_id = $session_id;
        $now = date("Y-m-d H:i:s");

        // Check if already voted this year
        $check = mysqli_prepare($conn, 
            "SELECT COUNT(*) FROM votes 
             WHERE voter_id = ? 
             AND academic_year = ?"
        );
        mysqli_stmt_bind_param($check, "ss", $voter_id, $academic_year);
        mysqli_stmt_execute($check);
        mysqli_stmt_bind_result($check, $vote_count);
        mysqli_stmt_fetch($check);
        mysqli_stmt_close($check);

        if ($vote_count > 0) {
            throw new Exception("You have already voted for academic year $academic_year");
        }

        // Process each vote
        $votes = [
            'gov' => $_POST['gov'] ?? null,
            'vice' => $_POST['vice'] ?? null,
            'rep' => $_POST['rep'] ?? null
        ];

        foreach ($votes as $vote) {
            if (!empty($vote) && $vote != '--Select Candidate--') {
                $stmt = mysqli_prepare($conn, 
                    "INSERT INTO votes (CandidateID, voter_id, academic_year, date)
                     VALUES (?, ?, ?, ?)"
                );
                mysqli_stmt_bind_param($stmt, "ssss", $vote, $voter_id, $academic_year, $now);
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception(mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);
            }
        }

        // Update voter status but keep academic_year specific
        $status_update = mysqli_prepare($conn, 
            "UPDATE voters 
             SET Status = 'Voted' 
             WHERE StudentID = ? 
             AND academic_year = ?"
        );
        mysqli_stmt_bind_param($status_update, "ss", $voter_id, $academic_year);
        mysqli_stmt_execute($status_update);
        mysqli_stmt_close($status_update);

        mysqli_commit($conn);
        header("Location: thankyou.php");
        exit();
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Error: " . htmlspecialchars($e->getMessage()) . "');</script>";
    }
}

// Initialize candidate data
$candidates = [
    'governor' => ['name' => 'No Candidate Selected', 'photo' => '', 'party' => ''],
    'vice' => ['name' => 'No Candidate Selected', 'photo' => '', 'party' => ''],
    'representative' => ['name' => 'No Candidate Selected', 'photo' => '', 'party' => '']
];

$governor_id = $vice_id = $rep_id = '';

if (isset($_POST['save'])) {
    // Get POST values
    $governor_id = $_POST['governor'] ?? '';
    $vice_id = $_POST['vice'] ?? '';
    $rep_id = $_POST['representative'] ?? '';

    // Fetch Governor data
    if (!empty($governor_id)) {
        $result = mysqli_query($conn, "SELECT * FROM candidate WHERE CandidateID='$governor_id'");
        if ($result && ($row = mysqli_fetch_array($result))) {
            $candidates['governor'] = [
                'name' => $row['FirstName'] . " " . ($row['MiddleName'] ? $row['MiddleName'] . " " : "") . $row['LastName'],
                'photo' => 'admin/' . $row['Photo'],
                'party' => $row['Party'] ?? 'No Party'
            ];
        }
    }

    // Fetch Vice Governor data
    if (!empty($vice_id)) {
        $result = mysqli_query($conn, "SELECT * FROM candidate WHERE CandidateID='$vice_id'");
        if ($result && ($row = mysqli_fetch_array($result))) {
            $candidates['vice'] = [
                'name' => $row['FirstName'] . " " . ($row['MiddleName'] ? $row['MiddleName'] . " " : "") . $row['LastName'],
                'photo' => 'admin/' . $row['Photo'],
                'party' => $row['Party'] ?? 'No Party'
            ];
        }
    }

    // Fetch Representative data
    if (!empty($rep_id)) {
        $result = mysqli_query($conn, "SELECT * FROM candidate WHERE CandidateID='$rep_id'");
        if ($result && ($row = mysqli_fetch_array($result))) {
            $candidates['representative'] = [
                'name' => $row['FirstName'] . " " . ($row['MiddleName'] ? $row['MiddleName'] . " " : "") . $row['LastName'],
                'photo' => 'admin/' . $row['Photo'],
                'party' => $row['Party'] ?? 'No Party'
            ];
        }
    }
}

include('header.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/voting.css">
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
    <div class="page-header">
        <h2>🗳️ Official Ballot Review</h2>
        <a href="voting.php" class="back-btn">
            <span>←</span> Back to Voting
        </a>
    </div>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="voteForm">
        <div class="ballot-container">
            <h3 class="ballot-title">Review Your Selections</h3>

            <!-- Governor Card -->
            <div class="candidate-review-card">
                <div class="position-label">Governor</div>
                <div class="candidate-info">
                    <?php if ($candidates['governor']['name'] != 'No Candidate Selected'): ?>
                        <img src="<?php echo $candidates['governor']['photo']; ?>" alt="Governor" class="candidate-photo">
                        <div class="candidate-details">
                            <p class="candidate-name"><?php echo $candidates['governor']['name']; ?></p>
                            <p class="candidate-party"><?php echo $candidates['governor']['party']; ?></p>
                        </div>
                    <?php else: ?>
                        <span class="no-selection">No Candidate Selected</span>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="gov" value="<?php echo htmlspecialchars($governor_id); ?>"/>
            </div>

            <!-- Vice Governor Card -->
            <div class="candidate-review-card">
                <div class="position-label">Vice-Governor</div>
                <div class="candidate-info">
                    <?php if ($candidates['vice']['name'] != 'No Candidate Selected'): ?>
                        <img src="<?php echo $candidates['vice']['photo']; ?>" alt="Vice Governor" class="candidate-photo">
                        <div class="candidate-details">
                            <p class="candidate-name"><?php echo $candidates['vice']['name']; ?></p>
                            <p class="candidate-party"><?php echo $candidates['vice']['party']; ?></p>
                        </div>
                    <?php else: ?>
                        <span class="no-selection">No Candidate Selected</span>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="vice" value="<?php echo htmlspecialchars($vice_id); ?>"/>
            </div>

            <!-- Representative Card -->
            <div class="candidate-review-card">
                <div class="position-label"><?php echo $rep_position; ?></div>
                <div class="candidate-info">
                    <?php if ($candidates['representative']['name'] != 'No Candidate Selected'): ?>
                        <img src="<?php echo $candidates['representative']['photo']; ?>" alt="Representative" class="candidate-photo">
                        <div class="candidate-details">
                            <p class="candidate-name"><?php echo $candidates['representative']['name']; ?></p>
                            <p class="candidate-party"><?php echo $candidates['representative']['party']; ?></p>
                        </div>
                    <?php else: ?>
                        <span class="no-selection">No Candidate Selected</span>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="rep" value="<?php echo htmlspecialchars($rep_id); ?>"/>
            </div>

            <div class="action-buttons">
                <button type="button" class="submit-btn" onclick="showConfirmModal()">
                    <span>✓</span> Submit Final Votes
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Modern Confirmation Modal -->
<div class="modal" id="confirmModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirm Vote Submission</h3>
            <button type="button" class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to submit your final votes? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal()">Cancel</button>
            <button type="button" class="modal-btn modal-btn-confirm" onclick="submitVote()">Yes, Submit</button>
        </div>
    </div>
</div>

<script>
function showConfirmModal() {
    document.getElementById('confirmModal').classList.add('show');
}

function closeModal() {
    document.getElementById('confirmModal').classList.remove('show');
}

function submitVote() {
    // Add the vote input to trigger the PHP vote processing
    const form = document.getElementById('voteForm');
    const voteInput = document.createElement('input');
    voteInput.type = 'hidden';
    voteInput.name = 'vote';
    voteInput.value = '1';
    form.appendChild(voteInput);
    form.submit();
}

// Close modal when clicking outside
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

</body>
</html>
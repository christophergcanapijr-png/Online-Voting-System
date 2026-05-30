<?php
include('dbcon.php');
if(!isset($_SESSION)) session_start();

$current_year = $_SESSION['academic_year'];

// Get total voters for current academic year
$voters_query = mysqli_query($conn, "SELECT 
    COUNT(*) as total_voters,
    SUM(CASE WHEN Status = 'Voted' THEN 1 ELSE 0 END) as voted,
    SUM(CASE WHEN Status = 'Unvoted' THEN 1 ELSE 0 END) as not_voted
    FROM voters 
    WHERE academic_year = '$current_year'");
$voters_data = mysqli_fetch_assoc($voters_query);

// Get candidate counts by position for current year
$positions = array(
    'Governor',
    'Vice-Governor',
    '1st Year Representative',
    '2nd Year Representative',
    '3rd Year Representative',
    '4th Year Representative'
);

$position_stats = [];
foreach($positions as $position) {
    $query = mysqli_query($conn, "SELECT 
        COUNT(*) as candidate_count,
        (SELECT COUNT(*) FROM votes v 
         INNER JOIN candidate c ON v.CandidateID = c.CandidateID 
         WHERE c.Position = '$position' 
         AND v.academic_year = '$current_year') as total_votes
        FROM candidate 
        WHERE Position = '$position' 
        AND academic_year = '$current_year'");
    $position_stats[$position] = mysqli_fetch_assoc($query);
}
?>

<div class="analytics-dashboard">
    <div class="academic-year-indicator">
        <h3>Analytics for Academic Year: <?php echo htmlspecialchars($current_year); ?></h3>
    </div>
    
    <div class="voter-stats">
        <h4>Voter Statistics</h4>
        <div class="stat-boxes">
            <div class="stat-box">
                <span class="stat-label">Total Voters</span>
                <span class="stat-value"><?php echo $voters_data['total_voters']; ?></span>
            </div>
            <div class="stat-box">
                <span class="stat-label">Voted</span>
                <span class="stat-value"><?php echo $voters_data['voted']; ?></span>
            </div>
            <div class="stat-box">
                <span class="stat-label">Not Voted</span>
                <span class="stat-value"><?php echo $voters_data['not_voted']; ?></span>
            </div>
        </div>
    </div>

    <div class="position-stats">
        <h4>Position Statistics</h4>
        <div class="position-grid">
            <?php foreach($position_stats as $position => $stats): ?>
            <div class="position-box">
                <h5><?php echo htmlspecialchars($position); ?></h5>
                <div class="position-details">
                    <span>Candidates: <?php echo $stats['candidate_count']; ?></span>
                    <span>Total Votes: <?php echo $stats['total_votes']; ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<h2 class="analytics-title">Live Vote Analytics</h2>

<!-- Governor Section -->
<div class="position-card">
    <h3 class="position-header">Governor Race</h3>
    <?php
    $gov_query = mysqli_query($conn, "SELECT 
        c.FirstName, c.LastName, 
        COUNT(v.CandidateID) as vote_count,
        (COUNT(v.CandidateID) * 100.0 / NULLIF((SELECT COUNT(*) FROM votes WHERE Position='Governor'), 0)) as percentage
        FROM candidate c 
        LEFT JOIN votes v ON c.CandidateID = v.CandidateID 
        WHERE c.Position='Governor'
        GROUP BY c.CandidateID
        ORDER BY vote_count DESC");
    
    while($gov_row = mysqli_fetch_array($gov_query)) {
        $percentage = number_format($gov_row['percentage'] ?? 0, 1);
        ?>
        <div class="candidate-item">
            <div class="candidate-info">
                <span class="candidate-name">
                    <?php echo htmlspecialchars($gov_row['FirstName'] . " " . $gov_row['LastName']); ?>
                </span>
                <span class="vote-stats">
                    <?php echo $gov_row['vote_count']; ?> votes (<?php echo $percentage; ?>%)
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar governor-bar" style="width: <?php echo $percentage; ?>%"></div>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<!-- Vice Governor Section -->
<div class="position-card">
    <h3 class="position-header">Vice Governor Race</h3>
    <?php
    $vice_query = mysqli_query($conn, "SELECT 
        c.FirstName, c.LastName, 
        COUNT(v.CandidateID) as vote_count,
        (COUNT(v.CandidateID) * 100.0 / NULLIF((SELECT COUNT(*) FROM votes WHERE Position='Vice-Governor'), 0)) as percentage
        FROM candidate c 
        LEFT JOIN votes v ON c.CandidateID = v.CandidateID 
        WHERE c.Position='Vice-Governor'
        GROUP BY c.CandidateID
        ORDER BY vote_count DESC");
    
    while($vice_row = mysqli_fetch_array($vice_query)) {
        $percentage = number_format($vice_row['percentage'] ?? 0, 1);
        ?>
        <div class="candidate-item">
            <div class="candidate-info">
                <span class="candidate-name">
                    <?php echo htmlspecialchars($vice_row['FirstName'] . " " . $vice_row['LastName']); ?>
                </span>
                <span class="vote-stats">
                    <?php echo $vice_row['vote_count']; ?> votes (<?php echo $percentage; ?>%)
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar vice-governor-bar" style="width: <?php echo $percentage; ?>%"></div>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<!-- Representatives Section -->
<div class="position-card">
    <h3 class="position-header">Representative Race</h3>
    <?php
    $years = array('1st Year', '2nd Year', '3rd Year', '4th Year');
    foreach($years as $year): ?>
        <div class="year-section">
            <h4 class="year-title"><?php echo $year; ?> Representatives</h4>
            <?php
            $rep_query = mysqli_query($conn, "SELECT 
                c.FirstName, c.LastName, 
                COUNT(v.CandidateID) as vote_count,
                (COUNT(v.CandidateID) * 100.0 / NULLIF((SELECT COUNT(*) FROM votes WHERE Position LIKE '%$year%'), 0)) as percentage
                FROM candidate c 
                LEFT JOIN votes v ON c.CandidateID = v.CandidateID 
                WHERE c.Position LIKE '%$year%'
                GROUP BY c.CandidateID
                ORDER BY vote_count DESC");
            
            while($rep_row = mysqli_fetch_array($rep_query)) {
                $percentage = number_format($rep_row['percentage'] ?? 0, 1);
                ?>
                <div class="candidate-item">
                    <div class="candidate-info">
                        <span class="candidate-name">
                            <?php echo htmlspecialchars($rep_row['FirstName'] . " " . $rep_row['LastName']); ?>
                        </span>
                        <span class="vote-stats">
                            <?php echo $rep_row['vote_count']; ?> votes (<?php echo $percentage; ?>%)
                        </span>
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar representative-bar" style="width: <?php echo $percentage; ?>%"></div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    <?php endforeach; ?>
</div>

<!-- Live Vote Tracking Section -->
<div class="live-analytics">
    <div class="live-header">
        <h3>Live Vote Tracking</h3>
        <div class="live-indicator">
            <div class="live-dot"></div>
            <span>Live Updates</span>
        </div>
    </div>
    
    <div id="liveResults">
        <?php foreach(['Governor', 'Vice-Governor', '1st Year Representative', 
                      '2nd Year Representative', '3rd Year Representative', 
                      '4th Year Representative'] as $position): ?>
            <div class="position-results">
                <h4><?php echo $position; ?></h4>
                <div id="<?php echo strtolower(str_replace(' ', '-', $position)); ?>-results" 
                     class="candidate-results <?php echo strtolower(explode(' ', $position)[0]); ?>">
                    <!-- Results will be populated by JavaScript -->
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
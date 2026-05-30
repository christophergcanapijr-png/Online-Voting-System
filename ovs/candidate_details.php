<?php
require_once('session.php');
require_once('dbcon.php');

$candidate_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get candidate info
$stmt = mysqli_prepare($conn, 
    "SELECT FirstName, LastName, Position, Photo 
     FROM candidate 
     WHERE CandidateID = ?"
);
mysqli_stmt_bind_param($stmt, "i", $candidate_id);
mysqli_stmt_execute($stmt);
$candidate = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$candidate) {
    header("Location: index.php");
    exit();
}

include('header.php');
?>

<div class="wrapper">
    <div class="hero-body-voting">
        <div class="vote_wise1">
            <font color="white" size="6">
                Vote Details for <?php echo htmlspecialchars($candidate['FirstName'] . ' ' . $candidate['LastName']); ?>
            </font>
        </div>
        <div class="back">
            <a class="btn btn-info" href="javascript:history.back()">
                <i class="icon-arrow-left icon-large"></i>&nbsp;Back
            </a>
        </div>
    </div>

    <div class="candidate-details">
        <div class="candidate-info">
            <img src="admin/<?php echo htmlspecialchars($candidate['Photo']); ?>" 
                 class="candidate-photo" alt="Candidate Photo">
            <h3><?php echo htmlspecialchars($candidate['Position']); ?></h3>
        </div>

        <div class="vote-breakdown">
            <?php
            // Get vote counts by year
            $vote_query = mysqli_prepare($conn,
                "SELECT v.academic_year, 
                        COUNT(*) as vote_count,
                        (SELECT COUNT(*) FROM voters WHERE academic_year = v.academic_year) as total_voters
                 FROM votes v 
                 WHERE v.CandidateID = ?
                 GROUP BY v.academic_year 
                 ORDER BY v.academic_year DESC"
            );
            mysqli_stmt_bind_param($vote_query, "i", $candidate_id);
            mysqli_stmt_execute($vote_query);
            $votes = mysqli_stmt_get_result($vote_query);
            ?>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Academic Year</th>
                        <th>Votes Received</th>
                        <th>Total Voters</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($votes)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['academic_year']); ?></td>
                        <td><?php echo $row['vote_count']; ?></td>
                        <td><?php echo $row['total_voters']; ?></td>
                        <td>
                            <?php 
                            $percentage = $row['total_voters'] > 0 
                                ? round(($row['vote_count'] / $row['total_voters']) * 100, 2) 
                                : 0;
                            echo $percentage . '%';
                            ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.candidate-details {
    background: white;
    padding: 20px;
    margin: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.candidate-info {
    text-align: center;
    margin-bottom: 20px;
}

.candidate-photo {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin-bottom: 15px;
}

.vote-breakdown {
    max-width: 800px;
    margin: 0 auto;
}

.table th {
    background-color: #maroon;
    color: white;
}
</style>
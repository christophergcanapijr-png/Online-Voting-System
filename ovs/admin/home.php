<?php ob_start(); ?>
<?php
date_default_timezone_set('Asia/Manila');
include('session.php');
include('header.php');
include('dbcon.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$academic_year = $_SESSION['academic_year'] ?? '2024-2025';

// Add new admin logic
if(isset($_POST['add_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['new_username']);
    $password = $_POST['password'];

    if($password != $_POST['confirm_password']) {
        $_SESSION['error'] = "Passwords do not match!";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO users (UserName, Password, User_Type) VALUES (?, ?, 'Admin')");
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        $_SESSION['success'] = mysqli_stmt_execute($stmt) ? "New admin added successfully!" : "Failed to add new admin!";
    }
    header("Location: home.php");
    exit();
}
?>
<head>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/analytics.css">
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
        <li class="active"><a href="home.php"><i class="icon-home icon-large"></i>Home</a></li>
        <li><a href="candidate_list.php"><i class="icon-align-justify icon-large"></i>Candidates List</a></li>  
        <li><a href="voter_list.php"><i class="icon-align-justify icon-large"></i>Student List</a></li>
        <li><a href="canvassing_report.php"><i class="icon-book icon-large"></i>Votes Report</a></li>
        <li><a href="History.php"><i class="icon-table icon-large"></i>History Log</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="icon-cog icon-large"></i> Admin <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li><a href="#profileModal" data-toggle="modal"><i class="icon-pencil"></i> Edit Profile</a></li>
                <li class="dropdown-submenu">
                    <a href="#"></i> Academic Year</a>
                    <ul class="dropdown-menu">
                        <?php
                        $result = mysqli_query($conn, "SELECT academic_year, is_current FROM settings ORDER BY academic_year DESC");
                        if($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $current = $row['is_current'] ? ' <i class="icon-ok"></i>' : '';
                                echo "<li><a href='javascript:void(0)' onclick='changeAcademicYear(\"{$row['academic_year']}\")'>".
                                     htmlspecialchars($row['academic_year']) . $current ."</a></li>";
                            }
                        }
                        ?>
                        <li class="divider"></li>
                        <li><a href="#addAcademicYearModal" data-toggle="modal">
                            <i class="icon-plus"></i> Add New Academic Year
                        </a></li>
                    </ul>
                </li>
                <li class="divider"></li>
                <li><a href="#myModal" data-toggle="modal"><i class="icon-off"></i> Logout</a></li>
            </ul>
        </li>
    </ul>
    </div>
    </div>
</div>
<div class="container" style="margin-top: 20px;">
    <h2 style="color: maroon;">Live Vote Counts - Academic Year: <?php echo htmlspecialchars($_SESSION['academic_year']); ?></h2>

    <?php
    // Get total active voters and votes
    $voters_query = mysqli_query($conn, 
        "SELECT 
            (SELECT COUNT(*) 
             FROM voters 
             WHERE academic_year = '$academic_year' 
               AND Enrollment = 'Enrolled') AS total_voters,

            (SELECT COUNT(DISTINCT v.StudentID)
             FROM voters v
             JOIN votes vt ON v.StudentID = vt.voter_id
             WHERE v.academic_year = '$academic_year'
               AND v.Enrollment = 'Enrolled'
               AND vt.academic_year = '$academic_year') AS voted_count"
    ) or die(mysqli_error($conn));

    $voters_data = mysqli_fetch_assoc($voters_query);
    $total_voters = $voters_data['total_voters'];
    $voted_count = $voters_data['voted_count'];
    $remaining = $total_voters - $voted_count;
    ?>

   <div class="voter-stats">
        <h4 style="color: maroon; margin-bottom: 10px;">Student Statistics</h4>
        <div style="display: inline-block; ">
            <strong>Total Registered Voters:</strong> <?php echo $total_voters; ?>
        </div>
        <div style="display: inline-block; ">
            <strong>Already Voted:</strong> <?php echo $voted_count; ?>
        </div>
        <div style="display: inline-block;">
            <strong>Yet to Vote:</strong> <?php echo $remaining; ?>
        </div>
    </div>

    <p>As of <?php echo date('F d, Y h:i A'); ?></p>

    <div class="comparison-controls">
        <button id="compareBtn" class="btn btn-primary" disabled style="color: white">
            Compare Selected (<span id="selectedCount">0</span>/5)
        </button>
        <button id="clearBtn" class="btn">Clear Selection</button>
    </div>

    <div class="row">
        <!-- =======================
             LEFT COLUMN: GOVERNOR
        ======================== -->
        <div class="span6">
            <div class="well">
                <h4>Governor</h4>
                <?php
                $gov_query = mysqli_query($conn, "SELECT 
                    candidate.CandidateID,
                    FirstName, 
                    LastName, 
                    COUNT(votes.CandidateID) AS vote_count
                    FROM candidate
                    LEFT JOIN votes ON candidate.CandidateID = votes.CandidateID
                    WHERE candidate.Position = 'Governor'
                    AND candidate.academic_year = '$academic_year'
                    GROUP BY candidate.CandidateID
                    ORDER BY vote_count DESC") or die(mysqli_error($conn));

                $gov_total = mysqli_query($conn, "SELECT COUNT(*) AS total_votes FROM votes
                    JOIN candidate ON candidate.CandidateID = votes.CandidateID
                    WHERE candidate.Position = 'Governor'
                    AND candidate.academic_year = '$academic_year'");

                $gov_total_row = mysqli_fetch_assoc($gov_total);
                $gov_total_votes = max(1, $gov_total_row['total_votes']);

                while ($row = mysqli_fetch_assoc($gov_query)) {
                    $name = htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']);
                    $votes = intval($row['vote_count']);
                    $percent = round(($votes / $gov_total_votes) * 100, 1);

                    echo "<div class='candidate-row' data-id='{$row['CandidateID']}'>
                            <strong>$name</strong>
                            <div class='progress'>
                                <div class='progress-bar' style='width:{$percent}%;background-color:maroon;'></div>
                            </div>
                            <div style='text-align:right'>{$votes} Votes ({$percent}%)</div>
                          </div><br>";
                }
                ?>
            </div>

            <!-- =======================
                 LEFT COLUMN: VICE-GOVERNOR
            ======================== -->
            <div class="well">
                <h4>Vice-Governor</h4>
                <?php
                $vice_total = mysqli_query($conn, "SELECT COUNT(*) AS total_votes FROM votes
                    JOIN candidate ON candidate.CandidateID = votes.CandidateID
                    WHERE candidate.Position = 'Vice-Governor'
                    AND candidate.academic_year = '$academic_year'");
                $vice_total_row = mysqli_fetch_assoc($vice_total);
                $vice_total_votes = max(1, $vice_total_row['total_votes']);

                $vice_query = mysqli_query($conn, "SELECT 
                    candidate.CandidateID,
                    FirstName, 
                    LastName, 
                    COUNT(votes.CandidateID) AS vote_count
                    FROM candidate
                    LEFT JOIN votes ON candidate.CandidateID = votes.CandidateID
                    WHERE candidate.Position = 'Vice-Governor'
                    AND candidate.academic_year = '$academic_year'
                    GROUP BY candidate.CandidateID
                    ORDER BY vote_count DESC") or die(mysqli_error($conn));

                while ($row = mysqli_fetch_assoc($vice_query)) {
                    $name = htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']);
                    $votes = intval($row['vote_count']);
                    $percent = round(($votes / $vice_total_votes) * 100, 1);

                    echo "<div class='candidate-row' data-id='{$row['CandidateID']}'>
                            <strong>$name</strong>
                            <div class='progress'>
                                <div class='progress-bar' style='width:{$percent}%;background-color:maroon;'></div>
                            </div>
                            <div style='text-align:right'>{$votes} Votes ({$percent}%)</div>
                          </div><br>";
                }
                ?>
            </div>
        </div>

        <!-- =======================
             RIGHT COLUMN: YEAR REPS
        ======================== -->
        <div class="span6">
            <?php
            $years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

            foreach ($years as $year) {
                echo "<div class='well'><h4>$year Representatives</h4>";

                $rep_query = mysqli_query($conn, "SELECT 
                    candidate.CandidateID,
                    FirstName, 
                    LastName, 
                    COUNT(votes.CandidateID) AS vote_count
                    FROM candidate
                    LEFT JOIN votes ON candidate.CandidateID = votes.CandidateID
                    WHERE candidate.Position = '$year Representative'
                    AND candidate.academic_year = '$academic_year'
                    GROUP BY candidate.CandidateID
                    ORDER BY vote_count DESC");

                $rep_total = mysqli_query($conn, "SELECT COUNT(*) AS total_votes FROM votes
                    JOIN candidate ON candidate.CandidateID = votes.CandidateID
                    WHERE candidate.Position = '$year Representative'
                    AND candidate.academic_year = '$academic_year'");

                $rep_total_row = mysqli_fetch_assoc($rep_total);
                $rep_total_votes = max(1, $rep_total_row['total_votes']);

                while ($row = mysqli_fetch_assoc($rep_query)) {
                    $name = htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']);
                    $votes = intval($row['vote_count']);
                    $percent = round(($votes / $rep_total_votes) * 100, 1);

                    echo "<div class='candidate-row' data-id='{$row['CandidateID']}'>
                            <strong>$name</strong>
                            <div class='progress'>
                                <div class='progress-bar' style='width:{$percent}%;background-color:maroon;'></div>
                            </div>
                            <div style='text-align:right'>{$votes} Votes ({$percent}%)</div>
                          </div><br>";
                }

                echo "</div>";
            }
            ?>
        </div>
    </div>
</div>
</div>


<!-- Vote Details Modal -->
<div id="voteDetailsModal" class="vote-details-modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3 id="detailsModalTitle"></h3>
        <div id="voteBreakdown" class="vote-breakdown"></div>
    </div>
</div>

<!-- Comparison Modal -->
<div id="comparisonModal" class="vote-details-modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3 id="comparisonModalTitle"></h3>
        <div id="comparisonContent" class="comparison-chart-container">
            <canvas id="comparisonChart"></canvas>
        </div>
    </div>
</div>

<!-- Add Academic Year Modal -->
<div id="addAcademicYearModal" class="modal hide fade" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3><i class="icon-plus"></i> Add New Academic Year</h3>
    </div>

    <form method="POST" action="add_academic_year.php">
        <div class="modal-body">
            <div class="control-group">
                <label>Academic Year:</label>
                <input type="text" name="academic_year" class="input-xlarge" placeholder="YYYY-YYYY" required>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary">Add Year</button>
        </div>
    </form>
</div>

<!-- Logout Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3><i class="icon-off"></i> Logout</h3>
    </div>

    <div class="modal-body">
        <p>Are you sure you want to logout?</p>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal">Cancel</button>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>
<script>
// Change Academic Year function
function changeAcademicYear(year) {
    if(confirm('Change academic year to ' + year + '?')) {
        $.ajax({
            type: 'POST',
            url: 'set_academic_year.php',
            data: { academic_year: year },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    showPopup('success', 'Success!', 'Academic year changed to ' + year);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showPopup('error', 'Error', 'Failed to change academic year');
                }
            },
            error: function() {
                showPopup('error', 'Connection Error', 'Unable to connect to server');
            }
        });
    }
}

// Popup function
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
// Document ready functions
$(document).ready(function() {
    // Handle academic year form submission
    $('#addAcademicYearModal form').on('submit', function(e) {
        e.preventDefault();
        
        var yearInput = $(this).find('input[name="academic_year"]').val();
        
        $.ajax({
            url: 'add_academic_year.php',
            type: 'POST',
            data: { academic_year: yearInput },
            dataType: 'json',
            beforeSend: function() {
                $('#addAcademicYearModal .btn-primary').prop('disabled', true).text('Adding...');
            },
            success: function(response) {
                $('#addAcademicYearModal').modal('hide');
                
                if(response.success) {
                    showPopup('success', 'Success!', response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showPopup('error', 'Error', response.message);
                }
            },
            error: function(xhr, status, error) {
                $('#addAcademicYearModal').modal('hide');
                showPopup('error', 'Connection Error', 'Unable to connect to server. Please try again.');
            },
            complete: function() {
                $('#addAcademicYearModal .btn-primary').prop('disabled', false).text('Add Year');
            }
        });
    });
    
    // Close popup when clicking X or outside
    $(document).on('click', '.popup-close, .popup-overlay', function(e) {
        if($(e.target).hasClass('popup-overlay') || $(e.target).hasClass('popup-close')) {
            $('.popup-overlay').fadeOut(300, function() {
                $(this).remove();
            });
        }
    });
    
    // Keep your existing document.ready code below this...
    // (checkboxes, comparison, modals, etc.)
});
</script>

<script>
$(document).ready(function() {
    let selectedCandidates = new Set();
    let comparisonChart;

    // Add checkbox
    $('.candidate-row').each(function() {
        $(this).prepend('<input type="checkbox" class="candidate-checkbox">');
    });

    // ===============================
    // SHOW VOTE DETAILS (Governor/Vice-Gov)
    // ===============================
    $('.candidate-row').on('click', function(e) {
        if (!$(e.target).hasClass('candidate-checkbox')) {
            const candidateId = $(this).data('id');
            const position = $(this).closest('.well').find('h4').text().trim();

            if (position === 'Governor' || position === 'Vice-Governor') {
                showVoteDetails(candidateId);
            }
        }
    });

    // ===============================
    // CHECKBOX SELECTION FOR COMPARISON
    // ===============================
    $('.candidate-checkbox').on('click', function(e) {
        e.stopPropagation();
        const candidateId = $(this).closest('.candidate-row').data('id');

        if (this.checked) {
            if (selectedCandidates.size >= 5) {
                this.checked = false;
                alert("You can only select up to 5 candidates");
                return;
            }
            selectedCandidates.add(candidateId);
        } else {
            selectedCandidates.delete(candidateId);
        }

        $('#selectedCount').text(selectedCandidates.size);
        $('#compareBtn').prop('disabled', selectedCandidates.size < 2);
    });

    // ===============================
    // COMPARE SELECTED CANDIDATES
    // ===============================
    $('#compareBtn').on('click', function() {
        $('#comparisonModal').addClass('show');
        $('#comparisonModalTitle').text("Vote Comparison");

        $('#comparisonContent').html(`<div style="padding:20px;text-align:center;">Loading...</div>`);

        $.ajax({
            url: 'get_comparison_data.php',
            type: 'POST',
            dataType: 'json',
            data: {
                candidates: Array.from(selectedCandidates),
                academic_year: '<?php echo $academic_year; ?>'
            },
            success: function(response) {
                if (response.success) {
                    $('#comparisonContent').html('<canvas id="comparisonChart"></canvas>');
                    createComparisonChart(response.data);
                }
            }
        });
    });

    // Clear selection
    $('#clearBtn').on('click', function() {
        $('.candidate-checkbox').prop('checked', false);
        selectedCandidates.clear();
        $('#selectedCount').text(0);
        $('#compareBtn').prop('disabled', true);
    });

    // ===============================
    // CLOSE MODALS
    // ===============================
    $('.close-btn').on('click', function() {
        $(this).closest('.vote-details-modal').removeClass('show');
        if (comparisonChart) comparisonChart.destroy();
    });
    $('.vote-details-modal').on('click', function(e) {
        if ($(e.target).hasClass('vote-details-modal')) {
            $(this).removeClass('show');
            if (comparisonChart) comparisonChart.destroy();
        }
    });

    // ===============================
    // LOAD VOTE DETAILS
    // ===============================
   function showVoteDetails(id) {
    $('#voteDetailsModal').addClass('show');
    $('#voteBreakdown').html("Loading...");

    $.ajax({
        url: 'get_vote_details.php',
        type: 'POST',
        dataType: 'json',
        data: { 
            candidate_id: id,
            academic_year: "<?php echo $academic_year; ?>"
        },
        success: function(res) {
            if (res.success) {
                renderVoteDetails(res);
            } else {
                $('#voteBreakdown').html("<p style='color:red;'>" + res.message + "</p>");
            }
        }
    });
}

    // ===============================
    // RENDER VOTE DETAILS TABLE
    // ===============================
    function renderVoteDetails(res) {
        let html = `
        <h4>${res.candidate_name} — ${res.position}</h4>
        <table>
            <thead>
                <tr>
                    <th>Year Level</th>
                    <th>Votes</th>
                    <th>Total Voters</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
        `;

        res.votes.forEach(v => {
            let pct = ((v.votes / v.total_voters) * 100).toFixed(1);
            html += `
                <tr>
                    <td>${v.year_level}</td>
                    <td>${v.votes}</td>
                    <td>${v.total_voters}</td>
                    <td>${pct}%</td>
                </tr>
            `;
        });

        html += "</tbody></table>";

        $('#voteBreakdown').html(html);
    }

    // ===============================
    // COMPARISON CHART
    // ===============================
    function createComparisonChart(data) {
        const ctx = document.getElementById('comparisonChart');

        if (comparisonChart) comparisonChart.destroy();

        comparisonChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['1st Year', '2nd Year', '3rd Year', '4th Year'],
                datasets: data.datasets
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // ===============================
    // FIX ADDED: EDIT PROFILE BUTTON WORKS
    // ===============================
    $(document).on('click', 'a[href="#profileModal"]', function (e) {
        e.preventDefault();

        // Auto-fill current username
        $('input[name="username"]').val("<?php echo $_SESSION['username']; ?>");

        // Clear password fields
        $('input[name="new_password"]').val('');
        $('input[name="confirm_password"]').val('');

        // Open modal
        $('#profileModal').modal('show');
    });

    // ===============================
    // DROPDOWN FIXES
    // ===============================
    $('.dropdown-toggle').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const menu = $(this).next('.dropdown-menu');
        $('.dropdown-menu.show').not(menu).removeClass('show');
        menu.toggleClass('show');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu.show').removeClass('show');
        }
    });

});
</script>
<?php include('edit_profile_modal.php'); ?>
</body>
</html>
<?php ob_end_flush(); ?>

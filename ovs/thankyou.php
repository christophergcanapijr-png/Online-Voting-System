<?php
include('dbcon.php');
session_start();

// Handle redirects before any output
if (!isset($_SESSION['voter_id'])){
    header('location:index.php');
    exit();
}

$session_id = $_SESSION['voter_id'];

// Get current active academic year from settings
$settings_query = mysqli_query($conn, "SELECT academic_year FROM settings WHERE is_current = 1") 
    or die(mysqli_error($conn));
$settings_row = mysqli_fetch_array($settings_query);
$current_academic_year = $settings_row['academic_year'];

// Store the academic year in session for comparison
if (!isset($_SESSION['current_academic_year'])) {
    $_SESSION['current_academic_year'] = $current_academic_year;
}

// Check if voter exists in current academic year
$voter_check = mysqli_query($conn, 
    "SELECT * FROM voters 
     WHERE StudentID = '$session_id' 
     AND academic_year = '$current_academic_year'"
) or die(mysqli_error($conn));

// Auto redirect if voter not found
if(mysqli_num_rows($voter_check) == 0) {
    $error_message = "Access denied: You are not registered for the current academic year ($current_academic_year)";
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <script>
            alert("<?php echo $error_message; ?>");
            window.location.href = 'logout.php';
        </script>
    </head>
    </html>
    <?php
    exit();
}

$user_row = mysqli_fetch_array($voter_check);
include('header.php');
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/voting.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
        }

        .wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Success Banner */
        .success-banner {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: auto 30px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            animation: fadeInDown 0.6s ease;
        }

        .success-banner h2 {
            color: #4caf50;
            font-size: 28px;
            margin: 0 0 10px 0;
            font-weight: 600;
        }

        .success-banner p {
            color: #666;
            font-size: 16px;
            margin: 0;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #4caf50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: scaleIn 0.5s ease;
        }

        .success-icon::before {
            content: '✓';
            color: white;
            font-size: 50px;
            font-weight: bold;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }

        /* Last Updated Indicator */
        .last-updated {
            text-align: center;
            color: white;
            font-size: 14px;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        /* Vote Results Grid */
        .vote-results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .vote-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            animation: fadeIn 0.8s ease;
        }

        .vote-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .vote-card-title {
            font-size: 20px;
            font-weight: 600;
            color: maroon;
            margin: 0 0 20px 0;
            padding-bottom: 12px;
            border-bottom: 3px solid #f0f0f0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Candidate Bar */
        .candidate-bar {
            display: flex;
            align-items: center;
            margin: 15px 0;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .candidate-bar:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .candidate-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid maroon;
            margin-right: 12px;
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }

        .candidate-bar:hover .candidate-photo {
            transform: scale(1.1);
        }

        .candidate-details {
            flex: 1;
            min-width: 0;
        }

        .candidate-name {
            font-size: 15px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .progress-container {
            width: 100%;
            height: 24px;
            background: #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #8B0000 0%, #DC143C 100%);
            border-radius: 12px;
            transition: width 0.8s ease;
            position: relative;
            overflow: hidden;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .vote-stats {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: 12px;
            flex-shrink: 0;
        }

        .vote-count {
            font-size: 16px;
            font-weight: 600;
            color: maroon;
            min-width: 40px;
        }

        .vote-percentage {
            font-size: 13px;
            color: #666;
            background: #f0f0f0;
            padding: 3px 8px;
            border-radius: 12px;
        }

        /* Logout Section */
        .logout-section {
            text-align: center;
            margin-top: 30px;
            padding: 20px 0;
        }

        .logout-btn {
            padding: 15px 50px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }

        .modal.show {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 15px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            padding: 25px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 28px;
            color: #999;
            cursor: pointer;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .close-modal:hover {
            background: #f0f0f0;
            color: #333;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 30px 25px;
            text-align: center;
        }

        .modal-body p {
            margin: 0;
            font-size: 16px;
            color: #666;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 2px solid #f0f0f0;
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .modal-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .modal-btn-cancel {
            background: #e9ecef;
            color: #495057;
        }

        .modal-btn-cancel:hover {
            background: #dee2e6;
        }

        .modal-btn-confirm {
            background: #dc3545;
            color: white;
        }

        .modal-btn-confirm:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        /* Preview Modal - Fixed Version */
#previewModal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.85);
    backdrop-filter: blur(8px);
    z-index: 2000; /* Increased z-index */
    overflow-y: auto;
    padding: 20px;
}

.preview-content {
    position: relative;
    width: 100%;
    max-width: 500px; /* Increased width */
    margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 50px rgba(0,0,0,0.5);
    z-index: 2001;
}

.close-preview {
    position: absolute;
    top: 15px;
    right: 15px;
    color: #333;
    font-size: 30px;
    cursor: pointer;
    background: #f0f0f0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    z-index: 1;
}

.close-preview:hover {
    background: #dc3545;
    color: white;
    transform: rotate(90deg);
}

.preview-image {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid maroon;
    margin: 0 auto 20px;
    display: block;
}

#previewName {
    color: #333;
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 20px;
    text-align: center;
}

.candidate-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    text-align: left;
}

.candidate-info p {
    margin: 12px 0;
    color: #333;
    font-size: 15px;
    display: flex;
    flex-direction: column; /* Changed to column layout */
    gap: 5px; /* Added spacing */
}

.candidate-info strong {
    color: maroon;
    font-weight: 600;
    display: block; /* Force to be on separate line */
}

.candidate-info span {
    display: block;
    padding-left: 0; /* Remove indentation */
}

#previewPlatform {
    background: white;
    padding: 15px;
    border-radius: 8px;
    margin-top: 10px;
    max-height: 200px; /* Increased height */
    overflow-y: auto;
    line-height: 1.6;
    border: 1px solid #e0e0e0;
    word-wrap: break-word;
}

/* Mobile Responsive for Preview Modal */
@media (max-width: 768px) {
    .preview-content {
        padding: 20px;
        margin: 30px auto;
        max-width: 90%;
    }

    .preview-image {
        width: 110px;
        height: 110px;
    }

    #previewName {
        font-size: 18px;
    }

    .candidate-info {
        padding: 15px;
    }

    .candidate-info p {
        font-size: 14px;
    }

    #previewPlatform {
        max-height: 150px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .preview-content {
        margin: 20px auto;
        padding: 15px;
    }

    .preview-image {
        width: 90px;
        height: 90px;
    }

    #previewName {
        font-size: 16px;
        margin-bottom: 15px;
    }

    .candidate-info {
        padding: 12px;
    }

    .candidate-info p {
        font-size: 13px;
        margin: 10px 0;
    }

    #previewPlatform {
        font-size: 13px;
        padding: 12px;
    }
}
    </style>
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
    <!-- Success Banner -->
    <div class="success-banner">
        <div class="success-icon"></div>
        <h2>Thank You for Voting!</h2>
        <p><?php echo $user_row['FirstName']." ".$user_row['LastName']; ?>, your vote has been successfully recorded.</p>
    </div>

    <!-- Last Updated -->
    <div class="last-updated">
        Live Results • Updates every 5 seconds • Last updated: <span id="lastUpdate"></span>
    </div>

    <!-- Vote Results Grid -->
    <div class="vote-results-grid">
        <!-- Governor Results -->
        <div class="vote-card">
            <h3 class="vote-card-title">Governor</h3>
            <div id="governor-votes"></div>
        </div>

        <!-- Vice Governor Results -->
        <div class="vote-card">
            <h3 class="vote-card-title">Vice Governor</h3>
            <div id="vice-votes"></div>
        </div>

        <!-- Representative Results -->
        <div class="vote-card" style="grid-column: 1 / -1;">
            <h3 class="vote-card-title"><?php echo $user_row['Year']; ?> Representative</h3>
            <div id="rep-votes"></div>
        </div>
    </div>

    <!-- Logout Section -->
    <div class="logout-section">
        <button class="logout-btn" onclick="showLogoutModal()">
            <span></span> Logout
        </button>
    </div>
</div>

<!-- Logout Modal -->
<div class="modal" id="logoutModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirm Logout</h3>
            <button class="close-modal" onclick="closeLogoutModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to logout?</p>
        </div>
        <div class="modal-footer">
            <button class="modal-btn modal-btn-cancel" onclick="closeLogoutModal()">Cancel</button>
            <a href="logout.php" class="modal-btn modal-btn-confirm" style="text-decoration: none;">Logout</a>
        </div>
    </div>
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
  </div>
</div>

<script type="text/javascript">
function updateLastUpdateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    document.getElementById('lastUpdate').textContent = timeString;
}

$(document).ready(function() {
    updateLastUpdateTime();

    function updateVoteCounts() {
        $.ajax({
            url: 'get_votes.php',
            type: 'POST',
            dataType: 'json',
            data: { 
                voter_year: '<?php echo htmlspecialchars($user_row["Year"]); ?>',
                academic_year: '<?php echo htmlspecialchars($current_academic_year); ?>'
            },
            success: function(response) {
                console.log('Response:', response);
                if(response.governor) updateDisplay('governor-votes', response.governor);
                if(response.vice_governor) updateDisplay('vice-votes', response.vice_governor);
                if(response.representatives) updateDisplay('rep-votes', response.representatives);
                updateLastUpdateTime();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    }

    function updateDisplay(containerId, data) {
        const container = document.getElementById(containerId);
        if(!container || !data || !data.length) return;

        const total = data.reduce((sum, item) => sum + item.votes, 0) || 1;

        let html = '';
        data.forEach(candidate => {
            const percentage = ((candidate.votes / total) * 100).toFixed(1);
            html += `
                <div class="candidate-bar" onclick='showCandidatePreview(${JSON.stringify(candidate).replace(/'/g, "&apos;")})'>
                    <img src="${candidate.photo}" 
                         class="candidate-photo" 
                         alt="${candidate.name}"/>
                    <div class="candidate-details">
                        <div class="candidate-name">${candidate.name}</div>
                        <div class="progress-container">
                            <div class="progress-fill" style="width: ${percentage}%"></div>
                        </div>
                    </div>
                    <div class="vote-stats">
                        <span class="vote-count">${candidate.votes}</span>
                        <span class="vote-percentage">${percentage}%</span>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    updateVoteCounts();
    setInterval(updateVoteCounts, 5000);
});

function showLogoutModal() {
    document.getElementById('logoutModal').classList.add('show');
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.remove('show');
}

function showCandidatePreview(candidate) {
    const fullName = `${candidate.firstName || ''} ${candidate.middleName || ''} ${candidate.lastName || ''}`.trim();
    
    document.getElementById('panelImage').src = candidate.photo;
    document.getElementById('panelName').textContent = fullName;
    document.getElementById('panelPosition').textContent = candidate.position || 'N/A';
    document.getElementById('panelParty').textContent = candidate.party || 'No Party';
    document.getElementById('panelYear').textContent = candidate.year || 'N/A';
    document.getElementById('panelPlatform').textContent = candidate.platform || 'No platform provided';
    
    document.getElementById('slidePanel').classList.add('active');
    document.getElementById('panelOverlay').classList.add('active');
}

function closePanel() {
    document.getElementById('slidePanel').classList.remove('active');
    document.getElementById('panelOverlay').classList.remove('active');
}

// Check academic year
function checkAcademicYear() {
    $.ajax({
        url: 'check_academic_year.php',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.reload) {
                alert(response.message);
                window.location.href = 'logout.php';
            }
        }
    });
}

$(document).ready(function() {
    setInterval(checkAcademicYear, 5000);
});
</script>

</body>
</html>
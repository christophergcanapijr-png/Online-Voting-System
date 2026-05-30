<?php
// ======================================================
// AJAX SEARCH ENDPOINT — NO OUTPUT BEFORE JSON
// ======================================================
if (isset($_GET['action']) && $_GET['action'] === 'search_student') {

    ob_clean();
    error_reporting(0);
    ini_set('display_errors', 0);

    session_start();
    require_once('dbcon.php');

    header('Content-Type: application/json; charset=utf-8');

    $search = mysqli_real_escape_string($conn, $_GET['q'] ?? "");
    $ay     = mysqli_real_escape_string($conn, $_SESSION['academic_year'] ?? "");

    if ($search === "" || $ay === "") {
        echo json_encode([]);
        exit;
    }

    // FIXED SQL — removed Gender, added MiddleName
    $sql = "
        SELECT StudentID, FirstName, LastName, MiddleName, Year
        FROM voters
        WHERE academic_year = '$ay'
        AND (
            StudentID LIKE '%$search%'
            OR FirstName LIKE '%$search%'
            OR LastName LIKE '%$search%'
        )
        LIMIT 10
    ";

    $res = mysqli_query($conn, $sql);

    if (!$res) {
        echo json_encode([]);
        exit;
    }

    echo json_encode(mysqli_fetch_all($res, MYSQLI_ASSOC));
    exit;
}
?>

<?php
// ======================================================
// MAIN PAGE LOGIC
// ======================================================
ob_start();
session_start();
require_once("session.php");
require_once("dbcon.php");

$error_msg   = "";
$success_msg = "";

// Preserve values if error occurs
$old = [
    'student_id'   => $_POST['student_id']   ?? '',
    'rfirstname'   => $_POST['rfirstname']   ?? '',
    'rlastname'    => $_POST['rlastname']    ?? '',
    'rmname'       => $_POST['rmname']       ?? '',
    'ryear'        => $_POST['ryear']        ?? '1st Year',
    'rposition'    => $_POST['rposition']    ?? 'Governor',
    'party'        => $_POST['party']        ?? '',
    'platformType' => $_POST['platformType'] ?? 'text',
    'platformText' => $_POST['platformText'] ?? ''
];

if (isset($_POST['save'])) {
    try {

        // Required fields
        $required = [
            'student_id' => 'Student ID',
            'rfirstname' => 'First Name',
            'rlastname'  => 'Last Name',
            'rposition'  => 'Position',
            'party'      => 'Party'
        ];

        foreach ($required as $field => $label) {
            if (empty($_POST[$field])) {
                throw new Exception("$label is required");
            }
        }

        // Prevent duplicate candidate
        $student = mysqli_real_escape_string($conn, $_POST['student_id']);
        $ay      = mysqli_real_escape_string($conn, $_SESSION['academic_year']);

        $dup = mysqli_query($conn, "
            SELECT CandidateID 
            FROM candidate 
            WHERE StudentID = '$student' AND academic_year = '$ay'
            LIMIT 1
        ");

        if (mysqli_num_rows($dup) > 0) {
            throw new Exception("This student is already registered as a candidate for $ay.");
        }

        // Photo upload
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK)
            throw new Exception("Candidate photo is required");

        $allowed = ["image/jpeg", "image/png", "image/gif"];
        if (!in_array($_FILES['image']['type'], $allowed))
            throw new Exception("Invalid photo type (JPG, PNG, GIF only)");

        if ($_FILES['image']['size'] > 5*1024*1024)
            throw new Exception("Photo too large (max 5MB)");

        $imgDir = "images/";
        if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);

        $imgName = time() . "_" . basename($_FILES['image']['name']);
        $photoPath = $imgDir . $imgName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $photoPath))
            throw new Exception("Failed to upload candidate photo");

        // Platform
        $platformType = $_POST['platformType'];
        $platformContent = "";

        if ($platformType === "text") {
            if (empty($_POST['platformText']))
                throw new Exception("Platform text is required");

            $platformContent = mysqli_real_escape_string($conn, $_POST['platformText']);
        }
        else if ($platformType === "image") {

            if (!isset($_FILES['platformImage']) || $_FILES['platformImage']['error'] !== UPLOAD_ERR_OK)
                throw new Exception("Platform image is required");

            $dir = "platform_uploads/images/";
            if (!is_dir($dir)) mkdir($dir, 0777, true);

            $pimgName = time() . "_" . basename($_FILES['platformImage']['name']);
            $pimgPath = $dir . $pimgName;

            if (!move_uploaded_file($_FILES['platformImage']['tmp_name'], $pimgPath))
                throw new Exception("Failed to upload platform image");

            $platformContent = json_encode([
                "type" => "image",
                "path" => $pimgPath
            ]);
        }

        // Defaults for safety
        $middle = $_POST['rmname'] ?: "-";
        $year   = $_POST['ryear'] ?: "1st Year";

        // Insert Candidate (without Gender)
        $sql = "
            INSERT INTO candidate
            (StudentID, FirstName, LastName, MiddleName, Position, Party, Year, Photo, Platform, academic_year)
            VALUES(
                '$student',
                '".mysqli_real_escape_string($conn, $_POST['rfirstname'])."',
                '".mysqli_real_escape_string($conn, $_POST['rlastname'])."',
                '".mysqli_real_escape_string($conn, $middle)."',
                '".mysqli_real_escape_string($conn, $_POST['rposition'])."',
                '".mysqli_real_escape_string($conn, $_POST['party'])."',
                '".mysqli_real_escape_string($conn, $year)."',
                '$photoPath',
                '".mysqli_real_escape_string($conn, $platformContent)."',
                '$ay'
            )
        ";

        if (!mysqli_query($conn, $sql)) {
            throw new Exception(mysqli_error($conn));
        }

        // SUCCESS LOG ONLY
        if (isset($_SESSION['admin_id'])) {
            $admin = (int)$_SESSION['admin_id'];
            $fullname = mysqli_real_escape_string($conn, $_POST['rfirstname'] . " " . $_POST['rlastname']);
            mysqli_query($conn, "
                INSERT INTO history (action, data, user_id, academic_year, date)
                VALUES (
                    'Added candidate',
                    'StudentID: $student — $fullname added as a candidate',
                    $admin,
                    '$ay',
                    NOW()
                )
            ");
        }

        $success_msg = "Candidate " . $_POST['rfirstname'] . " " . $_POST['rlastname'] . " added successfully!";

    } catch (Exception $e) {
        $error_msg = $e->getMessage();
    }
}

include('header.php');
?>
<link rel="stylesheet" href="css/navbar.css">
<link rel="stylesheet" href="css/modern.css">
<style>
/* Success/Error popup notification */
.success-popup, .error-popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    padding: 30px 40px;
    border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    z-index: 9999;
    text-align: center;
    min-width: 300px;
    animation: popupSlideIn 0.3s ease-out;
}

@keyframes popupSlideIn {
    from {
        transform: translate(-50%, -60%);
        opacity: 0;
    }
    to {
        transform: translate(-50%, -50%);
        opacity: 1;
    }
}

.success-popup.fade-out, .error-popup.fade-out {
    animation: popupFadeOut 0.3s ease-out forwards;
}

@keyframes popupFadeOut {
    to {
        opacity: 0;
        transform: translate(-50%, -40%);
    }
}

.success-popup-icon {
    font-size: 50px;
    color: #28a745;
    margin-bottom: 15px;
}

.error-popup-icon {
    font-size: 50px;
    color: #dc3545;
    margin-bottom: 15px;
}

.success-popup-title, .error-popup-title {
    font-size: 22px;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
}

.success-popup-message, .error-popup-message {
    font-size: 16px;
    color: #666;
    margin-bottom: 20px;
}

.success-popup-backdrop, .error-popup-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9998;
    animation: backdropFadeIn 0.3s ease-out;
}

@keyframes backdropFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
</head>
<body style="background:#fff;">
<?php include('nav_top.php'); ?>

<div class="wrapper">
<div class="home_body">

<div class="navbar">
    <div class="navbar-inner">
        <ul class="nav nav-pills">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="candidate_list.php">Candidates</a></li>
            <li><a href="voter_list.php">Student List</a></li>
            <li><a href="canvassing_report.php">Votes</a></li>
            <li><a href="History.php">History</a></li>
        </ul>
    </div>
</div>

<div class="hero-body">
<form method="POST" class="form-horizontal" enctype="multipart/form-data">
<fieldset>

<div class="candidate_margin" style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,0.1);margin-right:250px;">

<?php if ($error_msg): ?>
    <input type="hidden" id="errorMessage" value="<?= htmlspecialchars($error_msg) ?>">
<?php endif; ?>

<?php if ($success_msg): ?>
    <input type="hidden" id="successMessage" value="<?= htmlspecialchars($success_msg) ?>">
<?php endif; ?>


<!-- SEARCH STUDENT -->
<div class="control-group">
    <label class="control-label">Search Student:</label>
    <div class="controls">
        <div style="position:relative;">
            <input type="text" id="studentSearch" placeholder="Search by ID or Name" autocomplete="off" style="width:100%;">
            <div id="studentDropdown" style="position:absolute;top:100%;left:0;right:0;background:white;border:1px solid #ccc;display:none;max-height:200px;overflow-y:auto;z-index:999;"></div>
        </div>
    </div>
</div>

<input type="hidden" id="student_id" name="student_id" value="<?= $old['student_id'] ?>">


<!-- Autofill fields -->
<div class="control-group">
    <label class="control-label">First Name:</label>
    <div class="controls">
        <input type="text" id="rfirstname" name="rfirstname" readonly value="<?= $old['rfirstname'] ?>">
    </div>
</div>

<div class="control-group">
    <label class="control-label">Last Name:</label>
    <div class="controls">
        <input type="text" id="rlastname" name="rlastname" readonly value="<?= $old['rlastname'] ?>">
    </div>
</div>

<div class="control-group">
    <label class="control-label">Middle Name:</label>
    <div class="controls">
        <input type="text" id="rmname" name="rmname" readonly value="<?= $old['rmname'] ?>">
    </div>
</div>


<!-- Candidate Photo -->
<div class="control-group">
    <label class="control-label">Photo:</label>
    <div class="controls"><input type="file" name="image" required></div>
</div>

<!-- Year autofilled -->
<div class="control-group">
    <label class="control-label">Year Level:</label>
    <div class="controls">
        <input type="text" id="ryear" name="ryear" readonly value="<?= $old['ryear'] ?>">
    </div>
</div>

<!-- Position -->
<div class="control-group">
    <label class="control-label">Position:</label>
    <div class="controls">
        <select name="rposition">
            <option <?= $old['rposition']=="Governor"?"selected":"" ?>>Governor</option>
            <option <?= $old['rposition']=="Vice-Governor"?"selected":"" ?>>Vice-Governor</option>
            <option <?= $old['rposition']=="1st Year Representative"?"selected":"" ?>>1st Year Representative</option>
            <option <?= $old['rposition']=="2nd Year Representative"?"selected":"" ?>>2nd Year Representative</option>
            <option <?= $old['rposition']=="3rd Year Representative"?"selected":"" ?>>3rd Year Representative</option>
            <option <?= $old['rposition']=="4th Year Representative"?"selected":"" ?>>4th Year Representative</option>
        </select>
    </div>
</div>

<!-- Party -->
<div class="control-group">
    <label class="control-label">Party:</label>
    <div class="controls"><input type="text" name="party" required value="<?= $old['party'] ?>"></div>
</div>

<!-- Platform -->
<div class="control-group">
    <label class="control-label">Platform:</label>
    <div class="controls">
        <select id="platformType" name="platformType">
            <option value="text"  <?= $old['platformType']=="text"?"selected":"" ?>>Text</option>
            <option value="image" <?= $old['platformType']=="image"?"selected":"" ?>>Image</option>
        </select>

        <div id="textPlatform" style="display:<?= $old['platformType']=="text"?"block":"none" ?>">
            <textarea name="platformText" rows="5"><?= $old['platformText'] ?></textarea>
        </div>

        <div id="imagePlatform" style="display:<?= $old['platformType']=="image"?"block":"none" ?>">
            <input type="file" name="platformImage">
        </div>
    </div>
</div>

<div class="control-group">
    <div class="controls">
        <a href="candidate_list.php" class="btn">Back</a>
        <button type="submit" name="save" class="btn btn-primary">Save</button>
    </div>
</div>

</div>
</fieldset>
</form>
</div>

</div>
</div>


<script>
// Success popup function
function showSuccessPopup(title, message) {
    // Remove any existing popups
    const existingPopups = document.querySelectorAll('.success-popup-backdrop, .success-popup, .error-popup-backdrop, .error-popup');
    existingPopups.forEach(el => el.remove());
    
    // Create backdrop
    const backdrop = document.createElement('div');
    backdrop.className = 'success-popup-backdrop';
    
    // Create popup
    const popup = document.createElement('div');
    popup.className = 'success-popup';
    popup.innerHTML = `
        <div class="success-popup-icon">✓</div>
        <div class="success-popup-title">${title}</div>
        <div class="success-popup-message">${message}</div>
    `;
    
    document.body.appendChild(backdrop);
    document.body.appendChild(popup);
    
    // Auto close after 3 seconds
    setTimeout(() => {
        popup.classList.add('fade-out');
        backdrop.style.animation = 'backdropFadeIn 0.3s ease-out reverse';
        setTimeout(() => {
            backdrop.remove();
            popup.remove();
        }, 300);
    }, 3000);
    
    // Click to close
    backdrop.addEventListener('click', () => {
        popup.classList.add('fade-out');
        backdrop.style.animation = 'backdropFadeIn 0.3s ease-out reverse';
        setTimeout(() => {
            backdrop.remove();
            popup.remove();
        }, 300);
    });
}

// Error popup function
function showErrorPopup(title, message) {
    // Remove any existing popups
    const existingPopups = document.querySelectorAll('.success-popup-backdrop, .success-popup, .error-popup-backdrop, .error-popup');
    existingPopups.forEach(el => el.remove());
    
    // Create backdrop
    const backdrop = document.createElement('div');
    backdrop.className = 'error-popup-backdrop';
    
    // Create popup
    const popup = document.createElement('div');
    popup.className = 'error-popup';
    popup.innerHTML = `
        <div class="error-popup-icon">✕</div>
        <div class="error-popup-title">${title}</div>
        <div class="error-popup-message">${message}</div>
    `;
    
    document.body.appendChild(backdrop);
    document.body.appendChild(popup);
    
    // Auto close after 3 seconds
    setTimeout(() => {
        popup.classList.add('fade-out');
        backdrop.style.animation = 'backdropFadeIn 0.3s ease-out reverse';
        setTimeout(() => {
            backdrop.remove();
            popup.remove();
        }, 300);
    }, 3000);
    
    // Click to close
    backdrop.addEventListener('click', () => {
        popup.classList.add('fade-out');
        backdrop.style.animation = 'backdropFadeIn 0.3s ease-out reverse';
        setTimeout(() => {
            backdrop.remove();
            popup.remove();
        }, 300);
    });
}

// Check for success/error message on page load
document.addEventListener('DOMContentLoaded', () => {
    const successMsg = document.getElementById('successMessage');
    if (successMsg) {
        showSuccessPopup('Candidate Added!', successMsg.value);
    }
    
    const errorMsg = document.getElementById('errorMessage');
    if (errorMsg) {
        showErrorPopup('Error!', errorMsg.value);
    }
});

// Toggle platform visibility
function togglePlatformInputs() {
    let t = document.getElementById("platformType").value;
    document.getElementById("textPlatform").style.display  = t === "text" ? "block" : "none";
    document.getElementById("imagePlatform").style.display = t === "image" ? "block" : "none";
}

document.addEventListener("DOMContentLoaded", () => {

    togglePlatformInputs();
    document.getElementById("platformType").addEventListener("change", togglePlatformInputs);

    let search = document.getElementById("studentSearch");
    let box = document.getElementById("studentDropdown");

    let studentIdField = document.getElementById("student_id");
    let firstNameField = document.getElementById("rfirstname");
    let lastNameField  = document.getElementById("rlastname");
    let middleNameField= document.getElementById("rmname");
    let yearField      = document.getElementById("ryear");

    search.addEventListener("input", function() {

        let q = this.value.trim();
        if (q.length === 0) {
            box.style.display = "none";
            return;
        }

        fetch(`?action=search_student&q=${encodeURIComponent(q)}`)
        .then(res => res.json())
        .then(students => {

            box.innerHTML = "";
            if (!Array.isArray(students)) return;

            if (students.length === 0) {
                box.innerHTML = "<div style='padding:8px;color:#666;'>No results</div>";
            } 
            else {
                students.forEach(student => {
                    let d = document.createElement("div");
                    d.style.cssText = "padding:8px;border-bottom:1px solid #ccc;cursor:pointer;";
                    d.innerHTML = `<strong>${student.StudentID}</strong> — ${student.FirstName} ${student.LastName}`;

                    d.onclick = () => {

                        studentIdField.value   = student.StudentID;
                        firstNameField.value   = student.FirstName;
                        lastNameField.value    = student.LastName;
                        middleNameField.value  = student.MiddleName ?? "-";
                        yearField.value        = student.Year ?? "1st Year";

                        search.value = `${student.StudentID} - ${student.FirstName} ${student.LastName}`;
                        box.style.display = "none";
                    };

                    box.appendChild(d);
                });
            }

            box.style.display = "block";
        });
    });

    document.addEventListener("click", e => {
        if (e.target !== search) box.style.display = "none";
    });

});
</script>

</body>
</html>
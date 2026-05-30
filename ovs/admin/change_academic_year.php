<?php
include('dbcon.php');
include('session.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Academic Year</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Change Academic Year</h2>

    <!-- Show success/error message -->
    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'success') {
            echo "<div class='alert alert-success'>Successfully Changed Academic Year.</div>";
        } elseif ($_GET['status'] == 'error') {
            echo "<div class='alert alert-danger'>Error changing academic year. Please try again.</div>";
        } elseif ($_GET['status'] == 'invalid') {
            echo "<div class='alert alert-warning'>Invalid request. No academic year selected.</div>";
        }
    }
    ?>

    <!-- Button to trigger modal for adding new academic year -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addAcademicYearModal">
        Add New Academic Year
    </button>

    <!-- Form to change academic year -->
    <form id="changeAcademicYearForm" method="post">
        <div class="form-group">
            <label for="academic_year">Select Academic Year:</label>
            <select name="academic_year" id="academic_year" class="form-control" required>
                <?php
                $result = mysqli_query($conn, "SELECT DISTINCT academic_year FROM settings ORDER BY academic_year DESC");
                while ($row = mysqli_fetch_assoc($result)) {
                    $selected = ($row['academic_year'] == $_SESSION['academic_year']) ? 'selected' : '';
                    echo "<option value='{$row['academic_year']}' $selected>{$row['academic_year']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Change Academic Year</button>
    </form>
</div>

<!-- Modal for adding new academic year -->
<div class="modal fade" id="addAcademicYearModal" tabindex="-1" role="dialog" aria-labelledby="addAcademicYearModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Academic Year</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addAcademicYearForm">
                    <div class="form-group">
                        <label for="newAcademicYear">Enter New Academic Year:</label>
                        <input type="text" name="new_academic_year" id="newAcademicYear" class="form-control" placeholder="e.g., 2025-2026" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Academic Year</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS + Custom AJAX for modal -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('addAcademicYearForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    fetch('add_academic_year.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        $('#addAcademicYearModal').modal('hide');
        location.reload();
    })
    .catch(error => console.error('Error:', error));
});

// Replace the existing form submission with this AJAX version
$(document).ready(function() {
    $('#changeAcademicYearForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            type: 'POST',
            url: 'process_change_academic_year.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Successfully Changed Academic Year to ' + $('#academic_year').val());
                    location.reload();
                } else {
                    alert('Failed to change academic year: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred while changing the academic year');
            }
        });
    });
});
</script>

</body>
</html>

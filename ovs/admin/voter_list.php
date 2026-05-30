<?php
include('session.php');
include('header.php');
include('dbcon.php');
?>
<head>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/modern.css">
    <style>
    /* Multi-select styling */
    .selected-row {
        background-color: #d9edf7 !important;
    }
    .checkbox-cell {
        width: 40px;
        text-align: center;
    }
    .select-checkbox {
        cursor: pointer;
        width: 18px;
        height: 18px;
    }
    #selectAllCheckbox {
        cursor: pointer;
        width: 18px;
        height: 18px;
    }
    </style>
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
            <li><a href="candidate_list.php"><i class="icon-align-justify icon-large"></i>Candidates List</a></li>  
            <li class="active"><a href="voter_list.php"><i class="icon-align-justify icon-large"></i>Student List</a></li>  
            <li><a href="canvassing_report.php"><i class="icon-book icon-large"></i>Votes Report</a></li>
            <li><a href="History.php"><i class="icon-table icon-large"></i>History Log</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div id="element" class="hero-body">
      
      <div class="pagination">
  <ul>
    <li class="active"><a href="voter_list.php"><font color="white">All</font></a></li>
    <li><a href="Voted_voters.php"><font color="white">Voted Student</font></a></li>
    <li><a href="Unvoted_voters.php"><font color="white">UnVoted Student</font></a></li>
    <li><a href="new_voter.php"><font color="white"><i class="icon-plus icon-large"></i>Add Student</font></a></li>
  </ul>
</div>

<div class="excel_button">
  <form method="POST" action="excel_voter.php">
    <button id="excel" class="btn btn-success" name="save">
      <i class="icon-download icon-large"></i>Download Excel File
    </button>
  </form>
</div>

<div class="search-container">
    <form method="get" action="voter_list.php" class="form-inline">
        <div class="search-group">
            <input type="text" 
                   id="search_id" 
                   name="id" 
                   class="form-control" 
                   placeholder="Search by Student ID e.g. 225711426" 
                   value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
          
            <button type="submit" class="btn btn-primary">Search</button>
              
            <select name="year" class="form-control">
                <option value="">All Years</option>
                <option value="1st Year" <?php echo (isset($_GET['year']) && $_GET['year'] == '1st Year') ? 'selected' : ''; ?>>1st Year</option>
                <option value="2nd Year" <?php echo (isset($_GET['year']) && $_GET['year'] == '2nd Year') ? 'selected' : ''; ?>>2nd Year</option>
                <option value="3rd Year" <?php echo (isset($_GET['year']) && $_GET['year'] == '3rd Year') ? 'selected' : ''; ?>>3rd Year</option>
                <option value="4th Year" <?php echo (isset($_GET['year']) && $_GET['year'] == '4th Year') ? 'selected' : ''; ?>>4th Year</option>
            </select>
        </div>
    </form>
</div>

<!-- Password Controls -->
<div class="password-controls" style="margin:10px 0; padding:10px; background:#f5f5f5; border-radius:4px;">
  <button id="globalGenerateAllBtn" class="btn btn-secondary">
    <i class="icon-key"></i> Generate Passwords (All)
  </button>
  <button id="globalGenerateSelectedBtn" class="btn btn-primary" disabled>
    <i class="icon-key"></i> Generate Passwords (Selected)
  </button>
  <button id="globalPreviewAllBtn" class="btn btn-info">
    <i class="icon-eye-open"></i> Preview All Generated
  </button>
  <button id="globalPreviewSelectedBtn" class="btn btn-warning" disabled>
    <i class="icon-eye-open"></i> Preview Selected
  </button>
  <span id="selectedCountLabel" style="margin-left:15px;color:#666;font-weight:bold;">0 students selected</span>
</div>

      <!-- Voter list table -->
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th class="checkbox-cell">
              <input type="checkbox" id="selectAllCheckbox" title="Select/Deselect All">
            </th>
            <th>Student ID</th>
            <th>Name</th>
            <th>Year</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php 
        $current_year = $_SESSION['academic_year'];
        $where_conditions = ["academic_year = '$current_year'"];

        if (!empty($_GET['id'])) {
            $search_id = mysqli_real_escape_string($conn, $_GET['id']);
            $where_conditions[] = "StudentID LIKE '%$search_id%'";
        }

        if (!empty($_GET['year'])) {
            $year_filter = mysqli_real_escape_string($conn, $_GET['year']);
            $where_conditions[] = "Year = '$year_filter'";
        }

        $where_clause = implode(' AND ', $where_conditions);
        $query = "SELECT * FROM voters WHERE $where_clause ORDER BY LastName ASC";

        $voter_query = mysqli_query($conn, $query);
        if (!$voter_query) {
            die('Error: ' . mysqli_error($conn));
        }
        while($voter_rows = mysqli_fetch_array($voter_query)) {
            $id = $voter_rows['StudentID'];
        ?>
          <tr class="del<?php echo $id ?>" data-voter-id="<?php echo $id; ?>" data-generated="<?php echo htmlspecialchars($voter_rows['Password'] ?? '') ?>">
            <td class="checkbox-cell">
              <input type="checkbox" class="select-checkbox" value="<?php echo $id; ?>">
            </td>
            <td><?php echo $voter_rows['StudentID']; ?></td>
            <td><?php echo $voter_rows['FirstName'] . ' ' . $voter_rows['MiddleName'] . ' ' . $voter_rows['LastName']; ?></td>
            <td align="center"><?php echo $voter_rows['Year']; ?></td>
            <?php
    $voter_id = $voter_rows['StudentID'];
    $year_query = mysqli_query($conn, "SELECT academic_year FROM settings WHERE is_current = 1");
    $current_year_data = mysqli_fetch_assoc($year_query)['academic_year'];

    $status_query = mysqli_query($conn, "SELECT 1 FROM votes WHERE voter_id = '$voter_id' AND academic_year = '$current_year_data' LIMIT 1");
    $has_voted = (mysqli_num_rows($status_query) > 0) ? 'Voted' : 'Not Voted';
?>
<td align="center"><?php echo $has_voted; ?></td>

            <td align="center">
              <?php 
                  $enrollment_status = $voter_rows['enrollment'];
                  $btn_class = ($enrollment_status == 'enrolled') ? 'btn-success' : 'btn-danger';
                  $btn_text = ($enrollment_status == 'enrolled') ? 'Enrolled' : 'Unenrolled';
              ?>
              <button class="btn <?php echo $btn_class; ?> toggle-enrollment" 
                      data-voter-id="<?php echo $id; ?>"
                      data-status="<?php echo $enrollment_status; ?>">
                  <?php echo $btn_text; ?>
              </button>
              <button class="btn btn-danger delete-voter" data-voter-id="<?php echo $id; ?>">
                  <i class="icon-trash icon-large"></i> Delete
              </button>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Password Preview Modal -->
<div id="passwordPreviewModal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="modalTitle">Password Preview</h3>
  </div>
  <div class="modal-body">
    <div id="previewPassword" style="min-height:100px; max-height:60vh; overflow:auto;"></div>
  </div>
  <div class="modal-footer">
    <button id="copyPasswordBtn" class="btn btn-success">Copy All</button>
    <button class="btn" data-dismiss="modal">Close</button>
  </div>
</div>

<input type="hidden" class="pc_date" name="pc_date"/>
<input type="hidden" class="pc_time" name="pc_time"/>
</body>
</html>

<script type="text/javascript">
  $(document).ready(function() {

    var myDate = new Date();
    var pc_date = (myDate.getMonth()+1) + '/' + (myDate.getDate()) + '/' + myDate.getFullYear();
    var pc_time = myDate.getHours()+':'+myDate.getMinutes()+':'+myDate.getSeconds();
    jQuery(".pc_date").val(pc_date);
    jQuery(".pc_time").val(pc_time);

    // Success popup function
    function showSuccessPopup(title, message) {
      $('.success-popup-backdrop, .success-popup').remove();
      
      const backdrop = $('<div class="success-popup-backdrop"></div>');
      
      const popup = $('<div class="success-popup">' +
        '<div class="success-popup-icon">✓</div>' +
        '<div class="success-popup-title">' + title + '</div>' +
        '<div class="success-popup-message">' + message + '</div>' +
        '</div>');
      
      $('body').append(backdrop).append(popup);
      
      setTimeout(function() {
        popup.addClass('fade-out');
        backdrop.fadeOut(300);
        setTimeout(function() {
          backdrop.remove();
          popup.remove();
        }, 300);
      }, 2500);
      
      backdrop.on('click', function() {
        popup.addClass('fade-out');
        backdrop.fadeOut(300);
        setTimeout(function() {
          backdrop.remove();
          popup.remove();
        }, 300);
      });
    }

    // Toggle Enrollment AJAX
    $('.toggle-enrollment').click(function(e) {
        e.preventDefault();
        var button = $(this);
        var voter_id = button.data('voter-id');
        var current_status = button.data('status');

        $.ajax({
            url: 'update_enrollment.php',
            type: 'POST',
            dataType: 'json',
            data: {
                voter_id: voter_id,
                status: current_status
            },
            success: function(response) {
                if (response.success) {
                    if (response.new_status == 'enrolled') {
                        button.removeClass('btn-danger').addClass('btn-success').text('Enrolled');
                    } else {
                        button.removeClass('btn-success').addClass('btn-danger').text('Unenrolled');
                    }
                    button.data('status', response.new_status);
                } else {
                    alert('Failed to update enrollment status: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                alert('Error updating enrollment status');
            }
        });
    });

    // Delete Voter AJAX
    $('.delete-voter').click(function(e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to delete this voter?')) return;
        
        var voterId = $(this).data('voter-id');
        var row = $(this).closest('tr');

        $.ajax({
            type: 'POST',
            url: 'delete_voter.php',
            dataType: 'json',
            data: { voter_id: voterId },
            success: function(response) {
                if (response.success) {
                    row.fadeOut(400, function() { $(this).remove(); });
                    showSuccessPopup('Success!', response.message);
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr.responseText);
                alert('An unexpected error occurred. Please try again.');
            }
        });
    });
  });

  // MULTI-SELECT PASSWORD GENERATION
  $(function() {
    let selectedVoterIds = new Set();
    let currentPreviewData = [];

    // Build initial map from data-generated attributes
    let generatedMap = {};
    $('table tbody tr').each(function() {
      const sid = $(this).find('td').eq(1).text().trim(); // Column 1 is Student ID
      const gen = $(this).attr('data-generated') || '';
      if (gen) generatedMap[sid] = gen;
    });

    // Update selection count and button states
    function updateSelectionUI() {
      const count = selectedVoterIds.size;
      $('#selectedCountLabel').text(count + ' student' + (count !== 1 ? 's' : '') + ' selected');
      $('#globalGenerateSelectedBtn').prop('disabled', count === 0);
      $('#globalPreviewSelectedBtn').prop('disabled', count === 0);
    }

    // Individual checkbox change
    $('table tbody').on('change', '.select-checkbox', function() {
      const voterId = $(this).val();
      const row = $(this).closest('tr');
      
      if ($(this).is(':checked')) {
        selectedVoterIds.add(voterId);
        row.addClass('selected-row');
      } else {
        selectedVoterIds.delete(voterId);
        row.removeClass('selected-row');
      }
      
      updateSelectionUI();
      
      // Update "select all" checkbox state
      const totalCheckboxes = $('.select-checkbox').length;
      const checkedCheckboxes = $('.select-checkbox:checked').length;
      $('#selectAllCheckbox').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    // Select all checkbox
    $('#selectAllCheckbox').on('change', function() {
      const isChecked = $(this).is(':checked');
      
      $('.select-checkbox').each(function() {
        const voterId = $(this).val();
        const row = $(this).closest('tr');
        
        $(this).prop('checked', isChecked);
        
        if (isChecked) {
          selectedVoterIds.add(voterId);
          row.addClass('selected-row');
        } else {
          selectedVoterIds.delete(voterId);
          row.removeClass('selected-row');
        }
      });
      
      updateSelectionUI();
    });

    // Success popup function
    function showSuccessPopup(title, message) {
      $('.success-popup-backdrop, .success-popup').remove();
      const backdrop = $('<div class="success-popup-backdrop"></div>');
      const popup = $('<div class="success-popup">' +
        '<div class="success-popup-icon">✓</div>' +
        '<div class="success-popup-title">' + title + '</div>' +
        '<div class="success-popup-message">' + message + '</div>' +
        '</div>');
      $('body').append(backdrop).append(popup);
      setTimeout(function() {
        popup.addClass('fade-out');
        backdrop.fadeOut(300);
        setTimeout(function() {
          backdrop.remove();
          popup.remove();
        }, 300);
      }, 2500);
      backdrop.on('click', function() {
        popup.addClass('fade-out');
        backdrop.fadeOut(300);
        setTimeout(function() {
          backdrop.remove();
          popup.remove();
        }, 300);
      });
    }

    // Generate for all students
    $('#globalGenerateAllBtn').on('click', function(e){
      e.preventDefault();
      if (!confirm('Generate new passwords for ALL students in the current academic year?')) return;
      var btn = $(this);
      btn.prop('disabled', true).html('<i class="icon-refresh"></i> Generating…');

      $.ajax({
        url: 'generate_password.php',
        type: 'POST',
        dataType: 'json',
        data: { voter_id: 'all' },
        success: function(res){
          btn.prop('disabled', false).html('<i class="icon-key"></i> Generate Passwords (All)');
          if (!res.success) {
            alert('Error: ' + (res.message || 'Unknown'));
            return;
          }

          generatedMap = {};
          if (res.results) {
            for (const [id, info] of Object.entries(res.results)) {
              if (info.ok) {
                generatedMap[id] = info.password;
                $('table tbody tr').each(function(){
                  const rid = $(this).find('td').eq(1).text().trim();
                  if (rid === id) {
                    $(this).attr('data-generated', info.password);
                  }
                });
              }
            }
            showSuccessPopup('Success!', 'Passwords generated for ' + Object.keys(generatedMap).length + ' students');
          }
        },
        error: function(xhr){
          btn.prop('disabled', false).html('<i class="icon-key"></i> Generate Passwords (All)');
          alert('Server error; check console.');
          console.error(xhr.responseText);
        }
      });
    });

    // Generate for selected students
    $('#globalGenerateSelectedBtn').on('click', function(e){
      e.preventDefault();
      
      if (selectedVoterIds.size === 0) {
        alert('Please select at least one student');
        return;
      }
      
      const studentCount = selectedVoterIds.size;
      if (!confirm('Generate new passwords for ' + studentCount + ' selected student' + (studentCount !== 1 ? 's' : '') + '?')) return;
      
      var btn = $(this);
      btn.prop('disabled', true).html('<i class="icon-refresh"></i> Generating…');

      const selectedArray = Array.from(selectedVoterIds);

      $.ajax({
        url: 'generate_password.php',
        type: 'POST',
        dataType: 'json',
        data: { 
          voter_id: 'selected',
          selected_ids: selectedArray
        },
        success: function(res){
          btn.prop('disabled', false).html('<i class="icon-key"></i> Generate Passwords (Selected)');
          
          if (!res.success) {
            alert('Error: ' + (res.message || 'Unknown'));
            return;
          }

          // Update generatedMap with new passwords
          if (res.results) {
            let successCount = 0;
            for (const [id, info] of Object.entries(res.results)) {
              if (info.ok) {
                generatedMap[id] = info.password;
                successCount++;
                // Update data-generated attribute
                $('table tbody tr').each(function(){
                  const rid = $(this).find('td').eq(1).text().trim();
                  if (rid === id) {
                    $(this).attr('data-generated', info.password);
                  }
                });
              }
            }
            showSuccessPopup('Success!', 'Passwords generated for ' + successCount + ' student' + (successCount !== 1 ? 's' : ''));
          }
        },
        error: function(xhr){
          btn.prop('disabled', false).html('<i class="icon-key"></i> Generate Passwords (Selected)');
          alert('Server error; check console.');
          console.error(xhr.responseText);
        }
      });
    });

    // Preview ALL generated passwords
    $('#globalPreviewAllBtn').on('click', function(e){
      e.preventDefault();
      const rows = [];
      currentPreviewData = [];
      
      $('table tbody tr').each(function(){
        const sid = $(this).find('td').eq(1).text().trim();
        const generated = $(this).attr('data-generated') || '';
        if (generated) {
          rows.push({ id: sid, pw: generated });
          currentPreviewData.push(sid + '\t' + generated);
        }
      });

      if (rows.length === 0) {
        alert('No generated passwords found. Click "Generate Passwords (All)" first.');
        return;
      }

      let html = '<div style="margin-bottom:10px; padding:8px; background:#e8f4f8; border-radius:4px; color:#0066cc;"><strong>Total: ' + rows.length + ' students</strong></div>';
      html += '<table class="table table-striped table-bordered" style="margin:0; text-align:left;">';
      html += '<thead><tr style="background:#f5f5f5; color:black;"><th>Student ID</th><th>Password</th></tr></thead><tbody>';
      
      rows.forEach(r => {
        html += '<tr>';
        html += '<td><strong>' + escapeHtml(r.id) + '</strong></td>';
        html += '<td style="font-family:monospace; font-weight:bold; color:#d9534f;">' + escapeHtml(r.pw) + '</td>';
        html += '</tr>';
      });
      html += '</tbody></table>';

      $('#modalTitle').text('All Generated Passwords (' + rows.length + ')');
      $('#previewPassword').html(html);
      $('#copyPasswordBtn').text('Copy All (Excel Format)');

      showModal();
    });

    // Preview selected students' passwords
    $('#globalPreviewSelectedBtn').on('click', function(e){
      e.preventDefault();
      
      if (selectedVoterIds.size === 0) {
        alert('Please select at least one student');
        return;
      }

      const rows = [];
      currentPreviewData = [];
      
      selectedVoterIds.forEach(function(sid) {
        const pw = generatedMap[sid];
        if (pw) {
          rows.push({ id: sid, pw: pw });
          currentPreviewData.push(sid + '\t' + pw);
        }
      });

      if (rows.length === 0) {
        alert('No generated passwords for selected students. Generate passwords first.');
        return;
      }

      let html = '<div style="margin-bottom:10px; padding:8px; background:#e8f4f8; border-radius:4px; color:#0066cc;"><strong>Selected: ' + rows.length + ' students</strong></div>';
      html += '<table class="table table-striped table-bordered" style="margin:0; text-align:left;">';
      html += '<thead><tr style="background:#f5f5f5; color: black;"><th>Student ID</th><th>Password</th></tr></thead><tbody>';
      
      rows.forEach(r => {
        html += '<tr>';
        html += '<td><strong>' + escapeHtml(r.id) + '</strong></td>';
        html += '<td style="font-family:monospace; font-weight:bold; color:#d9534f;">' + escapeHtml(r.pw) + '</td>';
        html += '</tr>';
      });
      html += '</tbody></table>';

      $('#modalTitle').text('Selected Students Passwords (' + rows.length + ')');
      $('#previewPassword').html(html);
      $('#copyPasswordBtn').text('Copy All (Excel Format)');

      showModal();
    });

    // Copy button
    $('#copyPasswordBtn').off('click').on('click', function(){
      if (currentPreviewData.length === 0) {
        alert('No data to copy');
        return;
      }

      const textToCopy = currentPreviewData.join('\n');

      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(textToCopy).then(function(){ 
          showCopySuccess();
        }).catch(function(err){
          console.error('Clipboard error:', err);
          fallbackCopy(textToCopy);
        });
      } else {
        fallbackCopy(textToCopy);
      }
    });

    function showCopySuccess() {
      const btn = $('#copyPasswordBtn');
      const originalText = btn.text();
      const originalClass = btn.attr('class');
      
      btn.removeClass('btn-success').addClass('btn-primary')
         .html('<i class="icon-ok"></i> Copied!')
         .prop('disabled', true);
      
      setTimeout(function() {
        btn.attr('class', originalClass).text(originalText).prop('disabled', false);
      }, 2000);
    }

    function fallbackCopy(text) {
      const $temp = $('<textarea>')
        .val(text)
        .css({
          position: 'fixed',
          top: 0,
          left: 0,
          width: '2em',
          height: '2em',
          padding: 0,
          border: 'none',
          outline: 'none',
          boxShadow: 'none',
          background: 'transparent'
        })
        .appendTo('body')
        .select();
      
      try { 
        document.execCommand('copy');
        showCopySuccess();
      } catch(e) { 
        alert('Copy failed. Please copy manually.'); 
      }
      $temp.remove();
    }

    function escapeHtml(text) {
      return $('<div>').text(text).html();
    }

    function showModal() {
      if ($.fn && $.fn.modal) {
        $('#passwordPreviewModal').modal('show');
      } else {
        $('#passwordPreviewModal').show();
        $('<div class="custom-backdrop"/>').css({
          position:'fixed',
          left:0,
          top:0,
          right:0,
          bottom:0,
          background:'rgba(0,0,0,0.5)',
          zIndex:1040
        }).appendTo('body');
      }
    }

    // Close modal cleanup
    $('#passwordPreviewModal [data-dismiss="modal"]').on('click', function() {
      $('#passwordPreviewModal').hide();
      $('.custom-backdrop').remove();
      currentPreviewData = [];
    });

    // Close on backdrop click
    $(document).on('click', '.custom-backdrop', function() {
      $('#passwordPreviewModal').hide();
      $('.custom-backdrop').remove();
      currentPreviewData = [];
    });
  }); 
</script>
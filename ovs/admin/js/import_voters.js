$(document).ready(function() {
    $('#import_voters').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            type: 'POST',
            url: 'import_voters.php',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                // on full or partial success, go to ALL VOTERS list
                if (response.success || response.message.includes("successfully")) {
                    alert(response.message);
                    window.location = 'voter_list.php';
                } else {
                    // true failure
                    let errorMsg = response.message + "\n\n";
                    if (response.errors && response.errors.length > 0) {
                        errorMsg += "Errors:\n" + response.errors.join("\n");
                    }
                    alert(errorMsg);
                }
            },
            error: function(xhr, status, error) {
                // even on HTTP 500, try to parse JSON
                try {
                    let j = JSON.parse(xhr.responseText);
                    if (j.success || j.message.includes("successfully")) {
                        alert(j.message);
                        return window.location = 'voter_list.php';
                    }
                    let msg = j.message || 'Error importing voters.';
                    if (j.errors && j.errors.length) {
                        msg += "\n\nDetails:\n" + j.errors.join("\n");
                    }
                    alert(msg);
                    return;
                } catch (e) {
                    console.error('Non‑JSON error:', xhr.responseText);
                }
                alert('Error importing voters. Please try again.');
            }
        });
    });
});

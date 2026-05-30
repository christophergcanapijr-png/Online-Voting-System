<?php
// Start output buffering if not already started
if (ob_get_level() == 0) ob_start();

// Only declare the redirect function if it hasn't been declared yet
if (!function_exists('_redirect')) {
    function _redirect($url='') {
        if(!empty($url)) {
            echo "<script> location.href = '".$url."' </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
<title>College of Information Technology Voting System</title>

<!-- GLOBAL FIX: Center all Bootstrap 2 modals -->
<style>
.modal {
    position: fixed !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    margin: 0 !important;
    width: auto !important;
    z-index: 2000 !important;
}
.modal.fade.in { top: 50% !important; }
.modal .modal-content { border-radius: 10px; }

/* Disable hover tooltips inside modals */
.modal *[title] {
    pointer-events: none !important;
}
</style>

<!-- Core JavaScript Libraries -->
<script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-transition.js"></script>
<script src="js/bootstrap-collapse.js"></script>
<script src="js/bootstrap-tab.js"></script>

<!-- Popup and Notification Scripts -->
<script src="js/main.js"></script>
<script src="js/mouseover_popup.js"></script>
<script src="js/notify/jquery_notification_v.1.js"></script>

<!-- DataTables -->
<script src="js/dataTables/jquery.dataTables.js"></script>
<script>
jQuery(document).ready(function() {
    jQuery('#log, #attendance, #record, #cadet_list, #passed').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"
    });
});
</script>

<!-- Bootstrap Initializations -->
<script>
jQuery(document).ready(function(){
    $('.carousel').carousel({ interval: 1000 });
    $('.dropdown-toggle').dropdown();
});
</script>

<!-- QTip -->
<script src="js/qtip/jquery.qtip.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1"></script>
<script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>

<!-- jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Stylesheets -->
<link rel="stylesheet" href="css/notify/jquery_notification.css">
<link rel="stylesheet" href="js/qtip/jquery.qtip.min.css">
<link rel="stylesheet" type="text/css" href="css/datatable/demo_page.css">
<link rel="stylesheet" type="text/css" href="css/datatable/demo_table_jui.css">
<link rel="stylesheet" type="text/css" href="css/datatable/jquery-ui-1.8.4.custom.css">
<link rel="stylesheet" href="css/bootstrap.css" />
<link rel="stylesheet" href="css/bootstrap-responsive.css" />
<link rel="stylesheet" href="css/font-awesome.css">
<link rel="stylesheet" href="css/Home.css" />
<link rel="stylesheet" href="css/spacegallery.css" />
<link rel="stylesheet" href="css/custom.css" />

<!-- Favicon -->
<link rel="icon" href="images/chmsc.png" type="image/png" />

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Preview DIV -->
<div style="display:none; position:absolute; color:white; z-index:100;" id="preview_div"></div>

<!-- Hover Effects -->
<?php include('hover.php'); ?>

<!-- Additional Scripts -->
<script src="js/eye.js"></script>
<script src="js/spacegallery.js"></script>
<script src="js/layout.js"></script>

<style>
body { background: rgb(255, 250, 250) !important; }
</style>

<!-- GLOBAL TOOLTIP FIX -->
<script>
$(document).ready(function() {

    // Hide ALL tooltips when ANY modal opens
    $(document).on('show.bs.modal shown.bs.modal hide.bs.modal hidden.bs.modal', function () {
        $('#preview_div').hide();   // Custom preview tooltip
        $('.qtip').hide();          // qTip tooltip
        $('.tooltip').hide();       // Bootstrap tooltip
    });

    // Hide leftover tooltips when leaving delete buttons
    $(document).on('mouseleave', '[data-toggle="tooltip"], .delete-user, .delete-voter', function() {
        $('#preview_div').hide();
        $('.qtip').hide();
        $('.tooltip').hide();
    });

    // EXTRA FIX: disable tooltip on modal buttons
    $('.modal').on('mouseenter', '*', function() {
        $('#preview_div').hide();
        $('.qtip').hide();
        $('.tooltip').hide();
    });
});
</script>

</head>

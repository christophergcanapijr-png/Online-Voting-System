<?php
// Remove any whitespace or HTML before this PHP tag
?>

<script type="text/javascript">
jQuery(document).ready(function () {
    $('#back').qtip({
        content: 'Click to here return',
        position: { my: 'top left', target: 'mouse', show: { ready: true }, viewport: $(window), adjust: { x: 10, y: 10 } },
        hide: { fixed: true },
        style: 'ui-tooltip-shadow'
    });

    $('#bak').qtip({
        content: 'Click to return',
        position: { my: 'top left', target: 'mouse', show: { ready: true }, viewport: $(window), adjust: { x: 10, y: 10 } },
        hide: { fixed: true },
        style: 'ui-tooltip-shadow'
    });

    $('#vote').qtip({
        content: 'Click here to Submit Vote',
        position: { my: 'top left', target: 'mouse', show: { ready: true }, viewport: $(window), adjust: { x: 10, y: 10 } },
        hide: { fixed: true },
        style: 'ui-tooltip-shadow'
    });

    $('#index').qtip({
        content: 'Click here to vote later, return to main page',
        position: { my: 'top left', target: 'mouse', show: { ready: true }, viewport: $(window), adjust: { x: 10, y: 10 } },
        hide: { fixed: true },
        style: 'ui-tooltip-shadow'
    });

    $('#help').qtip({
        content: 'Click here to View Help',
        position: { my: 'top left', target: 'mouse', show: { ready: true }, viewport: $(window), adjust: { x: 10, y: 10 } },
        hide: { fixed: true },
        style: 'ui-tooltip-shadow'
    });

    $('#excel').qtip({
        content: 'Click here to download Excel File',
        position: { my: 'top left', target: 'mouse', show: { ready: true }, viewport: $(window), adjust: { x: 10, y: 10 } },
        hide: { fixed: true },
        style: 'ui-tooltip-shadow'
    });

    $('.UserName_hover').qtip({
        content: 'Enter your username here',
        position: { my: 'top left', target: 'mouse', show: { ready: true }, viewport: $(window), adjust: { x: 10, y: 10 } },
        hide: { fixed: true },
        style: 'ui-tooltip-shadow'
    });

    $('.Password_hover').qtip({
        content: 'Enter your Password here',
        position: { my: 'top left', target: 'mouse', show: { ready: true }, viewport: $(window), adjust: { x: 10, y: 10 } },
        hide: { fixed: true },
        style: 'ui-tooltip-shadow'
    });

    $('.Button_Login_Hover').qtip({
        content: 'Click Here To Login',
        position: { my: 'top left', target: 'mouse', show: { ready: true }, viewport: $(window), adjust: { x: 10, y: 10 } },
        hide: { fixed: true },
        style: 'ui-tooltip-shadow'
    });

    $('.delete_voter').qtip({
        content: 'Click Here To Delete Voter',
        position: { my: 'top left', target: 'mouse', show: { ready: true }, viewport: $(window), adjust: { x: 10, y: 10 } },
        hide: { fixed: true },
        style: 'ui-tooltip-shadow'
    });

   $('.btn-danger.delete_voter').qtip({
    content: 'Click here to delete this Voter',
        position: { my: 'top left', target: 'mouse', show: { ready: true }, viewport: $(window), adjust: { x: 10, y: 10 } },
        hide: { fixed: true },
        style: 'ui-tooltip-shadow'
    });

    $('#logout').qtip({
        content: 'Click Here to LogOut',
        position: { my: 'top left', target: 'mouse', show: { ready: true }, viewport: $(window), adjust: { x: 10, y: 10 } },
        hide: { fixed: true },
        style: 'ui-tooltip-shadow'
    });
});
</script>

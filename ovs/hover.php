<?php
// Tooltip Configuration for Online Voting System
?>
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
    // Login Page Tooltips
    $('.UserName_hover').qtip({
        content: {
            text: 'Enter your Student ID (e.g., 2025-0001)'
        },
        position: {
            my: 'bottom center',
            at: 'top center'
        },
        style: {
            classes: 'qtip-bootstrap qtip-shadow',
            tip: {
                width: 10,
                height: 5
            }
        }
    });

    $('.Password_hover').qtip({
        content: {
            text: 'Enter the password provided to you'
        },
        position: {
            my: 'bottom center',
            at: 'top center'
        },
        style: {
            classes: 'qtip-bootstrap qtip-shadow',
            tip: {
                width: 10,
                height: 5
            }
        }
    });

    $('.Button_Login_Hover').qtip({
        content: {
            text: 'Click here to access the voting system'
        },
        position: {
            my: 'bottom center',
            at: 'top center'
        },
        style: {
            classes: 'qtip-bootstrap qtip-shadow'
        }
    });

    // Voting Page Tooltips
    $('.candidate-photo').qtip({
        content: {
            text: 'Click to view candidate profile and platform'
        },
        position: {
            my: 'bottom center',
            at: 'top center'
        },
        style: {
            classes: 'qtip-bootstrap qtip-shadow'
        },
        show: {
            delay: 500
        }
    });

    $('.vote-button').qtip({
        content: {
            text: 'Review your selections before submitting'
        },
        position: {
            my: 'bottom center',
            at: 'top center'
        },
        style: {
            classes: 'qtip-bootstrap qtip-shadow'
        }
    });

    // Admin Panel Tooltips
    $('.delete_voter').qtip({
        content: {
            text: '⚠️ Warning: This will permanently delete the voter record'
        },
        position: {
            my: 'top center',
            at: 'bottom center'
        },
        style: {
            classes: 'qtip-red qtip-shadow',
            tip: {
                width: 10,
                height: 5
            }
        },
        show: {
            delay: 300
        }
    });

    $('.btn-danger').qtip({
        content: {
            text: '⚠️ Caution: This action cannot be undone'
        },
        position: {
            my: 'top center',
            at: 'bottom center'
        },
        style: {
            classes: 'qtip-red qtip-shadow'
        }
    });

    $('.edit_voter').qtip({
        content: {
            text: 'Edit voter information'
        },
        style: {
            classes: 'qtip-bootstrap qtip-shadow'
        }
    });

    $('.add_candidate').qtip({
        content: {
            text: 'Add a new candidate to the election'
        },
        style: {
            classes: 'qtip-bootstrap qtip-shadow'
        }
    });

    $('.view_results').qtip({
        content: {
            text: 'View real-time voting results'
        },
        style: {
            classes: 'qtip-bootstrap qtip-shadow'
        }
    });

    // Navigation Tooltips
    $('#logout').qtip({
        content: {
            text: 'Logout from the system'
        },
        position: {
            my: 'bottom right',
            at: 'top center'
        },
        style: {
            classes: 'qtip-bootstrap qtip-shadow'
        }
    });

    $('#help').qtip({
        content: {
            text: 'Need help? Click here for system guide'
        },
        position: {
            my: 'bottom center',
            at: 'top center'
        },
        style: {
            classes: 'qtip-blue qtip-shadow'
        }
    });

    // Academic Year Tooltips
    $('.activate-year').qtip({
        content: {
            text: 'Set this as the current active academic year'
        },
        style: {
            classes: 'qtip-bootstrap qtip-shadow'
        }
    });

    $('.delete-year').qtip({
        content: {
            text: '⚠️ This will delete all data for this academic year'
        },
        style: {
            classes: 'qtip-red qtip-shadow'
        }
    });

    // Ballot Tooltips
    $('.submit-vote').qtip({
        content: {
            text: '✓ Make sure you have selected all your candidates before submitting'
        },
        position: {
            my: 'top center',
            at: 'bottom center'
        },
        style: {
            classes: 'qtip-green qtip-shadow',
            tip: {
                width: 12,
                height: 6
            }
        }
    });

    $('.clear-selection').qtip({
        content: {
            text: 'Clear all your current selections'
        },
        style: {
            classes: 'qtip-bootstrap qtip-shadow'
        }
    });

    // Status Indicators
    $('.status-voted').qtip({
        content: {
            text: 'This voter has already cast their vote'
        },
        style: {
            classes: 'qtip-green qtip-shadow'
        }
    });

    $('.status-not-voted').qtip({
        content: {
            text: 'This voter has not voted yet'
        },
        style: {
            classes: 'qtip-bootstrap qtip-shadow'
        }
    });

    // Form Validation Tooltips
    $('input[required]').qtip({
        content: {
            text: 'This field is required'
        },
        position: {
            my: 'left center',
            at: 'right center'
        },
        style: {
            classes: 'qtip-red qtip-shadow'
        },
        show: {
            event: 'focus'
        },
        hide: {
            event: 'blur'
        }
    });
});
</script>

<style>
/* Custom qTip styles */
.qtip-bootstrap {
    background-color: #333;
    border: 1px solid #444;
    color: white;
    font-size: 12px;
    padding: 8px 12px;
    border-radius: 4px;
}

.qtip-red {
    background-color: #dc3545;
    border: 1px solid #bd2130;
    color: white;
    font-size: 12px;
    padding: 8px 12px;
    border-radius: 4px;
}

.qtip-green {
    background-color: #28a745;
    border: 1px solid #218838;
    color: white;
    font-size: 12px;
    padding: 8px 12px;
    border-radius: 4px;
}

.qtip-blue {
    background-color: #007bff;
    border: 1px solid #0056b3;
    color: white;
    font-size: 12px;
    padding: 8px 12px;
    border-radius: 4px;
}

.qtip-shadow {
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}
</style>


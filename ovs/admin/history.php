<?php
include('session.php');

// Check if this is an AJAX request
$is_ajax = isset($_GET['ajax']) && $_GET['ajax'] == '1';

if (!$is_ajax) {
    include('header.php');
}

include('dbcon.php');

// Get current academic year from settings with proper error handling
$academic_year = '2024-2025'; // Default fallback value
$year_query = "SELECT academic_year FROM settings WHERE is_current = 1 LIMIT 1";
$result = mysqli_query($conn, $year_query);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if (isset($row['academic_year']) && !empty($row['academic_year'])) {
        $academic_year = $row['academic_year'];
    }
}

// Pagination settings
$records_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';

// Build the WHERE clause
$where_clause = "WHERE h.academic_year = ?";
$params = [$academic_year];
$types = "s";

if (!empty($search)) {
    $where_clause .= " AND (h.action LIKE ? OR h.data LIKE ? OR u.UserName LIKE ? OR DATE_FORMAT(h.date, '%M %d, %Y %h:%i %p') LIKE ?)";
    $search_param = "%{$search}%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
    $types .= "ssss";
}

// Get total records for pagination
$count_sql = "SELECT COUNT(*) as total FROM history h LEFT JOIN users u ON h.user_id = u.User_id {$where_clause}";
$count_stmt = mysqli_prepare($conn, $count_sql);
mysqli_stmt_bind_param($count_stmt, $types, ...$params);
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$total_records = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_records / $records_per_page);

// Get history records for current page
$history_sql = "SELECT h.*, u.UserName 
                FROM history h
                LEFT JOIN users u ON h.user_id = u.User_id
                {$where_clause}
                ORDER BY h.date DESC
                LIMIT ? OFFSET ?";

$params[] = $records_per_page;
$params[] = $offset;
$types .= "ii";

$stmt = mysqli_prepare($conn, $history_sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$history_query = mysqli_stmt_get_result($stmt);

if (!$history_query) {
    die("Query failed: " . mysqli_error($conn));
}

// If AJAX request, return JSON
if ($is_ajax) {
    $html = '';
    if (mysqli_num_rows($history_query) > 0) {
        while($history_rows = mysqli_fetch_array($history_query)) { 
            $id = $history_rows['history_id'];
            $date = $history_rows['date'] ? date('M d, Y h:i A', strtotime($history_rows['date'])) : 'No Date';
            
            $html .= '<tr class="del' . $id . '">';
            $html .= '<td>' . htmlspecialchars($date) . '</td>';
            $html .= '<td>' . htmlspecialchars($history_rows['action']) . '</td>';
            $html .= '<td>' . htmlspecialchars($history_rows['data']) . '</td>';
            $html .= '<td>' . htmlspecialchars($history_rows['UserName']) . '</td>';
            $html .= '</tr>';
        }
    } else {
        $html = '<tr><td colspan="4" style="text-align: center; padding: 20px;">No records found' . (!empty($search) ? ' for your search' : '') . '</td></tr>';
    }
    
    // Build records info
    $records_info = 'Showing ' . ($total_records > 0 ? ($offset + 1) : 0) . ' to ' . min($offset + $records_per_page, $total_records) . ' of ' . $total_records . ' records';
    if (!empty($search)) {
        $records_info .= ' (filtered from search)';
    }
    
    // Build pagination HTML
    $pagination = '';
    if ($total_pages > 1) {
        $pagination .= '<div class="pagination">';
        
        // First Page
        if ($page > 1) {
            $pagination .= '<a href="#" class="page-link" data-page="1">First</a>';
        } else {
            $pagination .= '<span class="disabled">First</span>';
        }
        
        // Previous Page
        if ($page > 1) {
            $pagination .= '<a href="#" class="page-link" data-page="' . ($page - 1) . '">Previous</a>';
        } else {
            $pagination .= '<span class="disabled">Previous</span>';
        }
        
        // Page Numbers
        $start_page = max(1, $page - 2);
        $end_page = min($total_pages, $page + 2);
        
        if ($start_page > 1) {
            $pagination .= '<span>...</span>';
        }
        
        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i == $page) {
                $pagination .= '<span class="active">' . $i . '</span>';
            } else {
                $pagination .= '<a href="#" class="page-link" data-page="' . $i . '">' . $i . '</a>';
            }
        }
        
        if ($end_page < $total_pages) {
            $pagination .= '<span>...</span>';
        }
        
        // Next Page
        if ($page < $total_pages) {
            $pagination .= '<a href="#" class="page-link" data-page="' . ($page + 1) . '">Next</a>';
        } else {
            $pagination .= '<span class="disabled">Next</span>';
        }
        
        // Last Page
        if ($page < $total_pages) {
            $pagination .= '<a href="#" class="page-link" data-page="' . $total_pages . '">Last</a>';
        } else {
            $pagination .= '<span class="disabled">Last</span>';
        }
        
        $pagination .= '</div>';
    }
    
    header('Content-Type: application/json');
    echo json_encode([
        'html' => $html,
        'records_info' => $records_info,
        'pagination' => $pagination,
        'total_records' => $total_records,
        'current_page' => $page,
        'total_pages' => $total_pages
    ]);
    exit;
}
?>
</head>
<head>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/modern.css">
    <style>
        .search-container {
            margin: 20px 0;
            display: flex;
            gap: 10px;
            align-items: center;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .search-container input[type="text"] {
            padding: 10px 15px;
            border: 2px solid #ddd;
            border-radius: 6px;
            width: 350px;
            font-size: 15px;
            transition: border-color 0.3s;
        }
        
        .search-container input[type="text"]:focus {
            outline: none;
            border-color: #007bff;
        }
        
        .clear-search {
            padding: 10px 20px;
            background-color: #6c757d !important;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .clear-search:hover {
            background-color: #545b62 !important;
            color: white;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        
        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background-color: #fff;
            color: #007bff;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s;
            min-width: 40px;
            text-align: center;
            cursor: pointer;
        }
        
        .pagination a:hover {
            background-color: #007bff;
            color: white;
        }
        
        .pagination .active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
            font-weight: bold;
        }
        
        .pagination .disabled {
            color: #6c757d;
            cursor: not-allowed;
            opacity: 0.5;
        }
        
        .pagination .disabled:hover {
            background-color: #fff;
            color: #6c757d;
        }
        
        .records-info {
            text-align: center;
            margin: 15px 0;
            padding: 12px;
            background-color: #e9ecef;
            border-radius: 6px;
            color: #495057;
            font-size: 15px;
            font-weight: 500;
            border: 1px solid #dee2e6;
        }
        
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 20px;
            color: #007bff;
            font-size: 16px;
        }
        
        .loading-spinner.active {
            display: block;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .spinner-icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
    </style>
</head>
<body>
<?php include('nav_top.php'); ?>
<div class="wrapper">
<div class="home_body" style="margin-top:0px">
<div class="navbar">
    <div class="navbar-inner">
    <div class="container">    
    <ul class="nav nav-pills">
      <li><a href="home.php"><i class="icon-home icon-large"></i>Home</a></li>
      <li><a href="candidate_list.php"><i class="icon-align-justify icon-large"></i>Candidates List</a></li>  
      <li><a href="voter_list.php"><i class="icon-align-justify icon-large"></i>Student List</a></li>  
      <li><a href="canvassing_report.php"><i class="icon-book icon-large"></i>Votes Report</a></li>
      <li class="active"><a href="History.php"><i class="icon-table icon-large"></i>History Log</a></li>
     </ul>
    </div>
    </div>
</div>

<div id="element" class="hero-body">
<table class="users-table">
<div class="demo_jui">
    <div class="history-header">
        <h3>History Log - Academic Year: <?php echo htmlspecialchars($academic_year); ?></h3>
        <div class="excel_button">
            <form method="POST" action="export_history.php">
                <button id="excel" class="btn btn-success" name="save" style="margin-top: 10px;">
                    <i class="icon-download icon-large"></i>Download Excel File
                </button>
            </form>
        </div>
    </div>
    
    <!-- Search Form -->
    <div class="search-container">
        <input type="text" 
               id="searchInput"
               name="search" 
               placeholder="🔍 Search by action, data, user, or date..." 
               value="<?php echo htmlspecialchars($search); ?>"
               autocomplete="off">
        <button class="clear-search" id="clearBtn" style="<?php echo empty($search) ? 'display:none;' : ''; ?>">Clear</button>
    </div>
    
    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loadingSpinner">
        <span class="spinner-icon"></span> Loading...
    </div>
    
    <!-- Records Info -->
    <div class="records-info" id="recordsInfo">
        Showing <?php echo $total_records > 0 ? ($offset + 1) : 0; ?> to <?php echo min($offset + $records_per_page, $total_records); ?> of <?php echo $total_records; ?> records
        <?php if (!empty($search)): ?>
            (filtered from search)
        <?php endif; ?>
    </div>
    
   <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="historyTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Action</th>
                <th>Data</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody id="tableBody">
        <?php 
        if (mysqli_num_rows($history_query) > 0) {
            while($history_rows = mysqli_fetch_array($history_query)) { 
                $id = $history_rows['history_id']; ?>
                <tr class="del<?php echo $id ?>">
                    <td>
                      <?php
                        echo $history_rows['date'] 
                          ? date('M d, Y h:i A', strtotime($history_rows['date'])) 
                          : 'No Date';
                      ?>
                    </td>
                    <td><?php echo htmlspecialchars($history_rows['action']); ?></td>
                    <td><?php echo htmlspecialchars($history_rows['data']); ?></td>
                    <td><?php echo htmlspecialchars($history_rows['UserName']); ?></td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="4" style="text-align: center; padding: 20px;">No records found<?php echo !empty($search) ? ' for your search' : ''; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    
    <!-- Pagination -->
    <div id="paginationContainer">
    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <!-- First Page -->
        <?php if ($page > 1): ?>
            <a href="#" class="page-link" data-page="1">First</a>
        <?php else: ?>
            <span class="disabled">First</span>
        <?php endif; ?>
        
        <!-- Previous Page -->
        <?php if ($page > 1): ?>
            <a href="#" class="page-link" data-page="<?php echo ($page - 1); ?>">Previous</a>
        <?php else: ?>
            <span class="disabled">Previous</span>
        <?php endif; ?>
        
        <!-- Page Numbers -->
        <?php
        $start_page = max(1, $page - 2);
        $end_page = min($total_pages, $page + 2);
        
        if ($start_page > 1) {
            echo '<span>...</span>';
        }
        
        for ($i = $start_page; $i <= $end_page; $i++): ?>
            <?php if ($i == $page): ?>
                <span class="active"><?php echo $i; ?></span>
            <?php else: ?>
                <a href="#" class="page-link" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor;
        
        if ($end_page < $total_pages) {
            echo '<span>...</span>';
        }
        ?>
        
        <!-- Next Page -->
        <?php if ($page < $total_pages): ?>
            <a href="#" class="page-link" data-page="<?php echo ($page + 1); ?>">Next</a>
        <?php else: ?>
            <span class="disabled">Next</span>
        <?php endif; ?>
        
        <!-- Last Page -->
        <?php if ($page < $total_pages): ?>
            <a href="#" class="page-link" data-page="<?php echo $total_pages; ?>">Last</a>
        <?php else: ?>
            <span class="disabled">Last</span>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    </div>
</div>
</div>
</div>

<script>
// Live search functionality
let searchTimeout;
const searchInput = document.getElementById('searchInput');
const tableBody = document.getElementById('tableBody');
const recordsInfo = document.getElementById('recordsInfo');
const paginationContainer = document.getElementById('paginationContainer');
const loadingSpinner = document.getElementById('loadingSpinner');
const clearBtn = document.getElementById('clearBtn');

// Search as user types
searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    
    // Show/hide clear button
    if (searchInput.value.trim() !== '') {
        clearBtn.style.display = 'inline-block';
    } else {
        clearBtn.style.display = 'none';
    }
    
    searchTimeout = setTimeout(function() {
        const searchValue = searchInput.value.trim();
        performSearch(searchValue, 1);
    }, 300);
});

// Clear button functionality
clearBtn.addEventListener('click', function(e) {
    e.preventDefault();
    searchInput.value = '';
    clearBtn.style.display = 'none';
    performSearch('', 1);
});

// Pagination click handler
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('page-link')) {
        e.preventDefault();
        const page = e.target.getAttribute('data-page');
        const searchValue = searchInput.value.trim();
        performSearch(searchValue, page);
    }
});

function performSearch(searchValue, page = 1) {
    loadingSpinner.classList.add('active');
    tableBody.style.opacity = '0.5';
    
    const url = 'History.php?ajax=1&search=' + encodeURIComponent(searchValue) + '&page=' + page;
    
    fetch(url)
        .then(response => response.json())
        .then(result => {
            tableBody.innerHTML = result.html;
            tableBody.style.opacity = '1';
            recordsInfo.innerHTML = result.records_info;
            paginationContainer.innerHTML = result.pagination;
            loadingSpinner.classList.remove('active');
        })
        .catch(error => {
            console.error('Error:', error);
            loadingSpinner.classList.remove('active');
            tableBody.style.opacity = '1';
            tableBody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px; color: red;">Error loading results. Please try again.</td></tr>';
        });
}
</script>

</body>
</html>
<?php
// Database connection
try {
    require('../config/config.php');
    if (!$mysqli) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        "draw" => intval($_POST['draw']),
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => [],
        "error" => "An error occurred. Please try again later."
    ]);
    exit;
}


// Retrieve DataTable parameters
$tableName = $_POST['table'];  // Table name sent from DataTable
$start = $_POST['start'];
$length = $_POST['length'];
$searchValue = $_POST['search']['value'];
$orderColumn = $_POST['order'][0]['column']; // Column index for ordering
$orderDir = $_POST['order'][0]['dir']; // ASC or DESC for ordering

// Map column indices to database columns and queries for each table
$tableConfig = [
    'streamtarget' => [
        'columns' => [
            'st.streamtarget_id',
            'w.ward_name AS streamtarget_ward_id',
            'u.user_names AS streamtarget_user_id',
            'rs.stream_name AS streamtarget_stream_id',
            'st.streamtarget_amount',
            'fy.fy_year AS streamtarget_fy'
        ],
        'query' => "
            SELECT 
                st.streamtarget_id,
                w.ward_name AS streamtarget_ward_id,
                u.user_names AS streamtarget_user_id,
                rs.stream_name AS streamtarget_stream_id,
                st.streamtarget_amount,
                fy.fy_year AS streamtarget_fy
            FROM streamtarget st
            INNER JOIN ward w ON st.streamtarget_ward_id = w.ward_id
            INNER JOIN users u ON st.streamtarget_user_id = u.user_id
            INNER JOIN revenue_streams rs ON st.streamtarget_stream_id = rs.stream_id
            INNER JOIN financial_year fy ON st.streamtarget_fy = fy.fy_id
        "
    ],
    'collectorquarterlytarget' => [
        'columns' => [
            'cq.collectorquarterlytarget_id',
            'u.user_names AS collectorquarterlytarget_user_id',
            'cq.collectorquarterlytarget_quarter_number',
            'rs.stream_name AS collectorquarterlytarget_stream_id',
            'cq.collectorquarterlytarget_quarter_target',
            'cq.collectorquarterlytarget_quarter_collection',
            'cq.collectorquarterlytarget_quarter_deficit',
            'fy.fy_year AS collectorquarterlytarget_fy'
        ],
        'query' => "
            SELECT 
                cq.collectorquarterlytarget_id,
                u.user_names AS collectorquarterlytarget_user_id,
                cq.collectorquarterlytarget_quarter_number,
                rs.stream_name AS collectorquarterlytarget_stream_id,
                cq.collectorquarterlytarget_quarter_target,
                cq.collectorquarterlytarget_quarter_collection,
                cq.collectorquarterlytarget_quarter_deficit,
                fy.fy_year AS collectorquarterlytarget_fy
            FROM collectorquarterlytarget cq
            INNER JOIN users u ON cq.collectorquarterlytarget_user_id = u.user_id
            INNER JOIN revenue_streams rs ON cq.collectorquarterlytarget_stream_id = rs.stream_id
            INNER JOIN financial_year fy ON cq.collectorquarterlytarget_fy = fy.fy_id
        "
    ],
    'collectormonthlytarget' => [
        'columns' => [
            'cm.collectormonthlytarget_id',
            'u.user_names AS collectormonthlytarget_user_id',
            'cm.collectormonthlytarget_month',
            'rs.stream_name AS collectormonthlytarget_stream_id',
            'cm.collectormonthlytarget_fixed_amount',
            'cm.collectormonthlytarget_amount',
            'cm.collectormonthlytarget_deficit',
            'fy.fy_year AS collectormonthlytarget_fy'
        ],
        'query' => "
            SELECT 
                cm.collectormonthlytarget_id,
                u.user_names AS collectormonthlytarget_user_id,
                cm.collectormonthlytarget_month,
                rs.stream_name AS collectormonthlytarget_stream_id,
                cm.collectormonthlytarget_fixed_amount,
                cm.collectormonthlytarget_amount,
                cm.collectormonthlytarget_deficit,
                fy.fy_year AS collectormonthlytarget_fy
            FROM collectormonthlytarget cm
            INNER JOIN users u ON cm.collectormonthlytarget_user_id = u.user_id
            INNER JOIN revenue_streams rs ON cm.collectormonthlytarget_stream_id = rs.stream_id
            INNER JOIN financial_year fy ON cm.collectormonthlytarget_fy = fy.fy_id
        "
    ]
    
];



// Check if the requested table configuration exists
if (!isset($tableConfig[$tableName])) {
    die(json_encode(['error' => 'Invalid table']));
}

$columns = $tableConfig[$tableName]['columns'];
$query = $tableConfig[$tableName]['query'];

// Apply search filter if any
if (!empty($searchValue)) {
    $searchQuery = [];
    foreach ($columns as $col) {
        // Strip alias if present to prevent search errors
        $col = explode(" AS ", $col)[0];
        $searchQuery[] = "$col LIKE '%$searchValue%'";
    }
    $query .= " WHERE " . implode(" OR ", $searchQuery);
}

// Count total records without any filtering
$totalRecordsQuery = $mysqli->query("SELECT COUNT(*) FROM {$tableName}");
$totalRecords = $totalRecordsQuery->fetch_row()[0];

// Prepare filtered count query
$filteredQuery = $tableConfig[$tableName]['query']; // Start with the base query
if (!empty($searchValue)) {
    // Apply the same WHERE clause to get the count of filtered records
    $filteredQuery .= " WHERE " . implode(" OR ", $searchQuery);
}

// Count filtered records
$totalFilteredResult = $mysqli->query($filteredQuery);
$totalFiltered = $totalFilteredResult->num_rows;

// Order by selected column
$orderColumnName = explode(" AS ", $columns[$orderColumn])[0];
$query .= " ORDER BY $orderColumnName $orderDir";

// Limit data for pagination
$query .= " LIMIT $start, $length";

// Execute the data query
$result = $mysqli->query($query);

// Check for SQL errors
if (!$result) {
    error_log("SQL Error: " . $mysqli->error);
    echo json_encode([
        "draw" => intval($_POST['draw']),
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => [],
        "error" => "Database query failed."
    ]);
    exit;
}

// Collect data for DataTable
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Return data in JSON format
echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);

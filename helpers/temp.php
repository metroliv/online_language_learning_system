<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
try {
    require('../config/config.php');
    if (!$mysqli) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }
} catch (Exception $e) {
    error_log($e->getMessage());  // Log detailed error
    echo json_encode([
        "draw" => intval($_POST['draw']),
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => [],
        "error" => $e->getMessage()  // Return the error message in the response
    ]);
    exit;
}

// Get filter values
$fy = $_POST['fy'] ?? '';
$dateFilter = $_POST['dateFilter'] ?? '';
$startDate = $_POST['startDate'] ?? '';
$endDate = $_POST['endDate'] ?? '';

// Get DataTables request values for pagination and ordering
$start = $_POST['start'] ?? 0; // Starting row index for pagination
$length = $_POST['length'] ?? 10; // Number of rows per page
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0; // Index of column to order by
$orderDir = $_POST['order'][0]['dir'] ?? 'asc'; // Ordering direction (asc or desc)
$columns = ['c.collection_id', 'w.ward_name', 'u.user_name', 'rs.stream_name', 's.service_name', 'c.collection_amount', 'c.collection_date'];

// Determine column for ordering
$orderColumn = $columns[$orderColumnIndex] ?? 'c.collection_id';

// Base SQL query
$sql = "SELECT c.collection_id, 
               w.ward_name, 
               u.user_names,  -- Fixed column name
               rs.stream_name, 
               s.service_name, 
               c.collection_amount, 
               DATE_FORMAT(c.collection_date, '%Y-%m-%d') AS collection_date
        FROM revenue_collections AS c
        JOIN ward AS w ON c.collection_ward_id = w.ward_id
        JOIN users AS u ON c.collection_user_id = u.user_id
        JOIN revenue_streams AS rs ON c.collection_stream_id = rs.stream_id
        JOIN revenue_services AS s ON c.collection_service_id = s.service_id
        WHERE 1=1";

// Filtering by Financial Year (FY)
if (!empty($fy)) {
    list($startYear, $endYear) = explode('/', $fy);
    $fyStart = $startYear . '-07-01';  // Start of fiscal year (e.g., 2024-07-01)
    $fyEnd = ($endYear) . '-06-30';  // End of fiscal year (e.g., 2025-06-30)
    
    $sql .= " AND c.collection_date BETWEEN '$fyStart' AND '$fyEnd'";
}

// Filter by custom date range
if (!empty($startDate) && !empty($endDate)) {
    $sql .= " AND c.collection_date BETWEEN '$startDate' AND '$endDate'";
}

// Adding daily, weekly, or monthly filters if needed
if ($dateFilter === 'daily') {
    $sql .= " AND DATE(c.collection_date) = CURDATE()";
}

if ($dateFilter === 'weekly') {
    $sql .= " AND WEEK(c.collection_date) = WEEK(CURDATE())";
}

if ($dateFilter === 'monthly') {
    $sql .= " AND MONTH(c.collection_date) = MONTH(CURDATE())";
}

// Total records count without filtering
$totalRecordsQuery = "SELECT COUNT(*) FROM revenue_collections";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_row()[0];

// Total records count after filtering
$totalFilteredQuery = "SELECT COUNT(*) FROM ($sql) AS filtered_collections";
$totalFilteredResult = $conn->query($totalFilteredQuery);
$totalFiltered = $totalFilteredResult->fetch_row()[0];

// Add ordering and pagination to the SQL query
$sql .= " ORDER BY $orderColumn $orderDir LIMIT $start, $length";

// Execute the main query
$dataResult = $conn->query($sql);
$data = [];
if ($dataResult->num_rows > 0) {
    while ($row = $dataResult->fetch_assoc()) {
        $data[] = $row;
    }
}

// Prepare the response array
$response = [
    "draw" => intval($_POST['draw']), // Pass back the draw counter for DataTables
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
];

// Return JSON response
echo json_encode($response);
?>

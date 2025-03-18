<?php
require_once('../config/config.php');

// Handle form submission
$stream = isset($_POST['stream']) ? $_POST['stream'] : 'all';
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-01');
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-t');

// Prepare the query
$query = "
    SELECT 
        s.stream_id,
        s.stream_name,
        SUM(rc.collection_amount) AS total_collected
    FROM revenue_collections rc
    JOIN revenue_streams s ON rc.collection_stream_id = s.stream_id
    WHERE rc.collection_status = 'Approved'
    AND rc.collection_date BETWEEN ? AND ?
";

if ($stream != 'all') {
    $query .= " AND rc.collection_stream_id = ?";
}

$query .= " GROUP BY s.stream_id, s.stream_name ORDER BY total_collected DESC";

// Prepare and execute the query
$stmt = $mysqli->prepare($query);

if ($stream != 'all') {
    $stmt->bind_param("sss", $startDate, $endDate, $stream);
} else {
    $stmt->bind_param("ss", $startDate, $endDate);
}

$stmt->execute();
$result = $stmt->get_result();

// Store the results in an array for later use in the table
$streamPerformance = [];
while ($row = $result->fetch_assoc()) {
    $streamPerformance[] = $row;
}

// Pass the $streamPerformance array to the parent script
// No direct echo statements should be outside the table

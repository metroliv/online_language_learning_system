<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('../config/config.php');
require_once('../partials/head.php');
require_once('../helpers/stream_overview.php');

// Fetch distinct revenue streams
$query = "SELECT DISTINCT stream_id, stream_name FROM revenue_streams";
$result = $mysqli->query($query);

$streams = [];
while ($row = $result->fetch_assoc()) {
    $streams[] = $row;
}

// Fetch wards for the filter, including "All" option
$regions_query = "SELECT DISTINCT ward_id, ward_name FROM ward WHERE ward_name NOT IN ('County_HQ', 'All_Wards')";
$regions_result = $mysqli->query($regions_query);

$regions = [['ward_id' => '', 'ward_name' => 'All']]; // Add 'All' option
while ($row = $regions_result->fetch_assoc()) {
    $regions[] = $row;
}

// Fetch services for a specific stream based on selection
$selected_stream = $_GET['stream'] ?? null;
$selected_service = $_GET['service'] ?? null;
$selected_region = $_GET['region'] ?? null;
$selected_start_date = $_GET['start_date'] ?? null;
$selected_end_date = $_GET['end_date'] ?? null;
$report_data = [];
$services = [];

// Fetch services if stream is selected
if ($selected_stream) {
    // Fetch services for the selected revenue stream
    $services_query = "
        SELECT DISTINCT r.service_id, r.service_name
        FROM revenue_services r
        JOIN revenue_streams s ON r.service_stream_id = s.stream_id
        WHERE s.stream_id = ?
    ";
    $stmt = $mysqli->prepare($services_query);
    $stmt->bind_param('i', $selected_stream);
    $stmt->execute();
    $services_result = $stmt->get_result();

    $services[] = ['service_id' => '', 'service_name' => 'All']; // Add 'All' option
    while ($row = $services_result->fetch_assoc()) {
        $services[] = $row;
    }

    // Prepare the SQL query with the additional date range filter and status check
    $query = "
        SELECT
            rs.stream_name,
            r.service_name,
            w.ward_name,
            SUM(rc.collection_amount) AS total_collected,
            DATE_FORMAT(rc.collection_date, '%Y-%m') AS collection_month
        FROM revenue_collections rc
        JOIN revenue_streams rs ON rc.collection_stream_id = rs.stream_id
        JOIN revenue_services r ON rc.collection_service_id = r.service_id
        JOIN ward w ON rc.collection_ward_id = w.ward_id
        WHERE rs.stream_id = ?
        AND (r.service_id = ? OR ? = '')
        AND (w.ward_id = ? OR ? = '')
        AND rc.collection_status = 'Approved'
        AND (? = '' OR rc.collection_date >= ?)
        AND (? = '' OR rc.collection_date <= ?)
        GROUP BY rs.stream_name, r.service_name, w.ward_name, collection_month
        ORDER BY collection_month ASC
    ";

    $stmt = $mysqli->prepare($query);

    // Prepare the data for binding
    $params = [$selected_stream, $selected_service, $selected_service, $selected_region, $selected_region, $selected_start_date, $selected_start_date, $selected_end_date, $selected_end_date];

    // Build the type string dynamically
    $type_string = str_repeat('s', count($params)); // Use 's' for string binding

    // Bind parameters
    $stmt->bind_param($type_string, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $report_data[] = $row;
    }

    $stmt->close();
}

$mysqli->close();
?>

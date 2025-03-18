<?php
require_once('../config/config.php');

// Set the content type to JSON
header('Content-Type: application/json');

// Function to fetch data from the database
function fetchData($mysqli, $query) {
    $result = $mysqli->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch wards
$wardsQuery = "SELECT ward_id, ward_name FROM ward ORDER BY ward_name";
$wards = fetchData($mysqli, $wardsQuery);

// Fetch streams
$streamsQuery = "SELECT stream_id, stream_name FROM revenue_streams ORDER BY stream_name";
$streams = fetchData($mysqli, $streamsQuery);

// Output JSON
echo json_encode(['wards' => $wards, 'streams' => $streams]);
?>

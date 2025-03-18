<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('../functions/reusableQuery.php');
require_once('../config/config.php');
require_once('../helpers/auth.php');
require_once('../helpers/users.php');

// Get start and end dates from the query parameters
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-1 year'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// Debug: Log the received start and end dates
error_log("Start Date: $start_date, End Date: $end_date");

// Get growth rate data for the selected period
$query = "SELECT DATE_FORMAT(collection_date, '%Y-%m-%d') AS period, SUM(collection_amount) AS total_revenue
          FROM revenue_collections
          WHERE collection_date BETWEEN ? AND ?
          AND collection_status = 'Approved'
          GROUP BY DATE_FORMAT(collection_date, '%Y-%m-%d')";

$stmt = $mysqli->prepare($query);
$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

$growth_data = [];
while ($row = $result->fetch_assoc()) {
    $growth_data[] = $row;
}

$stmt->close();
$mysqli->close();

// Encode data for use in JavaScript
header('Content-Type: application/json');
echo json_encode($growth_data);
?>

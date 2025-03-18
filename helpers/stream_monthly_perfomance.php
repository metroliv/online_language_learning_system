<?php
require_once('../config/config.php');

// Fetch the fy_id for the active financial year

$financialYearQuery = "SELECT fy_year FROM financial_year WHERE fy_status = 1 LIMIT 1";
$fyStmt = $mysqli->prepare($financialYearQuery);
$fyStmt->execute();
$fyResult = $fyStmt->get_result();

if ($fyRow = $fyResult->fetch_assoc()) {
    $financialYear = $fyRow['fy_year']; // Get the active financial year
} else {
    die("No active financial year found.");
}

$fyQuery = "SELECT fy_id FROM financial_year WHERE fy_year = ? AND fy_status = 1";
$fyStmt = $mysqli->prepare($fyQuery);
$fyStmt->bind_param("s", $financialYear);
$fyStmt->execute();
$fyResult = $fyStmt->get_result();

if ($fyRow = $fyResult->fetch_assoc()) {
    $financialYearId = $fyRow['fy_id'];
} else {
    die("Financial year not found or inactive.");
}

// Define the start and end dates of the financial year
list($startYear, $endYear) = explode('/', $financialYear);
$startMonth = 7; // Assuming financial year starts in July
$endMonth = 6;   // Assuming financial year ends in June

// Generate an array of all months within the financial year
$months = [];
for ($year = $startYear; $year <= $endYear; $year++) {
    for ($month = ($year == $startYear ? $startMonth : 1); $month <= ($year == $endYear ? $endMonth : 12); $month++) {
        $months[] = sprintf('%04d-%02d', $year, $month);
    }
}

// Fetch monthly collected amounts for the specified financial year
$collectedQuery = "
    SELECT 
        DATE_FORMAT(rc.collection_date, '%Y-%m') AS month,
        rc.collection_stream_id,
        SUM(rc.collection_amount) AS total_collected
    FROM revenue_collections rc
    WHERE rc.collection_status = 'Approved' AND rc.collections_fy = ?
    GROUP BY month, rc.collection_stream_id
";
$collectedStmt = $mysqli->prepare($collectedQuery);
$collectedStmt->bind_param("i", $financialYearId);
$collectedStmt->execute();
$collectedResult = $collectedStmt->get_result();

// Combine collected data by stream and month
$collectedData = [];
while ($row = $collectedResult->fetch_assoc()) {
    $month = $row['month'];
    $streamId = $row['collection_stream_id'];
    
    if (!isset($collectedData[$streamId])) {
        $collectedData[$streamId] = [];
    }

    // Sum the collected amounts for the specific stream and month
    $collectedData[$streamId][$month] = $row['total_collected'];
}

// Fetch stream names
$streamNames = [];
$streamQuery = "SELECT stream_id, stream_name FROM revenue_streams";
$streamResult = $mysqli->query($streamQuery);
while ($streamRow = $streamResult->fetch_assoc()) {
    $streamNames[$streamRow['stream_id']] = $streamRow['stream_name'];
}

// Define a color palette for the chart
$colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];

// Create datasets from collected data
$datasets = [];
$colorIndex = 0;
foreach ($collectedData as $streamId => $data) {
    $streamDataset = [];
    
    // Ensure every month in the financial year has a value, even if it's 0
    foreach ($months as $month) {
        $streamDataset[] = isset($data[$month]) ? $data[$month] : 0;
    }
    
    $datasets[] = [
        'label' => $streamNames[$streamId] ?? 'Unknown Stream',
        'data' => $streamDataset,
        'borderColor' => $colors[$colorIndex % count($colors)],
        'backgroundColor' => $colors[$colorIndex % count($colors)] . '33',
        'fill' => false,
        'tension' => 0.4,
    ];
    $colorIndex++;
}

// Prepare data for JavaScript
$chartData = [
    'labels' => array_map(function($month) {
        return DateTime::createFromFormat('Y-m', $month)->format('F Y');
    }, $months),
    'datasets' => $datasets,
];

// Output or pass $chartData to your front-end for rendering with Chart.js

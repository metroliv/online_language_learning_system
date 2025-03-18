<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../config/config.php');

// Initialize the output array
$output = array(
    'success' => false,
    'wards' => [],
    'streams' => [],
    'collectedAmounts' => [],
    'targetAmounts' => [],
    'totalTarget' => 0,
    'tableHTML' => '', // For tabular report
    'chartData' => [], // For graphical report
    'total' => 0,
    'message' => ''
);

// Check for action parameter in GET request
if (isset($_GET['action']) && $_GET['action'] == 'fetchOptions') {
    $output = [
        'wards' => [],
        'streams' => []
    ];
    
    try {
        // Fetch Wards
        $wardQuery = "SELECT ward_id, ward_name FROM ward ORDER BY ward_name";
        $wardsResult = executeQuery($mysqli, $wardQuery, [], '');
        $output['wards'] = $wardsResult->fetch_all(MYSQLI_ASSOC);

        // Fetch Streams
        $streamQuery = "SELECT stream_id, stream_name FROM revenue_streams ORDER BY stream_name";
        $streamsResult = executeQuery($mysqli, $streamQuery, [], '');
        $output['streams'] = $streamsResult->fetch_all(MYSQLI_ASSOC);

        $output['success'] = true;
    } catch (Exception $e) {
        $output['message'] = $e->getMessage();
    } finally {
        echo json_encode($output);
        exit;
    }
}

// Get user inputs
$currentYear = date('Y');
$endYear = date('Y');
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : "$currentYear-07-01"; // Dynamic default start date
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : "$endYear-06-01"; // Dynamic default end date
$selectedWard = isset($_POST['ward']) ? $_POST['ward'] : 'all';     // Default to 'all' if not provided
$selectedStream = isset($_POST['stream']) ? $_POST['stream'] : 'all'; // Default to 'all' if not provided
$selectedType = isset($_POST['type']) ? $_POST['type'] : 'tabular';  // Default to 'tabular' if not provided

// Fetch the active fy_id for the current financial year
$fyQuery = "SELECT fy_id FROM financial_year WHERE fy_status = 1";
$fyStmt = $mysqli->prepare($fyQuery);
$fyStmt->execute();
$fyResult = $fyStmt->get_result();
$financialYear = $fyResult->fetch_assoc()['fy_id']; // Get the fy_id
$fyStmt->close(); // Close the statement

// Function to execute a query with parameters and return the result
function executeQuery($mysqli, $query, $params, $types = '') {
    $stmt = $mysqli->prepare($query);
    if ($stmt === false) {
        throw new Exception('Prepare failed: ' . $mysqli->error);
    }
    if ($types && $params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close(); // Close the statement
    return $result;
}

try {
    // 1. Fetch Collected Amount for Each Stream and Ward
    $collectedQuery = "
        SELECT 
            w.ward_name AS ward_name,   -- Fetch ward_name instead of ward_id
            s.stream_name AS stream_name,
            SUM(rc.collection_amount) AS total_collected
        FROM revenue_collections rc
        JOIN revenue_streams s ON rc.collection_stream_id = s.stream_id
        JOIN ward w ON rc.collection_ward_id = w.ward_id  -- Join with ward table
        WHERE rc.collection_status = 'Approved'
        AND rc.collection_date BETWEEN ? AND ?
        " . ($selectedWard !== 'all' ? "AND rc.collection_ward_id = ?" : "") . "
        " . ($selectedStream !== 'all' ? "AND rc.collection_stream_id = ?" : "") . "
        GROUP BY w.ward_name, s.stream_name
    ";

    $params = [$startDate, $endDate];
    $types = 'ss';
    if ($selectedWard !== 'all') {
        $params[] = $selectedWard;
        $types .= 'i'; // Assuming ward_id is an integer
    }
    if ($selectedStream !== 'all') {
        $params[] = $selectedStream;
        $types .= 'i'; // Assuming stream_id is an integer
    }

    $collectedResult = executeQuery($mysqli, $collectedQuery, $params, $types);

    // 2. Fetch Target Amount for Each Stream and Wards
    $targetQuery = "
        SELECT 
            w.ward_name AS ward_name,   -- Fetch ward_name instead of ward_id
            rs.stream_name AS stream_name,
            SUM(st.streamtarget_amount) AS total_target
        FROM streamtarget st
        JOIN revenue_streams rs ON st.streamtarget_stream_id = rs.stream_id
        JOIN ward w ON st.streamtarget_ward_id = w.ward_id  -- Join with ward table
        WHERE st.streamtarget_fy = ?
        " . ($selectedWard !== 'all' ? "AND st.streamtarget_ward_id = ?" : "") . "
        " . ($selectedStream !== 'all' ? "AND st.streamtarget_stream_id = ?" : "") . "
        GROUP BY w.ward_name, rs.stream_name
    ";

    $params = [$financialYear];
    $types = 'i'; // Assuming fy_id is an integer
    if ($selectedWard !== 'all') {
        $params[] = $selectedWard;
        $types .= 'i';
    }
    if ($selectedStream !== 'all') {
        $params[] = $selectedStream;
        $types .= 'i';
    }

    $targetResult = executeQuery($mysqli, $targetQuery, $params, $types);

    // Prepare Output Data
    $tableRows = '';
    $chartLabels = [];
    $chartCollectedAmounts = [];
    $chartTargetAmounts = [];

    $targets = [];
    while ($row = $targetResult->fetch_assoc()) {
        $targets[$row['ward_name']][$row['stream_name']] = $row['total_target'];
        $output['targetAmounts'][] = $row;
    }

    // Calculate Total Target for the Financial Year
    $totalTarget = array_sum(array_map('array_sum', $targets));
    $output['totalTarget'] = $totalTarget;

    // Calculate Total Collected Amount
    $totalCollected = 0;
    while ($row = $collectedResult->fetch_assoc()) {
        $wardName = $row['ward_name'];
        $streamName = $row['stream_name'];
        $collectedAmount = $row['total_collected'];
        $targetAmount = isset($targets[$wardName][$streamName]) ? $targets[$wardName][$streamName] : 0;
        // Calculate the percentage of collected amount vs target
        $percentage = ($targetAmount > 0) ? ($collectedAmount / $targetAmount) * 100 : 0;
        // Format numbers with commas
        $formattedTargetAmount = number_format($targetAmount, 0);
        $formattedCollectedAmount = number_format($collectedAmount, 0);
        $tableRows .= "
            <tr>
                <td>{$wardName}</td>
                <td>{$streamName}</td>
                <td>{$formattedTargetAmount}</td>
                <td>{$formattedCollectedAmount}</td>
                <td>" . number_format($percentage, 2) . "%</td>
            </tr>
        ";

        // Prepare Chart Data
        $chartLabels[] = $streamName;
        $chartCollectedAmounts[] = $collectedAmount;
        $chartTargetAmounts[] = $targetAmount;

        // Update Total Collected Amount
        $totalCollected += $collectedAmount;
    }

    $output['tableHTML'] = $tableRows;

    // Prepare Chart Data for Graphical Report
    $output['chartData'] = [
        'labels' => $chartLabels,
        'targetAmounts' => $chartTargetAmounts,
        'collectedAmounts' => $chartCollectedAmounts        
    ];

    $output['total'] = $totalCollected; // Set the total collected amount
    $output['success'] = true;
} catch (Exception $e) {
    $output['message'] = $e->getMessage();
} finally {
    echo json_encode($output);
    exit;
}
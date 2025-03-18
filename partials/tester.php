<?php
require('../vendor/autoload.php'); // For Dompdf
require('../config/config.php'); // Include the database configuration

use Dompdf\Dompdf;

// Function to generate a PDF report
function generate_pdf($data, $reportTitle) {
    $dompdf = new Dompdf();
    
    // Create HTML content for PDF
    $html = '<html><body>';
    $html .= '<h1 style="text-align:center;">' . $reportTitle . '</h1>';
    $html .= '<table border="1" style="width:100%; border-collapse:collapse; margin: 20px 0;">';
    $html .= '<thead><tr><th style="padding:8px; text-align:left;">Description</th><th style="padding:8px; text-align:left;">Value</th></tr></thead>';
    $html .= '<tbody>';
    
    foreach ($data as $item) {
        $html .= '<tr>';
        $html .= '<td style="padding:8px;">' . $item['title'] . '</td>';
        $html .= '<td style="padding:8px;">' . $item['value'] . '</td>';
        $html .= '</tr>';
    }
    
    $html .= '</tbody></table>';
    $html .= '</body></html>';
    
    // Load HTML content into Dompdf
    $dompdf->loadHtml($html);
    
    // Set paper size and orientation
    $dompdf->setPaper('A4', 'portrait');
    
    // Render PDF
    $dompdf->render();
    
    // Output the PDF (downloadable)
    $dompdf->stream($reportTitle . '.pdf', ['Attachment' => 0]);
}

// Function to fetch performance data including from revenue_targets and collectortarget table
function fetch_performance_data($filterBy, $timeframe, $reportType, $mysqli) {
    $data = [];
    
    // Query to fetch total revenue collected and approved collections including collector target
    if ($reportType === 'ward') {
        $sql = "SELECT SUM(rc.collection_amount) AS total_collected, COUNT(rc.collection_id) AS transactions,
                       SUM(rc.collection_amount) AS approved_collections, rt.target_amount AS target_amount
                FROM revenue_collections rc
                LEFT JOIN revenue_targets rt ON rt.target_ward_id = rc.collection_ward_id
                WHERE rc.collection_status = 'Approved' AND rc.collection_ward_id = ? AND rc.collection_date BETWEEN ? AND ?";
    } else {
        $sql = "SELECT SUM(rc.collection_amount) AS total_collected, COUNT(rc.collection_id) AS transactions,
                       SUM(rc.collection_amount) AS approved_collections, SUM(ct.collectortarget_amount) AS collector_target_amount,
                       rt.target_amount AS target_amount
                FROM revenue_collections rc
                LEFT JOIN revenue_targets rt ON rt.target_ward_id = rc.collection_ward_id
                LEFT JOIN collectortarget ct ON ct.collectortarget_streamtarget_id = rc.collection_stream_id
                WHERE rc.collection_status = 'Approved' AND rc.collection_stream_id = ? AND rc.collection_date BETWEEN ? AND ?
                GROUP BY rc.collection_stream_id";
    }

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", $filterBy, $timeframe['start'], $timeframe['end']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $data[] = ['title' => 'Total Revenue Collected', 'value' => $row['total_collected']];
        $data[] = ['title' => 'Number of Transactions', 'value' => $row['transactions']];
        $data[] = ['title' => 'Approved Collections', 'value' => $row['approved_collections']];
        $data[] = ['title' => 'Collector Target Amount', 'value' => $row['collector_target_amount']];
        $data[] = ['title' => 'Target Amount', 'value' => $row['target_amount']];
    }

    $stmt->close();

    return $data;
}

// Function to fetch wards for dropdown
function fetch_wards($mysqli) {
    $wards = [];
    $sql = "SELECT ward_id, ward_name FROM ward";
    $result = $mysqli->query($sql);

    while ($row = $result->fetch_assoc()) {
        $wards[] = $row;
    }

    return $wards;
}

// Handle the form submission for generating PDF reports
if (isset($_POST['generate_pdf'])) {
    $reportType = $_POST['report_type']; // "ward" or "stream"
    $filterBy = $_POST['filter_by'];
    $timeframe = [
        'start' => $_POST['timeframe_start'],
        'end' => $_POST['timeframe_end']
    ];
    
    // Use the existing mysqli connection from config.php
    global $mysqli;
    
    // Fetch the relevant data
    $data = fetch_performance_data($filterBy, $timeframe, $reportType, $mysqli);
    
    // Generate the PDF
    $reportTitle = $reportType === 'ward' ? 'Ward Performance Report' : 'Stream Performance Report';
    generate_pdf($data, $reportTitle); // Generate the PDF
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Reports</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- AdminLTE Skins -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/skins/_all-skins.min.css">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Main content -->
        <div class="content-wrapper">
            <div class="container-fluid mt-3">
                <section class="content">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h1 class="card-title">Generate Performance Reports</h1>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="">
                                        <div class="form-group">
                                            <label for="reportType">Report Type</label>
                                            <select name="report_type" id="reportType" class="form-control" required>
                                                <option value="ward">Ward Performance</option>
                                                <option value="stream">Stream Performance</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="filterBy">Filter By</label>
                                            <select name="filter_by" id="filterBy" class="form-control" required>
                                                <?php
                                                // Fetch wards and display them
                                                global $mysqli;
                                                $wards = fetch_wards($mysqli);
                                                foreach ($wards as $ward) {
                                                    echo '<option value="' . $ward['ward_id'] . '">' . $ward['ward_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="timeframeStart">Timeframe Start</label>
                                            <input type="date" name="timeframe_start" id="timeframeStart" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="timeframeEnd">Timeframe End</label>
                                            <input type="date" name="timeframe_end" id="timeframeEnd" class="form-control" required>
                                        </div>
                                        <button type="submit" name="generate_pdf" class="btn btn-primary">Generate PDF Report</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                Anything you want
            </div>
            <strong>&copy; 2024 <a href="#">Your Company</a>.</strong> All rights reserved.
        </footer>
    </div>

    <!-- AdminLTE and dependencies scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>

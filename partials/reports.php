<?php
// Start session if needed
session_start();
require('../vendor/autoload.php'); // For Dompdf
require('../partials/head.php');
require('../config/config.php'); // Include the database configuration

use Dompdf\Dompdf;

// Function to generate a PDF report
function generate_pdf($data, $reportTitle) {
    $dompdf = new Dompdf();

    // Create HTML content for PDF
    $html = '<html><body>';

    // Add the logo and title
    $html .= '<div style="text-align:center;">';
    $html .= '<img src="../public/img/merged_logos.png" alt="Makueni County Logo" style="width:150px;"/><br>';
    $html .= '<h1>Government of Makueni County</h1>';
    $html .= '<h2>Revenue Collection Reporting Tool</h2>';
    $html .= '</div>';

    // Add the report title
    $html .= '<h1 style="text-align:center; margin-top:30px;">' . htmlspecialchars($reportTitle) . '</h1>';

    // Create the table for data
    $html .= '<table border="1" style="width:100%; border-collapse:collapse; margin: 20px 0;">';
    $html .= '<thead><tr><th style="padding:8px; text-align:left;">Description</th><th style="padding:8px; text-align:left;">Value</th></tr></thead>';
    $html .= '<tbody>';

    // Loop through the data and add it to the table rows
    foreach ($data as $item) {
        $html .= '<tr>';
        $html .= '<td style="padding:8px;">' . htmlspecialchars($item['title']) . '</td>';
        $html .= '<td style="padding:8px;">' . htmlspecialchars($item['value']) . '</td>';
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
    
    // Clear the output buffer before streaming PDF
    ob_end_clean();
    
    // Output the PDF (inline display in browser)
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
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sss", $filterBy, $timeframe['start'], $timeframe['end']);
    } else {
        $sql = "SELECT SUM(rc.collection_amount) AS total_collected, COUNT(rc.collection_id) AS transactions,
                       SUM(rc.collection_amount) AS approved_collections, SUM(ct.collectortarget_amount) AS collector_target_amount,
                       rt.target_amount AS target_amount
                FROM revenue_collections rc
                LEFT JOIN revenue_targets rt ON rt.target_ward_id = rc.collection_ward_id
                LEFT JOIN collectortarget ct ON ct.collectortarget_streamtarget_id = rc.collection_stream_id
                WHERE rc.collection_status = 'Approved' AND rc.collection_stream_id = ? AND rc.collection_date BETWEEN ? AND ?
                GROUP BY rc.collection_stream_id";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sss", $filterBy, $timeframe['start'], $timeframe['end']);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $data[] = ['title' => 'Total Revenue Collected', 'value' => number_format($row['total_collected'], 2)];
        $data[] = ['title' => 'Number of Transactions', 'value' => $row['transactions']];
        $data[] = ['title' => 'Approved Collections', 'value' => number_format($row['approved_collections'], 2)];
        $data[] = ['title' => 'Collector Target Amount', 'value' => number_format($row['collector_target_amount'], 2)];
        $data[] = ['title' => 'Target Amount', 'value' => number_format($row['target_amount'], 2)];
    } else {
        // Handle case when no data is found
        $data[] = ['title' => 'No Data Found', 'value' => ''];
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
        <!-- Include Header -->
        <?php include('../partials/header.php'); ?>

        <!-- Include Sidebar -->
        <?php include('../partials/executive_sidenav.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Performance Reports</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../views/dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Performance Reports</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid mt-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Generate Performance Reports</h3>
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
                                                // Fetch wards for dropdown
                                                $wards = fetch_wards($mysqli);
                                                foreach ($wards as $ward) {
                                                    echo "<option value=\"{$ward['ward_id']}\">{$ward['ward_name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="timeframeStart">Start Date</label>
                                            <input type="date" name="timeframe_start" id="timeframeStart" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="timeframeEnd">End Date</label>
                                            <input type="date" name="timeframe_end" id="timeframeEnd" class="form-control" required>
                                        </div>
                                        <button type="submit" name="generate_pdf" class="btn btn-primary">Generate Report</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        <!-- Include Footer -->
        <?php include('../partials/footer.php'); ?>
    </div><!-- ./wrapper -->

    <!-- AdminLTE and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

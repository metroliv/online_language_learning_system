<?php
require('../config/config.php');
require('../vendor/autoload.php'); // For mPDF
use Dompdf\Dompdf;
// Fetch stream ID and export format from the query string
$stream_id = isset($_GET['stream_id']) ? intval($_GET['stream_id']) : 0;
$format = isset($_GET['format']) ? $_GET['format'] : 'csv';

// Validate the format
if (!in_array($format, ['csv', 'pdf'])) {
    die("Invalid format specified.");
}

// Fetch stream details
$stream_query = "SELECT stream_name FROM revenue_streams WHERE stream_id = ?";
$stream_stmt = $mysqli->prepare($stream_query);
$stream_stmt->bind_param('i', $stream_id);
$stream_stmt->execute();
$stream_stmt->bind_result($stream_name);
$stream_stmt->fetch();
$stream_stmt->close();

// Fetch collections and targets for the selected stream
$report_query = "
    SELECT
        COALESCE(SUM(rc.collection_amount), 0) AS total_collections,
        COALESCE(SUM(ct.collectortarget_amount), 0) AS total_targets
    FROM revenue_streams rs
    LEFT JOIN revenue_collections rc ON rs.stream_id = rc.collection_stream_id
        AND rc.collection_status = 'Approved'
    LEFT JOIN collectortarget ct ON rs.stream_id = ct.collectortarget_streamtarget_id
    WHERE rs.stream_id = ?
    GROUP BY rs.stream_id
";
$report_stmt = $mysqli->prepare($report_query);
$report_stmt->bind_param('i', $stream_id);
$report_stmt->execute();
$report_result = $report_stmt->get_result();
$report_data = $report_result->fetch_assoc();
$report_stmt->close();

$total_collections = $report_data['total_collections'];
$total_targets = $report_data['total_targets'];
$target_achievement = $total_targets > 0 ? ($total_collections / $total_targets) * 100 : 0;

if ($format == 'csv') {
    // Export as CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=stream_performance_report.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Stream Name', 'Total Collections', 'Total Targets', 'Target Achievement (%)']);
    fputcsv($output, [$stream_name, number_format($total_collections, 2), number_format($total_targets, 2), number_format($target_achievement, 2)]);
    fclose($output);
} elseif ($format == 'pdf') {
    // Export as PDF
    $Dompdf = new Dompdf();
    

    $html = "
    <h1>Stream Performance Report: " . htmlspecialchars($stream_name) . "</h1>
    <p><strong>Total Collections:</strong> " . number_format($total_collections, 2) . "</p>
    <p><strong>Total Targets:</strong> " . number_format($total_targets, 2) . "</p>
    <p><strong>Target Achievement:</strong> " . number_format($target_achievement, 2) . "%</p>
    ";

    $mpdf->WriteHTML($html);
    $mpdf->Output('stream_performance_report.pdf', 'D');
}

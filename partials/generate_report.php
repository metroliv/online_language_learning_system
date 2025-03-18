<?php
require_once('../config/config.php');
require_once('../vendor/autoload.php'); // Include Dompdf library

use Dompdf\Dompdf;
use Dompdf\Options;// Make sure to adjust the path to where you have FPDF installed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ward = $_POST['ward'] ?? '';
    $stream = $_POST['stream'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    // Initialize FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Revenue Collection Performance Report', 0, 1, 'C');

    // Add filter details
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Ward: $ward", 0, 1);
    $pdf->Cell(0, 10, "Stream: $stream", 0, 1);
    $pdf->Cell(0, 10, "Date Range: $start_date to $end_date", 0, 1);

    // Fetch and add data (example data used here, replace with actual data retrieval)
    $pdf->Cell(0, 10, '', 0, 1); // Empty line
    $pdf->Cell(0, 10, 'Revenue Data:', 0, 1);

    // Sample data (replace with actual data from your database)
    $data = [
        ['Ward Name 1', 'Stream Name 1', 1000, 1500],
        ['Ward Name 2', 'Stream Name 2', 2000, 2500],
    ];

    // Set table header
    $pdf->Cell(60, 10, 'Ward Name', 1);
    $pdf->Cell(60, 10, 'Stream Name', 1);
    $pdf->Cell(30, 10, 'Collected Amount', 1);
    $pdf->Cell(30, 10, 'Target Amount', 1);
    $pdf->Ln();

    // Add data to table
    foreach ($data as $row) {
        $pdf->Cell(60, 10, $row[0], 1);
        $pdf->Cell(60, 10, $row[1], 1);
        $pdf->Cell(30, 10, $row[2], 1);
        $pdf->Cell(30, 10, $row[3], 1);
        $pdf->Ln();
    }

    // Output the PDF
    $pdf->Output('I', 'Revenue_Collection_Performance_Report.pdf');
}
?>

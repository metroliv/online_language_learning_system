<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('../config/config.php');
require_once('../vendor/autoload.php'); // Ensure Dompdf is installed via Composer

use Dompdf\Dompdf;
use Dompdf\Options;
header('Content-Type: application/pdf');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve filter values from the POST request
    $ward = $_POST['ward'] ?? 'all';
    $stream = $_POST['stream'] ?? 'all';
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    $report_type = $_POST['type'] ?? 'tabular';
    
    // Fetch data based on the filters
    $query = "SELECT w.ward_name, s.stream_name, 
                     SUM(rc.collection_amount) AS collected_amount, 
                     st.streamtarget_amount AS target_amount
              FROM revenue_collections rc
              JOIN ward w ON rc.collection_ward_id = w.ward_id
              JOIN revenue_streams s ON rc.collection_stream_id = s.stream_id
              JOIN streamtarget st ON st.streamtarget_stream_id = rc.collection_stream_id 
                                   AND st.streamtarget_ward_id = rc.collection_ward_id
              WHERE 1=1";

    if ($ward !== 'all') {
        $query .= " AND rc.collection_ward_id = :ward";
    }
    if ($stream !== 'all') {
        $query .= " AND rc.collection_stream_id = :stream";
    }
    if ($start_date && $end_date) {
        $query .= " AND rc.collection_date BETWEEN :start_date AND :end_date";
    }

    $query .= " GROUP BY w.ward_name, s.stream_name, st.streamtarget_amount";

    $stmt = $pdo->prepare($query);
    
    if ($ward !== 'all') $stmt->bindParam(':ward', $ward);
    if ($stream !== 'all') $stmt->bindParam(':stream', $stream);
    if ($start_date && $end_date) {
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare the HTML content for the PDF
    ob_start();
    ?>
    <html>
    <head>
        <title>Revenue Collection Performance Report</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            table, th, td {
                border: 1px solid #ddd;
            }
            th, td {
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            h2 {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h2>Revenue Collection Performance Report</h2>
        <table>
            <thead>
                <tr>
                    <th>Ward Name</th>
                    <th>Stream Name</th>
                    <th>Collected Amount</th>
                    <th>Target Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['ward_name']) ?></td>
                        <td><?= htmlspecialchars($row['stream_name']) ?></td>
                        <td><?= htmlspecialchars(number_format($row['collected_amount'], 2)) ?></td>
                        <td><?= htmlspecialchars(number_format($row['target_amount'], 2)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
    </html>
    <?php
    $html = ob_get_clean();

    // Initialize Dompdf and load the HTML content
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);

    // Set paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the PDF
    $dompdf->render();

    // Output the PDF for download
    $dompdf->stream("Revenue_Collection_Performance_Report.pdf", array("Attachment" => true));
} else {
    echo "Invalid request method.";
}
?>

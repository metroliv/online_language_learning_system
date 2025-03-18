<?php
// Include your database configuration
require_once('../config/config.php');

// Fetch approved revenue collections grouped by ward and calculate the total sum
$query = "
    SELECT
        w.ward_name,
        SUM(rc.collection_amount) as total_amount,
        rc.collection_ward_id
    FROM
        revenue_collections rc
    JOIN
        ward w ON rc.collection_ward_id = w.ward_id
    WHERE
        rc.collection_status = 'Approved'
    GROUP BY
        rc.collection_ward_id
";
$result = $mysqli->query($query);

// Calculate the overall total amount
$total_sum_query = "
    SELECT SUM(collection_amount) as overall_total
    FROM revenue_collections
    WHERE collection_status = 'Approved'
";
$total_sum_result = $mysqli->query($total_sum_query);
$overall_total = $total_sum_result->fetch_assoc()['overall_total'];

if ($result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td>" . htmlspecialchars($row['ward_name']) . "</td>";
        echo "<td>Ksh " . number_format($row['total_amount'], 2) . "</td>";
        echo "<td><button onclick=\"viewDetails('" . htmlspecialchars($row['collection_ward_id']) . "')\">View Details</button></td>";
        echo "</tr>";
    }
    echo "<tr>";
    echo "<td colspan='2'><strong>Total Approved Collections</strong></td>";
    echo "<td colspan='2'><strong>Ksh " . number_format($overall_total, 2) . "</strong></td>";
    echo "</tr>";
} else {
    echo "<tr><td colspan='4'>No approved collections found.</td></tr>";
}
?>

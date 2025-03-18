<?php
require_once('../config/config.php');

if (isset($_GET['wardId']) && isset($_GET['date'])) {
    $wardId = mysqli_real_escape_string($mysqli, $_GET['wardId']);
    $date = mysqli_real_escape_string($mysqli, $_GET['date']);

    $query = "
        SELECT 
            rc.collection_user_id, 
            rc.collection_amount, 
            rc.collection_location, 
            rc.collection_comment, 
            rc.collection_date,
            u.user_names 
        FROM 
            revenue_collections rc 
        JOIN 
            users u ON rc.collection_user_id = u.user_id 
        WHERE 
            rc.collection_ward_id = '$wardId' 
            AND DATE(rc.collection_date) = '$date'
            AND rc.collection_status = 'Approved'
    ";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['user_names']) . "</td>";
            echo "<td>Ksh " . number_format($row['collection_amount'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($row['collection_location']) . "</td>";
            echo "<td>" . htmlspecialchars($row['collection_comment']) . "</td>";
            echo "<td>" . htmlspecialchars($row['collection_date']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No approved collections found for Ward: $wardId on Date: $date</td></tr>";
    }
} else {
    echo "<tr><td colspan='5'>Invalid request. Ward ID and Date are required.</td></tr>";
}
?>

<?php
require_once('../config/config.php');

if (isset($_GET['date'])) {
    $date = $_GET['date'];

    // Query to fetch stream details with collector information for the specified date
    $query = "
        SELECT 
            rs.stream_id, 
            rs.stream_name, 
            rc.collection_amount AS amount,
            u.user_names AS collector_name
        FROM 
            revenue_collections rc
        JOIN 
            revenue_streams rs ON rc.collection_stream_id = rs.stream_id
        JOIN 
            users u ON rc.collection_user_id = u.user_id
        WHERE 
            DATE(rc.collection_date) = ?
        AND 
            rc.collection_status = 'Approved'
    ";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $streams = [];
    while ($row = $result->fetch_assoc()) {
        $streams[] = $row;
    }

    echo json_encode($streams);
    $stmt->close();
    $mysqli->close();
}
?>


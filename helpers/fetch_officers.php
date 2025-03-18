<?php
// Include your database connection file here
include('../config/config.php');

// Check if ward_id is set
if (isset($_POST['ward_id'])) {
    $ward_id = $_POST['ward_id'];

    // Fetch officers for the selected ward
    $query = "SELECT user_id, user_names FROM users WHERE ward_id = '$ward_id' AND (user_access_level = 'Revenue Collector' OR user_access_level = 'Ward Administrator')";
    $result = mysqli_query($mysqli, $query);

    $officers = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $officers[] = $row;
    }

    // Return the officers in JSON format
    echo json_encode($officers);
}
?>

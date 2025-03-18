<?php

// Fetch data from revenue_streams
$query = "SELECT stream_id, stream_name FROM revenue_streams";
$result = mysqli_query($mysqli, $query);

$streams = [];
    while ($row = mysqli_fetch_assoc($result)) {
    $streams[] = $row;
}
//Form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Add_services'])) {
    $service_name = $_POST['service_name'];
    $service_stream_id = $_POST['service_stream_id'];

    // Insert data into revenue_services
    $query = "INSERT INTO revenue_services (service_name, service_stream_id) VALUES (?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("si", $service_name, $service_stream_id);

    if ($stmt->execute()) {
        $success = "Stream Added Successfully";
    } else {
        $err = "Error Adding Stream";
        //echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}


?>

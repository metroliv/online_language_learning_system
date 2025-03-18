<?php
// Include your database configuration
require_once('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $serviceId = mysqli_real_escape_string($mysqli, $_POST['service_id']);
    $serviceName = mysqli_real_escape_string($mysqli, $_POST['service_name']);
    $streamName = mysqli_real_escape_string($mysqli, $_POST['stream_name']);

    // Update the service in the database
    $query = "
        UPDATE revenue_services s
        JOIN revenue_streams r ON s.service_stream_id = r.stream_id
        SET s.service_name = '$serviceName', r.stream_name = '$streamName'
        WHERE s.service_id = '$serviceId'
    ";

    if ($mysqli->query($query)) {
        echo "service updated successfully";
        header('Location: ../views/services.php');
    } else {
        echo "Error: " . $mysqli->error;
    }

    // Update the status of the service to 'Inactive'
    $query = "UPDATE revenue_services SET service_status = 'Inactive' WHERE service_id = '$serviceId'";
    
    if ($mysqli->query($query) === TRUE) {
        echo "service deactivated successfully.";
    } else {
        echo "Error deactivating service: " . $mysqli->error;
    }

    // Redirect back to the main page or wherever needed
    header('Location: ../views/services.php'); // Adjust the path as necessary
    exit();
    } else {
    echo "Invalid request.";
   }

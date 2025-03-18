<?php
session_start();
require_once('../config/config.php');
require_once('../helpers/auth.php');


// Deactivate Service
if (isset($_POST['deactivate_service'])) {
    $service_id = mysqli_real_escape_string($mysqli, $_POST['service_id']);

    // Deactivate query
    $deactivate_query = "UPDATE revenue_services SET status='inactive' WHERE service_id='$service_id'";

    if (mysqli_query($mysqli, $deactivate_query)) {
        $_SESSION['success'] = "Service deactivated successfully.";
    } else {
        $_SESSION['error'] = "Error deactivating service.";
    }

    header('Location: ../views/services.php'); // Redirect back to the services page
    exit();
}


?>

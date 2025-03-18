<?php

require('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['uploadTargetsModal'])) {
        // Check if the file was uploaded without errors
        if (isset($_FILES['targets']) && $_FILES['targets']['error'] == 0) {
            // Define the path where you want to save the file
            $uploadDir = '../storage/'; // Ensure this directory exists and is writable
            $uploadFile = $uploadDir . basename($_FILES['targets']['name']); // Corrected to 'targets'

            // Move the uploaded file to the desired directory
            if (move_uploaded_file($_FILES['targets']['tmp_name'], $uploadFile)) {
                // Execute the SQL file
                $sql = file_get_contents($uploadFile);
                
                // Assuming you have a database connection $mysqli
                if (mysqli_multi_query($mysqli, $sql)) {
                    // Set the success message in the session
                    $_SESSION['success'] = 'SQL file uploaded and executed successfully.';
                } else {
                    // Set the error message in the session
                    $_SESSION['error'] = 'Error executing SQL file: ' . mysqli_error($mysqli);
                }
            } else {
                $_SESSION['error'] = 'Failed to move uploaded file.';
            }
        } else {
            // More detailed error reporting based on error code
            $error = $_FILES['targets']['error'];
            $_SESSION['error'] = "No file uploaded or there was an error uploading the file. Error code: $error";
        }

        // Redirect to the target page to display the message
        header("Location: ../views/revenue_target_setter.php"); 
        exit();
    }
}
?>


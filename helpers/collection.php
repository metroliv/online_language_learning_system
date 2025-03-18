<?php

require_once("../functions/reusableQuery.php");

if (isset($_POST['AddCollection'])) {
    // Check if a specific collector was selected by the receiver
    $collection_user_id = isset($_POST['collection_user_id']) ? mysqli_real_escape_string($mysqli, $_POST['collection_user_id']) : $_SESSION['user_id'];
    
    // Retrieve form data
    $collection_stream_id = mysqli_real_escape_string($mysqli, $_POST['collection_stream_id']);
    $collection_service_id = mysqli_real_escape_string($mysqli, $_POST['collection_service_id']);
    $collection_amount = str_replace(',', '', mysqli_real_escape_string($mysqli, $_POST['collection_amount']));
    $collection_date = mysqli_real_escape_string($mysqli, $_POST['collection_date']); // User-selected date and time
    $collection_location = mysqli_real_escape_string($mysqli, $_POST['collection_location']);
    $collection_assignment = mysqli_real_escape_string($mysqli, $_POST['collection_assignment']);
    $collection_comment = mysqli_real_escape_string($mysqli, $_POST['collection_comment']);
    $collection_ward_id = mysqli_real_escape_string($mysqli, $_SESSION['user_ward_id']);
    $collections_fy = mysqli_real_escape_string($mysqli, $_POST['collections_fy']);
    $collections_month = mysqli_real_escape_string($mysqli, $_POST['collections_month']);

    // Insert collection
    $insert_sql = "INSERT INTO revenue_collections 
                   (collection_user_id, collection_amount, collection_stream_id, collection_date, 
                    collection_location, collection_assignment, collection_service_id, collection_comment, 
                    collection_ward_id, collections_fy, collections_month) 
                   VALUES 
                   ('{$collection_user_id}', '{$collection_amount}', '{$collection_stream_id}', '{$collection_date}', 
                    '{$collection_location}', '{$collection_assignment}', '{$collection_service_id}', 
                    '{$collection_comment}', '{$collection_ward_id}', '{$collections_fy}', '{$collections_month}')";

    if (mysqli_query($mysqli, $insert_sql)) {
        $success = "Revenue collection submitted";
    } else {
        $err = "Error Adding Collection";
    }
}

if (isset($_POST['ExecAddCollection'])) {
    // Ensure a collector was selected
    if (!isset($_POST['collection_user_id']) || empty($_POST['collection_user_id'])) {
        $err = "Error: Please select a collector.";
    } else {
        // Sanitize collector ID and fetch associated ward_id
        $collection_user_id = mysqli_real_escape_string($mysqli, $_POST['collection_user_id']);
        
        // Fetch the ward_id for the selected collector
        $collector_query = mysqli_query($mysqli, "SELECT user_ward_id FROM users WHERE user_id = '{$collection_user_id}'");
        if ($collector_data = mysqli_fetch_assoc($collector_query)) {
            $collection_ward_id = $collector_data['user_ward_id'];
            
            // Retrieve other form data
            $collection_stream_id = mysqli_real_escape_string($mysqli, $_POST['collection_stream_id']);
            $collection_service_id = mysqli_real_escape_string($mysqli, $_POST['collection_service_id']);
            $collection_amount = str_replace(',', '', mysqli_real_escape_string($mysqli, $_POST['collection_amount']));
            $collection_date = mysqli_real_escape_string($mysqli, $_POST['collection_date']); // User-selected date and time
            $collection_location = mysqli_real_escape_string($mysqli, $_POST['collection_location']);
            $collection_assignment = mysqli_real_escape_string($mysqli, $_POST['collection_assignment']);
            $collection_comment = mysqli_real_escape_string($mysqli, $_POST['collection_comment']);
            $collections_fy = mysqli_real_escape_string($mysqli, $_POST['collections_fy']);
            $collections_month = mysqli_real_escape_string($mysqli, $_POST['collections_month']);

            // Insert collection with the selected collector's ward_id
            $insert_sql = "INSERT INTO revenue_collections 
                           (collection_user_id, collection_amount, collection_stream_id, collection_date, 
                            collection_location, collection_assignment, collection_service_id, collection_comment, 
                            collection_ward_id, collections_fy, collections_month) 
                           VALUES 
                           ('{$collection_user_id}', '{$collection_amount}', '{$collection_stream_id}', '{$collection_date}', 
                            '{$collection_location}', '{$collection_assignment}', '{$collection_service_id}', 
                            '{$collection_comment}', '{$collection_ward_id}', '{$collections_fy}', '{$collections_month}')";

            if (mysqli_query($mysqli, $insert_sql)) {
                $success = "Revenue collection submitted";
            } else {
                $err = "Error Adding Collection";
            }
        } else {
            $err = "Error: Invalid collector selected.";
        }
    }
}

// Update Collection
if (isset($_POST['UpdateCollection'])) {
    $collection_id = mysqli_real_escape_string($mysqli, $_POST['collection_id']);
    $collection_service_id  = mysqli_real_escape_string($mysqli, $_POST['collection_service_id']);
    $collection_stream_id  = mysqli_real_escape_string($mysqli, $_POST['collection_stream_id']);
    $collection_amount = str_replace(',', '', mysqli_real_escape_string($mysqli, $_POST['collection_amount']));
    $collection_date = mysqli_real_escape_string($mysqli, $_POST['collection_date']); // User-selected date and time
    $collection_location = mysqli_real_escape_string($mysqli, $_POST['collection_location']);
    $collection_assignment = mysqli_real_escape_string($mysqli, $_POST['collection_assignment']);
    $collection_comment = mysqli_real_escape_string($mysqli, $_POST['collection_comment']);
    $collection_status = 'Pending';

    // Update collection
    $update_sql = "UPDATE revenue_collections 
                   SET collection_service_id = '{$collection_service_id}', 
                       collection_stream_id = '{$collection_stream_id}', 
                       collection_amount = '{$collection_amount}', 
                       collection_date = '{$collection_date}', 
                       collection_location = '{$collection_location}', 
                       collection_assignment = '{$collection_assignment}', 
                       collection_comment = '{$collection_comment}', 
                       collection_status = '{$collection_status}' 
                   WHERE collection_id = '{$collection_id}'";

    if (mysqli_query($mysqli, $update_sql)) {
        $success = "Revenue collection updated";
    } else {
        $err = "Error Preparing Statement";
    }
}
?>

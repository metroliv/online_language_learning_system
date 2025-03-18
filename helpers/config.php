<?php
require('../config/config.php');

// Check if connection is successful
if ($mysqli->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $mysqli->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Log received data for debugging
    error_log("POST Data: " . print_r($_POST, true));
    
    // Retrieve POST data
    $financialYear = $_POST['financialYear'] ?? null;
    $baseDate = $_POST['baseDate'] ?? null;
    $ratios = $_POST['ratios'] ?? null;

    // Validate data
    if (empty($financialYear) || empty($baseDate) || empty($ratios)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    $mysqli->begin_transaction(); // Start transaction for atomic operation
    try {
        // Clear existing configuration in config_table
        $stmt_clear_config = $mysqli->prepare("TRUNCATE TABLE config_table");
        if (!$stmt_clear_config->execute()) {
            throw new Exception('Failed to clear existing configuration.');
        }

        // Clear existing ratios in stream_ratios table
        $stmt_clear_ratios = $mysqli->prepare("TRUNCATE TABLE stream_ratios");
        if (!$stmt_clear_ratios->execute()) {
            throw new Exception('Failed to clear existing stream ratios.');
        }

        // Insert new configuration into config_table
        $stmt_insert_config = $mysqli->prepare("INSERT INTO config_table (fiscal_year, base_date) VALUES (?, ?)");
        $stmt_insert_config->bind_param("ss", $financialYear, $baseDate);
        
        if (!$stmt_insert_config->execute()) {
            throw new Exception('Failed to save configuration.');
        }

        // Prepare and insert stream ratios into stream_ratios table
        $stmt_insert_ratios = $mysqli->prepare("INSERT INTO stream_ratios (stream_id, ratios) VALUES (?, ?)");
        
        foreach ($ratios as $stream_id => $ratio) {
            // Bind the stream ID and the ratio (as CSV string)
            $stmt_insert_ratios->bind_param("is", $stream_id, $ratio);
            if (!$stmt_insert_ratios->execute()) {
                throw new Exception('Failed to save stream ratios for stream ID: ' . $stream_id);
            }
        }

        $mysqli->commit();  // Commit the transaction if everything is fine
        echo json_encode(['success' => true, 'message' => 'Configuration saved successfully.']);

    } catch (Exception $e) {
        // Rollback the transaction on error
        $mysqli->rollback();
        error_log("Transaction failed: " . $e->getMessage());  // Log the error
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

} else {
    // Handle GET request (Fetch existing configuration)
    try {
        $query = "SELECT fiscal_year, base_date FROM config_table LIMIT 1";
        $result = $mysqli->query($query);

        if ($result && $row = $result->fetch_assoc()) {
            echo json_encode([
                'success' => true,
                'fiscal_year' => $row['fiscal_year'],
                'base_date' => $row['base_date'],
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No configuration found.']);
        }
    } catch (Exception $e) {
        error_log("Error fetching configuration: " . $e->getMessage());  // Log the error
        echo json_encode(['success' => false, 'message' => 'Error fetching configuration: ' . $e->getMessage()]);
    }
}

?>

<?php 
require_once('../config/config.php'); // connect to db

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Stream Target Update
    if (isset($_POST['streamtarget_id'])) {
        $id = $_POST['streamtarget_id'];
        $amount = $_POST['streamtarget_amount'];
        
        $query = "UPDATE streamtarget SET streamtarget_amount = ? WHERE streamtarget_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('di', $amount, $id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            echo "Stream target updated successfully.";
        } else {
            echo "Failed to update stream target.";
        }
    }

    // Quarterly Target Update
    if (isset($_POST['collectorquarterlytarget_id'])) {
        $id = $_POST['collectorquarterlytarget_id'];
        $target = $_POST['collectorquarterlytarget_quarter_target'];
        
        $query = "UPDATE collectorquarterlytarget SET collectorquarterlytarget_quarter_target = ? WHERE collectorquarterlytarget_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('di', $target, $id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            echo "Quarterly target updated successfully.";
        } else {
            echo "Failed to update quarterly target.";
        }
    }

    // Monthly Target Update
    if (isset($_POST['collectormonthlytarget_id'])) {
        $id = $_POST['collectormonthlytarget_id'];
        $amount = $_POST['collectormonthlytarget_amount'];
        
        $query = "UPDATE collectormonthlytarget SET collectormonthlytarget_amount = ? WHERE collectormonthlytarget_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('di', $amount, $id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            echo "Monthly target updated successfully.";
        } else {
            echo "Failed to update monthly target.";
        }
    }
}

// Redirect back to the admin view
header("Location: ../partials/system_admin_dashboard.php");
exit;


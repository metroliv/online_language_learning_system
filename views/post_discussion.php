<?php
include '../config/config.php'; // Database connection ($db)
include '../config/auth.php';

// Handle AJAX request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = ["status" => "error", "message" => "An error occurred."];

    // Validate inputs
    if (!empty($_POST['discussion_topic']) && !empty($_POST['discussion_message'])) {
        
        // Sanitize inputs
        $topic = htmlspecialchars(trim($_POST['discussion_topic']));
        $message = htmlspecialchars(trim($_POST['discussion_message']));
        $user_name = $_SESSION['user_name'] ?? 'Anonymous'; // Fetch from session or default

        // Prepare SQL statement
        $query = "INSERT INTO discussions (topic, message, user_name, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "sss", $topic, $message, $user_name);
        
        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            $response = ["status" => "success", "message" => "Discussion posted successfully!"];
        } else {
            $response = ["status" => "error", "message" => "Database error: " . mysqli_error($db)];
        }
    } else {
        $response = ["status" => "error", "message" => "Please fill in all fields."];
    }

    echo json_encode($response);
    exit();
}

<?php
require_once('../config/config.php'); // Ensure config is included

if (isset($_POST['change_password'])) {
    $user_id = $_SESSION['user_id']; // Get user ID from session
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if user ID is valid
    if (!$user_id) {
        die("User ID not set in session.");
    }

    // Prepare and execute the query to get the current user's password
    $stmt = $mysqli->prepare("SELECT user_password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("No user found with the provided ID.");
    }

    $user = $result->fetch_assoc();

    // Hash the old password using SHA1 of MD5
    $hashed_old_password = sha1(md5($old_password));

    // Verify old password
    if ($hashed_old_password === $user['user_password']) {
        // Ensure new password meets the criteria
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{5,}$/', $new_password)) {
            $err = "New password must contain at least one number, one uppercase letter, one lowercase letter, and be at least 5 characters long.";
        } elseif ($hashed_old_password === sha1(md5($new_password))) {
            $err = "You cannot use your old password as the new password.";
        } else {
            // Check if new password matches confirmation
            if ($new_password === $confirm_password) {
                // Hash the new password using SHA1 of MD5
                $hashed_new_password = sha1(md5($new_password));

                // Update password in the database
                $stmt = $mysqli->prepare("UPDATE users SET user_password = ? WHERE user_id = ?");
                $stmt->bind_param("si", $hashed_new_password, $user_id);

                if ($stmt->execute()) {
                    // Store success message in session
                    $_SESSION['success'] = "Password changed successfully.";
                    
                    // Redirect to dashboard
                    header('Location: dashboard.php');
                    exit(); // Ensure no further code is executed after the redirect
                } else {
                    $err = "Error updating password.";
                }
            } else {
                $err = "New password and confirmation do not match.";
            }
        }
    } else {
        $err = "Old password is incorrect.";
    }
}

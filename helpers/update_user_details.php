<?php
session_start();

include('../config/config.php'); // connect to db

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $user_names = $_POST['user_names'];
    $user_personal_number = $_POST['user_personal_number'];
    $user_email = $_POST['user_email'];
    $user_phone_number = $_POST['user_phone_number'];

    $query = "UPDATE users SET 
                user_names = '$user_names',
                user_personal_number = '$user_personal_number',
                user_email = '$user_email',
                user_phone_number = '$user_phone_number'
              WHERE user_id = '$user_id'";

    if (mysqli_query($mysqli, $query)) {
        $_SESSION['success'] = 'Officer Profile updated successfully!';
    } else {
        $_SESSION['error'] = 'Error updating officer Details: ' . mysqli_error($mysqli);
    }

    header("Location: ../views/ward_staff.php"); // Redirect back to the page
    exit();
}


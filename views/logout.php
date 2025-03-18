<?php
session_start();
require_once('../partials/alert.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_destroy();
    session_start(); // Restart session to store alert

    $_SESSION['alert'] = [
        'icon' => 'success',
        'title' => 'Logged Out',
        'message' => 'You have been successfully logged out.',
        'confirm' => false,
        'timer' => 2000
    ];

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Logout Confirmation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="end_session" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <form method="POST">
                    <div class="modal-body text-dark">
                        <img src="../public/img/small-logo.png" height="80px"><br>
                        <h4 class="mt-2">Leaving so soon?</h4>
                        <p>Have you finished exploring the system?</p>
                        <button type="button" class="btn btn-success" onclick="window.location.href='dashboard.php'">No, I'm still exploring</button>
                        <button type="submit" class="btn btn-danger">Yes, I'd like to leave</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Trigger the Modal Automatically -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var logoutModal = new bootstrap.Modal(document.getElementById('end_session'));
            logoutModal.show();
        });
    </script>
</body>
</html>

<?php
session_start();
include '../config/config.php'; // Ensure the correct path
require_once('../partials/alert.php');
include('../partials/head.php');

if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    // Use prepared statement for security
    $stmt = mysqli_prepare($db, "SELECT user_id, user_name, password FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['user_name'];

        // ✅ Use session-based SweetAlert
        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Login Successful',
            'message' => 'Welcome, ' . $user['user_name'] . '!',
            'confirm' => false,
            'timer' => 2000
        ];
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Login Failed',
            'message' => 'Invalid email or password!',
            'confirm' => true,
            'timer' => 3000
        ];
        header("Location: login.php");
        exit();
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: url('https://th.bing.com/th/id/OIP.NOH8bBZLrC_5WpwupOJNkwHaEX?rs=1&pid=ImgDetMain') no-repeat center center fixed;
            background-size: cover;
        }
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-section {
            display: flex;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .login-card {
            width: 50%;
            padding: 30px;
            text-align: center;
        }
        .info-section {
            width: 50%;
            background: #28a745;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            text-align: center;
        }
        .company-logo {
            width: 100px;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 20px;
        }
        .btn-custom {
            border-radius: 20px;
            background: #28a745;
            color: white;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="col-md-8 login-section d-flex">
            <div class="info-section">
                <img src="https://th.bing.com/th/id/OIP.LbeoUsgHDyOyOXuWW55dKwHaE7?rs=1&pid=ImgDetMain" alt="Company Logo" class="company-logo">
                <h2>Welcome to Our Online Language Learning Platform</h2>
                <p>Learn new languages with ease, interact with native speakers, and enhance your communication skills from anywhere in the world.</p>
            </div>
            <div class="login-card">
                <h3 class="mb-4">User Login</h3>
                <form method="POST" action="login.php">
                    <div class="mb-3 text-start">
                        <label class="form-label"><i class="fa fa-envelope"></i> Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label"><i class="fa fa-lock"></i> Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-custom w-100">Login</button>
                    <p class="mt-3">Don't have an account? <a href="register.php">Register</a></p>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

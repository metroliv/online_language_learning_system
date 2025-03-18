<?php
session_start();
include '../config/config.php'; // Ensure the correct path

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

        echo "<script>alert('Login successful! Redirecting to dashboard...'); window.location.href='dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Invalid email or password! Please try again.');</script>";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>User Login</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Login</button>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </form>
</body>
</html>

<?php
include '../config/config.php'; // Ensure the correct path

if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = mysqli_real_escape_string($db, $_POST['user_name']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password

    // Check if email already exists
    $check_email = mysqli_query($db, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check_email) > 0) {
        echo "<script>alert('Email already exists!'); window.location.href='register.php';</script>";
    } else {
        // Insert user into database
        $query = "INSERT INTO users (user_name, email, password) VALUES ('$user_name', '$email', '$password')";
        if (mysqli_query($db, $query)) {
            echo "<script>alert('Registration successful! Redirecting to login...'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error during registration. Please try again!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>User Registration</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="user_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
</body>
</html>

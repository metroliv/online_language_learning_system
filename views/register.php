<?php
session_start();
include '../config/config.php'; // Ensure the correct path
require_once('../partials/alert.php');

if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = mysqli_real_escape_string($db, $_POST['user_name']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $phone_number = mysqli_real_escape_string($db, $_POST['phone_number']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Registration Failed',
            'message' => 'Passwords do not match!',
            'confirm' => true,
            'timer' => 3000
        ];
        header("Location: register.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check_email = mysqli_query($db, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Registration Failed',
            'message' => 'Email already exists!',
            'confirm' => true,
            'timer' => 3000
        ];
        header("Location: register.php");
        exit();
    } else {
        $query = "INSERT INTO users (user_name, email, phone_number, password) VALUES ('$user_name', '$email', '$phone_number', '$hashed_password')";
        if (mysqli_query($db, $query)) {
            $_SESSION['alert'] = [
                'icon' => 'success',
                'title' => 'Registration Successful',
                'message' => 'Redirecting to login...',
                'confirm' => false,
                'timer' => 2000
            ];
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Registration Error',
                'message' => 'Something went wrong. Please try again!',
                'confirm' => true,
                'timer' => 3000
            ];
            header("Location: register.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: url('https://th.bing.com/th/id/OIP.NOH8bBZLrC_5WpwupOJNkwHaEX?rs=1&pid=ImgDetMain') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .carousel img {
            border-radius: 10px;
            max-height: 300px;
            object-fit: cover;
        }
        h4{
            background: rgba(255, 255, 255, 0.9);
        }
        p{
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <!-- Left: Carousel and Benefits -->
            <div class="col-md-6 d-flex align-items-center">
                <div class="text-center w-100">
                    <!-- Bootstrap Carousel -->
                    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="https://th.bing.com/th/id/R.3cf69bfffad32bb4cb4876f2ae1ad15c?rik=%2bnXMkjFjFYuyEw&riu=http%3a%2f%2fclipart-library.com%2fimg%2f680302.jpg&ehk=IhnMJORFA0wON5Jom1M9MKvIJgtrn%2bF411jB13HpAUM%3d&risl=1&pid=ImgRaw&r=0" class="d-block w-100" alt="Exclusive Content">
                            </div>
                            <div class="carousel-item">
                                <img src="https://th.bing.com/th/id/R.34159a69d8a106ec1e7e220dba6d3a02?rik=ZlRBZASY44UPlA&pid=ImgRaw&r=0" class="d-block w-100" alt="Community Support">
                            </div>
                            <div class="carousel-item">
                                <img src="https://th.bing.com/th/id/R.ef1746f871dbe6db313886f72f96af6a?rik=FjTSkVk8nISP%2bw&pid=ImgRaw&r=0" class="d-block w-100" alt="Personalized Experience">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>

                    <!-- Why Join Section -->
                    <h4 class="mt-3">Why Join Us?</h4>
                    <p>🌟 Get personalized recommendations</p>
                    <p>📩 Receive the latest updates</p>
                    <p>🤝 Connect with a thriving community</p>
                    <p>🔒 Secure & private membership</p>
                </div>
            </div>

            <!-- Right: Registration Form -->
            <div class="col-md-6">
                <div class="register-container">
                    <img src="../images/logo.png" alt="Company Logo" class="logo d-block mx-auto mb-3">
                    <h2 class="text-center">Create an Account</h2>
                    <?php include '../partials/alert.php'; ?>
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
                            <label>Phone</label>
                            <input type="text" name="phone_number" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                        <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php

include '../config/config.php';
include '../config/auth.php';
// Fetch user data
$user_name = $_SESSION['user_name'];

// Fetch available languages
$languages_query = "SELECT * FROM languages";
$languages_result = mysqli_query($db, $languages_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Language Learning System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="lessons.php">Lessons</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Profile</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Welcome Message -->
    <div class="mt-4">
        <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>! 👋</h2>
        <p>Select a language and start learning today!</p>
    </div>

    <!-- Display Available Languages -->
    <div class="row mt-4">
        <?php while ($language = mysqli_fetch_assoc($languages_result)): ?>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($language['lang_name']); ?></h5>
                        <p class="card-text">Start learning <?php echo htmlspecialchars($language['lang_name']); ?> now!</p>
                        <a href="lessons.php?lang_id=<?php echo $language['lang_id']; ?>" class="btn btn-primary">Start Learning</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Footer -->
    <footer class="mt-5 text-center text-muted">
        &copy; <?php echo date("Y"); ?> Language Learning System. All Rights Reserved.
    </footer>

</body>
</html>

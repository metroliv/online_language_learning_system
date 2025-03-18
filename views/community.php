<?php
include '../config/config.php'; // Database connection ($db)
include '../config/auth.php';
include('../partials/head.php');

// Check if database connection exists
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Pagination settings
$limit = 10;  // Number of discussions per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch discussions with pagination
$query = "SELECT * FROM discussions ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community - Online Language Learning</title>
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <?php include('../partials/preloader.php'); ?>

        <!-- Navbar -->
        <?php include('../partials/header.php'); ?>

        <!-- Main Sidebar Container -->
        <?php include('../partials/admin_sidenav1.php'); ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="container mt-4">
                <h2 class="text-primary"><i class="fas fa-users"></i> Community Discussions</h2>
                <p>Join the conversation, ask questions, and interact with fellow learners.</p>

                <!-- Discussion Form -->
                <div class="card">
                    <div class="card-body">
                        <form id="discussionForm">
                            <div class="form-group">
                                <label for="discussion_topic">Topic:</label>
                                <input type="text" class="form-control" id="discussion_topic" name="discussion_topic" required>
                            </div>
                            <div class="form-group">
                                <label for="discussion_message">Message:</label>
                                <textarea class="form-control" id="discussion_message" name="discussion_message" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Post</button>
                        </form>

                        <!-- Loader -->
                        <div id="loading" style="display:none; text-align:center; margin-top:10px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Community Posts -->
                <div class="mt-4">
                    <h4>Recent Discussions</h4>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="card mt-3">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title text-info">' . htmlspecialchars($row['topic']) . '</h5>';
                            echo '<p class="card-text">' . htmlspecialchars($row['message']) . '</p>';
                            echo '<p class="text-muted">Posted by ' . htmlspecialchars($row['user_name']) . ' on ' . htmlspecialchars($row['created_at']) . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="text-muted">No discussions yet. Start one now!</p>';
                    }

                    // Pagination buttons
                    $total_query = "SELECT COUNT(*) as total FROM discussions";
                    $total_result = mysqli_query($db, $total_query);
                    $total_row = mysqli_fetch_assoc($total_result);
                    $total_pages = ceil($total_row['total'] / $limit);

                    echo '<nav aria-label="Page navigation">';
                    echo '<ul class="pagination justify-content-center mt-3">';
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">';
                        echo '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
                        echo '</li>';
                    }
                    echo '</ul>';
                    echo '</nav>';
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../public/js/bootstrap.bundle.min.js"></script>
    
    <script>
    $(document).ready(function(){
        $("#discussionForm").submit(function(event){
            event.preventDefault(); // Prevent form from submitting normally

            // Show loading animation
            $("#loading").show();

            $.ajax({
                url: "post_discussion.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    $("#loading").hide(); // Hide loading

                    if (response.status === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            location.reload(); // Refresh page to show new discussion
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: response.message,
                            showConfirmButton: true
                        });
                    }
                },
                error: function() {
                    $("#loading").hide();
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                    });
                }
            });
        });
    });
    </script>
</body>
</html>
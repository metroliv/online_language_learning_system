<?php
include '../config/config.php';
include '../config/auth.php';
include('../partials/head.php');
require_once('../partials/alert.php');

// Fetch user data
$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

// Fetch available languages
$languages_query = "SELECT * FROM languages";
$languages_result = mysqli_query($db, $languages_query);

// Fetch recent activity
$recent_query = "SELECT lessons.title FROM lessons 
                 INNER JOIN user_progress ON lessons.lesson_id = user_progress.lesson_id 
                 WHERE user_progress.user_id = " . intval($user_id) . " 
                 ORDER BY user_progress.progress_id DESC LIMIT 5";
$recent_result = mysqli_query($db, $recent_query);

// Fetch user progress
$query = "SELECT lessons.title, lessons.lesson_id, user_progress.score, user_progress.status 
          FROM user_progress
          JOIN lessons ON user_progress.lesson_id = lessons.lesson_id
          WHERE user_progress.user_id = " . intval($user_id);
$result = mysqli_query($db, $query);
?>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">

		<!-- Preloader -->
		<?php include('../partials/preloader.php'); ?>

		<!-- Navbar -->
		<?php include('../partials/header.php'); ?>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<?php
		/* Load Specific side Based On User Access Level */
		
			/* Admin */
			include('../partials/admin_sidenav1.php');
	
		 ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">

		

		
	<!-- ./wrapper -->

        <!-- Welcome Message -->
        <h2 id="greeting"></h2>
        <h3>Welcome, <?php echo htmlspecialchars($user_name); ?>! 👋</h3>
        
        <!-- Display Available Languages -->
        <div class="row">
            <?php while ($language = mysqli_fetch_assoc($languages_result)): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"> <?php echo htmlspecialchars($language['lang_name']); ?> </h5>
                            <a href="lessons.php?lang_id=<?php echo $language['lang_id']; ?>" class="btn btn-primary">Start Learning</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Recent Activity -->
        <h4 class="mt-4">Recent Activity</h4>
        <ul class="list-group">
            <?php while ($recent = mysqli_fetch_assoc($recent_result)): ?>
                <li class="list-group-item"> <?php echo htmlspecialchars($recent['title']); ?> </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <!-- Progress Modal -->
    <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="progressModalLabel">Your Progress</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Lesson</th>
                                <th>Score</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo $row['score'] !== null ? $row['score'] : 'N/A'; ?></td>
                                    <td>
                                        <?php if ($row['status'] === 'completed') {
                                            echo '<span class="badge bg-success">Completed</span>';
                                        } elseif ($row['status'] === 'in_progress') {
                                            echo '<span class="badge bg-warning text-dark">In Progress</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">Not Started</span>';
                                        } ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    </div>
		<!-- /.content-wrapper -->
		<?php include('../partials/footer.php'); ?>

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
		</aside>
		<!-- /.control-sidebar -->
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

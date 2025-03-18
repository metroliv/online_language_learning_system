<?php
include '../config/config.php';
include '../config/auth.php';
include('../partials/head.php');

if (!isset($_GET['lang_id'])) {
    die("Language not selected!");
}

$lang_id = intval($_GET['lang_id']);

// Fetch language name
$lang_query = "SELECT lang_name FROM languages WHERE lang_id = $lang_id";
$lang_result = mysqli_query($db, $lang_query);
$lang = mysqli_fetch_assoc($lang_result);

if (!$lang) {
    die("Invalid language selection!");
}

// Fetch lessons
$lessons_query = "SELECT * FROM lessons WHERE lang_id = $lang_id";
$lessons_result = mysqli_query($db, $lessons_query);
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

    <h2><?php echo htmlspecialchars($lang['lang_name']); ?> Lessons</h2>
    <p>Select a lesson to begin:</p>

    <ul class="list-group">
        <?php while ($lesson = mysqli_fetch_assoc($lessons_result)): ?>
            <li class="list-group-item">
                <a href="lesson.php?lesson_id=<?php echo $lesson['lesson_id']; ?>">
                    <?php echo htmlspecialchars($lesson['title']); ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </div>
        </div>
</body>
</html>

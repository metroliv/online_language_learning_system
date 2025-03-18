<?php
include '../config/config.php';
include '../config/auth.php';
include('../partials/head.php');

if (!isset($_GET['lesson_id'])) {
    die("Lesson not selected!");
}

$lesson_id = intval($_GET['lesson_id']);

// Fetch lesson details
$lesson_query = "SELECT * FROM lessons WHERE lesson_id = $lesson_id";
$lesson_result = mysqli_query($db, $lesson_query);
$lesson = mysqli_fetch_assoc($lesson_result);

if (!$lesson) {
    die("Invalid lesson selection!");
}
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
            <h2 class="lesson-title"><?php echo htmlspecialchars($lesson['title']); ?></h2>
            <hr>
            <p class="lesson-content"><?php echo nl2br(htmlspecialchars($lesson['content'])); ?></p>

            <?php if (!empty($lesson['audio_link'])): ?>
                <div class="text-center mt-4">
                    <audio controls class="w-100">
                        <source src="<?php echo htmlspecialchars($lesson['audio_link']); ?>" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                </div>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="quizzes.php?lesson_id=<?php echo $lesson_id; ?>" class="btn btn-primary btn-custom me-2">📖 Take Quiz</a>
                <a href="lessons.php?lang_id=<?php echo $lesson['lang_id']; ?>" class="btn btn-secondary btn-custom">⬅ Back to Lessons</a>
            </div>
        </div>
    </div>

</body>
</html>

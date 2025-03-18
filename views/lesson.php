<?php
include '../config/config.php';
include '../config/auth.php';

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

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($lesson['title']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

    <h2><?php echo htmlspecialchars($lesson['title']); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($lesson['content'])); ?></p>

    <?php if (!empty($lesson['audio_link'])): ?>
        <audio controls>
            <source src="<?php echo htmlspecialchars($lesson['audio_link']); ?>" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    <?php endif; ?>

    <a href="quizzes.php?lesson_id=<?php echo $lesson_id; ?>" class="btn btn-primary mt-3">Take Quiz</a>
    <a href="lessons.php?lang_id=<?php echo $lesson['lang_id']; ?>" class="btn btn-secondary mt-3">Back to Lessons</a>

</body>
</html>

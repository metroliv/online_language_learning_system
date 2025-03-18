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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .lesson-container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .lesson-title {
            font-weight: bold;
            color: #007bff;
            text-align: center;
        }
        .lesson-content {
            font-size: 18px;
            line-height: 1.6;
        }
        .btn-custom {
            transition: all 0.3s ease-in-out;
        }
        .btn-custom:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="lesson-container">
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

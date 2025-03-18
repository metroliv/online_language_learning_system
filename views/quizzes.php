<?php
include '../config/config.php';
include '../config/auth.php';

if (!isset($_GET['lesson_id'])) {
    die("Lesson not selected!");
}

$lesson_id = intval($_GET['lesson_id']);

// Fetch quizzes
$quizzes_query = "SELECT * FROM quizzes WHERE lesson_id = $lesson_id";
$quizzes_result = mysqli_query($db, $quizzes_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lesson Quiz</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

    <h2>Quiz</h2>
    <form method="POST" action="submit_quiz.php">
        <?php while ($quiz = mysqli_fetch_assoc($quizzes_result)): ?>
            <p><strong><?php echo htmlspecialchars($quiz['question']); ?></strong></p>
            <input type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="A"> <?php echo htmlspecialchars($quiz['option_a']); ?><br>
            <input type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="B"> <?php echo htmlspecialchars($quiz['option_b']); ?><br>
            <input type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="C"> <?php echo htmlspecialchars($quiz['option_c']); ?><br>
            <input type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="D"> <?php echo htmlspecialchars($quiz['option_d']); ?><br>
            <hr>
        <?php endwhile; ?>
        <button type="submit" class="btn btn-success">Submit Quiz</button>
    </form>

</body>
</html>

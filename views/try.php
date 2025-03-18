<?php
include '../config/config.php';
include '../config/auth.php';

if (!isset($_GET['lesson_id']) || empty($_GET['lesson_id'])) {
    die("<div class='alert alert-danger text-center mt-5'>Error: Lesson not selected!</div>");
}

$lesson_id = intval($_GET['lesson_id']);

// Fetch quizzes
$quizzes_query = "SELECT * FROM quizzes WHERE lesson_id = $lesson_id";
$quizzes_result = mysqli_query($db, $quizzes_query);

// Check if quizzes exist
if (!$quizzes_result || mysqli_num_rows($quizzes_result) == 0) {
    die("<div class='alert alert-warning text-center mt-5'>No quizzes available for this lesson.</div>");
}

$question_count = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson Quiz</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: #f8f9fa;
        }
        .quiz-container {
            max-width: 800px;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .question-card {
            transition: 0.3s;
            cursor: pointer;
        }
        .question-card:hover {
            background: #f1f1f1;
        }
        .progress-bar {
            height: 8px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="quiz-container mx-auto p-4">
        <h2 class="text-center mb-4">📝 Take the Quiz</h2>
        
        <!-- Progress Bar -->
        <div class="progress mb-4">
            <div id="progressBar" class="progress-bar bg-success" role="progressbar" style="width: 0%;"></div>
        </div>

        <form method="POST" action="submit_quiz.php">
            <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">

            <?php while ($quiz = mysqli_fetch_assoc($quizzes_result)): ?>
                <?php $question_count++; ?>
                <div class="card question-card p-3 mb-3">
                    <p><strong>Q<?php echo $question_count; ?>: <?php echo htmlspecialchars($quiz['question']); ?></strong></p>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="A" required>
                        <label class="form-check-label"><?php echo htmlspecialchars($quiz['option_a']); ?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="B">
                        <label class="form-check-label"><?php echo htmlspecialchars($quiz['option_b']); ?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="C">
                        <label class="form-check-label"><?php echo htmlspecialchars($quiz['option_c']); ?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="D">
                        <label class="form-check-label"><?php echo htmlspecialchars($quiz['option_d']); ?></label>
                    </div>
                </div>
            <?php endwhile; ?>

            <button type="submit" class="btn btn-success w-100 mt-3">✅ Submit Quiz</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalQuestions = <?php echo $question_count; ?>;
        const progressBar = document.getElementById('progressBar');

        document.querySelectorAll('input[type="radio"]').forEach(input => {
            input.addEventListener('change', function() {
                let answered = new Set();
                document.querySelectorAll('input[type="radio"]:checked').forEach(item => {
                    answered.add(item.name);
                });

                let progress = (answered.size / totalQuestions) * 100;
                progressBar.style.width = progress + '%';
            });
        });
    });
</script>

</body>
</html>

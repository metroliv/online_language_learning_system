<?php
include '../config/config.php';
include '../config/auth.php';

if (!isset($_GET['lesson_id']) || empty($_GET['lesson_id'])) {
    die("Error: Lesson not selected!");
}

$lesson_id = intval($_GET['lesson_id']);

// Fetch quizzes
$quizzes_query = "SELECT * FROM quizzes WHERE lesson_id = $lesson_id";
$quizzes_result = mysqli_query($db, $quizzes_query);

// Check if quizzes exist
if (!$quizzes_result || mysqli_num_rows($quizzes_result) == 0) {
    die("No quizzes available for this lesson.");
}

$quizzes = [];
while ($quiz = mysqli_fetch_assoc($quizzes_result)) {
    $quizzes[] = $quiz;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lesson Quiz</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .quiz-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .correct { color: green; }
        .wrong { color: red; }
        .hidden { display: none; }
    </style>
</head>
<body class="container mt-5">
    <div class="quiz-container">
        <h2 class="text-center">Lesson Quiz</h2>
        <div id="timer" class="text-end text-danger fw-bold">Time Left: <span id="time">60</span> sec</div>
        
        <form id="quizForm" method="POST" action="submit_quiz.php">
            <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">
            
            <div id="quizContent">
                <?php foreach ($quizzes as $index => $quiz): ?>
                    <div class="quiz-question hidden" id="question_<?php echo $index; ?>">
                        <p><strong><?php echo htmlspecialchars($quiz['question']); ?></strong></p>
                        <input type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="A"> <?php echo htmlspecialchars($quiz['option_a']); ?><br>
                        <input type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="B"> <?php echo htmlspecialchars($quiz['option_b']); ?><br>
                        <input type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="C"> <?php echo htmlspecialchars($quiz['option_c']); ?><br>
                        <input type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="D"> <?php echo htmlspecialchars($quiz['option_d']); ?><br>
                        <p class="feedback hidden"></p>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-3">
                <button type="button" id="prevBtn" class="btn btn-secondary" disabled>Previous</button>
                <button type="button" id="nextBtn" class="btn btn-primary">Next</button>
                <button type="submit" id="submitBtn" class="btn btn-success hidden">Submit</button>
            </div>
        </form>
    </div>
    
    <script>
        let currentQuestion = 0;
        let totalQuestions = <?php echo count($quizzes); ?>;
        let timer = 60;
        
        function showQuestion(index) {
            $(".quiz-question").addClass("hidden");
            $(`#question_${index}`).removeClass("hidden");
            $("#prevBtn").prop("disabled", index === 0);
            $("#nextBtn").toggleClass("hidden", index === totalQuestions - 1);
            $("#submitBtn").toggleClass("hidden", index !== totalQuestions - 1);
        }
        
        function startTimer() {
            let interval = setInterval(() => {
                if (timer <= 0) {
                    clearInterval(interval);
                    alert("Time's up! Submitting your answers.");
                    $("#quizForm").submit();
                }
                $("#time").text(timer);
                timer--;
            }, 1000);
        }
        
        $(document).ready(() => {
            showQuestion(0);
            startTimer();
            
            $("#nextBtn").click(() => {
                if (currentQuestion < totalQuestions - 1) {
                    currentQuestion++;
                    showQuestion(currentQuestion);
                }
            });
            
            $("#prevBtn").click(() => {
                if (currentQuestion > 0) {
                    currentQuestion--;
                    showQuestion(currentQuestion);
                }
            });
        });
    </script>
</body>
</html>

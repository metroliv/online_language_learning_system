<?php
include '../config/config.php';
include '../config/auth.php';
include('../partials/head.php');

if (!isset($_POST['lesson_id']) || !isset($_POST['quiz'])) {
    die("Error: Missing lesson_id or quiz answers.");
}

$user_id = $_SESSION['user_id']; // Ensure user is logged in
$lesson_id = intval($_POST['lesson_id']);
$submitted_answers = $_POST['quiz'];

// Fetch correct answers from the database
$query = "SELECT quiz_id, question, option_a, option_b, option_c, option_d, correct_option FROM quizzes WHERE lesson_id = $lesson_id";
$result = mysqli_query($db, $query);

if (!$result) {
    die("Database error: " . mysqli_error($db));
}

$correct_answers = [];
$quiz_data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $correct_answers[$row['quiz_id']] = $row['correct_option'];
    $quiz_data[$row['quiz_id']] = $row;
}

// Check answers and calculate score
$total_questions = count($correct_answers);
$correct_count = 0;

foreach ($correct_answers as $quiz_id => $correct_option) {
    if (isset($submitted_answers[$quiz_id]) && $submitted_answers[$quiz_id] === $correct_option) {
        $correct_count++;
    }
}

// Calculate percentage score
$score = ($correct_count / $total_questions) * 100;

// Update user progress
$progress_query = "INSERT INTO user_progress (user_id, lesson_id, status, score) 
                   VALUES ($user_id, $lesson_id, 'Completed', $score)
                   ON DUPLICATE KEY UPDATE status='Completed', score=$score";
mysqli_query($db, $progress_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Quiz Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
        }
        .quiz-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .quiz-card:hover {
            transform: scale(1.02);
        }
        .correct {
            background-color: #d4edda !important;
            color: #155724 !important;
            border-left: 5px solid #28a745;
        }
        .wrong {
            background-color: #f8d7da !important;
            color: #721c24 !important;
            border-left: 5px solid #dc3545;
        }
        .progress-bar {
            background-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Quiz Results</h2>
            <p class="lead">Here's how you performed!</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress mb-4">
            <div class="progress-bar" role="progressbar" style="width: <?php echo $score; ?>%;" aria-valuenow="<?php echo $score; ?>" aria-valuemin="0" aria-valuemax="100">
                <?php echo number_format($score, 2); ?>%
            </div>
        </div>

        <p><strong>Total Questions:</strong> <?php echo $total_questions; ?></p>
        <p><strong>Correct Answers:</strong> <?php echo $correct_count; ?></p>

        <h3 class="mt-4">Review Your Answers</h3>

        <?php foreach ($quiz_data as $quiz_id => $quiz): ?>
            <div class="card quiz-card mb-3 p-3 
                <?php 
                    $user_answer = $submitted_answers[$quiz_id] ?? 'N/A';
                    echo ($user_answer === $quiz['correct_option']) ? 'correct' : 'wrong';
                ?>
            ">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($quiz['question']); ?></h5>
                    
                    <?php
                        $options = ['A' => $quiz['option_a'], 'B' => $quiz['option_b'], 'C' => $quiz['option_c'], 'D' => $quiz['option_d']];
                    ?>

                    <?php foreach ($options as $key => $option): ?>
                        <p class="p-2 
                            <?php 
                                if ($key === $quiz['correct_option']) echo 'bg-success text-white'; 
                                elseif ($key === $user_answer) echo 'bg-danger text-white'; 
                            ?>">
                            <?php echo "$key) " . htmlspecialchars($option); ?>
                        </p>
                    <?php endforeach; ?>

                    <p>
                        <?php if ($user_answer === $quiz['correct_option']): ?>
                            <span class="fw-bold text-success">✔ Correct</span>
                        <?php else: ?>
                            <span class="fw-bold text-danger">❌ Wrong</span>
                            <br>
                            <span class="fw-bold">Correct Answer:</span> <?php echo $quiz['correct_option'] . ") " . htmlspecialchars($options[$quiz['correct_option']]); ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="text-center">
            <a href="dashboard.php" class="btn btn-primary mt-3">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

<?php
include '../config/config.php';
include '../config/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $lesson_id = $_POST['lesson_id'];
    $score = 0;
    $total_questions = count($_POST['questions']);

    foreach ($_POST['questions'] as $quiz_id => $selected_option) {
        $selected_option = mysqli_real_escape_string($db, $selected_option);
        
        // Get the correct answer from the database
        $query = "SELECT correct_option FROM quizzes WHERE quiz_id = $quiz_id";
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($result);
        
        if ($row && $selected_option == $row['correct_option']) {
            $score++;
        }
    }

    $final_score = ($score / $total_questions) * 100; // Convert to percentage

    // Check if user progress exists
    $check_progress = "SELECT * FROM user_progress WHERE user_id = $user_id AND lesson_id = $lesson_id";
    $progress_result = mysqli_query($db, $check_progress);

    if (mysqli_num_rows($progress_result) > 0) {
        // Update existing progress
        $update_progress = "UPDATE user_progress SET status='Completed', score=$final_score WHERE user_id = $user_id AND lesson_id = $lesson_id";
        mysqli_query($db, $update_progress);
    } else {
        // Insert new progress record
        $insert_progress = "INSERT INTO user_progress (user_id, lesson_id, status, score) VALUES ($user_id, $lesson_id, 'Completed', $final_score)";
        mysqli_query($db, $insert_progress);
    }

    header("Location: lesson.php?lesson_id=$lesson_id&status=success&score=$final_score");
    exit();
} else {
    echo "Error: Unauthorized access or invalid request.";
}
?>

<?php
include '../config/config.php';
include '../config/auth.php';
include('../partials/head.php');

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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .quiz-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .hidden { display: none; }
    </style>
</head>
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
        <h2 class="text-center">Lesson Quiz</h2>
        <div id="timer" class="text-end text-danger fw-bold">Time Left: <span id="time">60</span> sec</div>
        
        <form id="quizForm" method="POST" action="submit_quiz.php">
            <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">
            
            <div id="quizContent">
                <?php foreach ($quizzes as $index => $quiz): ?>
                    <div class="quiz-question hidden" id="question_<?php echo $index; ?>" required>
                        <p><strong><?php echo htmlspecialchars($quiz['question']); ?></strong></p>
                        <input type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="A"> <?php echo htmlspecialchars($quiz['option_a']); ?><br>
                        <input type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="B"> <?php echo htmlspecialchars($quiz['option_b']); ?><br>
                        <input type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="C"> <?php echo htmlspecialchars($quiz['option_c']); ?><br>
                        <input type="radio" name="quiz[<?php echo $quiz['quiz_id']; ?>]" value="D"> <?php echo htmlspecialchars($quiz['option_d']); ?><br>
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
                    Swal.fire({
                        icon: "warning",
                        title: "Time's up!",
                        text: "Your quiz is being submitted.",
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        $("#quizForm").submit();
                    });
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

            // Handle form submission via AJAX
            $("#quizForm").submit(function(e) {
                e.preventDefault();
                
                $.ajax({
                    type: "POST",
                    url: "submit_quiz.php",
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: "success",
                            title: "Quiz Submitted",
                            text: "Your answers have been submitted successfully!",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.location.href = "submit_quiz.php";
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: "error",
                            title: "Submission Failed",
                            text: "An error occurred. Please try again."
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>

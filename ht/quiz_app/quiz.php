<?php
session_start();
require 'db.php';

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// On first visit or after finishing quiz, fetch 5 random questions
if (!isset($_SESSION['quiz_questions']) || isset($_POST['restart'])) {
    // Get 5 random questions
    $stmt = $pdo->query("SELECT * FROM questions ORDER BY RAND() LIMIT 5");
    $questions = $stmt->fetchAll();

    $_SESSION['quiz_questions'] = $questions;
    $_SESSION['current_question'] = 0;
    $_SESSION['score'] = 0;
}

// Process submitted answer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    $current = $_SESSION['current_question'];
    $questions = $_SESSION['quiz_questions'];
    $selected = (int)$_POST['answer'];

    $correct = $questions[$current]['correct_option'];

    if ($selected === $correct) {
        $_SESSION['score']++;
    }
    $_SESSION['current_question']++;
}

$questions = $_SESSION['quiz_questions'];
$current = $_SESSION['current_question'];
$total = count($questions);

if ($current >= $total) {
    // Quiz finished
    $score = $_SESSION['score'];
    $username = $_SESSION['username'];

    // Clear session quiz data but keep user logged in
    unset($_SESSION['quiz_questions'], $_SESSION['current_question'], $_SESSION['score']);
    ?>

    <!DOCTYPE html>
    <html>
    <head><title>Quiz Result</title></head>
    <body>
        <h2>Quiz Completed!</h2>
        <p>Hello <b><?php echo htmlspecialchars($username); ?></b>, your score is: <b><?php echo $score; ?></b> out of <?php echo $total; ?></p>
        <form method="post" action="quiz.php">
            <button name="restart" value="1">Try Again</button>
        </form>
        <br>
        <a href="logout.php">Logout</a>
    </body>
    </html>

    <?php
    exit();
}

// Show current question form
$question = $questions[$current];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Question <?php echo $current + 1; ?></title>
</head>
<body>
    <h2>Question <?php echo $current + 1; ?> of <?php echo $total; ?></h2>
    <p><?php echo htmlspecialchars($question['question_text']); ?></p>
    <form method="post" action="quiz.php">
        <label><input type="radio" name="answer" value="1" required> <?php echo htmlspecialchars($question['option1']); ?></label><br>
        <label><input type="radio" name="answer" value="2"> <?php echo htmlspecialchars($question['option2']); ?></label><br>
        <label><input type="radio" name="answer" value="3"> <?php echo htmlspecialchars($question['option3']); ?></label><br>
        <label><input type="radio" name="answer" value="4"> <?php echo htmlspecialchars($question['option4']); ?></label><br><br>
        <button type="submit">Submit Answer</button>
    </form>
    <br>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>

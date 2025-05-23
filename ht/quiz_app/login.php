<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');

    if ($username === '') {
        $error = "Please enter a username.";
    } else {
        // Insert user if not exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user) {
            $insert = $pdo->prepare("INSERT INTO users (username) VALUES (?)");
            $insert->execute([$username]);
            $userId = $pdo->lastInsertId();
        } else {
            $userId = $user['id'];
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;

        // Redirect to quiz page
        header('Location: quiz.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login for Quiz</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" action="login.php">
        <label>Username: <input type="text" name="username" required></label><br><br>
        <button type="submit">Start Quiz</button>
    </form>
</body>
</html>

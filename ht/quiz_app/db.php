<?php
$host = 'localhost';
$db   = 'quiz_app';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connection successful!";
} catch (PDOException $e) {
    die("❌ DB connection failed: " . $e->getMessage());
}
?>

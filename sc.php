<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stu";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database and select it
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Create required tables
$conn->query("CREATE TABLE IF NOT EXISTS stu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    roll_number VARCHAR(20),
    gender VARCHAR(10),
    department VARCHAR(50),
    grade VARCHAR(5),
    course VARCHAR(50)
)");

$conn->query("CREATE TABLE IF NOT EXISTS search_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filter_type VARCHAR(50),
    filter_value VARCHAR(50),
    time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Handle student form submission
if (isset($_POST['submit_student'])) {
    $name = $_POST['name'];
    $roll = $_POST['roll_number'];
    $gender = $_POST['gender'];
    $department = $_POST['department'];
    $grade = $_POST['grade'];
    $course = $_POST['course'];

    $sql = "INSERT INTO stu (name, roll_number, gender, department, grade, course)
            VALUES ('$name', '$roll', '$gender', '$department', '$grade', '$course')";
    $conn->query($sql);
}

// Handle filter request
if (isset($_POST['filter'])) {
    $filterType = $_POST['filter_type'];
    $filterValue = $_POST['filter_value'];

    $_SESSION['filters'][$filterType] = $filterValue;
    setcookie("filter_$filterType", $filterValue, time() + 86400, "/");

    $stmt = $conn->prepare("INSERT INTO search_logs (filter_type, filter_value) VALUES (?, ?)");
    $stmt->bind_param("ss", $filterType, $filterValue);
    $stmt->execute();
    $stmt->close();
}

// Clear filters
if (isset($_POST['clear_filters'])) {
    unset($_SESSION['filters']);
    foreach ($_COOKIE as $key => $val) {
        if (strpos($key, 'filter_') === 0) {
            setcookie($key, '', time() - 3600, "/");
        }
    }
}

// Build WHERE clause
$where = [];
if (isset($_SESSION['filters'])) {
    foreach ($_SESSION['filters'] as $key => $value) {
        $where[] = "$key = '$value'";
    }
}
$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

$result = null;
if (!empty($_SESSION['filters'])) {
    $result = $conn->query("SELECT * FROM stu $whereSQL");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Entry & Filter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f9f9f9;
            color: #333;
        }

        h2 {
            color: #005792;
            border-bottom: 2px solid #005792;
            padding-bottom: 5px;
        }

        form {
            margin-bottom: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #005792;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #003f63;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #005792;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        hr {
            border: 0;
            height: 1px;
            background: #ccc;
            margin: 30px 0;
        }

        p {
            margin: 5px 0;
        }

        strong {
            color: #005792;
        }
    </style>
</head>
<body>
    <h2>Enter Student Details</h2>
    <form method="post">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="roll_number" placeholder="Roll Number" required>
        <select name="gender" required>
            <option value="">Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <select name="department" required>
    <option value="">Select Department</option>
    <option value="CSE">CSE</option>
    <option value="IT">IT</option>
    <option value="ECE">ECE</option>
    <option value="MECH">MECH</option>
</select>
        
        <select name="grade" required>
    <option value="">Select Grade</option>
    <option value="A">A</option>
    <option value="B">B</option>
    <option value="C">C</option>
    <option value="D">D</option>
</select>
        
        <select name="course" required>
    <option value="">Select Course</option>
    <option value="B.Tech">B.Tech</option>
    <option value="M.Tech">M.Tech</option>
    <option value="MCA">MCA</option>
</select>
<input type="submit" name="submit_student" value="Add Student">
    </form>

    <hr>

    <h2>Filter Students</h2>
    <form method="post">
        <select name="filter_type" required>
            <option value="">Select Filter Type</option>
            <option value="gender">Gender</option>
            <option value="department">Department</option>
            <option value="course">Course</option>
            <option value="grade">Grade</option>
        </select>
        <input type="text" name="filter_value" placeholder="Enter value" required>
        <input type="submit" name="filter" value="Apply Filter">
    </form>

    <form method="post" style="margin-top: 10px;">
        <input type="submit" name="clear_filters" value="Clear Filters">
    </form>

    <h3>Active Filters:</h3>
    <?php
    if (!empty($_SESSION['filters'])) {
        foreach ($_SESSION['filters'] as $k => $v) {
            echo "<p><strong>$k:</strong> $v</p>";
        }
    } else {
        echo "<p>No filters applied.</p>";
    }
    ?>

    <?php if (!empty($_SESSION['filters'])): ?>
        <h2>Filtered Students</h2>
        <table>
            <tr>
                <th>ID</th><th>Name</th><th>Roll No</th><th>Gender</th><th>Department</th><th>Grade</th><th>Course</th>
            </tr>
            <?php
            if ($result && $result->num_rows > 0):
                while($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['roll_number'] ?></td>
                <td><?= $row['gender'] ?></td>
                <td><?= $row['department'] ?></td>
                <td><?= $row['grade'] ?></td>
                <td><?= $row['course'] ?></td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="7">No students found matching the filters.</td></tr>
            <?php endif; ?>
        </table>
    <?php endif; ?>
</body>
</html>

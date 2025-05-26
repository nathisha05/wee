<?php

$host = "localhost";
$user = "root"; 
$pass = ""; 
$db = "student";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("❌ Database Connection Failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $roll_no = $_POST["roll_no"]; 
    
    $email = $_POST["email"];
    $fathername = $_POST["fathername"];
    $mothername = $_POST["mothername"];
    $phonenumber = $_POST["phonenumber"];
    $gender = $_POST["gender"];
    $dob = $_POST["dob"];
    $age = $_POST["age"];
    $address = $_POST["address"];
    $bloodgroup = $_POST["bloodgroup"];

    $sql = "INSERT INTO students (name, roll_no, email, fathername, mothername, phonenumber, gender, dob, age, address, bloodgroup)
            VALUES ('$name', '$roll_no', '$email', '$fathername', '$mothername', '$phonenumber', '$gender', '$dob', '$age', '$address', '$bloodgroup')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green; text-align:center;'>✅ Student Registered Successfully!</p>";
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Error: " . $conn->error . "</p>";
    }
}


$search_results = '';
if (isset($_GET["roll_no"])) {
    $roll_no = $_GET["roll_no"];
    $sql = "SELECT * FROM students WHERE roll_no = '$roll_no'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $search_results .= "<h2>Search Results</h2>";
        $search_results .= "<table><tr><th>Roll No</th><th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Gender</th><th>Date of Birth</th><th>Age</th><th>Blood Group</th></tr>";
        while ($row = $result->fetch_assoc()) {
            $search_results .= "<tr>
                    <td>" . $row["roll_no"] . "</td>
                    <td>" . $row["name"] . "</td>
                    <td>" . $row["email"] . "</td>
                    <td>" . $row["phonenumber"] . "</td>
                    <td>" . $row["address"] . "</td>
                    <td>" . $row["gender"] . "</td>
                    <td>" . $row["dob"] . "</td>
                    <td>" . $row["age"] . "</td>
                    <td>" . $row["bloodgroup"] . "</td>
                  </tr>";
        }
        $search_results .= "</table>";
    } else {
        $search_results .= "<p style='color:red; text-align:center;'>No student found with Roll Number: '$roll_no'.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration & Search</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #ece9e6, #ffffff);
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            font-size: 28px;
            font-weight: bold;
            margin: 30px 0 10px;
            color: #333;
        }

        form {
            background-color: #ffffff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin-bottom: 30px;
        }

        input, select, textarea, button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            font-size: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            transition: 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #28a745;
            outline: none;
        }

        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            font-weight: bold;
        }

        button:hover {
            background-color: #218838;
        }

        table {
            width: 90%;
            max-width: 1000px;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        h2 {
            margin-top: 40px;
            color: #333;
        }
    </style>
</head>
<body>

    <div class="header">Student Registration</div>

    <form method="POST">
        <input type="text" name="roll_no" placeholder="Enter Roll Number" required>
        <input type="text" name="name" placeholder="Enter Name" required>
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="text" name="fathername" placeholder="Father's Name" required>
        <input type="text" name="mothername" placeholder="Mother's Name" required>
        <input type="text" name="phonenumber" placeholder="Phone Number" required>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <input type="date" name="dob" required>
        <input type="number" name="age" placeholder="Age" required min="1">
        <textarea name="address" placeholder="Enter Address" required></textarea>
        <input type="text" name="bloodgroup" placeholder="Blood Group" required>
        <button type="submit">Register Student</button>
    </form>

    <div class="header">Search Student by Roll Number</div>
    <form method="GET">
        <input type="text" name="roll_no" placeholder="Enter Roll Number to Search" required>
        <button type="submit">Search</button>
    </form>

    <?= $search_results; ?>

</body>
</html>

<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $status = $_POST['status'];

    $sql = "INSERT INTO employees (name, email, department, status) VALUES ('$name', '$email', '$department', '$status')";

    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
</head>
<body>
    <h2>Add Employee</h2>
    <form method="POST">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>

        <label for="department">Department:</label><br>
        <select id="department" name="department" required>
            <option value="STEM">STEM</option>
            <option value="ABM">ABM</option>
            <option value="HUMSS">HUMSS</option>
        </select><br>

        <label for="status">Status:</label><br>
        <select id="status" name="status" required>
            <option value="FULL TIME">Full Time</option>
            <option value="PART TIME">Part Time</option>
        </select><br><br>

        <input type="submit" value="Add Employee">
    </form>
</body>
</html>

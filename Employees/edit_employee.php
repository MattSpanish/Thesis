<?php
include 'db.php';
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $status = $_POST['status'];

    $sql = "UPDATE employees SET name='$name', email='$email', department='$department', status='$status' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    $sql = "SELECT * FROM employees WHERE id=$id";
    $result = $conn->query($sql);
    $employee = $result->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
</head>
<body>
    <h2>Edit Employee</h2>
    <form method="POST">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $employee['name']; ?>" required><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo $employee['email']; ?>" required><br>

        <label for="department">Department:</label><br>
        <select id="department" name="department" required>
            <option value="STEM" <?php if ($employee['department'] == 'STEM') echo 'selected'; ?>>STEM</option>
            <option value="ABM" <?php if ($employee['department'] == 'ABM') echo 'selected'; ?>>ABM</option>
            <option value="HUMSS" <?php if ($employee['department'] == 'HUMSS') echo 'selected'; ?>>HUMSS</option>
        </select><br>

        <label for="status">Status:</label><br>
        <select id="status" name="status" required>
            <option value="FULL TIME" <?php if ($employee['status'] == 'FULL TIME') echo 'selected'; ?>>Full Time</option>
            <option value="PART TIME" <?php if ($employee['status'] == 'PART TIME') echo 'selected'; ?>>Part Time</option>
        </select><br><br>

        <input type="submit" value="Update Employee">
    </form>
</body>
</html>

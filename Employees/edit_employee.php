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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Edit Employee</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" class="p-4 border rounded bg-light">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $employee['name']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $employee['email']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="department">Department:</label>
                        <select class="form-control" id="department" name="department" required>
                            <option value="STEM" <?php if ($employee['department'] == 'STEM') echo 'selected'; ?>>STEM</option>
                            <option value="ABM" <?php if ($employee['department'] == 'ABM') echo 'selected'; ?>>ABM</option>
                            <option value="HUMSS" <?php if ($employee['department'] == 'HUMSS') echo 'selected'; ?>>HUMSS</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="FULL TIME" <?php if ($employee['status'] == 'FULL TIME') echo 'selected'; ?>>Full Time</option>
                            <option value="PART TIME" <?php if ($employee['status'] == 'PART TIME') echo 'selected'; ?>>Part Time</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Update Employee</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

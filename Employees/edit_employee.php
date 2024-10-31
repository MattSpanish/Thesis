<?php
include 'db.php';
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $status = $_POST['status'];
    $subjects = $_POST['subject'];
    $gender = $_POST['gender'];

    $sql = "UPDATE employees SET name='$name', email='$email', department='$department', status='$status', subjects='$subjects', gender='$gender' WHERE id=$id";

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
    <style>
        body {
            background-color: #f4f7fc;
        }
        .container {
            max-width: 700px;
        }
        .form-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            text-align: center;
        }
        .form-container {
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
            padding: 30px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .form-control:focus {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Form Header -->
        <div class="form-header">
            <h2>Edit Employee</h2>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="MALE" <?php echo $employee['gender'] == 'MALE' ? 'selected' : ''; ?>>MALE</option>
                        <option value="FEMALE" <?php echo $employee['gender'] == 'FEMALE' ? 'selected' : ''; ?>>FEMALE</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="subject">Subjects:</label>
                    <select class="form-control" id="subject" name="subject" required>
                        <option value="">Select Subjects</option>
                        <option value="BUSINESS MATHEMATICS" <?php echo $employee['subjects'] == 'BUSINESS MATHEMATICS' ? 'selected' : ''; ?>>BUSINESS MATHEMATICS</option>
                        <option value="KOMUNIKASYON AT PANANALIKSIK SA WIKA AT KULTURANG PILIPINO" <?php echo $employee['subjects'] == 'KOMUNIKASYON AT PANANALIKSIK SA WIKA AT KULTURANG PILIPINO' ? 'selected' : ''; ?>>KOMUNIKASYON AT PANANALIKSIK SA WIKA AT KULTURANG PILIPINO</option>
                        <option value="GENERAL MATHEMATICS" <?php echo $employee['subjects'] == 'GENERAL MATHEMATICS' ? 'selected' : ''; ?>>GENERAL MATHEMATICS</option>
                        <option value="ORAL COMMUNICATION" <?php echo $employee['subjects'] == 'ORAL COMMUNICATION' ? 'selected' : ''; ?>>ORAL COMMUNICATION</option>
                        <option value="ORGANIZATION AND MANAGEMENT" <?php echo $employee['subjects'] == 'ORGANIZATION AND MANAGEMENT' ? 'selected' : ''; ?>>ORGANIZATION AND MANAGEMENT</option>
                        <option value="PERSONALITY DEVELOPMENT" <?php echo $employee['subjects'] == 'PERSONALITY DEVELOPMENT' ? 'selected' : ''; ?>>PERSONALITY DEVELOPMENT</option>
                        <option value="PHYSICAL EDUCATION AND HEALTH 1" <?php echo $employee['subjects'] == 'PHYSICAL EDUCATION AND HEALTH 1' ? 'selected' : ''; ?>>PHYSICAL EDUCATION AND HEALTH 1</option>
                        <option value="UNDERSTANDING CULTURE, SOCIETY & POLITICS" <?php echo $employee['subjects'] == 'UNDERSTANDING CULTURE, SOCIETY & POLITICS' ? 'selected' : ''; ?>>UNDERSTANDING CULTURE, SOCIETY & POLITICS</option>
                        <option value="21ST CENTURY LITERATURE FROM THE PHILIPPINES AND THE WORLD" <?php echo $employee['subjects'] == '21ST CENTURY LITERATURE FROM THE PHILIPPINES AND THE WORLD' ? 'selected' : ''; ?>>21ST CENTURY LITERATURE FROM THE PHILIPPINES AND THE WORLD</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="department">Department:</label>
                    <select class="form-control" id="department" name="department" required>
                        <option value="">Select department</option>
                        <option value="STEM" <?php echo $employee['department'] == 'STEM' ? 'selected' : ''; ?>>STEM</option>
                        <option value="ABM" <?php echo $employee['department'] == 'ABM' ? 'selected' : ''; ?>>ABM</option>
                        <option value="HUMSS" <?php echo $employee['department'] == 'HUMSS' ? 'selected' : ''; ?>>HUMSS</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="">Select status</option>
                        <option value="FULL TIME" <?php echo $employee['status'] == 'FULL TIME' ? 'selected' : ''; ?>>Full Time</option>
                        <option value="PART TIME" <?php echo $employee['status'] == 'PART TIME' ? 'selected' : ''; ?>>Part Time</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Update Employee</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

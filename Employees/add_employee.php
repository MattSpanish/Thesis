<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $status = $_POST['status'];
    $subjects = $_POST['subject'];
    $gender = $_POST['gender'];

    // Insert employee into the employees table
    $sql_employee = "INSERT INTO employees (name, email, department, status, subjects, gender) VALUES ('$name', '$email', '$department', '$status', '$subjects', '$gender')";

    if ($conn->query($sql_employee) === TRUE) {
        // Get the last inserted employee ID
        $employee_id = $conn->insert_id;

        // Insert initial time tracking record for the new employee
        $sql_time = "INSERT INTO time_tracking (employee_id, regular, overtime, sick_leave, pto, paid_holiday) VALUES ('$employee_id', 0, 0, 0, 0, 0)";
        
        if ($conn->query($sql_time) === TRUE) {
            // Redirect to employee list page
            header('Location: index.php');
            exit;
        } else {
            echo "Error with time tracking record: " . $sql_time . "<br>" . $conn->error;
        }
    } else {
        echo "Error adding employee: " . $sql_employee . "<br>" . $conn->error;
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
    <!-- Bootstrap CSS -->
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
            <h2>Add Employee</h2>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" required>
                </div>
                
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="MALE">MALE</option>
                        <option value="FEMALE">FEMALE</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required>
                </div>

                <div class="form-group">
                    <label for="subject">Subjects:</label>
                    <select class="form-control" id="subject" name="subject" required>
                        <option value="">Select Subjects</option>
                        <option value="BUSINESS MATHEMATICS">BUSINESS MATHEMATICS</option>
                        <option value="KOMUNIKASYON AT PANANALIKSIK SA WIKA AT KULTURANG PILIPINO">KOMUNIKASYON AT PANANALIKSIK SA WIKA AT KULTURANG PILIPINO</option>
                        <option value="GENERAL MATHEMATICS">GENERAL MATHEMATICS</option>
                        <option value="ORAL COMMUNICATION">ORAL COMMUNICATION</option>
                        <option value="ORGANIZATION AND MANAGEMENT">ORGANIZATION AND MANAGEMENT</option>
                        <option value="PERSONALITY DEVELOPMENT">PERSONALITY DEVELOPMENT</option>
                        <option value="PHYSICAL EDUCATION AND HEALTH 1">PHYSICAL EDUCATION AND HEALTH 1</option>
                        <option value="UNDERSTANDING CULTURE, SOCIETY & POLITICS">UNDERSTANDING CULTURE, SOCIETY & POLITICS</option>
                        <option value="21ST CENTURY LITERATURE FROM THE PHILIPPINES AND THE WORLD">21ST CENTURY LITERATURE FROM THE PHILIPPINES AND THE WORLD</option>

                    </select>
                </div>

                <div class="form-group">
                    <label for="department">Department:</label>
                    <select class="form-control" id="department" name="department" required>
                        <option value="">Select department</option>
                        <option value="STEM">STEM</option>
                        <option value="ABM">ABM</option>
                        <option value="HUMSS">HUMSS</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="">Select status</option>
                        <option value="FULL TIME">Full Time</option>
                        <option value="PART TIME">Part Time</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Add Employee</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

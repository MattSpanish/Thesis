<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $status = $_POST['status'];

    // Insert employee into the employees table
    $sql_employee = "INSERT INTO employees (name, email, department, status) VALUES ('$name', '$email', '$department', '$status')";

    if ($conn->query($sql_employee) === TRUE) {
        // Get the last inserted employee ID
        $employee_id = $conn->insert_id;

        // Insert initial time tracking record for the new employee
        $sql_time = "INSERT INTO time_tracking (employee_id, regular, overtime, sick_leave, pto, paid_holiday, total_hours) VALUES ('$employee_id', 0, 0, 0, 0, 0, 0)";
        
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Add Employee</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" class="p-4 border rounded bg-light">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="department">Department:</label>
                        <select class="form-control" id="department" name="department" required>
                            <option value="STEM">STEM</option>
                            <option value="ABM">ABM</option>
                            <option value="HUMSS">HUMSS</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="FULL TIME">Full Time</option>
                            <option value="PART TIME">Part Time</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Add Employee</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

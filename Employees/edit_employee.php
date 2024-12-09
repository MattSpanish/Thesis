<?php
include 'db.php';

// Database connection
$servername = "localhost"; // Change to your server name
$username = "root";        // Change to your database username
$password = "";            // Change to your database password
$dbname = "enrollment_db"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Employee ID not provided.");
}

// Sanitize and validate the ID
$id = $conn->real_escape_string($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $id_no = $conn->real_escape_string($_POST['id_no']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $middle_name = $conn->real_escape_string($_POST['middle_name']);
    $department = $conn->real_escape_string($_POST['department']);
    $position = $conn->real_escape_string($_POST['position']);
    $date_hired = $conn->real_escape_string($_POST['date_hired']);
    $years_of_service = (int)$_POST['years_of_service']; // Cast to integer for safety
    $ranking = $conn->real_escape_string($_POST['ranking']);
    $status = $conn->real_escape_string($_POST['status']);

    // Update query (use id_no for the update)
    $sql = "UPDATE historical_data SET 
                id_no = '$id_no', 
                last_name = '$last_name', 
                first_name = '$first_name', 
                middle_name = '$middle_name', 
                department = '$department', 
                position = '$position', 
                date_hired = '$date_hired', 
                years_of_service = $years_of_service, 
                ranking = '$ranking', 
                status = '$status' 
            WHERE id_no = '$id'";  // Fix here: using id_no in the WHERE clause

    if ($conn->query($sql) === TRUE) {
        // Redirect on successful update
        header('Location: index.php');
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    // Fetch the employee record for the given ID
    $sql = "SELECT * FROM historical_data WHERE id_no = '$id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $historical_data = $result->fetch_assoc();
    } else {
        die("Error: No employee found with ID number $id.");
    }
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="form-header">
            <h2>Edit Employee</h2>
        </div>
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label for="id_no">ID Number:</label>
                    <input type="text" class="form-control" id="id_no" name="id_no" value="<?php echo htmlspecialchars($historical_data['id_no']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($historical_data['last_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($historical_data['first_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="middle_name">Middle Name:</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($historical_data['middle_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="department">Department:</label>
                    <input type="text" class="form-control" id="department" name="department" value="<?php echo htmlspecialchars($historical_data['department']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="position">Position:</label>
                    <input type="text" class="form-control" id="position" name="position" value="<?php echo htmlspecialchars($historical_data['position']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="date_hired">Date Hired:</label>
                    <input type="date" class="form-control" id="date_hired" name="date_hired" value="<?php echo htmlspecialchars($historical_data['date_hired']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="years_of_service">Years of Service:</label>
                    <input type="number" class="form-control" id="years_of_service" name="years_of_service" value="<?php echo htmlspecialchars($historical_data['years_of_service']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="ranking">Ranking:</label>
                    <input type="text" class="form-control" id="ranking" name="ranking" value="<?php echo htmlspecialchars($historical_data['ranking']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="ACTIVE" <?php echo $historical_data['status'] == 'ACTIVE' ? 'selected' : ''; ?>>Active</option>
                        <option value="INACTIVE" <?php echo $historical_data['status'] == 'INACTIVE' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Update</button>
            </form>
        </div>
    </div>
</body>
</html>
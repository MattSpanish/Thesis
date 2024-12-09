<?php
// Include database connection
include 'db.php';

$servername = "localhost"; // Change to your server name
$username = "root";        // Change to your database username
$password = "";            // Change to your database password
$dbname = "enrollment_db"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Fetch employee records from the database
$sql = "SELECT id_no, last_name, first_name, middle_name, department, position, date_hired, years_of_service, ranking, status FROM historical_data";
$result = $conn->query($sql);

if (!$result) {
    die("SQL error: " . $conn->error);
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    die("Error: Employee ID not provided.");
}

// Get the employee ID safely
$employee_id = intval($_GET['id']); // Ensure it's an integer

// Now delete the employee using the provided ID
$deleteEmployeeSQL = "DELETE FROM historical_data WHERE id_no = ?";
$stmt = $conn->prepare($deleteEmployeeSQL);

if ($stmt === false) {
    die("Failed to prepare SQL statement: " . $conn->error);
}

// Bind the parameter and execute
$stmt->bind_param("i", $employee_id);

// Only perform redirection if the deletion is successful
try {
    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        die("Error occurred while trying to delete the employee: " . $stmt->error);
    }
} catch (Exception $e) {
    die("Unexpected error: " . $e->getMessage());
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<?php
// Include database connection
include 'db.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    die("Error: Employee ID not provided.");
}

// Get the employee ID
$employee_id = intval($_GET['id']);

// Proceed with the deletion process
// First, delete related records from time_tracking
$deleteTimeTrackingSQL = "DELETE FROM time_tracking WHERE employee_id = ?";
$stmt = $conn->prepare($deleteTimeTrackingSQL);
$stmt->bind_param("i", $employee_id);
$stmt->execute();

// Now delete the employee
$deleteEmployeeSQL = "DELETE FROM employees WHERE id = ?";
$stmt = $conn->prepare($deleteEmployeeSQL);
$stmt->bind_param("i", $employee_id);
$stmt->execute();

// Close the statement and connection
$stmt->close();
$conn->close();

// Redirect or display a success message
header("Location: index.php");
exit();
?>

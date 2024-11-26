<?php
// update_status.php
require_once '../signin&signout/config.php';
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$task_id = $_POST['task_id'];
$status = $_POST['status'];

// Sanitize the inputs
$task_id = mysqli_real_escape_string($conn, $task_id);
$status = mysqli_real_escape_string($conn, $status);

// Ensure status is valid
$valid_statuses = ['complete', 'pending', 'due'];
if (!in_array($status, $valid_statuses)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid status']);
    exit;
}

// Update the task status in the database
$query = "UPDATE tasks SET status = ? WHERE id = ? AND employee_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'sii', $status, $task_id, $_SESSION['id']);
$success = mysqli_stmt_execute($stmt);

if ($success) {
    echo json_encode(['status' => 'success', 'message' => 'Task status updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
}
mysqli_stmt_close($stmt);

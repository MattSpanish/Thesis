<?php
require_once '../signin&signout/config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

// Validate POST data
if (isset($_POST['task_id']) && isset($_POST['status'])) {
    $task_id = mysqli_real_escape_string($conn, $_POST['task_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Ensure task_id is numeric
    if (!is_numeric($task_id)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid task ID.']);
        exit;
    }

    // Ensure status is a valid value
    $validStatuses = ['complete', 'pending', 'due'];
    if (!in_array($status, $validStatuses)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid status value.']);
        exit;
    }

    // Update the task status in the database
    $query = "UPDATE tasks SET status = '$status' WHERE id = '$task_id' AND employee_id = '{$_SESSION['id']}'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Task status updated successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to update task status.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}

// Close the database connection
mysqli_close($conn);

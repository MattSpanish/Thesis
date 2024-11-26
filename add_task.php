<?php
require 'signin&signout/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $task_name = $_POST['task_name'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    if (empty($employee_id) || empty($task_name) || empty($due_date) || empty($status)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO tasks (task_name, employee_id, due_date, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $task_name, $employee_id, $due_date, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Task added successfully!'); window.location.href = 'task.php';</script>";
    } else {
        echo "<script>alert('Error: Could not add task.'); window.history.back();</script>";
    }

    $stmt->close();
}
$conn->close();
?>

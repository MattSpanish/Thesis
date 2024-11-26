<?php
require_once 'signin&signout/config.php';

// Fetch task data from the database
$query = "SELECT id, task_name, status FROM tasks";
$result = mysqli_query($conn, $query);

$tasks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tasks[] = $row;
}

mysqli_free_result($result);
mysqli_close($conn);

echo json_encode($tasks);
?>

<?php
// Database connection
require_once '../signin&signout/config.php';
session_start(); // Start the session

// Assuming the user is logged in and their user ID is stored in the session
if (!isset($_SESSION['id'])) {
    header('Location: ../signin&signout/login.php');
    exit;
}
$id = mysqli_real_escape_string($conn, $_SESSION['id']); // Secure the user ID

// Fetch task data from the database with employee name for the logged-in user
$query = "SELECT t.id, t.task_name, t.due_date, t.status, u.fullname AS employee_name
          FROM tasks t
          JOIN users u ON t.employee_id = u.id
          WHERE t.employee_id = '$id'"; // Only fetch tasks assigned to the logged-in user
$tasksResult = mysqli_query($conn, $query);

if (!$tasksResult) {
    error_log("Database query failed: " . mysqli_error($conn));
    die("Error fetching tasks. Please try again later.");
}

// Prepare tasks array and counts for summaries
$tasks = [];
$taskCounts = ['complete' => 0, 'pending' => 0, 'due' => 0, 'total' => 0];

while ($row = mysqli_fetch_assoc($tasksResult)) {
    $tasks[] = $row;
    $taskCounts['total']++;
    if (isset($taskCounts[$row['status']])) {
        $taskCounts[$row['status']]++;
    }
}

// Free the result set and close the connection
mysqli_free_result($tasksResult);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #E3EED4; /* Light Accent */
        }
        .container-fluid {
            margin-top: 20px;
        }
        .header-container {
            display: flex;
            align-items: center;
        }
        .back-button {
            margin-right: 10px;
        }
       
        .card {
            border: none;
            transition: 0.3s;
            cursor: pointer;
            margin: 10px 0;
            background-color: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card:hover {
            background-color: #28a745;
            color: white;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #375534; /* Dark Green */
        }
        .card-text {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .badge-complete {
            background-color: #28a745;
            color: white;
        }
        .badge-pending {
            background-color: #ffc107;
            color: black;
        }
        .badge-due {
            background-color: #dc3545;
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="header-container">
                    <a href="profDASHBOARD.php" class="btn btn-white back-button">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Task Summary Cards -->
        <div class="row text-center mb-4">
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Task Left</h5>
                        <p id="task-left" class="card-text display-4"><?= $taskCounts['total'] - ($taskCounts['complete'] + $taskCounts['pending'] + $taskCounts['due']); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Complete Task</h5>
                        <p id="complete-task" class="card-text display-4"><?= $taskCounts['complete']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pending Task</h5>
                        <p id="pending-task" class="card-text display-4"><?= $taskCounts['pending']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Due Task</h5>
                        <p id="due-task" class="card-text display-4"><?= $taskCounts['due']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Employee</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
    <?php if (empty($tasks)): ?>
        <tr>
            <td colspan="4" class="text-center">No tasks assigned.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= htmlspecialchars($task['task_name']); ?></td>
                <td><?= htmlspecialchars($task['employee_name']); ?></td>
                <td><?= htmlspecialchars($task['due_date']); ?></td>
                <td>
                    <select class="status-dropdown" data-task-id="<?= $task['id']; ?>">
                        <option value="complete" <?= $task['status'] === 'complete' ? 'selected' : ''; ?>>Complete</option>
                        <option value="pending" <?= $task['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="due" <?= $task['status'] === 'due' ? 'selected' : ''; ?>>Due</option>
                    </select>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>

            </table>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdowns = document.querySelectorAll('.status-dropdown');

        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('change', function () {
                const taskId = this.getAttribute('data-task-id');
                const newStatus = this.value;

                // Send an AJAX request to update the task status
                fetch('update_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `task_id=${taskId}&status=${newStatus}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message); // Show the alert
                            window.location.reload(); // Refresh the page
                        } else {
                            alert(data.message || 'Failed to update status.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            });
        });
    });
</script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Require database configuration file
require_once 'signin&signout/config.php';

// Fetch all registered users to populate dropdown
$userQuery = "SELECT id, fullname FROM users";
$userResult = mysqli_query($conn, $userQuery);
if (!$userResult) {
    die("Error fetching users: " . mysqli_error($conn));
}

// Fetch task data from the database
$query = "SELECT t.task_name, u.fullname AS employee_name, t.due_date, t.status 
          FROM tasks t 
          JOIN users u ON t.employee_id = u.id";
$tasksResult = mysqli_query($conn, $query);

if (!$tasksResult) {
    die("Database query failed: " . mysqli_error($conn));
}

// Prepare tasks array for use in JavaScript
$tasks = [];
while ($row = mysqli_fetch_assoc($tasksResult)) {
    $tasks[] = $row;
}

// Free the result set
mysqli_free_result($tasksResult);
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
                    <a href="hr_dashboard.php" class="btn btn-white back-button">
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
                        <p id="task-left" class="card-text display-4">0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Complete Task</h5>
                        <p id="complete-task" class="card-text display-4">0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pending Task</h5>
                        <p id="pending-task" class="card-text display-4">0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Due Task</h5>
                        <p id="due-task" class="card-text display-4">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Task Button -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Employee Tasks</h4>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addTaskModal">
                <i class="fas fa-plus"></i> Add Task
            </button>
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
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['task_name']); ?></td>
                            <td><?= htmlspecialchars($task['employee_name']); ?></td>
                            <td><?= htmlspecialchars($task['due_date']); ?></td>
                            <td>
                                <span class="badge <?= $task['status'] === 'complete' ? 'badge-complete' : ($task['status'] === 'pending' ? 'badge-pending' : 'badge-due'); ?>">
                                    <?= htmlspecialchars($task['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="add_task.php" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTaskModalLabel">Add Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="employee_id">Assign To</label>
                            <select class="form-control" id="employee_id" name="employee_id" required>
                                <option value="">Select Employee</option>
                                <?php while ($user = mysqli_fetch_assoc($userResult)): ?>
                                    <option value="<?= htmlspecialchars($user['id']); ?>">
                                        <?= htmlspecialchars($user['fullname']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="task_name">Task Name</label>
                            <input type="text" class="form-control" id="task_name" name="task_name" required>
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="complete">Complete</option>
                                <option value="due">Due</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Task</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tasks = <?= json_encode($tasks); ?>;
            updateTaskSummary(tasks);
        });

        function updateTaskSummary(tasks) {
            let complete = 0, pending = 0, due = 0;

            tasks.forEach(task => {
                if (task.status === 'complete') {
                    complete++;
                } else if (task.status === 'pending') {
                    pending++;
                } else if (task.status === 'due') {
                    due++;
                }
            });

            document.getElementById("complete-task").innerText = complete;
            document.getElementById("pending-task").innerText = pending;
            document.getElementById("due-task").innerText = due;
            document.getElementById("task-left").innerText = tasks.length - (complete + pending + due);
        }
    </script>
</body>
</html>


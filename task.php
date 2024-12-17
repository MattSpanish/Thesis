<?php
// Require database configuration file
require_once 'signin&signout/config.php';

// SQL query to update task statuses to 'due' if the due date has passed and the status is not 'complete'
$updateQuery = "UPDATE tasks 
                SET status = 'due' 
                WHERE due_date < CURDATE() AND status != 'complete'";

if (!mysqli_query($conn, $updateQuery)) {
    die("Error updating task statuses: " . mysqli_error($conn));
}

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
            background-color: #375534; /* Dark green */
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-right: 10px;
            border-radius: 4px; /* Square with slight rounding */
        }
        .back-button:hover {
            background-color: #2b4435; /* Slightly darker shade */
            transform: scale(1.05); /* Slight zoom effect */
        }
        .back-button i {
            font-size: 16px;
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
                    <a href="hr_dashboard.php" class="back-button">
                        <i class="fas fa-arrow-left"></i>
                    </a>
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
                        <select class="form-control" id="status" name="status" readonly>
                            <option value="pending" selected>Pending</option>
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

    <!-- Bootstrap and jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

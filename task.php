<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include "Employees/db.php"; // Adjust path if necessary

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch tasks from the database
$result = $conn->query("SELECT employees.name AS employee_name, tasks.task_name, tasks.due_date, tasks.status FROM tasks JOIN employees ON tasks.employee_id = employees.id");

// Fetch tasks into a variable to display them in the table
$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
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

        .logo {
            height: 50px;
        }

        /* Task Summary Cards */
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

        /* Task Status Badges */
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

        /* Table Styling */
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .card-title {
                font-size: 16px;
            }

            .card-text {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .card-title {
                font-size: 14px;
            }

            .card-text {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Logo and Back Arrow Section -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="header-container">
                    <!-- Back Button (Arrow) -->
                    <a href="hr_dashboard.php" class="btn btn-white back-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16" stroke-width="2">
                            <path fill-rule="evenodd" d="M5.854 4.146a.5.5 0 0 1 0 .708L2.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0z"/>
                            <path fill-rule="evenodd" d="M10 8a.5.5 0 0 1-.5.5H2a.5.5 0 0 1 0-1h7.5A.5.5 0 0 1 10 8z"/>
                        </svg>
                    </a>
                    <!-- Logo -->
                    <img src="../signin&signout/assets1/img/logo.png" alt="MindVenture Logo" class="logo">
                </div>
            </div>
        </div>

        <!-- Task Summary Cards -->
        <div class="row text-center mb-4">
            <div class="col-md-3 mb-3">
                <a href="#" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Task Left</h5>
                            <p id="task-left" class="card-text display-4">0</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="#" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Complete Task</h5>
                            <p id="complete-task" class="card-text display-4">0</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="#" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pending Task</h5>
                            <p id="pending-task" class="card-text display-4">0</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="#" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Due Task</h5>
                            <p id="due-task" class="card-text display-4">0</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Employee Task Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ASSIGNED TO</th>
                        <th>TASK</th>
                        <th>DUE DATE</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody id="employeeTable">
                    <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['employee_name']); ?></td>
                        <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                        <td><?php echo htmlspecialchars($task['due_date']); ?></td>
                        <td class="status">
                            <span class="badge <?php echo ($task['status'] == 'Complete' ? 'badge-complete' : ($task['status'] == 'Due' ? 'badge-due' : 'badge-pending')); ?>" onclick="toggleStatus(this)">
                                <?php echo htmlspecialchars($task['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Script for Task Management -->
        <script>
            // Function to toggle task status
            function toggleStatus(element) {
                if (element.classList.contains('badge-pending')) {
                    element.classList.remove('badge-pending');
                    element.classList.add('badge-complete');
                    element.textContent = 'Complete';
                } else if (element.classList.contains('badge-complete')) {
                    element.classList.remove('badge-complete');
                    element.classList.add('badge-due');
                    element.textContent = 'Due';
                } else if (element.classList.contains('badge-due')) {
                    element.classList.remove('badge-due');
                    element.classList.add('badge-pending');
                    element.textContent = 'Pending';
                }
                updateTaskSummary();
            }

            function updateTaskSummary() {
                let totalTasks = document.querySelectorAll('#employeeTable tr').length;
                let completeTasks = document.querySelectorAll('.badge-complete').length;
                let pendingTasks = document.querySelectorAll('.badge-pending').length;
                let dueTasks = document.querySelectorAll('.badge-due').length;

                document.getElementById('task-left').textContent = pendingTasks + dueTasks;
                document.getElementById('complete-task').textContent = completeTasks;
                document.getElementById('pending-task').textContent = pendingTasks;
                document.getElementById('due-task').textContent = dueTasks;
            }

            updateTaskSummary();
        </script>
    </div>
</body>
</html>

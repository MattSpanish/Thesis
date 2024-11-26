

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom media query for small devices */
        @media (max-width: 576px) {
            .status {
                font-size: 0.8rem;
            }
        }

        /* Additional badge styles for responsiveness */
        .badge {
            font-size: 1rem;
            padding: 0.5em;
            cursor: pointer;
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

        /* Flexbox for logo and back button */
        .header-container {
            display: flex;
            align-items: center;
        }

        .back-button {
            margin-right: 10px;
        }

        /* Table styling for proper header alignment */
        .table th {
            text-align: center;
            vertical-align: middle;
            width: 25%;
        }

        .table td.status {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Main Container -->
    <div class="container-fluid mt-4">
        <!-- Logo and Back Arrow Section -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="header-container">
                    <!-- Back Button (Arrow) -->
                    <a href="profDASHBOARD.php" class="btn btn-white back-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16" stroke-width="2">
                            <path fill-rule="evenodd" d="M5.854 4.146a.5.5 0 0 1 0 .708L2.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0z"/>
                            <path fill-rule="evenodd" d="M10 8a.5.5 0 0 1-.5.5H2a.5.5 0 0 1 0-1h7.5A.5.5 0 0 1 10 8z"/>
                        </svg>
                    </a>
                    <!-- Logo -->
                    <div class="image">
                        <a class="navbar-brand brand-logo" href="hr_dashboard.php">
                            <img src="signin&signout/assets1/img/logo.png" alt="logo" class="img-fluid" />
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Header -->
        <header class="mb-4 text-left">
            <h1>Task</h1>
        </header>

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
                            <h5 class="card-title">Pending Task</h5>
                            <p id="complete-task" class="card-text display-4">0</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="#" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Complete Task</h5>
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
                        <th>TASK</th>
                        <th>PENDING</th>
                        <th>DUE DATE</th>
                        <th>STATUS</th>
                    </tr>
              
            </table>
        </div>

        <!-- Script for Adding Employee and Toggle Status -->
        <script>
            // Function to toggle task status
            function toggleStatus(element) {
                // Get current status and change accordingly
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

                // Update task summary counts
                updateTaskSummary();
            }

            // Function to update task summary counts
            function updateTaskSummary() {
                let totalTasks = document.querySelectorAll('#employeeTable tr').length;
                let completeTasks = document.querySelectorAll('.badge-complete').length;
                let pendingTasks = document.querySelectorAll('.badge-pending').length;
                let dueTasks = document.querySelectorAll('.badge-due').length;

                // Set task summary values
                document.getElementById('task-left').textContent = pendingTasks + dueTasks;
                document.getElementById('complete-task').textContent = completeTasks;
                document.getElementById('pending-task').textContent = pendingTasks;
                document.getElementById('due-task').textContent = dueTasks;
            }

            // Initial task summary count
            updateTaskSummary();
        </script>
    </div>
</body>
</html>

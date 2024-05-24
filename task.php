<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="taskstyles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row mb-3">
            <div class="col-12 d-flex align-items-center fixed-top-custom">
            <div class="image">
            <a class="navbar-brand brand-logo" href="Dashboard.php"><img src="signin&singout/assets1/img/logo.png" alt="logo" /></a>
        </div>
  </div>         
</div>
    <div class="container mt-5">
        <header class="mb-4">
            <h1>Task</h1>
        </header>
        <div class="row text-left mb-4">
            <div class="col-md-3">
                <a href="#" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Task Left</h5>
                            <p class="card-text display-4">10</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="#" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Complete Task</h5>
                            <p class="card-text display-4">3</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="#" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pending Task</h5>
                            <p class="card-text display-4">4</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="#" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Due Task</h5>
                            <p class="card-text display-4">2</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="text-right">
            <button id="addEmployeeBtn" class="btn btn-success">Add Employee</button>
        </div>
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
                <tr>
                    <td>Nic Parreno</td>
                    <td>Task 1</td>
                    <td>May 29, 2024</td>
                    <td class="status pending">Pending</td>
                </tr>
                <tr>
                    <td>Jam Riomulin</td>
                    <td>Task 2</td>
                    <td>May 20, 2024</td>
                    <td class="status complete">Complete</td>
                </tr>
                <tr>
                    <td>Matthew Espanol</td>
                    <td>Task 3</td>
                    <td>May 10, 2024</td>
                    <td class="status due">Due</td>
                </tr>
                <tr>
                    <td>Daryl Garcia</td>
                    <td>Task 4</td>
                    <td>June 10, 2024</td>
                    <td class="status pending">Pending</td>
                </tr>
                <tr>
                    <td>Nic Parreno</td>
                    <td>Task 1</td>
                    <td>May 29, 2024</td>
                    <td class="status pending">Pending</td>
                </tr>
                <tr>
                    <td>Jam Riomulin</td>
                    <td>Task 2</td>
                    <td>May 20, 2024</td>
                    <td class="status complete">Complete</td>
                </tr>
            </tbody>
        </table>

        <script>
            document.getElementById("addEmployeeBtn").addEventListener("click", function() {
                // Open a modal or form to collect employee details (consider UI libraries for a more polished experience)
                let employeeName = prompt("Enter Employee Name:");
                let employeeTask = prompt("Enter Employee Task:");
                let employeeDueDate = prompt("Enter Employee Due Date:");
                // Add prompts for additional employee details as required

                // Validate input (optional but recommended)
                if (!employeeName || !employeeTask || !employeeDueDate) {
                    alert("Please fill in all required fields.");
                    return;
                }

                // Create a new table row element
                let newRow = document.createElement("tr");

                // Create table cells (TD elements) for each employee detail
                let nameCell = document.createElement("td");
                nameCell.textContent = employeeName;

                let taskCell = document.createElement("td");
                taskCell.textContent = employeeTask;

                let dueDateCell = document.createElement("td");
                dueDateCell.textContent = employeeDueDate;

                let statusCell = document.createElement("td");
                statusCell.className = 'status pending';
                statusCell.textContent = 'Pending';

                // Append the new cells to the table row
                newRow.appendChild(nameCell);
                newRow.appendChild(taskCell);
                newRow.appendChild(dueDateCell);
                newRow.appendChild(statusCell);

                // Get a reference to the table body
                let employeeTable = document.getElementById("employeeTable");

                // Append the new table row to the table body
                employeeTable.appendChild(newRow);
            });
        </script>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Tracking</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="timestyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <style>
        .header-container {
            display: flex;
            align-items: center; /* Align items vertically */
            margin-bottom: 20px;
        }
        .header-container img {
            height: 50px; /* Adjust the height as needed */
            margin-right: 10px; /* Space between the logo and the arrow */
        }
        .header-container h1 {
            margin: 0;
        }
        .back-link {
            font-size: 30px; /* Font size for the arrow */
            color: #000; /* Color for the arrow */
            margin-right: 10px; /* Space between the arrow and the logo */
            text-decoration: none; /* Remove underline */
        }
        .back-link:hover {
            text-decoration: underline; /* Underline on hover */
        }
        .container {
            padding: 15px; /* Add padding for better fitting */
        }
        @media (max-width: 768px) {
            .header-container h1 {
                font-size: 20px; /* Adjust header size for smaller screens */
            }
            .back-link {
                font-size: 24px; /* Adjust back arrow size for smaller screens */
            }
            .form-group {
                margin-bottom: 15px; /* Adjust spacing for smaller screens */
            }
        }
    </style>
</head>
<body>

<div class="container-fluid mt-5">
    <div class="header-container">
        <a href="../Employees.php" class="back-link">
            <i class="fas fa-arrow-left"></i> <!-- Font Awesome back arrow -->
        </a>
        <a class="navbar-brand brand-logo" href="hr_dashboard.php">
            <img src="../signin&signout/assets1/img/logo.png" alt="logo" />
        </a>
    </div>
    <div>
        <h1>Time Tracking</h1>
    </div>

    <div class="form-group">
        <label for="timePeriod">Time Period :</label>
        <input type="text" id="timePeriod" class="form-control" value="1st June - 31st July 2024" readonly>
    </div>

    <div class="input-group mb-3">
        <input id="searchInput" type="text" class="form-control" placeholder="Search Employee">
        <div class="input-group-append">
            <button id="clearButton" class="btn btn-outline-secondary" type="button">&times;</button>
        </div>
    </div>

    <div class="table-responsive"> <!-- Responsive table wrapper -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Regular</th>
                    <th>Overtime</th>
                    <th>Sick Leave</th>
                    <th>PTO</th>
                    <th>Paid Holiday</th>
                    <th>Total Hours</th>
                </tr>
            </thead>
            <tbody>
                <tr class="employee">
                    <td class="employee-name"><i class="fas fa-user"></i> Nic Parreno</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="employee">
                    <td class="employee-name"><i class="fas fa-user"></i> Jam Riomulin</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="employee">
                    <td class="employee-name"><i class="fas fa-user"></i> Matthew Espanol</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="employee">
                    <td class="employee-name"><i class="fas fa-user"></i> Daryl Garcia</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- Duplicate rows are removed for brevity -->
            </tbody>
        </table>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchInput");
        const clearButton = document.getElementById("clearButton");

        // Function to filter results based on search input
        function filterResults() {
            const searchTerm = searchInput.value.trim().toLowerCase();
            const employeeList = document.querySelectorAll('.employee-name');
            employeeList.forEach(function(employee) {
                const name = employee.textContent.trim().toLowerCase();
                if (name.includes(searchTerm)) {
                    employee.closest('.employee').style.display = '';
                } else {
                    employee.closest('.employee').style.display = 'none';
                }
            });
        }

        // Event listener for input change
        searchInput.addEventListener("input", filterResults);

        // Event listener for clear button click
        clearButton.addEventListener("click", function() {
            searchInput.value = ""; // Clear the search input
            filterResults(); // Update the results (optional)
        });
    });
</script>
</body>
</html>

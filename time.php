<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Tracking</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="timestyles.css">
    <style>
        .header-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .header-container img {
            height: 50px; /* Adjust the height as needed */
            margin-bottom: 10px; /* Space between the logo and the header */
        }
        .header-container h1 {
            margin: 0;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="header-container">
        <a class="navbar-brand brand-logo" href="Dashboard.php">
            <img src="signin&singout/assets1/img/logo.png" alt="logo" />
        </a>
        <h1>Time Tracking</h1>
    </div>

    <div class="form-group">
        <label for="timePeriod">Time Period :</label>
        <input type="text" id="timePeriod" class="form-control" value="1st June - 31st July 2024" readonly>
    </div>

    <div class="container mt-5">
        <div class="input-group mb-3">
            <input id="searchInput" type="text" class="form-control" placeholder="Search Employee">
            <div class="input-group-append">
                <button id="clearButton" class="btn btn-outline-secondary" type="button">&times;</button>
            </div>
        </div>
    </div>

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
        </tbody>
    </table>
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
